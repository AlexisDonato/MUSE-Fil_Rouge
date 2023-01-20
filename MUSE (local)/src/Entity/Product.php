<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ApiResource(
    normalizationContext:["groups"=>"read:product"]
)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', unique: true)]
    #[Groups(["read:product"])]
    private $id;

    #[ORM\Column(type: 'string', length: 150)]
    #[Groups(["read:product", "read:orderDetail"])]
    private $name;

    #[ORM\Column(type: 'integer')]
    #[Groups(["read:product"])]
    private $price;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["read:product"])]
    private $description;

    #[ORM\Column(type: 'text')]
    #[Groups(["read:product"])]
    private $content;

    #[ORM\Column(type: 'boolean')]
    #[Groups(["read:product"])]
    private $discount;

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 3)]
    #[Groups(["read:product"])]
    private ?string $discountRate = '0';

    #[ORM\Column(nullable: true)]
    #[Groups(["read:product"])]
    private ?int $quantity = null;

    #[ORM\Column(length: 200, nullable: true)]
    #[Groups(["read:product"])]
    private ?string $image = null;

    #[ORM\Column(length: 200, nullable: true)]
    #[Groups(["read:product"])]
    private ?string $image1 = null;

    #[ORM\Column(length: 200, nullable: true)]
    #[Groups(["read:product"])]
    private ?string $image2 = null;

    #[ORM\ManyToOne(fetch: "EAGER")]
    #[Groups(["read:product"])]
    private ?Supplier $supplier = null;

    #[ORM\ManyToOne(inversedBy: 'product', fetch: "EAGER")]
    #[Groups(["read:product"])]
    private ?Category $category = null;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function isDiscount(): ?bool
    {
        return $this->discount;
    }

    public function setDiscount(bool $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getImage1(): ?string
    {
        return $this->image1;
    }

    public function setImage1(?string $image1): self
    {
        $this->image1 = $image1;

        return $this;
    }

    public function getImage2(): ?string
    {
        return $this->image2;
    }

    public function setImage2(?string $image2): self
    {
        $this->image2 = $image2;

        return $this;
    }

    public function getDiscountRate(): ?string
    {
        $discountRate = $this->discountRate;
        $discountRate = '0';
        return $this->discountRate;
    }

    public function setDiscountRate(?string $discountRate): self
    {
        $this->discountRate = $discountRate;

        return $this;
    }

    public function getSupplier(): ?Supplier
    {
        return $this->supplier;
    }

    public function setSupplier(?Supplier $supplier): self
    {
        $this->supplier = $supplier;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }


}
