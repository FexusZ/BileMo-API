<?php

namespace App\Entity;

use App\Repository\ProductRepository;

use Doctrine\ORM\Mapping as ORM;

use Hateoas\Configuration\Annotation as Hateoas;

use JMS\Serializer\Annotation as Serializer;

use OpenApi\Annotations as OA;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 *
 * @Hateoas\Relation("self",
 *      href = @Hateoas\Route("product.details", parameters = {"id" = "expr(object.getId())"}, absolute = true),
 *      exclusion = @Hateoas\Exclusion(groups={"product:details", "product:list"})
 * )
 * @OA\Schema(
 *      description="Product model",
 *      title="Product",
 * )
 * @OA\Schema(
 *    schema = "ProductDetail",
 *    description = "PostDetail",
 *     @OA\Property(type = "integer", property = "id"),
 *     @OA\Property(type = "string", property = "name"),
 *     @OA\Property(type = "string", property = "description"),
 *     @OA\Property(type = "float", property = "price"),
 *     @OA\Property(type = "integer", property = "year")
 * )
 *
 * @OA\Schema(
 *    schema = "ProductList",
 *    description = "PostList",
 *     @OA\Property(type="integer", property="id"),
 *     @OA\Property(type="string", property="name")
 * )
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"product:list","product:details"})
     * @OA\Property(
     *     format="int",
     *     description="Id",
     * )
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"product:list","product:details"})
     * @OA\Property(
     *     format="string",
     *     description="Product Name",
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Serializer\Groups({"product:details"})
     * @OA\Property(
     *     format="string",
     *     description="Product description",
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Serializer\Groups({"product:details"})
     * @OA\Property(
     *     format="float",
     *     description="Product price",
     * )
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"product:details"})
     * @OA\Property(
     *     format="integer",
     *     description="Product year",
     * )
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
