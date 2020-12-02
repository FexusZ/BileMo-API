<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;

use App\Entity\Product;
use App\Service\Paginate;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ProductController extends AbstractFOSRestController
{
     /**
     * @Rest\Get("/api/products", name="product.list")
     * @Rest\View(serializerGroups={"product:list"}, statusCode=200)
     */
    public function product_list(Request $request, Paginate $paginator)
    {
        return$paginator->paginate(
            $this->getDoctrine()->getRepository('App:Product')->findAll(),
            $request
        );
    }
    
     /**
     * @Rest\Get("/api/product/{id}", name="product.details", requirements = {"id"="\d+"})
     * @Rest\View(serializerGroups={"product:details"}, statusCode=200)
     */
    public function product_detail(Product $product)
    {
        return $product;
    }
}