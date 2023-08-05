<?php

namespace App\Swagger\responses;
/**
 * @OA\Response(
     *      response="forbidden",
     *      description="Token de autorização não encontrado / Token expirado",
     *      @OA\JsonContent(
     *         @OA\Property(property="success", type="string"),
     *         @OA\Property(property="data", type="object",
     *          @OA\Property(property="message", type="string"),
     *     ),
     *       @OA\Examples(example="Token de autorização não encontrado", summary="Token de autorização não encontrado",
     *        value={
     *        "success": "false",
     *        "data": {
     *                  "message": "Token de autorização não encontrado"
     *             },
     *        }),
     *       @OA\Examples(example="Token expirado", summary="Token expirado",
     *          value={
     *          "success": "false",
     *          "data": {
     *                  "message": "Token expirado"
     *             },
     *       })
     *      ),
     *    ), */

class Forbidden {}
