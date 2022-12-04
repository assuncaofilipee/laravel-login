<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class UnauthorizedException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => [
                'message' => 'Usuário ou senha incorreto'
            ]
        ], JsonResponse::HTTP_UNAUTHORIZED);
    }
}
