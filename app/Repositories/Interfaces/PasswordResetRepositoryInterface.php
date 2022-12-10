<?php

namespace App\Repositories\Interfaces;

use App\Models\PasswordReset;

interface PasswordResetRepositoryInterface
{
    public function getResetCode(): string;

    public function sendPasswordResentLink(string $email): PasswordReset;

    public function getResetIdentifierCode(PasswordReset $resetToken);

    public function getResetToken(string $passwordToken);

    public function expiresTokenNow(PasswordReset $resetToken);
}
