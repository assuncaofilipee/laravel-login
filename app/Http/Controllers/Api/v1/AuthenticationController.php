<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\LoginRequest;
use App\Repositories\Interfaces\AuthenticationRepositoryInterface;
use App\Services\AuthenticationService;
use Illuminate\Http\JsonResponse;

class AuthenticationController extends Controller
{
    private AuthenticationRepositoryInterface $authenticationRepository;

    public function __construct(AuthenticationRepositoryInterface $authenticationRepository)
    {
        $this->authenticationRepository = $authenticationRepository;
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);
        $dataToken = $this->authenticationRepository->createNewToken($credentials);

        return response()->success($dataToken);
    }

    public function logout(): JsonResponse
    {
        $this->authenticationRepository->logout();

        return response()->success([
            'message' => 'Usuário desconectado com sucesso'
        ]);
    }

    public function refresh(): JsonResponse
    {
        return response()->success($this->authenticationRepository->refreshToken());
    }

    public function me(): JsonResponse
    {
        return response()->success($this->authenticationRepository->me());
    }
}
