<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;

use App\Entity\Product;
use App\Service\Paginate;

use FOS\RestBundle\Controller\Annotations as Rest;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use OpenApi\Annotations as OA;
/**
 * @OA\SecurityScheme(
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 *      securityScheme="bearer",
 *  )
 */
class ProductController extends AbstractController
{
     /**
     * @Rest\Get("/api/products", name="product.list")
     * @Rest\View(serializerGroups={"product:list"}, statusCode=200)
     * 
     * @OA\Get(
     *     security={{"bearer":{}}},
     *     path="/api/products",
     *     tags={"product"},
     *     summary="Get product list",
     *     description="Get product list",
     *     @OA\Parameter(
     *          name="limit",
     *          in="query",
     *          description="limit of item per page",
     *          required=false,
     *          @OA\Schema(
     *              type="integer",
     *              format="int"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="page of item",
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
     *             ref="#/components/schemas/Product",
     *             example={
     *                 "items": {
     *                     "id": 1,
     *                     "name": "phone1",
     *                     "_links": {
     *                         "self": {
     *                             "href": "https://127.0.0.1:8000/api/product/1"
     *                          }
     *                      }
     *                 },
     *                 "items_per_page": 25,
     *                 "total_items": 1,
     *                 "current_page": 1
     *             },
     *         ),
     *     ),
     * )
     * 
     */
    public function productList(Request $request, Paginate $paginator)
    {
        return $paginator->paginate(
            $this->getDoctrine()->getRepository('App:Product')->findAll(),
            $request
        );
    }
    
     /**
     * @Rest\Get("/api/product/{id}", name="product.details", requirements = {"id"="\d+"})
     * @Rest\View(serializerGroups={"product:details"}, statusCode=200)
     *
     * @OA\Get(
     *     security={{"bearer":{}}},
     *     path="/api/product/{id}",
     *     tags={"product"},
     *     summary="Get product detail",
     *     description="Get product detail",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Id of product to return",
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
     *             ref="#/components/schemas/Product",
     *             example={
     *                     "id": 1,
     *                     "name": "phone1",
     *                     "description": "phone1 description",
     *                     "price": "299.99",
     *                     "year": "2020",
     *             },
     *         ),
     *     ),
     * )
     */
    public function productDetail(Product $product)
    {
        return $product;
    }
}