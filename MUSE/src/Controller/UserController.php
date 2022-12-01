<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Address;
use App\Data\SearchData;
use App\Service\Cart\CartService;
use App\Repository\UserRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Repository\OrderDetailsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(CartService $cartService, UserRepository $userRepository, CategoryRepository $categoryRepository, ProductRepository $productRepository, OrderDetailsRepository $orderDetails, ?UserInterface $user): Response
    {
        if (!$this->isGranted('ROLE_SHIP')) {
            $this->addFlash('error', 'Accès refusé');
            return $this->redirectToRoute('login');  
        }
        $this->denyAccessUnlessGranted('ROLE_SHIP', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");

        $data = new SearchData();
        
        $cartService->setUser($user);

        return $this->render('user/index.html.twig', [
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total' => $cartService->getTotal($orderDetails),
            'users' => $userRepository->findAll(),
            'products' => $productRepository->findSearch($data),
            'products2' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'discount' => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
            'addresses' =>$this->getDoctrine()->getRepository(Address::class)->findByUser($user),
        ]);
    }

    // #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, UserRepository $userRepository, CategoryRepository $categoryRepository, ProductRepository $productRepository): Response
    // {
    //     $this->denyAccessUnlessGranted('ROLE_SALES', null, 'User tried to access a page without having ROLE_SALES');

    //     $user = new User();
    //     $form = $this->createForm(UserType::class, $user);
    //     $form->handleRequest($request);

    //     $categories = $categoryRepository->findAll();
    //     $data = new SearchData();
    //     $products = $productRepository->findSearch($data);
    //     $products2 =$productRepository->findAll();
    //     $discount = $productRepository->findDiscount($data);
    //             $discount2 =$productRepository->findProductsDiscount();
            
    //     if ($form->isSubmitted() && $form->isValid()) {

    //         // transforms json column into str
    //         $roles = $form->get('roles')->getData();
    //         $user->setRoles($roles);

    //         // $userRepository->add($user, true);

    //         $userRepository->add($user, true);

    //         return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('user/new.html.twig', [
    //         'user' => $user,
    //         'form' => $form,
    //         'products' => $products,
    //         'products2' => $products2,
    //         'categories' => $categories,
    //         'discount' => $discount,
    //         'discount2' => $discount2,
    //     ]);
    // }
 
    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(UserRepository $userRepository, CartService $cartService, User $user, CategoryRepository $categoryRepository, ProductRepository $productRepository, OrderDetailsRepository $orderDetails): Response
    {
        if (!$this->isGranted('ROLE_SHIP')) {
            $this->addFlash('error', 'Accès refusé');
            return $this->redirectToRoute('login');  
        }
        $this->denyAccessUnlessGranted('ROLE_SHIP', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");

        // The user cannot access other users infos:
        if ($this->getUser()->getUserIdentifier() != $user->getUserIdentifier()) {
            $this->denyAccessUnlessGranted('ROLE_SHIP', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");
        }

        $data = new SearchData();

        $cartService->setUser($user);

        return $this->render('user/show.html.twig', [
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total' => $cartService->getTotal($orderDetails),
            'user' => $user,
            'products' => $productRepository->findSearch($data),
            'products2' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'discount' => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
            'addresses' =>$this->getDoctrine()->getRepository(Address::class)->findByUser($user),            
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(?Address $address, CartService $cartService, CategoryRepository $categoryRepository, ProductRepository $productRepository, Request $request, User $user, UserRepository $userRepository, OrderDetailsRepository $orderDetails): Response
    {
        if (!$this->isGranted('ROLE_SALES')) {
            $this->addFlash('error', 'Accès refusé');
            return $this->redirectToRoute('login');  
        }
        $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");

        if ($this->getUser()->getUserIdentifier() != $user->getUserIdentifier()) {
            $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");
        }
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        $data = new SearchData();

        $cartService->setUser($user);

        // $address->setUser($user);

        if ($form->isSubmitted() && $form->isValid()) {

            // transforms json column into str
            $roles = $form->get('roles')->getData();
            $user->setRoles($roles);
            
            $userRepository->add($user, true);

                return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
            // return $this->redirectToRoute('{{ path('app_user_show', {'id': app.user.id}) }}', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total' => $cartService->getTotal($orderDetails),
            'user' => $user,
            'form' => $form,
            'products' => $productRepository->findSearch($data),
            'products2' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'discount' => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
            'addresses' =>$this->getDoctrine()->getRepository(Address::class)->findByUser($user),      
            'address'   =>$address, 
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if (!$this->isGranted('ROLE_SALES')) {
            $this->addFlash('error', 'Accès refusé');
            return $this->redirectToRoute('login');  
        }
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

}

