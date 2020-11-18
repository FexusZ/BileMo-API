<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use App\Repository\ProductRepository;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Product;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product.list", methods={"GET"})
     */
    public function product_list(ProductRepository $productRepository, SerializerInterface $serializer): Response
    {
        return $this->json($productRepository->findAll(), 200, [], ['groups' => "product:list"]);
    }
    
    /**
     * @Route("/product/{id}", name="product.detail", methods={"GET"})
     */
    public function product_detail(Product $product, SerializerInterface $serializer): Response
    {
        return $this->json($product, 200, [], ['groups' => "product:details"]);
    }
}