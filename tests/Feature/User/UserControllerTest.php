<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserControllerTest extends TestCase
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

        $token = $this->post('/app/login', [
            'email' => $this->user->email,
            'password' => '123456ff'
        ])->json()['data']['access_token'];

        $this->auth = [
            'Authorization' => 'Bearer ' . $token
        ];
    }

    public function testShouldRegisterUser(): void
    {
        $this->post(
            '/app/users',
            [
                'email' => 'testuser@gmail.com',
                'email_confirmation' => 'testuser@gmail.com',
                'name' => 'Exemplo',
                'cpf' => '54528600021',
                'password' => '123456ff',
                'password_confirmation' => '123456ff',
                'terms_of_use' => 'true'
            ]
        )
            ->assertSuccessful()
            ->assertJsonFragment(['email' => 'testuser@gmail.com']);
    }

    public function testShoudNotRegisterUserAndReturnValidateErrors(): void
    {
        $this->post('/app/users')
            ->assertJson(
                [
                    'success' => false,
                    'error' => [
                        'email' => [
                            'O campo email é obrigatório.'
                        ],
                        'name' => [
                            'O campo nome é obrigatório.'
                        ],
                        'cpf' => [
                            'O campo cpf é obrigatório.'
                        ],
                        'password' => [
                            'O campo senha é obrigatório.'
                        ],
                        'terms_of_use' => [
                            'O campo termos de uso é obrigatório.'
                        ]
                    ]
                ]
            );
    }

    public function testShoudNotRegisterUserAndReturnAllOthersErrors(): void
    {
        $this->post('/app/users', [
            'email' => 'hakuna.com', 'password' => '123', 'terms_of_use' => false
        ])
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(
                [
                    'success' => false,
                    'error' => [
                        'email' => [
                            'O campo email deve ser um endereço de e-mail válido.',
                            'O campo email de confirmação não confere.'
                        ],
                        'password' => [
                            'O campo senha de confirmação não confere.',
                            'O campo senha deve ter pelo menos 8 caracteres.',
                            'O campo senha deve conter pelo menos uma letra.'
                        ],
                        'terms_of_use' => [
                            'É obrigatório o aceite dos Termos de uso',
                        ]
                    ]
                ]
            );
    }

    public function testShouldReturnModelNotFound(): void
    {
        $this->get('/app/users/9999999999999', $this->auth)
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }

    public function testShouldGetAllUsers(): void
    {
        $this->get('/app/users', $this->auth)
            ->assertSuccessful()
            ->assertJsonStructure(
                [
                    "success",
                    "data" =>  []
                ]
            );
    }

    public function testShouldReturnSpecificUsers(): void
    {
        $this->get('/app/users/' . $this->user->id, $this->auth)
            ->assertSuccessful()
            ->assertJson([
                "success" => true,
                "data" => $this->user->toArray()

            ]);
    }

    public function testShouldUpdateUser(): void
    {
        $expected = $this->user->toArray();
        $expected['name'] = 'zezinho';

        $this->put('/app/users/' . $this->user->id, ['name' => $expected['name']], $this->auth)
            ->assertSuccessful()
            ->assertJson([
                "success" => true,
                "data" => [
                    "name" => $expected['name']
                    ]
                ]
            );
    }

    public function testShouldUpdateInvalidUserAndReturnNotFound(): void
    {
        $this->put('/app/users/999999999999', [], $this->auth)
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }

    public function testShouldDeleteUser(): void
    {
        $this->delete('/app/users/' . $this->user->id, [], $this->auth)
            ->assertStatus(JsonResponse::HTTP_NO_CONTENT);
    }
}
