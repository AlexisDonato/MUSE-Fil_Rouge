<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Address;
use App\Data\SearchData;
use App\Form\Address1Type;
use App\Service\Cart\CartService;
use App\Repository\UserRepository;
use App\Repository\AddressRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OrderDetailsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/client/address')]
class ClientAddressController extends AbstractController
{
    #[Route('/', name: 'app_client_address_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, UserInterface $user, AddressRepository $addressRepository, CartService $cartService, ProductRepository $productRepository, CategoryRepository $categoryRepository, ?OrderDetailsRepository $orderDetails): Response
    {
        // Double access restriction for roles other than 'ROLE_CLIENT'
        if (!$this->isGranted('ROLE_CLIENT')) {
            $this->addFlash('error', 'Accès refusé');
            return $this->redirectToRoute('login');  
        }
        $this->denyAccessUnlessGranted('ROLE_CLIENT', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");

        $data = new SearchData();

        // Fetches the user addresses
        $addresses = $this->getDoctrine()->getRepository(Address::class)->findByUser($user);

        // Needed for using CartService
        $cartService->setUser($user);

        return $this->render('client_address/index.html.twig', [
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total'     => $cartService->getTotal($orderDetails),
            'user'      => $user,
            'products'  => $productRepository->findSearch($data),
            'products2' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'discount'  => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
            'addresses' => $addresses,
        ]);
    }


    #[Route('/new', name: 'app_client_address_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AddressRepository $addressRepository, CartService $cartService, ProductRepository $productRepository, CategoryRepository $categoryRepository, ?UserInterface $user, ?OrderDetailsRepository $orderDetails, EntityManagerInterface $entityManager): Response
    {
        // Double access restriction for roles other than 'ROLE_CLIENT'
        if (!$this->isGranted('ROLE_CLIENT')) {
            $this->addFlash('error', 'Accès refusé');
            return $this->redirectToRoute('login');  
        }
        $this->denyAccessUnlessGranted('ROLE_CLIENT', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");

        // The address form
        $address = new Address();
        $form = $this->createForm(Address1Type::class, $address);
        $form->handleRequest($request);

        $data = new SearchData();

        // Needed for using CartService
        $cartService->setUser($user);

        // Retrieves the client cart
        $cart = $cartService->getClientCart();

        // Binds the address to the user
        $address->setUser($user);

        // Saves the address if the form is valid
        if ($form->isSubmitted() && $form->isValid()) {
            $addressRepository->save($address, true);

            // Sets the address as billing and/or delivery addresses for the current cart if the checkboxes are validated
            if ($form->get('billingAddress')->getData(true)) {
                $cart->setBillingAddress($address);
            }
            if ($form->get('deliveryAddress')->getData(true)) {
                $cart->setDeliveryAddress($address);
            }
            $entityManager->persist($cart);
            $entityManager->flush();

            $this->addFlash('success', 'Adresse enregistrée !');
            return $this->redirectToRoute('app_client_address_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client_address/new.html.twig', [
            'address'   => $address,
            'form'      => $form,
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total'     => $cartService->getTotal($orderDetails),
            'user'      => $user,
            'products'  => $productRepository->findSearch($data),
            'products2' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'discount'  => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
        ]);
    }

    #[Route('/{id}', name: 'app_client_address_show', methods: ['GET'])]
    public function show(Address $address, CartService $cartService, ProductRepository $productRepository, CategoryRepository $categoryRepository, ?UserInterface $user, ?OrderDetailsRepository $orderDetails): Response
    {
        // Double access restriction for roles other than 'ROLE_CLIENT'
        if (!$this->isGranted('ROLE_CLIENT')) {
            $this->addFlash('error', 'Accès refusé');
            return $this->redirectToRoute('login');  
        }
        $this->denyAccessUnlessGranted('ROLE_CLIENT', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");

        // The user, without the role 'ROLE_SALES', cannot access other users infos:
        if (!$this->isGranted('ROLE_SALES')) {
            if ($this->getUser()->getUserIdentifier() != $address->getUser()->getUserIdentifier()) {
                $this->addFlash('error', 'Accès refusé');
                return $this->redirectToRoute('login');  
                $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");
            }
        }

        $data = new SearchData();

        // Needed for using CartService
        $cartService->setUser($user);

        return $this->render('client_address/show.html.twig', [
            'address'   => $address,
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total'     => $cartService->getTotal($orderDetails),
            'user'      => $user,
            'products'  => $productRepository->findSearch($data),
            'products2' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'discount'  => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_client_address_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Address $address, AddressRepository $addressRepository, CartService $cartService, ProductRepository $productRepository, CategoryRepository $categoryRepository, ?UserInterface $user, ?OrderDetailsRepository $orderDetails, EntityManagerInterface $entityManager): Response
    {
        // Double access restriction for roles other than 'ROLE_CLIENT'
        if (!$this->isGranted('ROLE_CLIENT')) {
            $this->addFlash('error', 'Accès refusé');
            return $this->redirectToRoute('login');  
        }
        $this->denyAccessUnlessGranted('ROLE_CLIENT', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");

        // The user, without the role 'ROLE_SALES', cannot access other users infos:
        if (!$this->isGranted('ROLE_SALES')) {
            if ($this->getUser()->getUserIdentifier() != $address->getUser()->getUserIdentifier()) {
                $this->addFlash('error', 'Accès refusé');
                return $this->redirectToRoute('login');  
                $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");
            }
        }

        // The address form
        $form = $this->createForm(Address1Type::class, $address);
        $form->handleRequest($request);

        $data = new SearchData();

        // Needed for using CartService
        $cartService->setUser($user);

        // Retrieves the client cart
        $cart = $cartService->getClientCart();

        // Saves the address if the form is valid
        if ($form->isSubmitted() && $form->isValid()) {
            $addressRepository->save($address, true);

            // Sets the address as billing and/or delivery addresses for the current cart if the checkboxes are validated
            if ($form->get('billingAddress')->getData(true)) {
                $cart->setBillingAddress($address);
            }
            if ($form->get('deliveryAddress')->getData(true)) {
                $cart->setDeliveryAddress($address);
            }
            $entityManager->persist($cart);
            $entityManager->flush();

            $this->addFlash('success', 'Adresse modifiée !');
            return $this->redirectToRoute('app_client_address_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client_address/edit.html.twig', [
            'address'   => $address,
            'form'      => $form,
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total'     => $cartService->getTotal($orderDetails),
            'user'      => $user,
            'products'  => $productRepository->findSearch($data),
            'products2' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'discount'  => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
        ]);
    }

    #[Route('/{id}', name: 'app_client_address_delete', methods: ['POST'])]
    public function delete(Request $request, Address $address, AddressRepository $addressRepository): Response
    {
        // Double access restriction for roles other than 'ROLE_CLIENT'
        if (!$this->isGranted('ROLE_CLIENT')) {
            $this->addFlash('error', 'Accès refusé');
            return $this->redirectToRoute('login');  
        }
        $this->denyAccessUnlessGranted('ROLE_CLIENT', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");

        // The user, without the role 'ROLE_SALES', cannot access other users infos:
        if (!$this->isGranted('ROLE_SALES')) {
            if ($this->getUser()->getUserIdentifier() != $address->getUser()->getUserIdentifier()) {
                $this->addFlash('error', 'Accès refusé');
                return $this->redirectToRoute('login');  
                $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");
            }
        }

        // Checks if the csrf token is valid in order to delete the address
        if ($this->isCsrfTokenValid('delete'.$address->getId(), $request->request->get('_token'))) {
            $addressRepository->remove($address, true);
        }

        return $this->redirectToRoute('app_client_address_index', [], Response::HTTP_SEE_OTHER);
    }
}
