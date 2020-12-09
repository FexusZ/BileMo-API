<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use OpenApi\Annotations as OA;
/**
 * @OA\SecurityScheme(
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 *      securityScheme="bearer",
 *  )
 */
class AbstractController extends AbstractFOSRestController
{
}