<?php

namespace App\Controller;

use Knp\Snappy\Pdf;
use App\Entity\Cart;
use Twig\Environment;
use App\Entity\Address;
use App\Data\SearchData;
use App\Service\PdfTools;
use App\Form\CouponInsertType;
use App\Form\OrderAddressType;
use App\Form\SelectAddressType;
use App\Security\EmailVerifier;
use App\Service\Cart\CartService;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Repository\CouponRepository;
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
    public function getData(CartService $cartService, ?UserInterface $user, ?OrderDetailsRepository $orderDetails, ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        $data = new SearchData();

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
    public function index(CartService $cartService, ProductRepository $productRepository, Request $request, CategoryRepository $categoryRepository, OrderDetailsRepository $orderDetails, ?UserInterface $user, EntityManagerInterface $entityManager, CouponRepository $couponRepository): Response
    {
        if (!$this->isGranted('ROLE_CLIENT')) {
            $this->addFlash('error', 'Accès refusé');
            return $this->redirectToRoute('login');
        }

        if ($this->getUser()->getUserIdentifier() != $user->getUserIdentifier()) {
            $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");
        }

        $data = new SearchData();
        $data->page = $request->get('page', 1);

        $addresses = $this->getDoctrine()->getRepository(Address::class)->findByUser($user);

        $cartService->setUser($user);

        $cart = $cartService->getClientCart();

        $couponInsertform = $this->createForm(CouponInsertType::class);
        $couponInsertform->handleRequest($request);
        $couponCode = $couponInsertform->get('code')->getData();
        $couponInsert = $couponRepository->findOneBy(["code" => $couponCode]);

        $coupon = null;
        if ($couponInsert) {
            $coupon = $couponRepository->findOneByCartAndCoupon($couponInsert, $this->getUser());
        }

        if ($couponInsertform->isSubmitted() && $couponInsertform->isValid()) {

            if ($coupon) {
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

        $address = new Address();
        $newAddressForm = $this->createForm(OrderAddressType::class);
        $newAddressForm->handleRequest($request);

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

        $selectForm = $this->createForm(SelectAddressType::class);
        $selectForm->handleRequest($request);
        $addresses = $this->getDoctrine()->getRepository(Address::class)->findByUser($user);



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

    public function checkoutAction(Request $request)
    {
        if ($request->isMethod('POST')) {
            $token = $request->request->get('stripeToken');
            \Stripe\Stripe::setApiKey("pk_test_HxZzNHy8LImKK9LDtgMDRBwd");
            \Stripe\Charge::create(array(
                "amount" => $this->get('cart')->getTotal() * 100,
                "currency" => "eur",
                "source" => $token,
                "description" => "Test charge!"
            ));

            $this->getCart()->setValidated(true);

            return $this->redirectToRoute('app_order_validated');
        }
    }

    #[Route('/order/validated', name: 'app_order_validated')]
    public function validateOrder(Request $request, PdfTools $pdf, EntrypointLookupInterface $entrypointLookup, ?CartService $cartService, ?CartRepository $cartRepository, ?Cart $cart, ?UserInterface $user, ?EntityManagerInterface $entityManager, OrderDetailsRepository $orderDetails, MailerInterface $mailer)
    {
        if (!$this->isGranted('ROLE_CLIENT')) {
            $this->addFlash('error', 'Accès refusé');
            return $this->redirectToRoute('login');
        }

        if ($this->getUser()->getUserIdentifier() != $user->getUserIdentifier()) {
            $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");
        }

        $cartService->setUser($user);

        $cart = $cartService->getClientCart();


        if ($cart->getBillingAddress() == null && $cart->getDeliveryAddress() == null) {

            $this->addFlash('error', "Merci d'enregister vos adresses de facturation et de livraison au préalable!");
            $route = $request->headers->get('referer');
            return $this->redirect($route);
        } else {

            $cart->setValidated(true);
            $cart->setShipped(false);
            $cart->setTotal($cartService->getTotal($orderDetails));
            $date = new \DateTime('@' . strtotime('now'));
            $cart->setOrderDate($date);

            $orderId = $cart->getId();
            $clientOrderId = $cart->getClientOrderId();
            $cart->setInvoice('INVOICE-' . $clientOrderId . '.pdf');

            $entityManager->persist($cart);
            $entityManager->flush();

            $clientOrderId = $cart->getClientOrderId();
            $details = $orderDetails->findBy(['cart' => $orderId]);

            $addresses = $this->getDoctrine()->getRepository(Address::class)->findByUser($user);

            $pdf->generateInvoice($orderId);

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
                ->attachFromPath('/home/alex/Bureau/Fil Rouge/MUSE/public/invoices/INVOICE-' . $cart->getClientOrderId() . '.pdf');

            $mailer->send($email);

            $this->addFlash('success', 'Commande validée, merci pour votre achat! Un email de confirmation de votre commande a été envoyé sur votre adresse mail');
            return $this->redirectToRoute('app_home');
        }
    }
}
