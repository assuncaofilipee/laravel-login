<?php

namespace App\Providers;

use App\Repositories\AuthenticationRepository;
use App\Repositories\Interfaces\AuthenticationRepositoryInterface;
use App\Repositories\Interfaces\PasswordResetRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\PasswordResetRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        $this->app->bind(
            AuthenticationRepositoryInterface::class,
            AuthenticationRepository::class
        );

        $this->app->bind(
            PasswordResetRepositoryInterface::class,
            PasswordResetRepository::class
        );
    }
}
