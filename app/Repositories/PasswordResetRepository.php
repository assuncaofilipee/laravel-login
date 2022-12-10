<?php

namespace App\Repositories;

use App\Models\PasswordReset;
use App\Models\User;
use App\Notifications\PasswordResetNotification;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PasswordResetRepository
{
    private PasswordReset $passwordReset;

    public function __construct(PasswordReset $passwordReset)
    {
        $this->passwordReset = $passwordReset;
    }

    public function getResetCode(): string
    {
        return Str::random(6);
    }

    public function sendPasswordResentLink(string $email): PasswordReset
    {
        $user = User::where('email', $email)->first();

        $token = $this->getResetCode();
        $signature = hash('md5', $token);
        $user->notify(new PasswordResetNotification($token));

        return $this->passwordReset::create([
            "user_id" => $user->id,
            "token_signature" => $signature,
            "expires_at" => Carbon::now()->addMinutes(30)
        ]);
    }

    public function getResetIdentifierCode(PasswordReset $resetToken): string
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

    public function getResetToken(string $passwordToken): ?PasswordReset
    {
        return  $this->passwordReset::where([
            ['token_signature', hash('md5', $passwordToken)],
        ])->first();
    }

    public function expiresTokenNow(PasswordReset $resetToken): bool
    {
        return $resetToken->update([
            "expires_at" => Carbon::now()
        ]);
    }
}
