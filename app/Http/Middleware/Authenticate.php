<?php

namespace App\Http\Middleware;

use Tymon\JWTAuth\Facades\JWTAuth;
use Closure;
use Illuminate\Http\Request;

class Authenticate
{
    public function handle(Request $request, Closure $next)
    {
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json(['success' => 'false', 'data' => ['message' => 'Token inválido']], 401);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json(['success' => 'false',  'data' => ['message' => 'Token expirado']], 422);
            }else{
                return response()->json(['success' => 'false', 'data' => ['message' => 'Token de autorização não encontrado']], 403);
            }
        }

        return $next($request);
    }
}
