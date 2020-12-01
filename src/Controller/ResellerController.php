<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Reseller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest; 

class ResellerController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/api/reseller", name="reseller.details")
     * @Rest\View(serializerGroups={"reseller:details"}, statusCode=200)
     */
    public function resellerInfo()
    {
    	return $this->getUser();
    }

    /**
     * @Rest\Post("/create/reseller", name="reseller.create")
     * @Rest\View(serializerGroups={"reseller:details"}, statusCode=200)
     * @ParamConverter("reseller", converter="reseller")
     */
    public function resellerCreate(Request $request, Reseller $reseller, ValidatorInterface $validator)
    {
    	$errors = $validator->validate($reseller);

        if (count($errors)) {
            return $this->view(
                $errors,
                404
            );
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($reseller);
        $em->flush();

        return $this->view(
            $reseller,
            201,
            [
                'Location' => $this->generateUrl('reseller.details', ['id' =>$reseller->getId()], 0)
            ]
        );
    }

    /**
     * @Rest\Delete(
     *     path = "/api/reseller",
     *     name = "reseller.delete"
     * )
     */
    public function resellerDelete()
    {
    	$em = $this->getDoctrine()->getManager();
        $em->remove($this->getUser());
        $em->flush();
        return $this->json("", 204, []);
    }
}