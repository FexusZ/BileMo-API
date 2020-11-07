<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product.list", methods={"GET"})
     */
    public function product_list(): Response
    {
    	// To do...
    }
    
    /**
     * @Route("/product/{id}", name="product.detail", methods={"GET"})
     */
    public function product_detail(): Response
    {
    	// To do...
    }
}