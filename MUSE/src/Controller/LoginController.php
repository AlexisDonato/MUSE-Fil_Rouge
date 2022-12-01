<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Form\SearchType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Repository\OrderDetailsRepository;
use App\Service\Cart\CartService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
    
    function getData(CartService $cartService, OrderDetailsRepository $orderDetails) {
        return [
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total'     => $cartService->getTotal($orderDetails),
        ];
    }
    
    class LoginController extends AbstractController
    {
        #[Route('/login', name: 'login')]
        public function index(CartService $cartService, AuthenticationUtils $authenticationUtils, CategoryRepository $categoryRepository, Request $request,ProductRepository $productRepository, OrderDetailsRepository $orderDetails): Response
        {
         // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();

            $data = new SearchData();
            $data->page = $request->get('page', 1);

            $form = $this->createForm(SearchType::class, $data);
            $form->handleRequest($request);
            
            return $this->render('login/index.html.twig', getData($cartService, $orderDetails) + [
                'last_username' => $lastUsername,
                'error'         => $error,
                'products'      => $productRepository->findSearch($data),
                'products2'     => $productRepository->findAll(),
                'categories'    => $categoryRepository->findAll(),
                'discount'      => $productRepository->findDiscount($data),
                'discount2'     => $productRepository->findProductsDiscount(),
                'form'          => $form->createView()
                ]);
        }

        #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout()
    {
        $this->addFlash('info', 'Vous êtes déconnecté');
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    }