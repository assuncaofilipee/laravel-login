<?php

use App\Actions\User\Authentication;
use App\Actions\User\RecoveryPassword;
use App\Actions\User\RegisterUser;
use App\Actions\User\RegisterProfile;
use App\Actions\User\TermsOfUse;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'api'], function ($router) {
    Route::group(['prefix' => 'user'], function () {
        // Rota cadastra usuário
        Route::post('register', [RegisterUser::class, 'create']);
    });
    // Rota autentica usuário
     Route::post('login', [Authentication::class, 'login']);
    // Rota obtém o texto do termos de usos
    Route::get('terms-of-use', [TermsOfUse::class, 'termsOfUse']);
    // Rota obtém os 6 caracteres recebidos por email e garante que são válidos
    Route::post('validate-password-token', [RecoveryPassword::class, 'validatePasswordResetToken']);
    // Rota verifica se email existe e envia um pin code de 6 caracteres para o email do usuário
    Route::post('forgot-password', [RecoveryPassword::class, 'sendPasswordResetToken']);
    // Rota renova password
    Route::post('new-password', [RecoveryPassword::class, 'setNewAccountPassword']);
});

Route::group(['middleware' => 'auth:api'], function ($router) {
    // Rota cadastra dados pessoais
    Route::post('user/profile', [RegisterProfile::class, 'create']);
    // Rota desconecta usuário
    Route::post('logout', [Authentication::class, 'logout']);
    // Rota obtem novo token
    Route::post('refresh', [Authentication::class, 'refresh']);
    // Rota obtém informações do usuário logado
    Route::get('me', [Authentication::class, 'me']);
});
