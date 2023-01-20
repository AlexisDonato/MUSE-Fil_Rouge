<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Service\Cart\CartService;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OrderDetailsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/category')]
class AdminCategoryController extends AbstractController
{
    #[Route('/', name: 'app_admin_category_index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository, ProductRepository $productRepository, OrderDetailsRepository $orderDetails, CartService $cartService): Response
    {
        // Double access restriction for roles other than 'ROLE_SALES'
        if (!$this->isGranted('ROLE_SALES')) {
            $this->addFlash('error', 'Accès refusé');
            return $this->redirectToRoute('login');  
        }
        $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");

        $data = new SearchData();
        
        return $this->render('admin_category/index.html.twig', [
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total'     => $cartService->getTotal($orderDetails),
            'products'  => $productRepository->findSearch($data),
            'products2' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'discount'  => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
        ]);
    }

    #[Route('/new', name: 'app_admin_category_new', methods: ['GET', 'POST'])]
    public function new(EntityManagerInterface $entityManager, CategoryRepository $categoryRepository, Request $request,ProductRepository $productRepository, OrderDetailsRepository $orderDetails, CartService $cartService): Response
    {
        // Double access restriction for roles other than 'ROLE_SALES'
        if (!$this->isGranted('ROLE_SALES')) {
            $this->addFlash('error', 'Accès refusé');
            return $this->redirectToRoute('login');  
        }
        $this->denyAccessUnlessGranted('ROLE_SALES', null, 'User tried to access a page without having ROLE_SALES');

        // The category form
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        $data = new SearchData();

        // Sets the category if the form is valid
        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->add($category, true);

            // Retrieves the data given in the image input if not null, checks the type of file, 
            // copies & pastes the image in the 'images_directory', sets its name in the database
            $image = $form->get('image')->getData();
            if ($image != null){
                $fileName = $form->get('name')->getData().'.'.$image->guessExtension();
                $image->move($this->getParameter('images_directory'), $fileName);
                $category->setImage($fileName);
            }
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'Catégorie ajoutée!');

            return $this->redirectToRoute('app_admin_category_index', [], Response::HTTP_SEE_OTHER);

        }

        return $this->renderForm('admin_category/new.html.twig', [
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total'     => $cartService->getTotal($orderDetails),
            'category'  => $category,
            'form'      => $form,
            'products'  => $productRepository->findSearch($data),
            'products2' => $productRepository->findAll(),
            'categories' => $categories = $categoryRepository->findAll(),
            'discount'  => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_category_show', methods: ['GET'])]
    public function show(Category $category, CategoryRepository $categoryRepository,ProductRepository $productRepository, OrderDetailsRepository $orderDetails, CartService $cartService, ?UserInterface $user): Response
    {
        // Double access restriction for roles other than 'ROLE_SALES'
        if (!$this->isGranted('ROLE_SALES')) {
            $this->addFlash('error', 'Accès refusé');
            return $this->redirectToRoute('login');  
        }
        $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");

        // The user, without the role 'ROLE_SALES', cannot access other users infos:
        if (!$this->isGranted('ROLE_SALES')) {
            if ($this->getUser()->getUserIdentifier() != $user->getUserIdentifier()) {
                $this->addFlash('error', 'Accès refusé');
                return $this->redirectToRoute('login');  
                $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");
            }
        }

        $data = new SearchData();

        return $this->render('admin_category/show.html.twig', [
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total'     => $cartService->getTotal($orderDetails),
            'category'  => $category,
            'products'  => $productRepository->findSearch($data),
            'products2' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'discount'  => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_category_edit', methods: ['GET', 'POST'])]
    public function edit(EntityManagerInterface $entityManager, Request $request, Category $category, CategoryRepository $categoryRepository,ProductRepository $productRepository, OrderDetailsRepository $orderDetails, CartService $cartService, ?UserInterface $user): Response
    {
        // Double access restriction for roles other than 'ROLE_SALES'
        if (!$this->isGranted('ROLE_SALES')) {
            $this->addFlash('error', 'Accès refusé');
            return $this->redirectToRoute('login');  
        }
        $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");

        // The user, without the role 'ROLE_SALES', cannot access other users infos:
        if (!$this->isGranted('ROLE_SALES')) {
            if ($this->getUser()->getUserIdentifier() != $user->getUserIdentifier()) {
                $this->addFlash('error', 'Accès refusé');
                return $this->redirectToRoute('login');  
                $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");
            }
        }

        // The category form
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        $data = new SearchData();

        // Sets the category if the form is valid
        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->add($category, true);

            // Retrieves the data given in the image input if not null, checks the type of file, 
            // copies & pastes the image in the 'images_directory', sets its name in the database
            $image = $form->get('image')->getData();
            if ($image != null){
                $fileName = $form->get('name')->getData().'.'.$image->guessExtension();
                $image->move($this->getParameter('images_directory'), $fileName);
                $category->setImage($fileName);
            }
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'Catégorie modifiée!');

            return $this->redirectToRoute('app_admin_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_category/edit.html.twig', [
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total' => $cartService->getTotal($orderDetails),
            'category' => $category,
            'form' => $form,
            'products' => $productRepository->findSearch($data),
            'products2' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'discount' => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_category_delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, CategoryRepository $categoryRepository, ?UserInterface $user): Response
    {
        // Double access restriction for roles other than 'ROLE_SALES'
        if (!$this->isGranted('ROLE_SALES')) {
            $this->addFlash('error', 'Accès refusé');
            return $this->redirectToRoute('login');  
        }
        $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");

        // The user, without the role 'ROLE_SALES', cannot access other users infos:
        if (!$this->isGranted('ROLE_SALES')) {
            if ($this->getUser()->getUserIdentifier() != $user->getUserIdentifier()) {
                $this->addFlash('error', 'Accès refusé');
                return $this->redirectToRoute('login');  
                $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");
            }
        }
        
        // Checks if the csrf token is valid in order to delete the category
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $categoryRepository->remove($category, true);
        }
        
        $this->addFlash('success', 'Catégorie supprimée!');

        return $this->redirectToRoute('app_admin_category_index', [], Response::HTTP_SEE_OTHER);
    }

}

