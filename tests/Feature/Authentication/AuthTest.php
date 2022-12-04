<?php

namespace Tests\Feature\Authentication;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    private $auth;
    private $user;

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

    /**
     * @test
     */
    public function shouldLogin()
    {
        $response = $this->post('/app/login',
        ['email' => $this->user->email, 'password' => '123456ff']);

        $response->assertJsonStructure(
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
             ]);
         $response->assertSuccessful();
    }

    /**
     *  @test
     */
    public function shoundInvalidEmailLogin()
    {
        $data = [
            'email' => 'fulano@gmail.com',
            'password' => 'batata123'
        ];
        $response = $this->post('/app/login', $data);
        $response->assertJson( [
            'success' => false,
            'error' => [
                'email' => ['Email não cadastrado']
            ]
         ]);
        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

       /**
     *  @test
     */
    public function shoundInvalidPasswordLogin()
    {

        $response = $this->post('/app/login', [
            'email' => $this->user->email,
            'password' => '123456zz'
        ]);

        $response->assertJson( [
            'success' => false,
            'error' => [
                'message' => 'Usuário ou senha incorreto'
            ]
         ]);
        $response->assertStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     *  @test
     */
    public function shoundDataInvalidLogin()
    {
        $response = $this->post('/app/login');
        $response->assertJson([
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
        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     */
    public function shoudGetMe()
    {
        $response = $this->get('/app/me', $this->auth);
        $response->assertJsonFragment( [
            'email' => $this->user->email,
         ]);
         $response->assertSuccessful();
    }

    /**
     * @test
     */
    public function shoudNotGetMeData()
    {
        $response = $this->get('/app/me');
        $response->assertJson([
            'success' => false,
            'error' => [
                'message' => 'Token de autorização não encontrado'
            ]
         ]);
        $response->assertStatus(JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function shouldLogout()
    {
        $response = $this->post('/app/logout', [], $this->auth);
        $response->assertJson([
            'success' => 'true',
            'data' => [
                'message' => 'Usuário desconectado com sucesso'
            ]
        ]);
        $response->assertSuccessful();
    }
}
