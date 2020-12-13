<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;

use App\Entity\Product;
use App\Service\Paginate;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Context\Context;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use OpenApi\Annotations as OA;

class ProductController extends AbstractController
{
    /**
     * @Rest\Get("/api/products", name="product.list")
     * @Rest\View(serializerGroups={"product:list"}, statusCode=200)
     * 
     * @OA\Get(
     *     security={{"bearer":{}}},
     *     path="/api/products",
     *     tags={"Product"},
     *     summary="Get product list",
     *     description="Get product list",
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
     *             ref="#/components/schemas/ProductList",
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
     *     @OA\Response(
     *          response=401,
     *          description="Bearer token missing",
     *          @OA\JsonContent(
     *              example={"code": "401", "message": "JWT Token not found"},
     *          ),
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
     *        description="Products not found",
     *         @OA\JsonContent(
     *             example={"message": "no data"},
     *         ),
     *     ),
     * )
     * 
     */
    public function productList(Request $request, Paginate $paginator)
    {
        $view = $this->view(
            $paginator->paginate(
                $this->getDoctrine()->getRepository('App:Product')->findAll(),
                $request
            ),
            200
        );
        $context = new Context();
        $context->addGroup('product:list');

        $view->setContext($context);

        $handler = $this->get('fos_rest.view_handler');
        $response = $handler->handle($view)
            ->setMaxAge(3600);

        return $response;
    }
    
    /**
     * @Rest\Get("/api/product/{id}", name="product.details", requirements = {"id"="\d+"})
     * @Rest\View(serializerGroups={"product:details"}, statusCode=200)
     *
     * @OA\Get(
     *     security={{"bearer":{}}},
     *     path="/api/product/{id}",
     *     tags={"Product"},
     *     summary="Get product detail",
     *     description="Get product detail",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Product ID to return",
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
     *             ref="#/components/schemas/ProductDetail",
     *             example={
     *                     "id": 1,
     *                     "name": "phone1",
     *                     "description": "phone1 description",
     *                     "price": "299.99",
     *                     "year": "2020",
     *                     "_links": {
     *                         "self": {
     *                             "href": "https://127.0.0.1:8000/api/product/1"
     *                          }
     *                      }
     *             },
     *         ),
     *     ),
     *     @OA\Response(
     *        response=404,
     *        description="User not found",
     *         @OA\JsonContent(
     *             example={"message": "Cannot access, Product not found"},
     *         ),
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Bearer token missing",
     *          @OA\JsonContent(
     *              example={"code": "401", "message": "JWT Token not found"},
     *          ),
     *     ),
     * )
     */
    public function productDetail(Product $product)
    {
        $view = $this->view(
            $product,
            200
        );
        $context = new Context();
        $context->addGroup('product:details');

        $view->setContext($context);

        $handler = $this->get('fos_rest.view_handler');
        $response = $handler->handle($view)
            ->setMaxAge(3600);

        return $response;
    }
}