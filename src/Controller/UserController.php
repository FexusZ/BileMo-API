<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use App\Entity\User;
use App\Service\Paginate;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Context\Context;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use OpenApi\Annotations as OA;

class UserController extends AbstractController
{
    /**
     * @Rest\Get("/api/users", name="user.list")
     * @Rest\View(serializerGroups={"user:list"}, statusCode=200)
     *
     * @OA\Get(
     *     security={{"bearer":{}}},
     *     path="/api/users",
     *     tags={"User"},
     *     summary="Get user list",
     *     description="Get user list",
     *     @OA\Parameter(
     *          name="limit",
     *          in="query",
     *          description="item limit per page (1 to 25), default 25",
     *          required=false,
     *          @OA\Schema(
     *              type="integer",
     *              format="int"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="current items page, default 1",
     *          required=false,
     *          @OA\Schema(
     *              type="integer",
     *              format="int"
     *          )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/UserList",
     *             example={
     *                 "items": {
     *                     "id": 1,
     *                     "email": "test",
     *                     "_links": {
     *                         "self": {
     *                             "href": "https://127.0.0.1:8000/api/user/2"
     *                         },
     *                         "modify": {
     *                             "href": "https://127.0.0.1:8000/api/user/2"
     *                         },
     *                         "delete": {
     *                             "href": "https://127.0.0.1:8000/api/user/2"
     *                         }
     *                     }
     *                 },
     *                 "items_per_page": 25,
     *                 "total_items": 1,
     *                 "current_page": 1
     *             },
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Bearer token missing",
     *         @OA\JsonContent(
     *             example={"code": "401", "message": "JWT Token not found"},
     *         ),
     *     ),
     *     @OA\Response(
     *        response=400,
     *        description="Invalid input",
     *         @OA\JsonContent(
     *             example={"message": "Bad request. Check your parameters"},
     *         ),
     *     ),
     *     @OA\Response(
     *        response=404,
     *        description="Users not found",
     *         @OA\JsonContent(
     *             example={"message": "no data"},
     *         ),
     *     ),
     * )
     */
    public function userList(Request $request, Paginate $paginator)
    {

        $view = $this->view(
            $paginator->paginate(
                $this->getDoctrine()->getRepository('App:User')->findBy(['reseller' => $this->getUser()->getId()]),
                $request
            ),
            200
        );
        $context = new Context();
        $context->addGroup('user:list');

        $view->setContext($context);
        
        $handler = $this->get('fos_rest.view_handler');
        $response = $handler->handle($view)
            ->setMaxAge(3600);

        return $response;
    }

