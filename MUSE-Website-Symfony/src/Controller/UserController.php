<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Address;
use App\Data\SearchData;
use App\Service\Cart\CartService;
use App\Repository\UserRepository;
use App\Repository\AddressRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Repository\OrderDetailsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


// This are the routes for the admin or the staff of the company
#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(AddressRepository $addressRepository, CartService $cartService, UserRepository $userRepository, CategoryRepository $categoryRepository, ProductRepository $productRepository, OrderDetailsRepository $orderDetails, ?UserInterface $user): Response
    {
        // Double access restriction for roles other than 'ROLE_SHIP'
        if (!$this->isGranted('ROLE_SHIP')) {
            $this->addFlash('error', 'Accès refusé');
            return $this->redirectToRoute('login');  
        }
        $this->denyAccessUnlessGranted('ROLE_SHIP', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");

        $data = new SearchData();
        
        // Needed for using CartService
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
            'addresses' =>$addressRepository->findByUser($user),
        ]);
    }

 
    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(AddressRepository $addressRepository, UserRepository $userRepository, CartService $cartService, User $user, CategoryRepository $categoryRepository, ProductRepository $productRepository, OrderDetailsRepository $orderDetails): Response
    {
        // Double access restriction for roles other than 'ROLE_SHIP'
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

        // Needed for using CartService
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
            'addresses' =>$addressRepository->findByUser($user),            
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(AddressRepository $addressRepository, ?Address $address, CartService $cartService, CategoryRepository $categoryRepository, ProductRepository $productRepository, Request $request, User $user, UserRepository $userRepository, OrderDetailsRepository $orderDetails): Response
    {
         // Double access restriction for roles other than 'ROLE_SALES'
         if (!$this->isGranted('ROLE_SALES')) {
            $this->addFlash('error', 'Accès refusé');
            return $this->redirectToRoute('login');  
        }
        $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");

        // The user, if its role is different from 'ROLE_SALES', cannot access other users infos:
        if (!$this->isGranted('ROLE_SALES')) {
            if ($this->getUser()->getUserIdentifier() != $address->getUser()->getUserIdentifier()) {
                $this->addFlash('error', 'Accès refusé');
                return $this->redirectToRoute('login');  
                $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");
            }
        }

        // The user form
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        $data = new SearchData();

        // Needed for using CartService
        $cartService->setUser($user);

        // Saves the user information if the form is valid
        if ($form->isSubmitted() && $form->isValid()) {

            // transforms json column into str
            $roles = $form->get('roles')->getData();
            $user->setRoles($roles);
            
            $userRepository->add($user, true);

                return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
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
            'addresses' =>$addressRepository->findByUser($user),      
            'address'   =>$address, 
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        // Double access restriction for roles other than 'ROLE_SALES'
        if (!$this->isGranted('ROLE_SALES')) {
            $this->addFlash('error', 'Accès refusé');
            return $this->redirectToRoute('login');  
        }
        $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");

        // The user, if its role is different from 'ROLE_SALES', cannot access other users infos:
        if (!$this->isGranted('ROLE_SALES')) {
            if ($this->getUser()->getUserIdentifier() != $user->getUserIdentifier()) {
                $this->addFlash('error', 'Accès refusé');
                return $this->redirectToRoute('login');  
                $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");
            }
        }

        // Checks if the csrf token is valid in order to delete the user
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

}

