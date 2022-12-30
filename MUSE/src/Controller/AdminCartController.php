<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Form\CartType;
use App\Data\SearchData;
use App\Service\Cart\CartService;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OrderDetailsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/cart')]
class AdminCartController extends AbstractController
{
    #[Route('/', name: 'app_admin_cart_index', methods: ['GET'])]
    public function index(CartRepository $cartRepository, CartService $cartService, ProductRepository $productRepository, CategoryRepository $categoryRepository, ?UserInterface $user, ?OrderDetailsRepository $orderDetails): Response
    {
        // Access restriction for roles other than 'ROLE_SALES'
        if (!$this->isGranted('ROLE_SALES')) {
            $this->addFlash('error', 'Accès refusé');
            return $this->redirectToRoute('login');  
        }

        // The user cannot access other users infos:
        if ($this->getUser()->getUserIdentifier() != $user->getUserIdentifier()) {
            $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");
        }

        $data = new SearchData();

        // Needed for using the CartService
        $cartService->setUser($user);
        
        $total = $cartService->getTotal($orderDetails);

        return $this->render('admin_cart/index.html.twig', [
            'carts'     => $cartRepository->findAll(),
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total'     => $total,
            'products'  => $productRepository->findSearch($data),
            'products2' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'discount'  => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
        ]);
    }

    #[Route('/new', name: 'app_admin_cart_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, CartService $cartService, ProductRepository $productRepository, CategoryRepository $categoryRepository, ?UserInterface $user, ?OrderDetailsRepository $orderDetails): Response
    {
        // Access restriction for roles other than 'ROLE_SALES'
        if (!$this->isGranted('ROLE_SALES')) {
            $this->addFlash('error', 'Accès refusé');
            return $this->redirectToRoute('login');  
        }

        // The user cannot access other users infos:
        if ($this->getUser()->getUserIdentifier() != $user->getUserIdentifier()) {
            $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");
        }

        // The cart form
        $cart = new Cart();
        $form = $this->createForm(CartType::class, $cart);
        $form->handleRequest($request);

        $data = new SearchData();

        // Needed for using the CartService
        $cartService->setUser($user);

        $total = $cartService->getTotal($orderDetails);

        // Sets the cart if the form is valid
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cart);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_cart_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_cart/new.html.twig', [
            'cart'      => $cart,
            'form'      => $form,
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total'     => $total,
            'products'  => $productRepository->findSearch($data),
            'products2' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'discount'  => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_cart_show', methods: ['GET'])]
    public function show(Cart $cart, CartService $cartService, ProductRepository $productRepository, CategoryRepository $categoryRepository, ?UserInterface $user, ?OrderDetailsRepository $orderDetails): Response
    {
        // Access restriction for roles other than 'ROLE_SALES'
        if (!$this->isGranted('ROLE_SALES')) {
            $this->addFlash('error', 'Accès refusé');
            return $this->redirectToRoute('login');  
        }

        // The user cannot access other users infos:
        if ($this->getUser()->getUserIdentifier() != $user->getUserIdentifier()) {
            $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");
        }

        $data = new SearchData();

        // Needed for using the CartService
        $cartService->setUser($user);

        $total = $cartService->getTotal($orderDetails);

        return $this->render('admin_cart/show.html.twig', [
            'cart'      => $cart,
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total'     => $total,
            'products'  => $productRepository->findSearch($data),
            'products2' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'discount'  => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_cart_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cart $cart, EntityManagerInterface $entityManager, CartService $cartService, ProductRepository $productRepository, CategoryRepository $categoryRepository, ?UserInterface $user, ?OrderDetailsRepository $orderDetails): Response
    {
        // Access restriction for roles other than 'ROLE_SALES'
        if (!$this->isGranted('ROLE_SALES')) {
            $this->addFlash('error', 'Accès refusé');
            return $this->redirectToRoute('login');  
        }

        // The user cannot access other users infos:
        if ($this->getUser()->getUserIdentifier() != $user->getUserIdentifier()) {
            $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");
        }

        $data = new SearchData();

        // Needed for using the CartService
        $cartService->setUser($user);

        $total = $cartService->getTotal($orderDetails);

        // the cart form
        $form = $this->createForm(CartType::class, $cart);
        $form->handleRequest($request);

        // Sets the cart if the form is valid
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_cart_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_cart/edit.html.twig', [
            'cart'      => $cart,
            'form'      => $form,
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total'     => $total,
            'products'  => $productRepository->findSearch($data),
            'products2' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'discount'  => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_cart_delete', methods: ['POST'])]
    public function delete(Request $request, Cart $cart, EntityManagerInterface $entityManager): Response
    {
        // Access restriction for roles other than 'ROLE_SALES'
        if (!$this->isGranted('ROLE_SALES')) {
            $this->addFlash('error', 'Accès refusé');
            return $this->redirectToRoute('login');  
        }

        // The user cannot access other users infos:
        if ($this->getUser()->getUserIdentifier() != $user->getUserIdentifier()) {
            $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");
        }

        // Checks if the csrf token is valid in order to delete the cart
        if ($this->isCsrfTokenValid('delete'.$cart->getId(), $request->request->get('_token'))) {
            $entityManager->remove($cart);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_cart_index', [], Response::HTTP_SEE_OTHER);
    }
}
