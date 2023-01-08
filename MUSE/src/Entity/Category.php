<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ApiResource(
    normalizationContext:["groups"=>"read:category"]
)]
class Category
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', unique: true)]
    #[Groups(["read:category"])]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    #[Groups(["read:category", "read:product"])]
    private $name;

    #[ORM\ManyToOne(targetEntity: self::class, fetch: "EAGER")]
    #[Groups(["read:category"])]
    private ?self $parentCategory = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Product::class)]
    #[Groups(["read:category"])]
    private Collection $product;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["read:category"])]
    private ?string $image = null;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->product = new ArrayCollection();
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

    public function __toString()
    {
        return $this->name;
    }

    public function getParentCategory(): ?self
    {
        return $this->parentCategory;
    }

    public function setParentCategory(?self $parentCategory): self
    {
        $this->parentCategory = $parentCategory;

        return $this;
    }

    public function getPath() {
        if ($this->parentCategory) {
            $tab = $this->parentCategory->getPath();
            $tab[] = $this->name;
            return  $tab;
        }
        else {
            return [ $this->name ];
        }
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->product->contains($product)) {
            $this->product->add($product);
            $product->setCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->product->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCategory() === $this) {
                $product->setCategory(null);
            }
        }

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
}
