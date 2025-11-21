<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = ''; // Properti publik yang dicari Test

    /**
     * Send the password reset link.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status == Password::RESET_LINK_SENT) {
            session()->flash('status', __($status));
            $this->email = ''; // Reset email field
        } else {
            $this->addError('email', __($status));
        }
    }
}; ?>

<div class="w-full max-w-md p-8 bg-white rounded-2xl shadow-2xl space-y-6">
    <div class="text-center">
        <h2 class="text-2xl font-bold text-gray-900">Forgot Password?</h2>
        <p class="mt-2 text-sm text-gray-600">
            {{ __('No problem. Just let us know your email address and we will email you a password reset link.') }}
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="space-y-6">
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full p-3 border border-gray-300 rounded-lg" type="email" name="email" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 transition shadow-md">
                {{ __('Email Password Reset Link') }}
            </button>
        </div>
    </form>

    <div class="text-center pt-2">
        <a href="{{ route('login') }}" wire:navigate class="text-sm text-gray-600 hover:text-gray-900 underline">
            Back to Login
        </a>
    </div>
</div>
