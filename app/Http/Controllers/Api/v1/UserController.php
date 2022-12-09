<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

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
     *          "current_page": 1,
     *          "data" : {
     *               "email": "exemplo@gmail.com",
     *               "name" : "Exemplo",
     *               "cpf" : "98364797085",
     *               "uuid": "fbd5d732-137b-462b-bfd1-d32b23209fa5",
     *               "updated_at": "2022-02-09T19:32:49.000000Z",
     *               "created_at": "2022-02-09T19:32:49.000000Z",
     *               "deleted_at": null
     *          },
     *          "first_page_url": "http://localhost:6001/app/user?page=1",
     *          "from": 1,
     *          "next_page_url": null,
     *          "path": "http://localhost:6001/app/user",
     *          "per_page": 10,
     *          "prev_page_url": null,
     *          "to": 1
     *          },
     *       ),
     *    ),
     *    @OA\Response(response=403,ref="#/components/responses/forbidden"),
     *    @OA\Response(response=401,ref="#/components/responses/unauthorized")
     *    ),
     * )
     */
    public function index()
    {
        $users = User::latest()->simplePaginate(10);
        return response()->success($users, JsonResponse::HTTP_OK, true);
    }

    /**
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
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->service->create(
            $request->only([
                'email',
                'name',
                'cpf',
                'password',
                'password_confirmation',
                'terms_of_use'
            ])
        );
        return response()->success($user, JsonResponse::HTTP_CREATED);
    }

    /**
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
     */
    public function show(User $user)
    {
        return response()->success($user, JsonResponse::HTTP_OK);
    }

    /**
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
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->only([
            'email',
            'name',
            'cpf',
            'password'
        ]));

        return response()->success($user, JsonResponse::HTTP_OK);
    }

    /**
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
    public function destroy(User $user)
    {
        $user->delete();
        return response()->success('', JsonResponse::HTTP_NO_CONTENT);
    }
}
