<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthenticationService
{
    public function createNewToken(string $token): JsonResponse
    {
        return response()->json(['success' => 'true',
            'data' => [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth::factory()->getTTL() * 60,
            'user' => auth()->user()
            ]
        ]);
    }

}