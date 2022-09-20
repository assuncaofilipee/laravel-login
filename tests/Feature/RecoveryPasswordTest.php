<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\PasswordResetNotification;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use SebastianBergmann\PeekAndPoke\Proxy;
use Tests\TestCase;


class RecoveryPasswordTest extends TestCase
{
    use DatabaseTransactions;

    private $auth;
    private $user;
    private $repository;

    /**  Proxy responsável por fazer um mock da classe PasswordRestNotification
     *   para poder recupear propriedades privadas */
    private $proxyNotification;

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
    public function shouldBeSendsRecoveryCodeToUser()
    {
        Notification::fake();

        $this->post('/app/forgot-password', ['email' => $this->user->email])->assertSuccessful();

        Notification::assertSentTo($this->user, PasswordResetNotification::class);
    }

    /**
     * @test
     *
     */
    public function shouldBeNotSendRecoveryCodeWithInvalidEmail()
    {
        Notification::fake();

        $this->post('/app/forgot-password', ['email' => 'haha@gmail.com'])->assertStatus(422);

        Notification::assertNotSentTo($this->user, PasswordResetNotification::class);
    }

    /**
     * @test
     */
    public function shouldBeValidatePasswordToken()
    {
        Notification::fake();

        $this->post('/app/forgot-password', ['email' => $this->user->email])->assertSuccessful();

        Notification::assertSentTo($this->user, PasswordResetNotification::class,
        function($notification) {
           $this->proxyNotification = new Proxy($notification);
           return $notification !== null;
            }
        );

        $this->post('/app/validate-password-token', ['password_token' => $this->proxyNotification->password_token])
        ->assertSuccessful()
        ->assertJsonStructure(["success", "data"=> ["password_token"]]);
    }

    /**
     * @test
     */
    public function shouldNotValidatePasswordTokenWithInvalidToken()
    {
        Notification::fake();

        $this->post('/app/validate-password-token', ['password_token' => 'abcdf'])
        ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJson(
            [
                "success" => "false",
                "data" => [
                      "message" => "Código de verificação inválido."
                   ]
             ]
        );
    }


    /**
     * @test
     */
    public function shouldNotValidatePasswordTokenWithMoreThanSixCaracteres()
    {
        Notification::fake();

        $this->post('/app/validate-password-token', ['password_token' => 'abcdefg'])
        ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJson(
            [
                "success" => "false",
                "data" => [
                      "password_token" => [
                         "O campo password token não pode ser superior a 6 caracteres."
                      ]
                   ]
             ]
        );
    }

    /**
     * @test
     */
    public function shouldBeResetNewPassword()
    {
        Notification::fake();

        $this->post('/app/forgot-password', ['email' => $this->user->email])->assertSuccessful();

        Notification::assertSentTo($this->user, PasswordResetNotification::class,
        function($notification) {
           $this->proxyNotification = new Proxy($notification);
           return $notification !== null;
            }
        );

        $this->post('/app/new-password', ['password_token' => $this->proxyNotification->password_token,
        'password' => 'abcd1234', 'password_confirmation' => 'abcd1234'])
        ->assertSuccessful()
        ->assertJson( [
            "success" => "true",
            "data" => [
                  "message" => "Senha alterada com sucesso."
               ]
         ]);
    }

    /**
     * @test
     */
    public function shouldNotBeResetNewPasswordWithoutFields()
    {
        Notification::fake();

        $this->post('/app/new-password')
        ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJson([
            "success" => "false",
            "data" => [
                  "password_token" => [
                     "O campo password token é obrigatório."
                  ],
                  "password" => [
                        "O campo senha é obrigatório."
                     ]
               ]
        ]);
    }

    /**
     * @test
     */
    public function shouldNotBeResetNewPasswordWithInvalidPassword()
    {
        Notification::fake();

        $this->post('/app/new-password',['password' => '123', 'password_confirmation' => '123'])
        ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJson([
            "success" => "false",
            "data" => [
                  "password" => [
                    "O campo senha deve ter pelo menos 8 caracteres.",
                    "O campo senha deve conter pelo menos uma letra."
                ]
            ]
        ]);
    }

      /**
     * @test
     */
    public function shouldNotResetNewPasswordWithInvalidToken()
    {
        Notification::fake();

        $this->post('/app/new-password',['password_token' => 'abcd14','password' => '1234abcd', 'password_confirmation' => '1234abcd'])
        ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJson([
            "success" => "false",
            "data" => [
                  "message" => "Token para alteração de senha inválido."
            ]
        ]);
    }
}
