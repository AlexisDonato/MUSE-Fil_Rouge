<?php

namespace App\Controller;

use Knp\Snappy\Pdf;
use App\Entity\Cart;
use App\Entity\Address;
use App\Data\SearchData;
use App\Service\PdfTools;
use App\Form\CouponInsertType;
use App\Form\OrderAddressType;
use App\Form\SelectAddressType;
use App\Security\EmailVerifier;
use App\Service\Cart\CartService;
use App\Repository\CartRepository;
use App\Repository\CouponRepository;
use App\Repository\AddressRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OrderDetailsRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Address as E_address;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\WebpackEncoreBundle\Asset\EntrypointLookupInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class OrderController extends AbstractController
{
    // Method to factorize some data in this controller
    public function getData(CartService $cartService, ?UserInterface $user, ?OrderDetailsRepository $orderDetails, ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        $data = new SearchData();

        // Needed for using the CartService
        $cartService->setUser($user);

        $info = [
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total'     => $cartService->getTotal($orderDetails),
            'products'  => $productRepository->findSearch($data),
            'products2' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'discount'  => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
        ];

        return $info;
    }


    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }


    #[Route('/order', name: 'app_order')]
    public function index(AddressRepository $addressRepository, CartService $cartService, ProductRepository $productRepository, Request $request, CategoryRepository $categoryRepository, OrderDetailsRepository $orderDetails, ?UserInterface $user, EntityManagerInterface $entityManager, CouponRepository $couponRepository): Response
    {
        // Double access restriction for roles other than 'ROLE_CLIENT'
        if (!$this->isGranted('ROLE_CLIENT')) {
            $this->addFlash('error', 'Accès refusé');
            return $this->redirectToRoute('login');  
        }
        $this->denyAccessUnlessGranted('ROLE_CLIENT', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");

        // The user, without the role 'ROLE_SALES', cannot access other users infos:
        if (!$this->isGranted('ROLE_SALES')) {
            if ($this->getUser()->getUserIdentifier() != $user->getUserIdentifier()) {
                $this->addFlash('error', 'Accès refusé');
                return $this->redirectToRoute('login');  
                $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");
            }
        }

        $data = new SearchData();
        // Paginator
        $data->page = $request->get('page', 1);

        // Fetches the user addresses
        $addresses = $addressRepository->findByUser($user);

        // Needed for using CartService
        $cartService->setUser($user);

        // Retrieves the client cart
        $cart = $cartService->getClientCart();

        // The coupon form
        $couponInsertform = $this->createForm(CouponInsertType::class);
        $couponInsertform->handleRequest($request);
        $couponInsert = $couponInsertform->get('code')->getData();
        $couponCode = $couponRepository->findOneBy(["code" => $couponInsert]);

        // Checks if the coupon exists
        $coupon = null;
        if ($couponCode) {
            $coupon = $couponRepository->findOneByCartAndCoupon($couponCode, $this->getUser());
        }

        // Sets a discount rate on the cart if the form is valid
        if ($couponInsertform->isSubmitted() && $couponInsertform->isValid()) {

            if ($coupon && $coupon->isValidated(true)) {
                $cart->setCoupon($coupon);
                $cart->setAdditionalDiscountRate($cart->getCoupon()->getDiscountRate());

                $entityManager->persist($cart);
                $entityManager->persist($cart->getCoupon());

                $entityManager->flush();

                $this->addFlash('success', 'Bon de réduction appliqué !');

            // Redirects to the last page :
            $route = $request->headers->get('referer');
            return $this->redirect($route);
            }
            else {
                $this->addFlash('error', 'Bon de réduction invalide');

            // Redirects to the last page :
            $route = $request->headers->get('referer');
            return $this->redirect($route);
            }
        }

        // The new address form on the order page
        $address = new Address();
        $newAddressForm = $this->createForm(OrderAddressType::class);
        $newAddressForm->handleRequest($request);

        // Saves the new address if the form is valid
        if ($newAddressForm->isSubmitted() && $newAddressForm->isValid()) {

            $this->addFlash('success', 'Adresse ajoutée !');

            $address->setName($newAddressForm->get('name')->getData())
                    ->setCountry($newAddressForm->get('country')->getData())
                    ->setZipcode($newAddressForm->get('zipcode')->getData())
                    ->setCity($newAddressForm->get('city')->getData())
                    ->setPathType($newAddressForm->get('pathType')->getData())
                    ->setPathNumber($newAddressForm->get('pathNumber')->getData())

                    ->setBillingAddress($newAddressForm->get('billingAddress')->getData())
                    ->setDeliveryAddress($newAddressForm->get('deliveryAddress')->getData());


            // $user->addAddress($address); 
            // this equals to :
            $address->setUser($user);
            // and these bind the two classes

            $entityManager->persist($address);
            $entityManager->flush();

            // Sets the delivery and billing addresses for the current cart if the checkboxes are validated
            if ($newAddressForm->get('billingAddress')->getData(true)) {
                $cart->setBillingAddress($address);
            }
            if ($newAddressForm->get('deliveryAddress')->getData(true)) {
                $cart->setDeliveryAddress($address);
            }
            $entityManager->persist($cart);
            $entityManager->flush();

            return $this->redirectToRoute("app_order");
        }

        // The "delivery and billing addresses" select form
        $selectForm = $this->createForm(SelectAddressType::class);
        $selectForm->handleRequest($request);
        $addresses = $addressRepository->findByUser($user);

        // Sets the delivery and billing addresses for the current cart when selected
        if ($selectForm->isSubmitted() && $selectForm->isValid()) {

            $this->addFlash('success', 'Adresses de FACTURATION et LIVRAISON définies!');

            $cart = $cartService->getClientCart();

            $cart->setBillingAddress($selectForm->get('selectBillingAddress')->getData())
                ->setDeliveryAddress($selectForm->get('selectDeliveryAddress')->getData());

            $address->setUser($user);

            $entityManager->persist($cart);
            $entityManager->flush();
        }
        return $this->render('order/index.html.twig', [
            'details'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total'     => $cartService->getTotal($orderDetails),
            'products'  => $productRepository->findSearch($data),
            'products2' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'discount'  => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
            'addresses' => $addresses,
            'cart'      => $cart,
            'couponInsertform' => $couponInsertform->createView(),
            'newAddressForm' => $newAddressForm->createView(),
            'selectForm' => $selectForm->createView(),
        ]);
    }

    
    #[Route('/order/validated', name: 'app_order_validated')]
    public function validateOrder(AddressRepository $addressRepository, Request $request, PdfTools $pdf, EntrypointLookupInterface $entrypointLookup, ?CartService $cartService, ?CartRepository $cartRepository, ?Cart $cart, ?UserInterface $user, ?EntityManagerInterface $entityManager, OrderDetailsRepository $orderDetails, MailerInterface $mailer)
    {
        // Double access restriction for roles other than 'ROLE_CLIENT'
        if (!$this->isGranted('ROLE_CLIENT')) {
            $this->addFlash('error', 'Accès refusé');
            return $this->redirectToRoute('login');  
        }
        $this->denyAccessUnlessGranted('ROLE_CLIENT', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");

        // The user, without the role 'ROLE_SALES', cannot access other users infos:
        if (!$this->isGranted('ROLE_SALES')) {
            if ($this->getUser()->getUserIdentifier() != $user->getUserIdentifier()) {
                $this->addFlash('error', 'Accès refusé');
                return $this->redirectToRoute('login');  
                $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");
            }
        }

        // Needed for using CartService
        $cartService->setUser($user);

        // Retrieves the client cart
        $cart = $cartService->getClientCart();

        // Doesn't allow the user to validate the order without having set delivery and billing addresses
        if ($cart->getBillingAddress() == null && $cart->getDeliveryAddress() == null) {
            $this->addFlash('error', "Merci d'enregister vos adresses de facturation et de livraison au préalable!");
            $route = $request->headers->get('referer');
            return $this->redirect($route);

        } else {

            // Sets additional information on the cart to become an order
            $cart->setValidated(true);
            $cart->setShipped(false);
            $cart->setTotal($cartService->getTotal($orderDetails));
            $date = new \DateTime('@' . strtotime('now'));
            $cart->setOrderDate($date);

            // Sets the path for the order invoice in the database
            $orderId = $cart->getId();
            $clientOrderId = $cart->getClientOrderId();
            $cart->setInvoice('INVOICE-' . $clientOrderId . '.pdf');

            $entityManager->persist($cart);
            $entityManager->flush();

            $clientOrderId = $cart->getClientOrderId();
            $details = $orderDetails->findBy(['cart' => $orderId]);

            // Fetches the user addresses
            // $addresses = $this->getDoctrine()->getRepository(Address::class)->findByUser($user);
            $addresses = $addressRepository->findByUser($user);

            // Generates an invoice thanks to the PdfTools service
            $pdf->generateInvoice($orderId);

            // Sends an email with the order info to both the user and to the shipping service of the company, with the invoice attached to it
            $email = (new TemplatedEmail())
                ->from(new E_address('info_noreply@muse.com', 'Muse MailBot'))
                ->to($user->getEmail())
                ->cc('Shipping@muse.com')
                ->subject('Votre commande est validée!')
                ->htmlTemplate('email/order_validation_email.html.twig')
                ->context([
                    'details' => $details,
                    'user' => $user,
                    'addresses' => $addresses,
                    'cart'      => $cart,
                ])
                ->attachFromPath('../public/invoices/INVOICE-' . $cart->getClientOrderId() . '.pdf');

            $mailer->send($email);

            $this->addFlash('success', 'Commande validée, merci pour votre achat! Un email de confirmation de votre commande a été envoyé sur votre adresse mail');
            return $this->redirectToRoute('app_home');
        }
    }
}
