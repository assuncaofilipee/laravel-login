<?php

namespace Tests\Unit\Repositories;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    public function testShouldCreateNewUser(): void
    {
        $user = new User();
        $userRepository = new UserRepository($user);

        $user = $userRepository->create([
            'email' => 'teste1@gmail.com',
            'name' => 'Exemplo',
            'cpf' => '98364797081',
            'password' => '123456f'
        ]);

        $this->assertInstanceOf(User::class, $user);
    }

}
