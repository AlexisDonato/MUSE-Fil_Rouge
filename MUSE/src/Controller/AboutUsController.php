<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Service\Cart\CartService;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Repository\OrderDetailsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AboutUsController extends AbstractController
{
    #[Route('/aboutus', name: 'app_about_us')]
    public function index(CartService $cartService, ProductRepository $productRepository, CategoryRepository $categoryRepository, OrderDetailsRepository $orderDetails): Response
    {

        $data = new SearchData();

        return $this->render('about_us/index.html.twig', [
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total'     => $cartService->getTotal($orderDetails),
            'products'  => $productRepository->findSearch($data),
            'products2' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'discount'  => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
            'count'     => $cartService->getItemCount($orderDetails),
        ]);
    }
}
