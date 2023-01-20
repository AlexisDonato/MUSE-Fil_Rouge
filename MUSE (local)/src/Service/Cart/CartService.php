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

    // Retrieves the client cart
    public function getClientCart() {
        if (isset($this->user)) {
            return $this->cartRepository->findOneByUser($this->user->getId());
        } else {
            return null;
        }
    }

    // Retrieves the cart order details
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

    // Retrieves the validated cart order details
    public function getValidatedOrderDetails($clientCart) {
        $orderDetails = $this->orderDetailsRepository->createQueryBuilder('o')
        ->join(Cart::class, 'c', 'WITH', 'o.cart = c.id')
        ->where('o.cart = :cart_id')
        ->setParameter('cart_id', $clientCart->getId())
        ->getQuery()
        ->getResult();
        
        return $orderDetails;
    }

    // The method "addOrRemove" from the CartService works with a boolean, either adds or removes one product
    #[IsGranted('ROLE_CLIENT')]
    public function addOrRemove(int $id, bool $remove=false)
    {
        // Retrieves the client cart
        $clientCart = $this->getClientCart();
        // Retrieves the cart order details
        $orderDetails = $this->getOrderDetails($clientCart, $id);
        $orderDetails->setCart($clientCart);
        // Retrieves the product
        $product = $this->productRepository->find($id);
        // Binds the order details with the product
        $orderDetails->setProduct($product);
        // Retrieves the order details quantity
        $quantity = $orderDetails->getQuantity();
        // Retrieves the product stock quantity
        $productQuantity = $product->getQuantity();
        $cart = $this->getCart();
        // Retrieves the user corresponding VAT
        $vat = $clientCart->getUser()->getVat();

        // Retrieves the product discount rate
        $discountRate = $product->getDiscountRate();

        // Retrieves the additional discount rate
        $additionalDiscountRate = $clientCart->getAdditionalDiscountRate();

        if ($remove) {
            // Adds one product to the order details quantity 
            $product->setQuantity($productQuantity + 1);
            // And removes one product from the product stock quantity
            $quantity--;
            if ($quantity == 0) {
                $this->entityManager->remove($orderDetails);
                $this->entityManager->flush();
                return;
            }    
        } else {
            // Removes one product from the order details quantity
            $product->setQuantity($productQuantity - 1);
            // And adds one product to the product stock quantity
            $quantity++;
        }

        $orderDetails->setQuantity($quantity); 

        // Sets the subtotal
        $orderDetails->setSubTotal($product->getPrice() * $quantity * (1 - ($discountRate + $additionalDiscountRate)) * (1 + $vat));

        $this->entityManager->persist($orderDetails);
        $this->entityManager->persist($product);
        $this->entityManager->flush();
        
    }

    // Removing the whole line of the same product, regardless of quantities
    #[IsGranted('ROLE_CLIENT')]
    public function delete(int $id)
    {
        // Retrieves the client cart
        $clientCart = $this->getClientCart();
        // Retrieves the order details and corresponding quantities
        $orderDetails = $this->getOrderDetails($clientCart, $id);
        $quantity = $orderDetails->getQuantity();
        // Retrieves the corresponding product
        $product = $this->productRepository->find($id);
        // Retrieves the corresponding product stock quantities
        $productQuantity = $product->getQuantity();

        // Brings back the ordered quantity in the stock before removing the product
        $product->setQuantity($productQuantity + $quantity);

        $this->entityManager->remove($orderDetails);
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }

    // Removing the cart
    #[IsGranted('ROLE_CLIENT')]
    public function deleteAll()
    {
        // Retrieves the client cart
        $clientCart = $this->getClientCart();

        $orderDetails = $this->orderDetailsRepository->createQueryBuilder('o')
        ->join(Cart::class, 'c', 'WITH', 'o.cart = c.id')
        ->where('o.cart = :cart_id')
        ->setParameter('cart_id', $clientCart->getId())
        ->getQuery()
        ->getResult();

        foreach($orderDetails as $orderDetail) 
        {
            // Gets the id and order quantity of each product
            $productId = $orderDetail->getProductId();
            $quantity = $orderDetail->getQuantity();
            // Finds the id and stock quantity for each product
            $product = $this->productRepository->find($productId);
            $productQuantity = $product->getQuantity();

            // Brings back the ordered quantity in the stock before removing the cart
            $product->setQuantity($productQuantity + $quantity);

            $this->entityManager->persist($product);
        }
        $this->entityManager->remove($clientCart);
        $this->entityManager->flush();
    }

    // Counting for the cart badge pill
    #[IsGranted('ROLE_CLIENT')]
    public function getItemCount(OrderDetailsRepository $orderDetails) : int
    {
        $count = -1;
        // Retrieves the client cart
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

    // Gets the full cart
    #[IsGranted('ROLE_CLIENT')]
    public function getFullCart(OrderDetailsRepository $orderDetails) {
        // Retrieves the client cart
        $clientCart = $this->getClientCart();
        if ($clientCart != null) {
                $full = $orderDetails->createQueryBuilder('o')
                ->join(Product::class, 'p', 'WITH', 'o.product = p.id')
                ->where('o.cart = :val')
                ->setParameter('val', $clientCart->getId())
                ->getQuery()
                ->getResult();
                return $full;
            }
            
    }
              
    // Fetches the total amount of the cart
    public function getTotal(?OrderDetailsRepository $orderDetails) : float
    {
        // Retrieves the client cart
        $clientCart =$this->getClientCart();

        if ($clientCart != null) {
            $total = $orderDetails->createQueryBuilder('o')
            // 'COALESCE' allows to not take he null data into account
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
