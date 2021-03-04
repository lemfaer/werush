<?php

/**
 * @noinspection PhpUnusedAliasInspection
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Listener\UserListener;
use App\Repository\UserRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\EntityListeners({UserListener::class})
 */
#[
    ApiResource,
    UniqueEntity("name"),
    UniqueEntity("email"),
]
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    #[Assert\NotBlank(message: "Please enter username")]
    protected ?string $name = null;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
     #[
         Assert\NotBlank(message: "Please enter user email"),
         Assert\Email,
     ]
    protected ?string $email = null;

    /**
     * @ORM\Column(type="string", length=64)
     */
    #[
        Assert\NotBlank(message: "Please enter password"),
        Assert\Length(min: 8, minMessage: "Password length should be >= 8"),
        Assert\Regex("/[a-zA-Z]/", message: "Should contain letter"),
        Assert\Regex("/[0-9]/", message: "Should contain digit"),
        Assert\Regex("/\W/", message: "Should contain special symbol"),
    ]
    protected ?string $password = null;

    /**
     * @ORM\Column(type="datetime")
     */
    protected ?DateTimeInterface $created_at = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected ?DateTimeInterface $updated_at = null;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getRoles(): array
    {
        return [];
    }

    public function getSalt(): ?string
    {
        return null;
    }

    #[Pure]
    public function getUsername(): string
    {
        return $this->getName() ?? "";
    }

    public function eraseCredentials(): void
    {}
}
