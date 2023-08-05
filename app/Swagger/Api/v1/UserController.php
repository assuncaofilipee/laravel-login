<?php

namespace App\Swagger\Api\v1;

use App\Http\Controllers\Controller;

/**
     * @OA\Get(
     *   tags={"Usuários"},
     *   summary="Busca todos os usuários cadastrados",
     *   description="Retorna todos os usuários cadastrados",
     *   path="/app/users",
     *    security={
     *           {"bearerAuth": {}}
     *       },
     *   @OA\Response(
     *      response=200,
     *      description="Dados dos usuários cadastrados",
     *      @OA\JsonContent(
     *        @OA\Property(property="success", type="string"),
     *       @OA\Property(property="data", type="object",
     *        @OA\Property(ref="#/components/schemas/user")
     *        ),
     *      example={
     *          "success" : "true",
     *          "data" : {{
     *               "email": "exemplo@gmail.com",
     *               "name" : "Exemplo",
     *               "cpf" : "98364797085",
     *               "uuid": "fbd5d732-137b-462b-bfd1-d32b23209fa5",
     *               "updated_at": "2022-02-09T19:32:49.000000Z",
     *               "created_at": "2022-02-09T19:32:49.000000Z",
     *               "deleted_at": null
     *          }},
     *          },
     *       ),
     *    ),
     *    @OA\Response(response=403,ref="#/components/responses/forbidden"),
     *    @OA\Response(response=401,ref="#/components/responses/unauthorized")
     *    ),
     * )
     *
     * @OA\Post(
     *     tags={"Usuários"},
     *     summary="Cadastro de usuário",
     *     description="Retorna as informações do usuário cadastrado",
     *     path="/app/users",
     *     @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="email", type="string"),
     *         @OA\Property(property="email_confirmation", type="string"),
     *         @OA\Property(property="name", type="string"),
     *         @OA\Property(property="cpf", type="string"),
     *         @OA\Property(property="password", type="string"),
     *         @OA\Property(property="password_confirmation", type="string"),
     *         @OA\Property(property="terms_of_use", type="boolean"),
     *         @OA\Examples(example="register", summary="Cadastro de usuário",
     *          value={
     *               "email": "exemplo@gmail.com",
     *               "email_confirmation": "exemplo@gmail.com",
     *               "name": "Exemplo",
     *               "cpf": "98364797085",
     *               "password": "123a123a",
     *               "password_confirmation": "123a123a",
     *               "terms_of_use": true
     *         }),
     *       ),
     *     ),
     *   @OA\Response(
     *      response=201,
     *      description="Cadastro feito com sucesso",
     *      @OA\JsonContent(ref="#/components/schemas/loginResponse")
     *    ),
     *    @OA\Response(
     *      response=422,
     *      description="Campos inválidos",
     *      @OA\JsonContent(
     *           @OA\Property(property="success", type="string"),
     *           @OA\Property(property="data", type="object",
     *               @OA\Property(property="email", type="string"),
     *               @OA\Property(property="password", type="string"),
     *           ),
     *         @OA\Examples(example="obrigatório", summary="Campos obrigatórios",
     *          value={
     *               "success" : "false",
     *               "data": {
     *                  "email": {
     *                       "O campo email é obrigatório."
     *                        },
     *                  "name": {
     *                       "O campo nome é obrigatório."
     *                        },
     *                  "cpf": {
     *                       "O campo cpf é obrigatório."
     *                        },
     *                   "password": {
     *                       "O campo senha é obrigatório."
     *                        },
     *                   "terms_of_use": {
     *                          "O campo termos de uso é obrigatório."
     *                        }
     *                     }
     *                  }),
     *          @OA\Examples(example="comfirmação", summary="Campos de confirmação / Regras inválidas",
     *          value={
     *               "success" : "false",
     *               "data": {
     *                   "email": {
     *                        "O campo email deve ser um endereço de e-mail válido.",
     *                        "O campo email de confirmação não confere."
     *                      },
     *                   "name": {
     *                               "O campo nome só pode conter letras.",
     *                               "O campo nome não pode ser superior a 100 caracteres."
     *                         },
     *                   "cpf": {
     *                       "O campo cpf já está sendo utilizado.",
     *                       "CPF inválido"
     *                    },
     *                   "password": {
     *                        "O campo senha de confirmação não confere.",
     *                        "O campo senha deve ter pelo menos 8 caracteres.",
     *                        "O campo senha deve conter pelo menos uma letra."
     *                        },
     *                   "terms_of_use": {
     *                        "É obrigatório o aceite dos Termos de uso"
     *                      }
     *                     }
     *                  }),
     *       )
     *    )
     * )
     *
     * @OA\Get(
     *   tags={"Usuários"},
     *   summary="Busca as informações do usuário solicitado",
     *   description="Retorna informações do usuário solicitado",
     *   path="/app/users/{id}",
     *    security={
     *           {"bearerAuth": {}}
     *       },
     *   @OA\Response(
     *      response=200,
     *      description="Dados do usuário solicitado",
     *      @OA\JsonContent(
     *        @OA\Property(property="success", type="string"),
     *       @OA\Property(property="data", type="object",
     *        @OA\Property(ref="#/components/schemas/user")
     *        ),
     *      example={
     *          "success" : "true",
     *          "data" : {
     *                       "email": "exemplo@gmail.com",
     *                       "name" : "Exemplo",
     *                       "cpf" : "98364797085",
     *                       "uuid": "fbd5d732-137b-462b-bfd1-d32b23209fa5",
     *                       "updated_at": "2022-02-09T19:32:49.000000Z",
     *                       "created_at": "2022-02-09T19:32:49.000000Z",
     *                       "id": 470
     *                      }
     *          },
     *       ),
     *    ),
     *    @OA\Response(response=403,ref="#/components/responses/forbidden"),
     *    @OA\Response(response=401,ref="#/components/responses/unauthorized")
     *    ),
     * )
     *
     * @OA\Put(
     *   tags={"Usuários"},
     *   summary="Atualiza as informações do usuário enviado",
     *   description="Retorna o usuário atualizado",
     *   path="/app/users/{id}",
     *    security={
     *           {"bearerAuth": {}}
     *       },
     *   @OA\Response(
     *      response=200,
     *      description="Dados do usuário solicitado",
     *      @OA\JsonContent(
     *        @OA\Property(property="success", type="string"),
     *       @OA\Property(property="data", type="object",
     *        @OA\Property(ref="#/components/schemas/user")
     *        ),
     *      example={
     *          "success" : "true",
     *          "data" : {
     *                       "email": "exemplo@gmail.com",
     *                       "name" : "Exemplo",
     *                       "cpf" : "98364797085",
     *                       "uuid": "fbd5d732-137b-462b-bfd1-d32b23209fa5",
     *                       "updated_at": "2022-02-09T19:32:49.000000Z",
     *                       "created_at": "2022-02-09T19:32:49.000000Z",
     *                       "id": 470
     *                      }
     *          },
     *       ),
     *    ),
     *    @OA\Response(response=403,ref="#/components/responses/forbidden"),
     *    @OA\Response(response=401,ref="#/components/responses/unauthorized")
     *    ),
     * )
     *
     * @OA\Delete(
     *   tags={"Usuários"},
     *   summary="Deleta o usuário enviado",
     *   description="Retorna status code 204 Not Content",
     *   path="/app/users/{id}",
     *    security={
     *           {"bearerAuth": {}}
     *       },
     *   @OA\Response(
     *      response=204,
     *      description=""
     *    ),
     *    @OA\Response(response=403,ref="#/components/responses/forbidden"),
     *    @OA\Response(response=401,ref="#/components/responses/unauthorized")
     *    ),
     * )
     */
class UserController extends Controller {}
