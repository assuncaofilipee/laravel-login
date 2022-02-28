<?php

namespace Tests\Unit\UserRepository;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function create()
    {
        $user = new User();
        $userRepository = new UserRepository($user);

        $user = $userRepository->create([
            'email' => 'teste1@gmail.com',
            'password' => '123456f'
        ]);

        $this->assertInstanceOf(User::class, $user);
    }

}
