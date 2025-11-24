<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $password = '';

    public function confirmPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden p-8 space-y-6">

        <div class="text-center">
            <div class="mx-auto bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                <x-heroicon-o-shield-check class="h-8 w-8 text-red-600" />
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Konfirmasi Password</h2>
            <p class="mt-2 text-sm text-gray-600">
                Ini adalah area aman. Mohon konfirmasi password Anda sebelum melanjutkan.
            </p>
        </div>

        <form wire:submit="confirmPassword" class="space-y-6">
            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <x-heroicon-o-lock-closed class="h-5 w-5 text-gray-400" />
                    </div>
                    <input type="password" id="password" wire:model="password" required autocomplete="current-password" autofocus
                           class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 outline-none transition duration-150">
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <button type="submit" class="w-full bg-red-600 text-white py-3 rounded-lg font-bold text-lg hover:bg-red-700 transition duration-150 shadow-md shadow-red-300/50">
                Konfirmasi
            </button>
        </form>
    </div>
</div>
