<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Serializer\Annotation\Groups;

use Symfony\Component\Validator\Constraints as Assert;

use JMS\Serializer\Annotation as Serializer;

use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 *
 * @Hateoas\Relation("_self",
 *      href = @Hateoas\Route("user.detail", parameters = {"id" = "expr(object.getId())"}, absolute = true),
 *      exclusion = @Hateoas\Exclusion(groups={"user:details", "user:list"})
 * )
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups({"user:list", "user:details"})
     *
     * @Serializer\Groups({"user:list", "user:details"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * 
     * @Groups({"user:details"})
     *
     * @Assert\NotBlank
     * @Assert\Email
     *
     *
     * @Serializer\Groups({"user:list", "user:details"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * 
     * @Groups({"user:list", "user:details"})
     * @Assert\NotBlank
     * @Assert\Length(min=3)
     *
     * @Serializer\Groups({"user:details"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * 
     * @Groups({"user:details"})
     * @Assert\NotBlank
     * @Assert\Length(min=3)
     *
     * @Serializer\Groups({"user:details"})
     */
    private $password;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     */
    private $client;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    private function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }
}
