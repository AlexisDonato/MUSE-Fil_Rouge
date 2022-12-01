<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(unique: true)]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $country = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $zipcode = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $pathType = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $pathNumber = null;

    #[ORM\ManyToOne(inversedBy: 'address', fetch: "EAGER")]
    private ?User $user = null;

    #[ORM\Column(nullable: true)]
    private ?bool $billingAddress = null;

    #[ORM\Column(nullable: true)]
    private ?bool $deliveryAddress = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(?string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPathType(): ?string
    {
        return $this->pathType;
    }

    public function setPathType(?string $pathType): self
    {
        $this->pathType = $pathType;

        return $this;
    }

    public function getPathNumber(): ?string
    {
        return $this->pathNumber;
    }

    public function setPathNumber(?string $pathNumber): self
    {
        $this->pathNumber = $pathNumber;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function __toString()
    {
        if($this->isDeliveryAddress(true)){
            $deliveryAddress = ' (Livraison)';
        } else {
            $deliveryAddress = '';
        }
        if ($this->isBillingAddress(true)){
            $billingAddress = ' (Facturation)';
        }else {
            $billingAddress = '';
        }

        return $this->name . $deliveryAddress . $billingAddress . " : " . $this->pathNumber . " " . $this->pathType . " " . $this->zipcode . " " . $this->city;
    }

    public function isBillingAddress(): ?bool
    {
        return $this->billingAddress;
    }

    public function setBillingAddress(?bool $billingAddress): self
    {
        $this->billingAddress = $billingAddress;

        return $this;
    }

    public function isDeliveryAddress(): ?bool
    {
        return $this->deliveryAddress;
    }

    public function setDeliveryAddress(?bool $deliveryAddress): self
    {
        $this->deliveryAddress = $deliveryAddress;

        return $this;
    }

    public function getFullName() {

        if($this->isDeliveryAddress(true)){
            $deliveryAddress = ' (Livraison)';
        } else {
            $deliveryAddress = '';
        }
        if ($this->isBillingAddress(true)){
            $billingAddress = ' (Facturation)';
        }else {
            $billingAddress = '';
        }

        return $this->name . $deliveryAddress . $billingAddress . " : " . $this->pathNumber . " " . $this->pathType . " " . $this->zipcode . " " . $this->city;
    }
}
// $this->billingAddress . '' . $this->deliveryAddress = true . 