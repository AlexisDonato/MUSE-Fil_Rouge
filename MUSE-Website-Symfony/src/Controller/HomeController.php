<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Data\SearchData;
use App\Service\Cart\CartService;
use App\Form\SearchType2;
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

class HomeController extends AbstractController
{

    private CartRepository $cartRepository;
    private ProductRepository $productRepository;

        public function __construct(CartRepository $cartRepository, ProductRepository $productRepository)
    {
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
    }

    #[Route('/', name: 'app_home')]
    public function index(Request $request, ?UserInterface $user, CartService $cartService, ProductRepository $productRepository, CategoryRepository $categoryRepository, CartRepository $cartRepository, OrderDetailsRepository $orderDetails, EntityManagerInterface $entityManager): Response
    {
        // If the user have the role 'ROLE_CLIENT', checks if the user has a cart. Whereas it will create one
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
        // Paginator
        $data->page = $request->get('page', 1);
        // The search filter
        $searchForm = $this->createForm(SearchType2::class, $data);
        $searchForm->handleRequest($request);
        
        return $this->render('home/index.html.twig', [
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total'     => $cartService->getTotal($orderDetails),
            'products'  => $productRepository->findSearch($data),
            'products2' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'discount'  => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
            'salesByProduct' => $this->cartRepository->findSalesByProduct(),
            'orderedProducts' => $this->cartRepository->findOrderedProducts(),
            'productsDiscount' => $this->productRepository->findProductsDiscount(),
            'searchForm2'      => $searchForm->createView()
        ]);
    }
}
