<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Service\Cart\CartService;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Repository\OrderDetailsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{

    #[Route('/category/{parent}', name: 'app_category', defaults: ['parent' => null])]
    public function index($parent, CartService $cartService, CategoryRepository $categoryRepository, Request $request, ProductRepository $productRepository, OrderDetailsRepository $orderDetails, ?UserInterface $user): Response
    {
        $data = new SearchData();

        // Paginator
        $data->page = $request->get('page', 1);

        // The search filter
        $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);

        // Needed for using CartService
        $cartService->setUser($user);

        return $this->render('category/index.html.twig', [
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total'     => $cartService->getTotal($orderDetails),
            'categories' => $categoryRepository->findByParent($parent),
            'products2' => $productRepository->findAll(),
            'products'  => $productRepository->findSearch($data),
            'discount'  => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
        ]);
    }
}
