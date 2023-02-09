<?php

namespace App\Entity;

use DateTime;
use App\Entity\Address;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[ApiResource(
    normalizationContext: [ "groups" => ["read:user"]]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', unique: true)]
    #[Groups(["read:user"])]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(["read:user", "read:address", "read:cart"])]
    private $email;

    #[ORM\Column(type: 'json')]
    #[Groups(["read:user"])]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    #[Groups(["read:user"])]
    private $password;

    #[ORM\Column(type: 'string', length: 100)]
    #[Groups(["read:user"])]
    private $userName;

    #[ORM\Column(type: 'string', length: 100)]
    #[Groups(["read:user"])]
    private $userLastname;

    #[ORM\Column(type: 'datetime')]
    #[Groups(["read:user"])]
    private $birthdate;

    #[ORM\Column(type: 'string', length: 25)]
    #[Groups(["read:user"])]
    private $phoneNumber;

    #[ORM\Column]
    #[Groups(["read:user"])]
    private ?bool $verified = false;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Cart::class)]
    #[Groups(["read:user"])]
    private Collection $carts;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["read:user"])]
    private ?\DateTimeInterface $registerDate = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2, nullable: true)]
    #[Groups(["read:user"])]
    private ?string $vat = "0.2";

    #[ORM\Column]
    #[Groups(["read:user", "read:cart"])]
    private ?bool $pro = false;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(["read:user"])]
    private ?string $proCompanyName = null;

    #[ORM\Column(length: 9, nullable: true)]
    #[Groups(["read:user"])]
    private ?string $proDuns = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(["read:user"])]
    private ?string $proJobPosition = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Address::class)]
    #[Groups(["read:user"])]
    private Collection $address;

    #[ORM\Column(nullable: true)]
    #[Groups(["read:user"])]
    private ?bool $agreeTerms = null;

    public function __construct()
    {
        $this->carts = new ArrayCollection();
        $this->address = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    public function getUserLastName(): ?string
    {
        return $this->userLastname;
    }

    public function setUserLastName(string $userLastname): self
    {
        $this->userLastname = $userLastname;

        return $this;
    }

    public function getBirthdate(): ?DateTime
    {
        return $this->birthdate;
    }

    public function setBirthdate(DateTime $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    // /**
    //  * @deprecated since Symfony 5.3, use getUserIdentifier instead
    //  */
    // public function getUsername(): string
    // {
    //     return (string) $this->email;
    // }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->verified;
    }

    public function setIsVerified(bool $verified): self
    {
        $this->verified = $verified;

        return $this;
    }

    /**
     * @return Collection<int, Cart>
     */
    public function getCarts(): Collection
    {
        return $this->carts;
    }

    public function addCart(Cart $cart): self
    {
        if (!$this->carts->contains($cart)) {
            $this->carts[] = $cart;
            $cart->setUser($this);
        }

        return $this;
    }

    public function removeCart(Cart $cart): self
    {
        if ($this->carts->removeElement($cart)) {
            // set the owning side to null (unless already changed)
            if ($cart->getUser() === $this) {
                $cart->setUser(null);
            }
        }

        return $this;
    }

    public function getRegisterDate(): ?\DateTimeInterface
    {
        return $this->registerDate;
    }

    public function setRegisterDate(\DateTimeInterface $registerDate): self
    {
        $this->registerDate = $registerDate;

        return $this;
    }

    public function getVat(): ?string
    {
        return $this->vat;
    }

    public function setVat(?string $vat): self
    {
        $this->vat = $vat;

        return $this;
    }

    public function isPro(): ?bool
    {
        return $this->pro;
    }

    public function setPro(bool $pro): self
    {
        $this->pro = $pro;

        return $this;
    }

    public function getProCompanyName(): ?string
    {
        return $this->proCompanyName;
    }

    public function setProCompanyName(?string $proCompanyName): self
    {
        $this->proCompanyName = $proCompanyName;

        return $this;
    }

    public function getProDuns(): ?string
    {
        return $this->proDuns;
    }

    public function setProDuns(?string $proDuns): self
    {
        $this->proDuns = $proDuns;

        return $this;
    }

    public function getProJobPosition(): ?string
    {
        return $this->proJobPosition;
    }

    public function setProJobPosition(?string $proJobPosition): self
    {
        $this->proJobPosition = $proJobPosition;

        return $this;
    }

    /**
     * @return Collection<int, Address>
     */
    public function getAddress(): Collection
    {
        return $this->address;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->address->contains($address)) {
            $this->address->add($address);
            $address->setUser($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->address->removeElement($address)) {
            // set the owning side to null (unless already changed)
            if ($address->getUser() === $this) {
                $address->setUser(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->email;
    }

    public function isAgreeTerms(): ?bool
    {
        return $this->agreeTerms;
    }

    public function setAgreeTerms(?bool $agreeTerms): self
    {
        $this->agreeTerms = $agreeTerms;

        return $this;
    }
}