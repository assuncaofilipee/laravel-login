<?php

namespace Tests\Unit\Repositories;

use App\Models\PasswordReset;
use App\Models\User;
use App\Notifications\PasswordResetNotification;
use App\Repositories\PasswordResetRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    private User $user;
    private PasswordResetRepository $passwordResetRepository;
    private PasswordResetNotification $proxyNotification;
    private PasswordReset $resetToken;

    public function setUp(): void
    {
        parent::setUp();

        $this->user =  User::factory()->create();

        $this->passwordResetRepository = new PasswordResetRepository(new PasswordReset(), new User());

        Notification::fake();
        $this->passwordResetRepository->sendPasswordResentLink($this->user->email);

        Notification::assertSentTo(
            $this->user,
            PasswordResetNotification::class,
            function ($notification) {
                $this->proxyNotification = $notification;
                return $notification !== null;
            }
        );

        $this->resetToken = $this->passwordResetRepository->getResetToken($this->proxyNotification->password_token);
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
        $token = $this->passwordResetRepository->getResetCode();
        $this->assertTrue(ctype_alnum($token));
        $this->assertEquals(strlen($token), 6);
    }

    public function testShouldSendPasswordResentLink(): void
    {
        Notification::fake();
        $this->passwordResetRepository->sendPasswordResentLink($this->user->email);
        Notification::assertSentTo($this->user, PasswordResetNotification::class);
    }

    public function testShouldGetResetIdentifierCode(): void
    {
        $newToken = $this->passwordResetRepository->getResetIdentifierCode($this->resetToken);

        $this->assertTrue(ctype_alnum($newToken));
        $this->assertEquals(strlen($newToken), 6);
    }

    public function testShouldGetResetToken(): void
    {
        $this->assertInstanceOf(PasswordReset::class, $this->resetToken);
    }

    public function ShouldExpiresToken(): void
    {
        $this->assertTrue($this->passwordResetRepository->expiresTokenNow($this->resetToken));
        $token = $this->resetToken->fresh();
        sleep(1);
        $this->assertLessThan(Carbon::now()->toArray()['formatted'], $token->expires_at);
    }
}
