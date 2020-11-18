<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;

use App\Entity\User;
use App\Repository\ClientRepository;

use Symfony\Component\Serializer\Exception\NotEncodableValueException;

use Symfony\Component\Validator\Validator\ValidatorInterface;

use FOS\RestBundle\Controller\Annotations as Rest; 
use FOS\RestBundle\Controller\AbstractFOSRestController;

use JMS\Serializer\SerializerInterface;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Symfony\Component\Security\Core\Security;
// use Knp\Component\Pager\PaginatorInterface;

use \App\Service\Paginate;

class UserController extends AbstractFOSRestController
{

    public function __construct()
    {
        
    }
    /**
     * @Rest\Get("/api/users", name="user.list")
     *
     * @Rest\View(serializerGroups={"user:list"}, statusCode=200)
     */
    public function userList(Request $request, Paginate $paginator)
    {
        return $paginator->paginate(
            $this->getDoctrine()->getRepository('App:User')->findBy(['client' => $this->getUser()->getId()]),
            $request
        );
    }
    
    /**
     * @Rest\Get(
     *     path = "/api/user/{id}",
     *     name = "user.detail",
     *     requirements = {"id"="\d+"}
     * )
     * 
     * @Rest\View(serializerGroups={"user:details"}, statusCode=200)
     */
    public function userDetail(User $user)
    {
        return $user;
    }

    /**
     * @Rest\Post(
     *     path = "/api/user",
     *     name = "user.create"
     * )
     *
     * @Rest\View(serializerGroups={"user:details"}, statusCode=201)
     *
     * @ParamConverter("user", converter="user")
     */
    public function userCreate(User $user, Request $request, ClientRepository $clientRepository, ValidatorInterface $validator)
    {
        $errors = $validator->validate($user);

        if (count($errors)) {
            return $this->view(
                $errors,
                404
            );
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->view(
            $user,
            201,
            [
                'Location' => $this->generateUrl('user.detail', ['id' =>$user->getId()], 0)
            ]
        );
    }

    /**
     * @Rest\Delete(
     *     path = "/api/user/{id}",
     *     name = "user.delete",
     *     requirements = {"id"="\d+"}
     * )
     *
     * @Rest\View
     */
    public function userDelete()
    {
        // return $this->json(, 200, [], ['groups' => ""]);
    }


    /**
     * @Rest\Put(
     *     path = "/api/user/{id}",
     *     name = "user.update",
     *     requirements = {"id"="\d+"}
     * )
     *
     * @Rest\View(serializerGroups={"user:details"}, statusCode=200)
     */
    public function userUpdate(User $user, Request $request, ClientRepository $clientRepository, ValidatorInterface $validator)
    {
        dd($user);
        // return $this->json(, 200, [], ['groups' => ""]);
    }
}