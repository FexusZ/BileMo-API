<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user.list", methods={"GET"})
     */
    public function user_list(): Response
    {
    	// To do...
    }
    
    /**
     * @Route("/user/{id}", name="user.detail", methods={"GET"})
     */
    public function user_detail(): Response
    {
    	// To do...
    }

    /**
     * @Route("/user", name="user.create", methods={"POST"})
     */
    public function user_create(): Response
    {
    	// To do...
    }

    /**
     * @Route("/user/{id}", name="user.delete", methods={"DELETE"})
     */
    public function user_delete(): Response
    {
    	// To do...
    }
}