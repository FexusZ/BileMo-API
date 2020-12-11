<?php
use OpenApi\Annotations as OA;

/**
 * @OA\Info(title="BileMo-API", version="1")
 * 
 * @OA\Server(
 *     url="https://127.0.0.1:8000",
 *     description="Dev Server"
 * )
 * 
 * @OA\Post(
 *     path="/api/login_check",
 *     tags={"Token"},
 *     summary="Get JWT token",
 *     description="Get JWT token",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 type="object",
 *                 @OA\Property(
 *                     property="username",
 *                     description="Reseller email",
 *                     type="string",
 *                 ),
 *                 @OA\Property(
 *                     property="password",
 *                     description="Reseller password",
 *                     type="string"
 *                 ),
 *                 example={"username": "test@email.fr", "password": "test"}
 *             ),
 *         ),
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             ref="#/components/schemas/Token",
 *             example={
 *                  "token": "token.."
 *             },
 *         ),
 *     ),
 *	   @OA\Response(
 *          response=401,
 *          description="Invalid credentials",
 *          @OA\JsonContent(
 *              example={"code": "401", "message": "Invalid credentials"},
 *          ),
 *     ),
 * )
 *
 * @OA\Schema(
 *    schema = "Token",
 *    description = "Token JWT",
 *     @OA\Property(type="string", property="token")
 * )
 */