<?php

namespace Tests\Feature\Authentication;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    private array $auth;
    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'password' => Hash::make('123456ff')
        ]);
        $response = $this->post('/app/login', [
            'email' => $this->user->email,
            'password' => '123456ff'
        ]);
        $token = $response->json()['data']['access_token'];
        $this->auth = [
            'Authorization' => 'Bearer ' . $token
        ];
    }

    public function testShouldReturnTokenInLogin()
    {
        $this->post('/app/login', ['email' => $this->user->email, 'password' => '123456ff'])
            ->assertSuccessful()
            ->assertJsonStructure(
                [
                    'success',
                    'data' => [
                        'access_token',
                        'token_type',
                        'expires_in',
                        'user' => [
                            'id',
                            'uuid',
                            'email',
                            'email_verified_at',
                            'deleted_at',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ]
            );
    }

    public function testShoundReturnInvalidEmailInLogin()
    {
        $this->post('/app/login', [
            'email' => 'fulano@gmail.com',
            'password' => 'batata123'
        ])
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'success' => false,
                'error' => [
                    'email' => ['Email não cadastrado']
                ]
            ]);
    }

    public function testShoundReturnInvalidPasswordInLogin()
    {
        $this->post('/app/login', [
            'email' => $this->user->email,
            'password' => '123456zz'
        ])
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED)
            ->assertJson([
                'success' => false,
                'error' => [
                    'message' => 'Usuário ou senha incorreto'
                ]
            ]);
    }

    public function testShouldReturnInvalidFieldsInLogin()
    {
        $this->post('/app/login')
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'success' => false,
                'error' => [
                    'email' => [
                        'O campo email é obrigatório.'
                    ],
                    'password' => [
                        'O campo senha é obrigatório.'
                    ]
                ]
            ]);
    }

    public function testShouldReturnEmailInGetMe()
    {
        $this->get('/app/me', $this->auth)
            ->assertSuccessful()
            ->assertJsonFragment([
                'email' => $this->user->email,
            ]);
    }

    public function testShoudReturnForbiddenInGetMeData()
    {
        $this->get('/app/me')
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN)
            ->assertJson([
                'success' => false,
                'error' => [
                    'message' => 'Token de autorização não encontrado'
                ]
            ]);
    }

    public function testShouldLogout()
    {
        $this->post('/app/logout', [], $this->auth)
            ->assertSuccessful()
            ->assertJson([
                'success' => 'true',
                'data' => [
                    'message' => 'Usuário desconectado com sucesso'
                ]
            ]);
    }

    public function testShouldReturnTokenInRefresh()
    {
        $this->post('/app/refresh', [], $this->auth)
            ->assertSuccessful()
            ->assertJsonStructure(
                [
                    'success',
                    'data' => [
                        'access_token',
                        'token_type',
                        'expires_in',
                        'user' => [
                            'id',
                            'uuid',
                            'email',
                            'email_verified_at',
                            'deleted_at',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ]
            );
    }
}
