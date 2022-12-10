<?php

namespace App\Repositories;

use App\Exceptions\UnauthorizedException;
use App\Models\User;
use App\Repositories\Interfaces\AuthenticationRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class AuthenticationRepository implements AuthenticationRepositoryInterface
{
    public function createNewToken(array $credentials): array
    {
        if (!$token = auth('api')->attempt($credentials)) {
            throw new UnauthorizedException();
        }

        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth::factory()->getTTL() * 60,
            'user' => auth()->user()
        ];
    }

    public function logout(): void
    {
        auth()->logout();
    }

    public function refreshToken(): array
    {
        return [
            'access_token' => auth::refresh(),
            'token_type' => 'bearer',
            'expires_in' => auth::factory()->getTTL() * 60,
            'user' => auth()->user()
        ];
    }

    public function me(): User
    {
        return auth()->user();
    }
}
