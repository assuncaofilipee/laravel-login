<?php
/**
    * @OA\Schema(
    *    schema="loginResponse",
    *    type="object",
    *            @OA\Property(property="success", type="string"),
     *           @OA\Property(property="data", type="object",
     *               @OA\Property(property="access_token", type="string"),
     *               @OA\Property(property="token_type", type="string"),
     *               @OA\Property(property="expires_in", type="date"),
     *                  @OA\Property(property="user", ref="#/components/schemas/user")
     *               ),
     *
     *           example= {
     *                        "success": "true",
     *                        "data": {
     *                            "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC90cm91dy1hcHBcL2FwcFwvYXV0aFwvbG9naW4iLCJpYXQiOjE2NDQzMjYwODksImV4cCI6MTY0NDMyOTY4OSwibmJmIjoxNjQ0MzI2MDg5LCJqdGkiOiJDeENTS0x6cGRLQ3ZUc2ZQIiwic3ViIjoxOTUsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.D8i4qR2vgW9G-lz-ej1swDUToFgU4AnKHEFY_2hWRGA",
     *                            "token_type": "bearer",
     *                            "expires_in": 3600,
     *                            "user": {
     *                                "id": 195,
     *                                "uuid": "d83810de-4d4a-4306-85ed-c2e761fc998a",
     *                                "email": "teste2@gmail.com",
     *                                "email_verified_at": null,
     *                                "created_at": "2022-02-08T13:09:57.000000Z",
     *                                "updated_at": "2022-02-08T13:09:57.000000Z"
     *                       }
     *                   }
     *                },
     *  ),
     *
 */
class LoginResponse
{}
