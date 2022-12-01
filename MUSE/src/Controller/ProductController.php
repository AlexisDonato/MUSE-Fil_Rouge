<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use App\Data\SearchData;
use App\Entity\Category;
use App\Form\SearchType;
use App\Form\SearchType2;
use App\Service\Cart\CartService;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Repository\SupplierRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OrderDetailsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(CartRepository $cartRepository, CartService $cartService, ?UserInterface $user, ProductRepository $productRepository, Request $request, CategoryRepository $categoryRepository, OrderDetailsRepository $orderDetails, ?EntityManagerInterface $entityManager): Response
    {
        if ($this->isGranted('ROLE_CLIENT')) {
            $clientCart = $cartRepository->findOneByUser($user->getId());

            if (!isset($clientCart)) {
                $clientCart = new Cart();

                $clientCart->setUser($user);
                $clientCart->setClientOrderId(strtoupper(uniqid('MUSE::')));
                $entityManager->persist($clientCart);
                $entityManager->flush();
            }

            $cartService->setCart($clientCart);
            $cartService->setUser($user);
        }

        $data = new SearchData();
        $data->page = $request->get('page', 1);

        $searchForm = $this->createForm(SearchType::class, $data);
        $searchForm->handleRequest($request);
 
        $cartService->setUser($user);

        return $this->render('product/index.html.twig', [
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total'     => $cartService->getTotal($orderDetails),
            'products'  => $productRepository->findSearch($data),
            'products2' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'discount'  => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
            'searchForm' => $searchForm->createView(),
        ]);
    }

    #[Route('/product/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product, ProductRepository $productRepository, CategoryRepository $categoryRepository, CartService $cartService, OrderDetailsRepository $orderDetails, ?UserInterface $user): Response
    {
        $data = new SearchData();

        $cartService->setUser($user);
// dd($productRepository->findAccessories());
        return $this->render('product/product_show.html.twig', [
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total'     => $cartService->getTotal($orderDetails),
            'product'   => $product,
            'products'  => $productRepository->findSearch($data),
            'products2' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'discount'  => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
            'path'      => $product->getCategory()->getPath(),
            'accessories' => $productRepository->findAccessories(),
        ]);
    }

    #[Route('/catalogue/{category}', name: 'app_catalogue')]
    public function index2(CartService $cartService, ProductRepository $productRepository, Request $request, Category $category, CategoryRepository $categoryRepository, OrderDetailsRepository $orderDetails): Response
    {
        $data = new SearchData();
        $data->category = [$category];
        $data->page = $request->get('page', 1);

        $searchForm = $this->createForm(SearchType::class, $data);
        $searchForm->handleRequest($request);

        return $this->render('product/index.html.twig', [
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total'     => $cartService->getTotal($orderDetails),
            'products'  => $productRepository->findSearch($data),
            'products2' => $productRepository->findAll(),
            'categories' => $categoryRepository->find($category),
            'discount'  => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
            'searchForm' => $searchForm->createView(),
        ]);
    }

    #[Route('/discount/{disc}', name: 'app_discount', defaults: ['disc' => 1])]
    public function index3(CartService $cartService, ProductRepository $productRepository, Request $request, CategoryRepository $categoryRepository, int $disc, OrderDetailsRepository $orderDetails, ?UserInterface $user): Response
    {
        switch ($disc) {
            case "0":
                $disc = false;
                break;
            case "1":
                $disc = true;
                break;
            default:
                $disc = false;
                break;
        }

        $data = new SearchData();
        $data->discount = $disc;
        $data->page = $request->get('page', 1);

        $searchForm = $this->createForm(SearchType::class, $data);
        $searchForm->handleRequest($request);

        $cartService->setUser($user);

        return $this->render('product/index.html.twig', [
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total'     => $cartService->getTotal($orderDetails),
            'products'  => $productRepository->findSearch($data),
            'products2' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'discount'  => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
            'searchForm'      => $searchForm->createView()
        ]);
    }
}
