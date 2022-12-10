<?php

namespace Tests\Feature\PasswordRecovery;

use App\Models\PasswordReset;
use App\Models\User;
use App\Notifications\PasswordResetNotification;
use App\Services\PasswordResetService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class RecoveryPasswordControllerTest extends TestCase
{
    use DatabaseTransactions;

    private array $auth;
    private User $user;
    private MockObject $service;
    private PasswordResetNotification $proxyNotification;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = $this->createMock(PasswordResetService::class);

        $this->user =  User::factory()->create([
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

    public function testShouldBeSendsRecoveryCodeToUser()
    {
        Notification::fake();

        $this->post('/app/forgot-password', ['email' => $this->user->email])->assertSuccessful();

        Notification::assertSentTo($this->user, PasswordResetNotification::class);
    }

    public function testShouldBeNotSendRecoveryCodeWithInvalidEmail(): void
    {
        Notification::fake();

        $this->post('/app/forgot-password', ['email' => 'haha@gmail.com'])->assertStatus(422);

        Notification::assertNotSentTo($this->user, PasswordResetNotification::class);
    }

    public function testShouldBeValidatePasswordToken(): void
    {
        Notification::fake();

        $this->post('/app/forgot-password', ['email' => $this->user->email])->assertSuccessful();

        Notification::assertSentTo(
            $this->user,
            PasswordResetNotification::class,
            function ($notification) {
                $this->proxyNotification = $notification;
                return $notification !== null;
            }
        );

        $this->post('/app/validate-password-token', ['password_token' => $this->proxyNotification->password_token])
            ->assertSuccessful()
            ->assertJsonStructure(['success', 'data' => ['password_token']]);
    }

    public function testShouldNotValidatePasswordTokenWithInvalidToken(): void
    {
        $this->post('/app/validate-password-token', ['password_token' => 'abcdf'])
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(
                [
                    'success' => false,
                    'error' => [
                        'message' => 'Código de verificação inválido.'
                    ]
                ]
            );
    }

    public function testShouldNotValidatePasswordTokenWithExpiredToken(): void
    {
        $this->post('/app/validate-password-token', ['password_token' =>  $this->getValidToken()])
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED)
            ->assertJson(
                [
                    'success' => false,
                    'error' => [
                        'message' => 'Código de verificação expirado.'
                    ]
                ]
            );
    }

    public function testShouldNotValidatePasswordTokenWithMoreThanSixCaracteres(): void
    {
        $this->post('/app/validate-password-token', ['password_token' => 'abcdefg'])
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(
                [
                    'success' => false,
                    'error' => [
                        'password_token' => [
                            'O campo password token não pode ser superior a 6 caracteres.'
                        ]
                    ]
                ]
            );
    }

    public function testShouldBeResetNewPassword(): void
    {
        Notification::fake();

        $this->post('/app/forgot-password', ['email' => $this->user->email])->assertSuccessful();

        Notification::assertSentTo(
            $this->user,
            PasswordResetNotification::class,
            function ($notification) {
                $this->proxyNotification = $notification;
                return $notification !== null;
            }
        );

        $this->post('/app/new-password', [
            'password_token' => $this->proxyNotification->password_token,
            'password' => 'abcd1234', 'password_confirmation' => 'abcd1234'
        ])
            ->assertSuccessful()
            ->assertJson([
                'success' => 'true',
                'data' => [
                    'message' => 'Senha alterada com sucesso.'
                ]
            ]);
    }

    public function testShouldNotBeResetNewPasswordWithoutFields(): void
    {
        $this->post('/app/new-password')
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'success' => false,
                'error' => [
                    'password_token' => [
                        'O campo password token é obrigatório.'
                    ],
                    'password' => [
                        'O campo senha é obrigatório.'
                    ]
                ]
            ]);
    }

    public function testShouldNotBeResetNewPasswordWithInvalidPassword(): void
    {
        $this->post('/app/new-password', ['password' => '123', 'password_confirmation' => '123'])
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'success' => false,
                'error' => [
                    'password' => [
                        'O campo senha deve ter pelo menos 8 caracteres.',
                        'O campo senha deve conter pelo menos uma letra.'
                    ]
                ]
            ]);
    }

    public function testShouldNotResetNewPasswordWithInvalidToken(): void
    {
        $this->post('/app/new-password', ['password_token' => 'abcd14', 'password' => '1234abcd', 'password_confirmation' => '1234abcd'])
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'success' => false,
                'error' => [
                    'message' => 'Token para alteração de senha inválido.'
                ]
            ]);
    }

    public function testShouldReturnForbiddenWithExpiredToken(): void
    {

        $this->post('/app/new-password', ['password_token' => $this->getValidToken(), 'password' => '1234abcd', 'password_confirmation' => '1234abcd'])
            ->assertStatus(JsonResponse::HTTP_FORBIDDEN)
            ->assertJson(
                [
                    'success' => false,
                    'error' => [
                        'message' => 'Token para alteração de senha expirado.'
                    ]
                ]
            );
    }

    public function getValidToken(): string
    {
        $token = Str::random(6);
        PasswordReset::create([
            "user_id" => $this->user->id,
            "token_signature" => hash('md5', $token),
            "used_token" => 1,
            "expires_at" => Carbon::now()
        ]);
        return $token;
    }
}
