<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Laravel\Fortify\Features;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        if (!Features::enabled(Features::resetPasswords())) {
            $this->markTestSkipped('Password updates are not enabled.');
        }

        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        if (!Features::enabled(Features::resetPasswords())) {
            $this->markTestSkipped('Password updates are not enabled.');
        }

        Notification::fake();

        $user = User::factory()->create();

        // Use the Password facade directly to send the reset link (bypassing Livewire form)
        Password::sendResetLink(['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        if (!Features::enabled(Features::resetPasswords())) {
            $this->markTestSkipped('Password updates are not enabled.');
        }

        Notification::fake();

        $user = User::factory()->create();

        Password::sendResetLink(['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function (object $notification): true {
            $response = $this->get('/reset-password/'.$notification->token);

            $response->assertStatus(200);

            return true;
        });
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        if (!Features::enabled(Features::resetPasswords())) {
            $this->markTestSkipped('Password updates are not enabled.');
        }

        Notification::fake();

        $user = User::factory()->create();

        Password::sendResetLink(['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function (object $notification) use ($user): true {
            $status = Password::reset([
                'token'                 => $notification->token,
                'email'                 => $user->email,
                'password'              => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ], function ($user, $password): void {
                $user->forceFill(['password' => bcrypt($password)])->save();
            });

            $this->assertEquals(Password::PASSWORD_RESET, $status);

            return true;
        });
    }
}
