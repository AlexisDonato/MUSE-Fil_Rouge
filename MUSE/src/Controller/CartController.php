<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Form\CouponInsertType;
use App\Service\Cart\CartService;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OrderDetailsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart_index')]

    public function index(Request $request, CouponRepository $couponRepository, CartService $cartService, ProductRepository $productRepository, CategoryRepository $categoryRepository, ?UserInterface $user, ?OrderDetailsRepository $orderDetails, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_CLIENT')) {
            $this->addFlash('info', 'Merci de vous connecter ou de vous inscrire au préalable');
            return $this->redirectToRoute('login');  
        }

        if ($this->getUser()->getUserIdentifier() != $user->getUserIdentifier()) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");
        }

        $data = new SearchData();

        $cartService->setUser($user);
        $total = $cartService->getTotal($orderDetails);

        $cart = $cartService->getClientCart();

        $couponInsertform = $this->createForm(CouponInsertType::class);
        $couponInsertform->handleRequest($request);
        $couponCode = $couponInsertform->get('code')->getData();
        $couponInsert = $couponRepository->findOneBy(["code" => $couponCode]);

        $coupon = null;
        if ($couponInsert) {
            $coupon = $couponRepository->findOneByCartAndCoupon($couponInsert, $this->getUser());
        }

        if ($couponInsertform->isSubmitted() && $couponInsertform->isValid()) {

            if ($coupon) {
                $cart->setCoupon($coupon);
                $cart->setAdditionalDiscountRate($cart->getCoupon()->getDiscountRate());

                $entityManager->persist($cart);
                $entityManager->persist($cart->getCoupon());

                $entityManager->flush();

                $this->addFlash('success', 'Bon de réduction appliqué !');
            // Redirects to the last page :
            $route = $request->headers->get('referer');
            return $this->redirect($route);
            }
            else {
                $this->addFlash('error', 'Bon de réduction invalide');
            // Redirects to the last page :
            $route = $request->headers->get('referer');
            return $this->redirect($route);
            }
        }
    
        return $this->render('cart/index.html.twig', [
            'couponInsertform'      =>$couponInsertform->createView(),
            'total'     => $total,
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'products'  => $productRepository->findSearch($data),
            'products2' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'discount'  => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
        ]);
    }

    #[Route('/cart/add/{id}', name: 'app_cart_add')]
    public function add($id, CartService $cartService, ?UserInterface $user, Request $request) 
    {
        if (!$this->isGranted('ROLE_CLIENT')) {
            $this->addFlash('info', 'Merci de vous connecter ou de vous inscrire au préalable');
            return $this->redirectToRoute('login');  
        }

        if ($this->getUser()->getUserIdentifier() != $user->getUserIdentifier()) {
            $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");
        }

        $cartService->setUser($user);
        $cartService->addOrRemove($id);

        // Redirects to the last page :
        $route = $request->headers->get('referer');
        return $this->redirect($route);
    }

    #[Route('/cart/remove/{id}', name: 'app_cart_remove')]
    public function remove($id, CartService $cartService, ?UserInterface $user, Request $request) 
    {
        if (!$this->isGranted('ROLE_CLIENT')) {
            $this->addFlash('error', 'Accès refusé');
            return $this->redirectToRoute('login');  
        }

        if ($this->getUser()->getUserIdentifier() != $user->getUserIdentifier()) {
            $this->denyAccessUnlessGranted('ROLE_SALES', null, 'User tried to access a page without having ROLE_SALES');
        }

        $cartService->setUser($user);
        $cartService->addOrRemove($id, $remove=true);

        $route = $request->headers->get('referer');
        return $this->redirect($route);
    }

    #[Route('/cart/deleteAll', name: 'app_cart_deleteAll')]
    public function deleteALL(CartService $cartService, ?UserInterface $user) 
    {
        if (!$this->isGranted('ROLE_CLIENT')) {
            $this->addFlash('error', 'Accès refusé');
            return $this->redirectToRoute('login');  
        }

        if ($this->getUser()->getUserIdentifier() != $user->getUserIdentifier()) {
            $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");
        }

        $cartService->setUser($user);
        $cartService->deleteALL();

        $this->addFlash('success', 'Votre panier a bien été vidé.');
        return $this->redirectToRoute('app_home');
    }

    #[Route('/cart/delete/{id}', name: 'app_cart_delete')]
    public function delete($id, CartService $cartService, ?UserInterface $user, Request $request) 
    {
        if (!$this->isGranted('ROLE_CLIENT')) {
            $this->addFlash('error', 'Accès refusé');
            return $this->redirectToRoute('login');  
        }

        if ($this->getUser()->getUserIdentifier() != $user->getUserIdentifier()) {
            $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");
        }

        $cartService->setUser($user);
        $cartService->delete($id);

        $route = $request->headers->get('referer');
        return $this->redirect($route);
    }
}
