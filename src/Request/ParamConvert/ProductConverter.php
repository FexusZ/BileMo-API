<?php

namespace App\Request\ParamConverter;

use App\Entity\Product;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Class ProductConverter
 * @package App\Request\ParamConverter
 */
class ProductConverter implements ParamConverterInterface
{
    private EntityManagerInterface $em;

    /**
     * ProductConverter constructor.
     * @param SerializerInterface $serializer
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Request $request
     * @param ParamConverter $configuration
     * @return bool|void
     */
    public function apply(Request $request, ParamConverter $configuration): void
    {
        if ($request->attributes->has("id")) {
            $this->hasId($request, $configuration);
        }
    }

    /**
     * @param ParamConverter $configuration
     * @return bool|void
     */
    public function supports(ParamConverter $configuration): bool
    {
        return $configuration->getClass() === Product::class;
    }

    public function hasId(Request $request, ParamConverter $configuration): void
    {
        $product = $this->em
            ->getRepository($configuration->getClass())
            ->find($request->attributes->get("id"))
        ;

        if (!$product) {
            throw new Exception\NotFoundHttpException('Cannot access, Product not found');
        }

        $request->attributes->set($configuration->getName(), $product);
    }
}