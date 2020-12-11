<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use App\Entity\Reseller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest; 

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use OpenApi\Annotations as OA;

class ResellerController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/api/reseller", name="reseller.details")
     * @Rest\View(serializerGroups={"reseller:details"}, statusCode=200)
     *
     * @OA\Get(
     *     security={{"bearer":{}}},
     *     path="/api/reseller",
     *     tags={"Reseller"},
     *     summary="Get reseller detail",
     *     description="Get reseller detail",
     *     @OA\Response(
     *         response="200",
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/ResellerDetail",
     *             example={
     *                 "id": 1,
     *                 "email": "test@email.fr",
     *                 "password": "test",
     *                 "_links": {
     *                     "self": {
     *                         "href": "https://127.0.0.1:8000/api/reseller"
     *                     },
     *                     "delete": {
     *                         "href": "https://127.0.0.1:8000/api/reseller"
     *                     },
     *                     "create_user": {
     *                         "href": "https://127.0.0.1:8000/api/user"
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
     * )
     */
    public function resellerInfo()
    {
    	return $this->getUser();
    }

    /**
     * @Rest\Post("/create/reseller", name="reseller.create")
     * @Rest\View(serializerGroups={"reseller:details"}, statusCode=200)
     * @ParamConverter("reseller", converter="reseller")
     *
     * @OA\Post(
     *     security={{"bearer":{}}},
     *     path="/create/reseller",
     *     tags={"Reseller"},
     *     summary="Create reseller",
     *     description="Create reseller",
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
     *                     property="password",
     *                     description="User passord",
     *                     type="string"
     *                 ),
     *                 example={"email": "test@email.fr", "password": "test"}
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/ResellerDetail",
     *             example={
     *                 "id": 1,
     *                 "email": "test@email.fr",
     *                 "password": "test",
     *                 "_links": {
     *                     "self": {
     *                         "href": "https://127.0.0.1:8000/api/reseller"
     *                     },
     *                     "delete": {
     *                         "href": "https://127.0.0.1:8000/api/reseller"
     *                     },
     *                     "create_user": {
     *                         "href": "https://127.0.0.1:8000/api/user"
     *                     }
     *                 }
     *             },
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input, or duplicate Reseller",
     *          @OA\JsonContent(
     *              example={"message": "Bad request. Check your parameters"},
     *          ),
     *     ),
     * )
     */
    public function resellerCreate(Request $request, Reseller $reseller, ValidatorInterface $validator)
    {
    	$errors = $validator->validate($reseller);

        if (count($errors)) {
            return $this->view(
                $errors,
                400
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
     *
     * @OA\Delete(
     *     security={{"bearer":{}}},
     *     path="/api/reseller",
     *     tags={"Reseller"},
     *     summary="Delete reseller",
     *     description="Delete reseller",
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