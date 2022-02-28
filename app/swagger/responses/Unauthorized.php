<?php

/**
* @OA\Response(
*      response="unauthorized",
*      description="Token inválido",
*      @OA\JsonContent(
*          @OA\Property(property="success", type="string"),
*          @OA\Property(property="data", type="object",
*          @OA\Property(property="message", type="string"),
*           ),
 *         example= {
 *             "success": "false",
 *             "data": {
 *                  "message": "Token inválido"
 *             }
 *         },
 *      ),
 *    ),
 *  ),
 */
class Unauthorized {}
