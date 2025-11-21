<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response
            ->assertOk()
            ->assertSeeVolt('pages.auth.register');
    }

    public function test_new_users_can_register_step_one(): void
    {
        $component = Volt::test('pages.auth.register')
            ->set('first_name', 'Test')      // GANTI: name -> first_name
            ->set('last_name', 'User')       // TAMBAH: last_name
            ->set('email', 'test@example.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password');

        $component->call('register');
        $component->assertHasNoErrors();

        // Cek apakah user berhasil dibuat di database
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'first_name' => 'Test',
            'last_name' => 'User',
        ]);
    }
}
