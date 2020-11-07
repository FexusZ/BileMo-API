<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    /**
     * @Route("/client", name="client.detail", methods={"GET"})
     */
    public function client_info(): Response
    {
    	// To do...
    }

    /**
     * @Route("/client", name="client.create", methods={"POST"})
     */
    public function client_create(): Response
    {
    	// To do...
    }

    /**
     * @Route("/client/{id}", name="client.update", methods={"PUT"})
     */
    public function client_update(): Response
    {
        // To do...
    }

    /**
     * @Route("/client/{id}", name="client.delete", methods={"DELETE"})
     */
    public function client_delete(): Response
    {
    	// To do...
    }
}