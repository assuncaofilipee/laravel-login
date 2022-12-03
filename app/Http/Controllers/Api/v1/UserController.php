<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
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
     * @OA\Post(
     *     tags={"Acesso"},
     *     summary="Cadastro de usuário para utilização da API",
     *     description="Retorna as informações do usuário cadastrado",
     *     path="/app/user/register",
     *     @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="email", type="string"),
     *         @OA\Property(property="email_confirmation", type="string"),
     *         @OA\Property(property="password", type="string"),
     *         @OA\Property(property="password_confirmation", type="string"),
     *         @OA\Property(property="terms_of_use", type="boolean"),
     *         @OA\Examples(example="register", summary="Cadastro de usuário",
     *          value={
     *               "email": "exemplo@gmail.com",
     *               "email_confirmation": "exemplo@gmail.com",
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
     *                   "email": {
     *                       "O campo email é obrigatório."
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
     *                        },
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
    public function create(RegisterUserRequest $request): JsonResponse
    {
        $user = $this->service->create(
            $request->only([
                'email',
                'email_confirmation',
                'password',
                'password_confirmation',
                'terms_of_use'
            ])
        );
        return response()->success($user, JsonResponse::HTTP_CREATED);
    }
}
