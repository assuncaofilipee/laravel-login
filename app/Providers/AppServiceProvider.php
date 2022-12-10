<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\JsonResponse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('success', function (mixed $data, int $statusCode = JsonResponse::HTTP_OK) {
            return response()->json([
                'success' => true,
                'data' => $data
            ], $statusCode);
        });

        Response::macro('error', function (mixed $error, int $statusCode) {
            return response()->json([
                'success' => false,
                'error' => $error
            ], $statusCode);
        });
    }
}
