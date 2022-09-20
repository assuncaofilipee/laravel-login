<?php

namespace App\Services;

use App\Models\PasswordReset;
use App\Notifications\PasswordResetNotification;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PasswordResetService
{
    private $passwordReset;

    public function __construct(PasswordReset $passwordReset)
    {
        $this->passwordReset = $passwordReset;
    }

    // Geração de um código de verificação de 6 dígitos
    public function getResetCode()
    {
        return Str::random(6);
    }

    // Envia um código de verificação para o usuário
    public function sendPasswordResentLink($user)
    {
        $token = $this->getResetCode();
        $signature = hash('md5', $token);
        $user->notify(new PasswordResetNotification($token));

        return $this->passwordReset::create([
            "user_id" => $user->id,
            "token_signature" => $signature,
            "expires_at" => Carbon::now()->addMinutes(30)
        ]);
    }

    // Cria um novo token a partir do antigo
    public function getResetIdentifierCode($resetToken)
    {
        $token = $this->getResetCode();

        $this->passwordReset::create([
            "user_id" => $resetToken->user_id,
            "token_signature" => hash('md5', $token),
            "used_token" => $resetToken->id,
            "expires_at" => Carbon::now()->addMinutes(30)
        ]);

        return $token;
    }

    // Obtém token do código de verificação enviado ao usuário
    public function getResetToken($passwordToken)
    {
        return  $this->passwordReset::where([
            ['token_signature', hash('md5', $passwordToken)],
        ])->first();
    }

    // Força a expiração do token
    public function expiresTokenNow($resetToken)
    {
        return $resetToken->update([
            "expires_at" => Carbon::now()
        ]);
    }
}
