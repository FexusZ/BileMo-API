<?php

namespace App\Entity;

use App\Repository\UserRepository;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use JMS\Serializer\Annotation as Serializer;

use Hateoas\Configuration\Annotation as Hateoas;

use OpenApi\Annotations as OA;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="Email already use")
 * @Hateoas\Relation("self",
 *      href = @Hateoas\Route("user.detail", parameters = {"id" = "expr(object.getId())"}, absolute = true),
 *      exclusion = @Hateoas\Exclusion(groups={"user:details", "user:list"})
 * )
 * @Hateoas\Relation("modify",
 *      href = @Hateoas\Route("user.update", parameters = {"id" = "expr(object.getId())"}, absolute = true),
 *      exclusion = @Hateoas\Exclusion(groups={"user:details", "user:list"})
 * )
 * @Hateoas\Relation("delete",
 *      href = @Hateoas\Route("user.delete", parameters = {"id" = "expr(object.getId())"}, absolute = true),
 *      exclusion = @Hateoas\Exclusion(groups={"user:details", "user:list"})
 * )
 *
 * @OA\Schema(
 *      description="User model",
 *      title="User",
 * )
 * @OA\Schema(
 *    schema = "UserDetail",
 *    description = "UserDetail",
 *     @OA\Property(type = "integer", property = "id"),
 *     @OA\Property(type = "string", property = "email"),
 *     @OA\Property(type = "string", property = "username"),
 *     @OA\Property(type = "string", property = "password")
 * )
 *
 * @OA\Schema(
 *    schema = "UserList",
 *    description = "UserList",
 *     @OA\Property(type="integer", property="id"),
 *     @OA\Property(type="string", property="email")
 * )
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"user:list", "user:details"})
     * @OA\Property(
     *     format="integer",
     *     description="Id",
     * )
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank
     * @Assert\Email
     * @Serializer\Groups({"user:list", "user:details"})
     * @OA\Property(
     *     format="string",
     *     description="User email",
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank
     * @Assert\Length(min=3)
     * @Serializer\Groups({"user:details"})
     * @OA\Property(
     *     format="string",
     *     description="User username",
     * )
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank
     * @Assert\Length(min=3)
     * @Serializer\Groups({"user:details"})
     * @OA\Property(
     *     format="string",
     *     description="User password",
     * )
     */
    private $password;

    /**
     * @ORM\ManyToOne(targetEntity=Reseller::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     * @OA\Property(
     *     ref="#/components/schemas/Reseller",
     *     description="Reseller model",
     * )
     */
    private $reseller;

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

    public function getReseller(): ?Reseller
    {
        return $this->reseller;
    }

    public function setReseller(?Reseller $reseller): self
    {
        $this->reseller = $reseller;

        return $this;
    }
}