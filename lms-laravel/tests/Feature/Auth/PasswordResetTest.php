<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response
            ->assertOk()
            ->assertSeeVolt('pages.auth.forgot-password');
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        $user = User::factory()->create();

        // Pastikan pakai set('email') karena di komponen propertinya $email
        Volt::test('pages.auth.forgot-password')
            ->set('email', $user->email)
            ->call('sendPasswordResetLink')
            ->assertHasNoErrors();
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $token = Password::createToken($user);

        $response = $this->get('/reset-password/'.$token);

        $response
            ->assertOk()
            ->assertSeeVolt('pages.auth.reset-password');
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        $user = User::factory()->create();

        $token = Password::createToken($user);

        Volt::test('pages.auth.reset-password', ['token' => $token])
            ->set('email', $user->email)
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('resetPassword');

        $this->assertTrue(Hash::check('password', $user->refresh()->password));
    }
}
