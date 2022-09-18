<?php

namespace App\swagger\schemas;

/**
    * @OA\Schema(
    *    schema="profileResponse",
    *    type="object",
    *            @OA\Property(property="success", type="string"),
     *           @OA\Property(property="data", type="object",
     *               @OA\Property(property="first_name", type="string"),
     *               @OA\Property(property="last_name", type="string"),
     *               @OA\Property(property="cpf", type="string"),
     *               @OA\Property(property="uuid", type="string"),
     *               @OA\Property(property="updated_at", type="date"),
     *               @OA\Property(property="created_at", type="date"),
     *               @OA\Property(property="id", type="integer")
     *           ),
     *           example= {
     *                        "success": "true",
     *                        "data": {
     *                            "first_name": "Olávo",
     *                            "last_name": "Sales",
     *                            "cpf": "61906713065",
     *                            "uuid": "250c9e0e-65df-4658-ad08-4cfe6d8abbf9",
     *                            "updated_at": "2022-02-10T17:09:56.000000Z",
     *                            "created_at": "2022-02-10T17:09:56.000000Z",
     *                            "id": 2
     *                       }
     *                },
     *  ),
     *
 */
class ProfileResponse
{}
