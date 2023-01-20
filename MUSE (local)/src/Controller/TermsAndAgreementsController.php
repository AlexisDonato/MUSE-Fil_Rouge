<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Form\SearchType;
use App\Service\Cart\CartService;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Repository\OrderDetailsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TermsAndAgreementsController extends AbstractController
{
    #[Route('/terms', name: 'app_terms_and_agreements')]
    public function index(CartService $cartService, ?UserInterface $user, ProductRepository $productRepository, Request $request, CategoryRepository $categoryRepository, OrderDetailsRepository $orderDetails): Response
    {
        $data = new SearchData();
        // Paginator
        $data->page = $request->get('page', 1);

        // The search filter
        $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);

        // Needed for using CartService
        $cartService->setUser($user);

        return $this->render('terms_and_agreements/terms.html.twig', [
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total' => $cartService->getTotal($orderDetails),
            'products' => $productRepository->findSearch($data),
            'products2' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'discount' => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
            'form' => $form->createView()
        ]);
    }


    #[Route('/sustainability', name: 'app_sustainability')]
    public function index2(CartService $cartService, ?UserInterface $user, ProductRepository $productRepository, Request $request, CategoryRepository $categoryRepository, OrderDetailsRepository $orderDetails): Response
    {
        $data = new SearchData();
        // Paginator
        $data->page = $request->get('page', 1);

        // The search filter
        $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);

        // Needed for using CartService
        $cartService->setUser($user);

        return $this->render('terms_and_agreements/sustainability.html.twig', [
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total' => $cartService->getTotal($orderDetails),
            'products' => $productRepository->findSearch($data),
            'products2' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'discount' => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
            'form' => $form->createView()
        ]);
    }


    #[Route('/withdrawal', name: 'app_withdrawal')]
    public function index3(CartService $cartService, ?UserInterface $user, ProductRepository $productRepository, Request $request, CategoryRepository $categoryRepository, OrderDetailsRepository $orderDetails): Response
    {
        $data = new SearchData();
        // Paginator
        $data->page = $request->get('page', 1);

        // The search filter
        $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);

        // Needed for using CartService
        $cartService->setUser($user);

        return $this->render('terms_and_agreements/withdrawal.html.twig', [
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total' => $cartService->getTotal($orderDetails),
            'products' => $productRepository->findSearch($data),
            'products2' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'discount' => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
            'form' => $form->createView()
        ]);
    }


    #[Route('/shipping_options', name: 'app_shipping_options')]
    public function index4(CartService $cartService, ?UserInterface $user, ProductRepository $productRepository, Request $request, CategoryRepository $categoryRepository, OrderDetailsRepository $orderDetails): Response
    {

        $data = new SearchData();
        // Paginator
        $data->page = $request->get('page', 1);

        // The search filter
        $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);

        // Needed for using CartService
        $cartService->setUser($user);

        return $this->render('terms_and_agreements/shipping_options.html.twig', [
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total' => $cartService->getTotal($orderDetails),
            'products' => $productRepository->findSearch($data),
            'products2' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'discount' => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
            'form' => $form->createView()
        ]);
    }


    #[Route('/returns', name: 'app_returns')]
    public function index5(CartService $cartService, ?UserInterface $user, ProductRepository $productRepository, Request $request, CategoryRepository $categoryRepository, OrderDetailsRepository $orderDetails): Response
    {
        $data = new SearchData();
        // Paginator
        $data->page = $request->get('page', 1);

        // The search filter
        $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);

        // Needed for using CartService
        $cartService->setUser($user);

        return $this->render('terms_and_agreements/returns.html.twig', [
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total' => $cartService->getTotal($orderDetails),
            'products' => $productRepository->findSearch($data),
            'products2' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'discount' => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
            'form' => $form->createView()
        ]);
    }


    #[Route('/plan', name: 'app_plan')]
    public function index6(CartService $cartService, ?UserInterface $user, ProductRepository $productRepository, Request $request, CategoryRepository $categoryRepository, OrderDetailsRepository $orderDetails): Response
    {
        $data = new SearchData();
        // Paginator
        $data->page = $request->get('page', 1);

        // The search filter
        $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);

        // Needed for using CartService
        $cartService->setUser($user);
        
        return $this->render('terms_and_agreements/plan.html.twig', [
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total' => $cartService->getTotal($orderDetails),
            'products' => $productRepository->findSearch($data),
            'products2' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'discount' => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
            'form' => $form->createView()
        ]);
    }
}
