<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 *
 * @Hateoas\Relation("self",
 *      href = @Hateoas\Route("product.details", parameters = {"id" = "expr(object.getId())"}, absolute = true),
 *      exclusion = @Hateoas\Exclusion(groups={"product:details", "product:list"})
 * )
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"product:list","product:details"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"product:list","product:details"})
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Serializer\Groups({"product:details"})
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Serializer\Groups({"product:details"})
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"product:details"})
     */
    private $year;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }
}
