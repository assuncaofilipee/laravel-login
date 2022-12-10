<?php

namespace Tests\Unit\Services;

use App\Models\PasswordReset;
use App\Models\User;
use App\Notifications\PasswordResetNotification;
use App\Services\PasswordResetService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use SebastianBergmann\PeekAndPoke\Proxy;
use Tests\TestCase;

class PasswordResetServiceTest extends TestCase
{
    use DatabaseTransactions;

    private User $user;
    private PasswordResetService $passwordResetService;
    private PasswordResetNotification $proxyNotification;
    private PasswordReset $resetToken;

    public function setUp(): void
    {
        parent::setUp();

        $this->user =  User::factory()->create();

        $passwordReset = new PasswordReset();
        $this->passwordResetService = new PasswordResetService($passwordReset);

        Notification::fake();
        $this->passwordResetService->sendPasswordResentLink($this->user->email);

        Notification::assertSentTo(
            $this->user,
            PasswordResetNotification::class,
            function ($notification) {
                $this->proxyNotification = $notification;
                return $notification !== null;
            }
        );

        $this->resetToken = $this->passwordResetService->getResetToken($this->proxyNotification->password_token);
    }

    public function testSendEmailText(): void
    {
        Notification::assertSentTo(
            $this->user,
            PasswordResetNotification::class,
            function ($notification) {
                return $notification->toMail($this->user)->subject === 'Laravel Login - Recuperação de senha';
            }
        );
    }

    public function testShouldGetResetCodeIsAlphaNumericString(): void
    {
        $token = $this->passwordResetService->getResetCode();
        $this->assertTrue(ctype_alnum($token));
        $this->assertEquals(strlen($token), 6);
    }

    public function testShouldSendPasswordResentLink(): void
    {
        Notification::fake();
        $this->passwordResetService->sendPasswordResentLink($this->user->email);
        Notification::assertSentTo($this->user, PasswordResetNotification::class);
    }

    public function testShouldGetResetIdentifierCode(): void
    {
        $newToken = $this->passwordResetService->getResetIdentifierCode($this->resetToken);

        $this->assertTrue(ctype_alnum($newToken));
        $this->assertEquals(strlen($newToken), 6);
    }

    public function testShouldGetResetToken(): void
    {
        $this->assertInstanceOf(PasswordReset::class, $this->resetToken);
    }

    public function ShouldExpiresToken(): void
    {
        $this->assertTrue($this->passwordResetService->expiresTokenNow($this->resetToken));
        $token = $this->resetToken->fresh();
        sleep(1);
        $this->assertLessThan(Carbon::now()->toArray()['formatted'], $token->expires_at);
    }
}
