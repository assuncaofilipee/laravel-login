<?php

use App\Http\Controllers\Api\v1\AuthenticationController;
use App\Http\Controllers\Api\v1\RecoveryPasswordController;
use App\Http\Controllers\Api\v1\RegisterProfileController;
use App\Http\Controllers\Api\v1\RegisterUserController;
use App\Http\Controllers\Api\v1\TermsOfUseController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'api'], function ($router) {
    Route::group(['prefix' => 'user'], function () {
        // Rota cadastra usuário
        Route::post('register', [RegisterUserController::class, 'create']);
    });
    // Rota autentica usuário
     Route::post('login', [AuthenticationController::class, 'login']);
    // Rota obtém o texto do termos de usos
    Route::get('terms-of-use', [TermsOfUseController::class, 'termsOfUse']);
    // Rota obtém os 6 caracteres recebidos por email e garante que são válidos
    Route::post('validate-password-token', [RecoveryPasswordController::class, 'validatePasswordResetToken']);
    // Rota verifica se email existe e envia um pin code de 6 caracteres para o email do usuário
    Route::post('forgot-password', [RecoveryPasswordController::class, 'sendPasswordResetToken']);
    // Rota renova password
    Route::post('new-password', [RecoveryPasswordController::class, 'setNewAccountPassword']);
});

Route::group(['middleware' => 'auth:api'], function ($router) {
    // Rota cadastra dados pessoais
    Route::post('user/profile', [RegisterProfileController::class, 'create']);
    // Rota desconecta usuário
    Route::post('logout', [AuthenticationController::class, 'logout']);
    // Rota obtem novo token
    Route::post('refresh', [AuthenticationController::class, 'refresh']);
    // Rota obtém informações do usuário logado
    Route::get('me', [AuthenticationController::class, 'me']);
});
