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
        // Access restriction for roles other than 'ROLE_CLIENT'
        if (!$this->isGranted('ROLE_CLIENT')) {
            $this->addFlash('info', 'Merci de vous connecter ou de vous inscrire au préalable');
            return $this->redirectToRoute('login');  
        }

        // The user cannot access other users infos:
        if ($this->getUser()->getUserIdentifier() != $user->getUserIdentifier()) {
            $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");
        }

        $data = new SearchData();

        // Needed for using the CartService
        $cartService->setUser($user);

        $total = $cartService->getTotal($orderDetails);

        // Retrieves the client cart
        $cart = $cartService->getClientCart();

        // The coupon form
        $couponInsertform = $this->createForm(CouponInsertType::class);
        $couponInsertform->handleRequest($request);
        $couponInsert = $couponInsertform->get('code')->getData();
        $couponCode = $couponRepository->findOneBy(["code" => $couponInsert]);

        // Checks if the coupon exists
        $coupon = null;
        if ($couponCode) {
            $coupon = $couponRepository->findOneByCartAndCoupon($couponCode, $this->getUser());
        }

        // Sets a discount rate on the cart if the form is valid
        if ($couponInsertform->isSubmitted() && $couponInsertform->isValid()) {

            if ($coupon && $coupon->isValidated(true)) {
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
        // Access restriction for roles other than 'ROLE_CLIENT'
        if (!$this->isGranted('ROLE_CLIENT')) {
            $this->addFlash('info', 'Merci de vous connecter ou de vous inscrire au préalable');
            return $this->redirectToRoute('login');  
        }

        // The user cannot access other users infos:
        if ($this->getUser()->getUserIdentifier() != $user->getUserIdentifier()) {
            $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");
        }

        $cartService->setUser($user);

        // The method "addOrRemove" from the CartService works with a boolean, here the methods adds one product
        $cartService->addOrRemove($id);

        // Redirects to the last page :
        $route = $request->headers->get('referer');
        return $this->redirect($route);
    }

    #[Route('/cart/remove/{id}', name: 'app_cart_remove')]
    public function remove($id, CartService $cartService, ?UserInterface $user, Request $request) 
    {
        // Access restriction for roles other than 'ROLE_CLIENT'
        if (!$this->isGranted('ROLE_CLIENT')) {
            $this->addFlash('info', 'Merci de vous connecter ou de vous inscrire au préalable');
            return $this->redirectToRoute('login');  
        }

        // The user cannot access other users infos:
        if ($this->getUser()->getUserIdentifier() != $user->getUserIdentifier()) {
            $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");
        }

        $cartService->setUser($user);

        // The method "addOrRemove" from the CartService works with a boolean, here the methods removes one product
        $cartService->addOrRemove($id, $remove=true);

        $route = $request->headers->get('referer');
        return $this->redirect($route);
    }

    #[Route('/cart/deleteAll', name: 'app_cart_deleteAll')]
    public function deleteALL(CartService $cartService, ?UserInterface $user) 
    {
        // Access restriction for roles other than 'ROLE_CLIENT'
        if (!$this->isGranted('ROLE_CLIENT')) {
            $this->addFlash('info', 'Merci de vous connecter ou de vous inscrire au préalable');
            return $this->redirectToRoute('login');  
        }

        // The user cannot access other users infos:
        if ($this->getUser()->getUserIdentifier() != $user->getUserIdentifier()) {
            $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");
        }

        $cartService->setUser($user);

        // Removes the cart
        $cartService->deleteALL();

        $this->addFlash('success', 'Votre panier a bien été vidé.');
        return $this->redirectToRoute('app_home');
    }

    #[Route('/cart/delete/{id}', name: 'app_cart_delete')]
    public function delete($id, CartService $cartService, ?UserInterface $user, Request $request) 
    {
        // Access restriction for roles other than 'ROLE_CLIENT'
        if (!$this->isGranted('ROLE_CLIENT')) {
            $this->addFlash('info', 'Merci de vous connecter ou de vous inscrire au préalable');
            return $this->redirectToRoute('login');  
        }

        // The user cannot access other users infos:
        if ($this->getUser()->getUserIdentifier() != $user->getUserIdentifier()) {
            $this->denyAccessUnlessGranted('ROLE_SALES', null, "Vous n'avez pas les autorisations nécessaires pour accéder à la page");
        }

        $cartService->setUser($user);

        // Removes the whole line of the same product, regardless of quantities
        $cartService->delete($id);

        $route = $request->headers->get('referer');
        return $this->redirect($route);
    }
}
