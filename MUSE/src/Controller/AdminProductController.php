<?php

namespace App\Controller;

use App\Entity\Product;
use App\Data\SearchData;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Repository\OrderDetailsRepository;
use App\Service\Cart\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AdminProductController extends AbstractController
{
    #[Route('/admin/product/', name: 'app_admin_product_index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository,ProductRepository $productRepository, CartService $cartService, OrderDetailsRepository $orderDetails): Response
    {
        // Double access restriction for roles other than 'ROLE_SALES'
        if (!$this->isGranted('ROLE_SALES')) {
            $this->addFlash('error', 'Accès refusé');
            return $this->redirectToRoute('login');  
        }
        $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");

        $data = new SearchData();

        return $this->render('admin_product/index.html.twig', [
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total'     => $cartService->getTotal($orderDetails),
            'products'  => $productRepository->findAll(),
            'products2' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'discount'  => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
            ]);
    }

    #[Route('/admin/product/new', name: 'app_admin_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductRepository $productRepository, CategoryRepository $categoryRepository,EntityManagerInterface $entityManager, CartService $cartService, OrderDetailsRepository $orderDetails): Response
    {
        // Double access restriction for roles other than 'ROLE_SALES'
        if (!$this->isGranted('ROLE_SALES')) {
            $this->addFlash('error', 'Accès refusé');
            return $this->redirectToRoute('login');  
        }
        $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");

        // The product form
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        $data = new SearchData();

        // Sets the product if the form is valid
        if ($form->isSubmitted() && $form->isValid()) {

            // Retrieves the data given in the images inputs if not null, checks the type of file, 
            // copies & pastes the image in the 'images_directory', sets its name in the database
            $image = $form->get('image')->getData();
                if ($image != null){
                    $fileName = $form->get('name')->getData().'.'.$image->guessExtension();
                    $image->move($this->getParameter('images_directory'), $fileName);
                    $product->setImage($fileName);
                }
            $image1 = $form->get('image1')->getData();
                if ($image1 != null){
                    $fileName1 = $form->get('name')->getData().'-1.'.$image1->guessExtension();
                    $image1->move($this->getParameter('images_directory'), $fileName1);
                    $product->setImage1($fileName1);
                }
            $image2 = $form->get('image2')->getData();
                if ($image2 != null){
                    $image2 = $form->get('image2')->getData();
                    $fileName2 = $form->get('name')->getData().'-2.'.$image2->guessExtension();
                    $image2->move($this->getParameter('images_directory'), $fileName2);
                    $product->setImage2($fileName2);
                }
            $entityManager->persist($product);
            $entityManager->flush();
 
            $this->addFlash('success', 'Produit ajouté!');

            return $this->redirectToRoute('app_admin_product_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->renderForm('admin_product/new.html.twig', [
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total'     => $cartService->getTotal($orderDetails),
            'product'   => $product,
            'form'      => $form,
            'products'  => $productRepository->findAll(),
            'products2' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'discount'  => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
        ]);
    }

    #[Route('/admin/product/{id}', name: 'app_admin_product_show', methods: ['GET'])]
    public function show(Product $product, ProductRepository $productRepository, CategoryRepository $categoryRepository, CartService $cartService, OrderDetailsRepository $orderDetails): Response
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

        $data = new SearchData();

        return $this->render('admin_product/show.html.twig', [
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total'     => $cartService->getTotal($orderDetails),
            'product'   => $product,
            'products'  => $productRepository->findSearch($data),
            'products2' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'discount'  => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
        ]);
    }

    #[Route('/admin/product/{id}/edit', name: 'app_admin_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, ProductRepository $productRepository, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager, CartService $cartService, OrderDetailsRepository $orderDetails): Response
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

        // The product form
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        $data = new SearchData();
        
        // Sets the product if the form is valid
        if ($form->isSubmitted() && $form->isValid()) {

            // Retrieves the data given in the images inputs if not null, checks the type of file, 
            // copies & pastes the image in the 'images_directory', sets its name in the database
            $image = $form->get('image')->getData();
                if ($image != null){
                    $fileName = $form->get('name')->getData().'.'.$image->guessExtension();
                    $image->move($this->getParameter('images_directory'), $fileName);
                    $product->setImage($fileName);
                }
            $image1 = $form->get('image1')->getData();
                if ($image1 != null){
                    $fileName1 = $form->get('name')->getData().'-1.'.$image1->guessExtension();
                    $image1->move($this->getParameter('images_directory'), $fileName1);
                    $product->setImage1($fileName1);
                }
            $image2 = $form->get('image2')->getData();
                if ($image2 != null){
                    $fileName2 = $form->get('name')->getData().'-2.'.$image2->guessExtension();
                    $image2->move($this->getParameter('images_directory'), $fileName2);
                    $product->setImage2($fileName2);
                }
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'Produit modifié!');

            return $this->redirectToRoute('app_admin_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_product/edit.html.twig', [
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total'     => $cartService->getTotal($orderDetails),
            'product'   => $product,
            'form'      => $form,
            'product'   => $product,
            'products'  => $productRepository->findSearch($data),
            'products2' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'discount'  => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
        ]);
    }

    #[Route('/admin/product/{id}', name: 'app_admin_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
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

        // Checks if the csrf token is valid in order to delete the product
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Produit supprimé!');

        return $this->redirectToRoute('app_admin_product_index', [], Response::HTTP_SEE_OTHER);
    }

}

