<?php

use App\Http\Controllers\Api\v1\AuthenticationController;
use App\Http\Controllers\Api\v1\ProfileController;
use App\Http\Controllers\Api\v1\RecoveryPasswordController;
use App\Http\Controllers\Api\v1\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'api'], function ($router) {
    Route::group(['prefix' => 'user'], function () {
        Route::post('register', [UserController::class, 'create']);
    });
    Route::post('login', [AuthenticationController::class, 'login']);
    Route::post('validate-password-token', [RecoveryPasswordController::class, 'validatePasswordResetToken']);
    Route::post('forgot-password', [RecoveryPasswordController::class, 'sendPasswordResetToken']);
    Route::post('new-password', [RecoveryPasswordController::class, 'setNewAccountPassword']);
});

Route::group(['middleware' => 'auth:api'], function ($router) {
    Route::post('user/profile', [ProfileController::class, 'create']);
    Route::post('logout', [AuthenticationController::class, 'logout']);
    Route::post('refresh', [AuthenticationController::class, 'refresh']);
    Route::get('me', [AuthenticationController::class, 'me']);
});
