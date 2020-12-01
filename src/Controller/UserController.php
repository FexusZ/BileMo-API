<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use App\Entity\User;
use App\Repository\ResellerRepository;

use FOS\RestBundle\Controller\Annotations as Rest; 
use FOS\RestBundle\Controller\AbstractFOSRestController;

use JMS\Serializer\SerializerInterface;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use App\Service\Paginate;

class UserController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/api/users", name="user.list")
     * @Rest\View(serializerGroups={"user:list"}, statusCode=200)
     */
    public function userList(Request $request, Paginate $paginator)
    {
        return $paginator->paginate(
            $this->getDoctrine()->getRepository('App:User')->findBy(['reseller' => $this->getUser()->getId()]),
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
    public function userDetail(User $user, Request $request)
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
    public function userCreate(User $user, Request $request, ValidatorInterface $validator)
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
    public function userDelete(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->json("", 204, []);
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
    public function userUpdate(User $user, Request $request, ValidatorInterface $validator)
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
            200,
            [
                'Location' => $this->generateUrl('user.detail', ['id' => $user->getId()], 0)
            ]
        );
    }
}