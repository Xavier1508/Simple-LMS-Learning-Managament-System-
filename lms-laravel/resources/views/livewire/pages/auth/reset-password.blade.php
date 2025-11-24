<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;
use Illuminate\Validation\Rules;

new #[Layout('layouts.guest')] class extends Component
{
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount(string $token): void
    {
        $this->token = $token;
        $this->email = request()->string('email', '');
    }

    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            session()->flash('status', __($status));
            $this->redirectRoute('login', navigate: true);
        } else {
            $this->addError('email', __($status));
        }
    }
}; ?>

<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden p-8 space-y-6">

        <div class="text-center">
            <div class="mx-auto bg-orange-100 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                <x-heroicon-o-lock-closed class="h-8 w-8 text-orange-600" />
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Reset Password</h2>
            <p class="mt-2 text-sm text-gray-600">
                Silakan buat password baru untuk akun Anda.
            </p>
        </div>

        <form wire:submit="resetPassword" class="space-y-6">

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <x-heroicon-o-envelope class="h-5 w-5 text-gray-400" />
                    </div>
                    <input type="email" id="email" wire:model="email" required readonly
                           class="w-full pl-10 p-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed focus:ring-orange-500 focus:border-orange-500">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- New Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <x-heroicon-o-key class="h-5 w-5 text-gray-400" />
                    </div>
                    <input type="password" id="password" wire:model="password" required autocomplete="new-password" autofocus placeholder="Minimal 8 karakter"
                           class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500 outline-none transition duration-150">
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <x-heroicon-o-check-circle class="h-5 w-5 text-gray-400" />
                    </div>
                    <input type="password" id="password_confirmation" wire:model="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password baru"
                           class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500 outline-none transition duration-150">
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <button type="submit" class="w-full bg-orange-600 text-white py-3 rounded-lg font-bold text-lg hover:bg-orange-700 transition duration-150 shadow-md shadow-orange-300/50">
                Reset Password
            </button>
        </form>
    </div>
</div>
