<?php

namespace App\Service\Cart;

use App\Entity\Cart;
use App\Entity\User;
use App\Entity\Product;
use App\Entity\OrderDetails;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OrderDetailsRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class CartService
{
    protected $session;
    protected $productRepository;
    protected $cartRepository;
    protected $orderDetailsRepository;
    protected $entityManager;
    protected $UserInterface;
    protected $security;
    private $clientCart;
    private $user;

    #[IsGranted('ROLE_CLIENT')]
    public function __construct(?CartRepository $cartRepository, OrderDetailsRepository $orderDetailsRepository, ?UserInterface $user, SessionInterface $session, ProductRepository $productRepository, Security $security, EntityManagerInterface $entityManager)
    {
        if ($security->isGranted('ROLE_CLIENT')) {
            $this->user = $user;
            $this->session = $session;
            $this->productRepository = $productRepository;
            $this->cartRepository = $cartRepository;
            $this->orderDetailsRepository = $orderDetailsRepository;
            $this->entityManager = $entityManager;
            $this->security = $security;
        }
    }

    public function getClientCart() {
        if (isset($this->user)) {
            return $this->cartRepository->findOneByUser($this->user->getId());
        } else {
            return null;
        }
    }

    public function getClientValidatedCart() {
        if (isset($this->user)) {
            return $this->cartRepository->findOneByUser($this->user->getId());
        } else {
            return null;
        }
    }


    public function getOrderDetails($clientCart, $productId): ?OrderDetails {
        $orderDetails = $this->orderDetailsRepository->createQueryBuilder('o')
        ->join(Cart::class, 'c', 'WITH', 'o.cart = c.id')
        ->where('o.cart = :cart_id')
        ->andWhere('o.product = :product_id')
        ->setParameter('cart_id', $clientCart->getId())
        ->setParameter('product_id', $productId)
        ->getQuery()
        ->getOneOrNullResult();
        
        if ($orderDetails == null)
            $orderDetails = new OrderDetails();

        return $orderDetails;
    }

    public function getValidatedOrderDetails($clientCart) {
        $orderDetails = $this->orderDetailsRepository->createQueryBuilder('o')
        ->join(Cart::class, 'c', 'WITH', 'o.cart = c.id')
        ->where('o.cart = :cart_id')
        ->setParameter('cart_id', $clientCart->getId())
        ->getQuery()
        ->getResult();
        
        return $orderDetails;
    }

    #[IsGranted('ROLE_CLIENT')]
    public function addOrRemove(int $id, bool $remove=false)
    {
        $clientCart = $this->getClientCart();
        $orderDetails = $this->getOrderDetails($clientCart, $id);
        $orderDetails->setCart($clientCart);
        $product = $this->productRepository->find($id);
        $orderDetails->setProduct($product);
        $quantity = $orderDetails->getQuantity();
        $productQuantity = $product->getQuantity();
        $cart = $this->getCart();
        $vat = $clientCart->getUser()->getVat();

        $discountRate = $product->getDiscountRate();
        $additionalDiscountRate = $clientCart->getAdditionalDiscountRate();

        if ($remove) {
            $product->setQuantity($productQuantity + 1);
            $quantity--;
            if ($quantity == 0) {
                $this->entityManager->remove($orderDetails);
                $this->entityManager->flush();
                return;
            }    
        } else {
            $product->setQuantity($productQuantity - 1);
            $quantity++;
        }

        $orderDetails->setQuantity($quantity); 
        $orderDetails->setSubTotal($product->getPrice() * $quantity * (1 - ($discountRate + $additionalDiscountRate)) * (1 + $vat));

        $this->entityManager->persist($orderDetails);
        $this->entityManager->persist($product);
        $this->entityManager->flush();
        
    }

    #[IsGranted('ROLE_CLIENT')]
    public function delete(int $id)
    {
        $clientCart = $this->getClientCart();
        $orderDetails = $this->getOrderDetails($clientCart, $id);
        $quantity = $orderDetails->getQuantity();
        $product = $this->productRepository->find($id);
        $productQuantity = $product->getQuantity();
        $product->setQuantity($productQuantity + $quantity);

        $this->entityManager->remove($orderDetails);
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }

    #[IsGranted('ROLE_CLIENT')]
    public function deleteAll()
    {
        $clientCart = $this->getClientCart();
        $orderDetails = $this->orderDetailsRepository->createQueryBuilder('o')
        ->join(Cart::class, 'c', 'WITH', 'o.cart = c.id')
        ->where('o.cart = :cart_id')
        ->setParameter('cart_id', $clientCart->getId())
        ->getQuery()
        ->getResult();

        foreach($orderDetails as $orderDetail) 
        {
            $productId = $orderDetail->getProductId();
            $quantity = $orderDetail->getQuantity();
            $product = $this->productRepository->find($productId);
            $productQuantity = $product->getQuantity();
            $product->setQuantity($productQuantity + $quantity);

            $this->entityManager->persist($product);
        }
        $this->entityManager->remove($clientCart);
        $this->entityManager->flush();
    }

    #[IsGranted('ROLE_CLIENT')]
    public function getItemCount(OrderDetailsRepository $orderDetails) : int
    {
        $count = -1;
        $clientCart = $this->getClientCart();
        if ($clientCart != null) {
            $count = $orderDetails->createQueryBuilder('c')
            ->select('sum(c.quantity)')
            ->where('c.cart = :val')
            ->setParameter('val', $clientCart->getId())
            ->getQuery()
            ->getResult()[0][1];
        }

        if ($count == null)
            return 0;
 
        return (int)$count;
    }

    #[IsGranted('ROLE_CLIENT')]
    public function getFullCart(OrderDetailsRepository $orderDetails) {
        $clientCart = $this->getClientCart();
        if ($clientCart != null) {
                $rs = $orderDetails->createQueryBuilder('o')
                ->join(Product::class, 'p', 'WITH', 'o.product = p.id')
                ->where('o.cart = :val')
                ->setParameter('val', $clientCart->getId())
                ->getQuery()
                ->getResult();
                return $rs;
            }
            
    }
              
    public function getTotal(?OrderDetailsRepository $orderDetails) : float
    {
        $clientCart =$this->getClientCart();

        if ($clientCart != null) {
            $total = $orderDetails->createQueryBuilder('o')
            ->select('sum((p.price * o.quantity) * (1-(COALESCE(p.discountRate,0))-(COALESCE(c.additionalDiscountRate,0))) * (1+u.vat))')
            ->join(Product::class, 'p', 'WITH', 'o.product = p.id')
            ->join(Cart::class, 'c', 'WITH', 'o.cart = c.id')
            ->join(User::class, 'u', 'WITH', 'c.user = u.id')
            ->where('o.cart = :val')
            ->setParameter('val', $clientCart->getId())
            ->getQuery()
            ->getResult()[0][1];

            if ($total == null) {
                return 0;
            }

            return $total;

        }

        return 0;
    }


    /**
     * Get the value of session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Set the value of user
     */
    public function setUser($user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the value of user
     */
    public function getUser()
    {
        return $this->user;
    }


    public function getCart() {
        return $this->clientCart;
    }

    public function setCart($clientCart) {
        $this->clientCart = $clientCart;
        return $this;
    }
}
