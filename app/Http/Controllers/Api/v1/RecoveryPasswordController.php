<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordRecovery\PasswordRecoveryRequest;
use App\Http\Requests\PasswordRecovery\PasswordResetRequest;
use App\Http\Requests\PasswordRecovery\PasswordTokenValidateRequest;
use App\Repositories\Interfaces\PasswordResetRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class RecoveryPasswordController extends Controller
{
    private PasswordResetRepositoryInterface $passwordResetRepositoryInterface;

    public function __construct(PasswordResetRepositoryInterface $passwordResetRepositoryInterface)
    {
        $this->passwordResetRepositoryInterface = $passwordResetRepositoryInterface;
    }

    public function sendPasswordResetToken(PasswordRecoveryRequest $request): JsonResponse
    {
        $this->passwordResetRepositoryInterface->sendPasswordResentLink($request->get('email'));

        return response()->success([
            "message" => "Código de recuperação de senha enviado ao seu email."
        ]);
    }

    public function validatePasswordResetToken(PasswordTokenValidateRequest $request): JsonResponse
    {
        $resetToken = $this->passwordResetRepositoryInterface->getResetToken($request->get('password_token'));

        if (empty($resetToken)) {
            return response()->error([
                "message" => "Código de verificação inválido."
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (Carbon::now()->greaterThan($resetToken->expires_at)) {
            return response()->error([
                "message" => "Código de verificação expirado."
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $newToken = $this->passwordResetRepositoryInterface->getResetIdentifierCode($resetToken);

        if ($newToken) {
            $this->passwordResetRepositoryInterface->expiresTokenNow($resetToken);

            return response()->success([
                "password_token" => $newToken
            ]);
        }
    }

    public function setNewAccountPassword(PasswordResetRequest $request): JsonResponse
    {
        $verifyToken = $this->passwordResetRepositoryInterface->getResetToken($request->get('password_token'));

        if (empty($verifyToken)) {
            return response()->error([
                "message" => "Token para alteração de senha inválido."
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (Carbon::now()->greaterThan($verifyToken->expires_at)) {
            return response()->error([
                "message" => "Token para alteração de senha expirado."
            ], JsonResponse::HTTP_FORBIDDEN);
        }

        $newPassword = Hash::make($request->all()['password']);
        $verifyToken->user->password = $newPassword;

        if ($verifyToken->user->save()) {
            $this->passwordResetRepositoryInterface->expiresTokenNow($verifyToken);

            return response()->success([
                "message" => "Senha alterada com sucesso."
            ]);
        }
    }
}
