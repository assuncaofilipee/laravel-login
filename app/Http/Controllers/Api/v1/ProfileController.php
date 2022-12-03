<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterProfileRequest;
use App\Services\ProfileService;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    protected $service;

    public function __construct(ProfileService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Post(
     *     tags={"Acesso"},
     *     summary="Cadastro de dados pessoais do usuário da API",
     *     description="Cadastra os dados pessoais do usuário",
     *     path="/app/user/profile",
     *  security={
     *           {"bearerAuth": {}}
     *       },
     *     @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="first_name", type="string"),
     *         @OA\Property(property="last_name", type="string"),
     *         @OA\Property(property="cpf", type="string"),
     *         @OA\Examples(example="register-profile", summary="Exemplo cpf sem separador",
     *          value={
     *               "first_name": "Olávo",
     *               "last_name": "Sales",
     *               "cpf": "61906713065",
     *         }),
     *         @OA\Examples(example="register-profile1", summary="Exemplo cpf com separador",
     *          value={
     *               "first_name": "Olávo",
     *               "last_name": "Sales",
     *               "cpf": "619.067.130-65",
     *         }),
     *       ),
     *     ),
     *   @OA\Response(
     *      response=201,
     *      description="Cadastro feito com sucesso",
     *      @OA\JsonContent(ref="#/components/schemas/profileResponse")
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
     *                   "email": {
     *                       "O campo primeiro nome é obrigatório."
     *                        },
     *                   "password": {
     *                       "O campo sobrenome é obrigatório."
     *                        },
     *                   "terms_of_use": {
     *                          "O campo cpf é obrigatório."
     *                        }
     *                     }
     *                  }),
     *          @OA\Examples(example="comfirmação", summary="Campos inválidos",
     *          value={
     *              "success": "false",
     *                       "data": {
     *                           "first_name": {
     *                               "O campo primeiro nome só pode conter letras.",
     *                               "O campo primeiro nome não pode ser superior a 50 caracteres."
     *                           },
     *                           "last_name": {
     *                               "O campo sobrenome só pode conter letras.",
     *                               "O campo sobrenome não pode ser superior a 100 caracteres."
     *                           },
     *                           "cpf": {
     *                               "O campo cpf já está sendo utilizado.",
     *                               "CPF inválido"
     *                           }
     *                       }
     *                  }),
     *       )
     *    ),
     *    @OA\Response(response=403,ref="#/components/responses/forbidden"),
     *    @OA\Response(response=401,ref="#/components/responses/unauthorized")
     * )
     */
    public function create(RegisterProfileRequest $request): JsonResponse
    {
        $profile = $this->service->create(
            $request->only([
                'first_name',
                'last_name',
                'cpf'
            ])
        );

        return response()->success($profile, JsonResponse::HTTP_CREATED);
    }
}
