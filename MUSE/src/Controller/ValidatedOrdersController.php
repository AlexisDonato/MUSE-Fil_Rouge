<?php

namespace App\Controller;

use Knp\Snappy\Pdf;
use App\Entity\Cart;
use Twig\Environment;
use App\Data\SearchData;
use App\Service\Cart\CartService;
use App\Repository\CartRepository;
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
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Loader\Configurator\mailer;

class ValidatedOrdersController extends AbstractController
{
    private $twig;
    private $pdf;
    public function __construct(MailerInterface $mailer, Environment $twig, Pdf $pdf)
    {
        $this->twig = $twig;
        $this->pdf = $pdf;
    }

    // Method to factorize some data in this controller
    public function getData(?CartRepository $cartRepository, CartService $cartService, ?UserInterface $user, ?OrderDetailsRepository $orderDetails, ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();
        $data = new SearchData();
        $products = $productRepository->findSearch($data);
        $products2 =$productRepository->findAll();
        $discount = $productRepository->findDiscount($data);
        $discount2 =$productRepository->findProductsDiscount();
        $cartService->setUser($user);
        $info = [
            'count'     => $cartService->getItemCount($orderDetails),
            'total'     => $cartService->getTotal($orderDetails),
            'products'  => $products,
            'products2' => $products2,
            'categories' => $categories,
            'discount'  => $discount,
            'discount2' => $discount2,
        ];

        return $info;
    }

    #[Route('/validated/orders', name: 'app_validated_orders')]
    public function index(CartRepository $cartRepository, CartService $cartService, ?UserInterface $user, ?OrderDetailsRepository $orderDetails, ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        // Access restriction for roles other than 'ROLE_CLIENT'
        if (!$this->isGranted('ROLE_CLIENT')) {
            $this->addFlash('info', 'Merci de vous connecter ou de vous inscrire au préalable');
            return $this->redirectToRoute('login');  
        }

        // The user cannot access other users infos:
        if ($this->getUser()->getUserIdentifier() != $user->getUserIdentifier()) {
            $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");
        }

        // Fetches the validated orders for the corresponding user
        if ($this->isGranted('ROLE_CLIENT')) {
            $clientCarts = $cartRepository->findAllByUser($user->getId(), true);
        }

        // Fetches all the validated orders for the company staff
        if ($this->isGranted('ROLE_SHIP')) {
            $clientCarts = $cartRepository->findAllUsers();
        }
        $validatedOrder = $cartRepository->findAllUsers();
        return $this->render(
            'validated_orders/index.html.twig',
            $this->getData($cartRepository, $cartService, $user, $orderDetails, $productRepository, $categoryRepository) + [
                'orders'    => $clientCarts,
                'validatedOrder' => $clientCarts,
            ]
        );
    }


    #[Route('/validated/orders/{id}', name: 'app_validated_orders_show')]
    public function orderShow(Request $request, Cart $cart, CartRepository $cartRepository, CartService $cartService, ?UserInterface $user, ?OrderDetailsRepository $orderDetails, ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        $orderId = $request->attributes->get('id');
        $details = $orderDetails->findBy(['cart' => $orderId]);

        // Needed for using CartService
        $cartService->setUser($user);

        // Fetches the user
        $user = $cart->getUser();

        return $this->render(
            'validated_orders/show.html.twig',
            $this->getData($cartRepository, $cartService, $user, $orderDetails, $productRepository, $categoryRepository) + [
                'details' => $details,
                'user' => $user,
                'cart' => $cart,
            ]
        );
    }


    #[Route('validated/orders/{id}/shipped', name: 'app_shipped_order')]
    public function shippedOrder(Request $request, CartRepository $cartRepository, EntityManagerInterface $entityManager, MailerInterface $mailer, ?UserInterface $user, OrderDetailsRepository $orderDetails)
    {
        // Access restriction for roles other than 'ROLE_SHIP'
        if (!$this->isGranted('ROLE_SHIP')) {
            $this->addFlash('error', 'Accès refusé');
            return $this->redirectToRoute('login');  
        }

        // Sets additional information on the cart to become an order
        $orderId = $request->attributes->get('id');
        $cart = $cartRepository->find($orderId);
        $cart->setShipped(true);
        $date = new \DateTime('@'.strtotime('now'));
        $cart->setShipmentDate($date);
        $cart->setCarrierShipmentId(uniqid('SHIP::'));
        $cart->setCarrier(uniqid('CARRIER::'));

        $entityManager->persist($cart);
        $entityManager->flush();

        $orderId = $cart->getId();
        $details = $orderDetails->findBy(['cart' => $orderId]);

        // Sends an email to both the user and the company
        $email = (new TemplatedEmail())
        ->from(new E_address('info_noreply@muse.com', 'Muse MailBot'))
        ->to($cart->getUser()->getEmail())
        ->cc('Shipping@muse.com')
        ->subject('Votre commande a bien été expédiée!')
        ->htmlTemplate('email/order_shipment_email.html.twig')
        ->context([
            'details' => $details,
            'user' => $user,
            'cart' => $cart,
        ]);
        $mailer->send($email);

        $this->addFlash('success', 'La commande a bien été envoyée');
        return $this->redirectToRoute('app_validated_orders');

    }
}