    /**
     * @Rest\Get(
     *     path = "/api/user/{id}",
     *     name = "user.detail",
     *     requirements = {"id"="\d+"}
     * )
     * 
     * @Rest\View(serializerGroups={"user:details"}, statusCode=200)
     *
     * @OA\Get(
     *     security={{"bearer":{}}},
     *     path="/api/user/{id}",
     *     tags={"User"},
     *     summary="Get user detail",
     *     description="Get user detail",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="User ID to return",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int"
     *          )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/UserDetail",
     *             example={
     *                 "id": 1,
     *                 "email": "test@email.fr",
     *                 "username": "test",
     *                 "password": "test",
     *                 "_links": {
     *                     "self": {
     *                         "href": "https://127.0.0.1:8000/api/user/2"
     *                     },
     *                     "modify": {
     *                         "href": "https://127.0.0.1:8000/api/user/2"
     *                     },
     *                     "delete": {
     *                         "href": "https://127.0.0.1:8000/api/user/2"
     *                     }
     *                 }
     *             },
     *         ),
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Bearer token missing",
     *          @OA\JsonContent(
     *              example={"code": "401", "message": "JWT Token not found"},
     *          ),
     *      ),
     *      @OA\Response(
     *        response=404,
     *        description="User not found",
     *         @OA\JsonContent(
     *             example={"message": "Cannot access, User not found"},
     *         ),
     *     ),
     * )
     */
    public function userDetail(User $user, Request $request)
    {
        $view = $this->view(
            $user,
            200
        );
        $context = new Context();
        $context->addGroup('user:details');

        $view->setContext($context);
        
        $handler = $this->get('fos_rest.view_handler');
        $response = $handler->handle($view)
            ->setMaxAge(3600);

        return $response;
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
     *
     * @OA\Post(
     *     security={{"bearer":{}}},
     *     path="/api/user",
     *     tags={"User"},
     *     summary="Create user",
     *     description="Create user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     description="User email",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="username",
     *                     description="User name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="User passord",
     *                     type="string"
     *                 ),
     *                 example={"email": "test@email.fr", "username": "test", "password": "test"}
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/UserDetail",
     *             example={
     *                 "id": 1,
     *                 "email": "test@email.fr",
     *                 "username": "test",
     *                 "password": "test",
     *                 "_links": {
     *                     "self": {
     *                         "href": "https://127.0.0.1:8000/api/user/2"
     *                     },
     *                     "modify": {
     *                         "href": "https://127.0.0.1:8000/api/user/2"
     *                     },
     *                     "delete": {
     *                         "href": "https://127.0.0.1:8000/api/user/2"
     *                     }
     *                 }
     *             },
     *         ),
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Bearer token missing",
     *          @OA\JsonContent(
     *              example={"code": "401", "message": "JWT Token not found"},
     *          ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input, or duplicate user",
     *          @OA\JsonContent(
     *              example={"message": "Bad request. Check your parameters"},
     *          ),
     *     ),
     * )
     */
    public function userCreate(User $user, Request $request, ValidatorInterface $validator)
    {
        $errors = $validator->validate($user);

        if (count($errors)) {
            return $this->view(
                $errors,
                400
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
     *
     * @OA\Delete(
     *     security={{"bearer":{}}},
     *     path="/api/user/{id}",
     *     tags={"User"},
     *     summary="Remove user",
     *     description="Remove user",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="User ID to remove",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int"
     *          )
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             format="string",
     *             example={
     *                 ""
     *             },
     *         ),
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Bearer token missing",
     *          @OA\JsonContent(
     *              example={"code": "401", "message": "JWT Token not found"},
     *          ),
     *      ),
     *      @OA\Response(
     *        response=404,
     *        description="User not found",
     *         @OA\JsonContent(
     *             example={"message": "Cannot access, User not found"},
     *         ),
     *     ),
     * )
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
     *
     * @OA\Put(
     *     security={{"bearer":{}}},
     *     path="/api/user/{id}",
     *     tags={"User"},
     *     summary="Modify user",
     *     description="Modify user",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="User ID to modify",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="int"
     *          )
     *     ),
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     description="User email",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="username",
     *                     description="User name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="User passord",
     *                     type="string"
     *                 ),
     *                 example={"email": "test@email.fr", "username": "test", "password": "test"}
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/UserDetail",
     *             example={
     *                 "id": 1,
     *                 "email": "test@email.fr",
     *                 "username": "test",
     *                 "password": "test",
     *                 "_links": {
     *                     "self": {
     *                         "href": "https://127.0.0.1:8000/api/user/2"
     *                     },
     *                     "modify": {
     *                         "href": "https://127.0.0.1:8000/api/user/2"
     *                     },
     *                     "delete": {
     *                         "href": "https://127.0.0.1:8000/api/user/2"
     *                     }
     *                 }
     *             },
     *         ),
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Bearer token missing",
     *          @OA\JsonContent(
     *              example={"code": "401", "message": "JWT Token not found"},
     *          ),
     *      ),
     *      @OA\Response(
     *         response=400,
     *         description="Invalid input",
     *          @OA\JsonContent(
     *              example={"message": "Bad request. Check your parameters"},
     *          ),
     *     ),
     *     @OA\Response(
     *        response=404,
     *        description="User not found",
     *         @OA\JsonContent(
     *             example={"message": "Cannot access, User not found"},
     *         ),
     *     ),
     * )
     */
    public function userUpdate(User $user, Request $request, ValidatorInterface $validator)
    {
        $errors = $validator->validate($user);

        if (count($errors)) {
            return $this->view(
                $errors,
                400
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