<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface AuthenticationRepositoryInterface
{
    public function createNewToken(array $credentials): array;

    public function logout(): void;

    public function refreshToken(): array;

    public function me(): User;
}
