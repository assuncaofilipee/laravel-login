<?php

namespace Tests\Unit\Services;

use App\Models\Profile;
use App\Models\User;
use App\Repositories\ProfileRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'password' => Hash::make('123456ff')
        ]);

        $this->post('/app/login', [
            'email' => $this->user->email,
            'password' => '123456ff'
        ]);
    }

    /**
     * @test
     */
    public function create()
    {
        $profile = new Profile();
        $profileRepository = new ProfileRepository($profile);

        $profile = $profileRepository->create(['first_name' => 'Olávo',
                                    'last_name' => 'Silva',
                                    'cpf' => '09367899050'
                                    ]);

        $this->assertInstanceOf(Profile::class, $profile);
    }

}
