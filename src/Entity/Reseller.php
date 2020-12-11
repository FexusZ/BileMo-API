<?php

namespace App\Entity;

use App\Repository\ResellerRepository;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as Serializer;

use Hateoas\Configuration\Annotation as Hateoas;

use OpenApi\Annotations as OA;

/**
 * @ORM\Entity(repositoryClass=ResellerRepository::class)
 * @UniqueEntity(fields={"email"}, message="Email already use")
 * @Hateoas\Relation("self",
 *      href = @Hateoas\Route("reseller.details", absolute = true),
 *      exclusion = @Hateoas\Exclusion(groups={"reseller:details"})
 * )
 * @Hateoas\Relation("delete",
 *      href = @Hateoas\Route("reseller.delete", absolute = true),
 *      exclusion = @Hateoas\Exclusion(groups={"reseller:details"})
 * )
 * @Hateoas\Relation("create_user",
 *      href = @Hateoas\Route("user.create", absolute = true),
 *      exclusion = @Hateoas\Exclusion(groups={"reseller:details"})
 * )
 *
 * @OA\Schema(
 *      description="Reseller model",
 *      title="Reseller",
 * )
 * @OA\Schema(
 *    schema = "ResellerDetail",
 *    description = "ResellerDetail",
 *     @OA\Property(type = "integer", property = "id"),
 *     @OA\Property(type = "string", property = "email"),
 *     @OA\Property(type = "string", property = "password"),
 * )
 */
class Reseller implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"reseller:details"})
     * @OA\Property(
     *     format="integer",
     *     description="Id",
     * )
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"reseller:details"})
     * @OA\Property(
     *     format="string",
     *     description="Reseller email",
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"reseller:details"})
     * @OA\Property(
     *     format="string",
     *     description="Reseller password",
     * )
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="reseller", orphanRemoval=true)
     * @OA\Property(
     *     ref="#/components/schemas/User",
     *     description="users Model",
     * )
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setReseller($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getReseller() === $this) {
                $user->setReseller(null);
            }
        }

        return $this;
    }

    /**
     * @return string[]
     */
    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function getSalt()
    {
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
    }
}
