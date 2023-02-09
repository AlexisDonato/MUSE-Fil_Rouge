<?php

namespace App\Entity;

use App\Entity\Product;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrderDetailsRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OrderDetailsRepository::class)]
#[ApiResource(
    normalizationContext:["groups"=>"read:orderDetail"]
)]
class OrderDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(unique: true)]
    #[Groups(["read:orderDetail", "read:cart"])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(["read:orderDetail", "read:cart"])]
    private ?int $productId = null;

    #[ORM\Column]
    #[Groups(["read:orderDetail", "read:cart"])]
    private ?int $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'orderDetails', fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["read:orderDetail"])]
    private ?Cart $cart = null;

    #[ORM\ManyToOne(fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["read:orderDetail", "read:cart"])]
    private ?Product $product = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["read:orderDetail", "read:cart"])]
    private ?float $subTotal = null;

    public function __construct()
    {
        $this->orderProductId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): self
    {
        $this->productId = $productId;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getCartId(): ?Cart
    {
        return $this->cartId;
    }

    public function setCartId(?Cart $cartId): self
    {
        $this->cartId = $cartId;

        return $this;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): self
    {
        $this->cart = $cart;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getSubTotal(): ?float
    {
        return $this->subTotal;
    }

    public function setSubTotal(?float $subTotal): self
    {
        $this->subTotal = $subTotal;

        return $this;
    }

    public function __toString()
    {
        return $this->id;
    }
}
