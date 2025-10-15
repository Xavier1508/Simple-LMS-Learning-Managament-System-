<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
            return;
        }

        Auth::user()->sendEmailVerificationNotification();
        Session::flash('status', 'verification-link-sent');
    }

    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="w-full max-w-lg text-center p-8 md:p-12 bg-white rounded-2xl shadow-2xl space-y-6">
    <div class="space-y-2">
        <h2 class="text-3xl font-bold text-gray-900">Verify Your Email Address</h2>
        <p class="text-gray-600">
            Thanks for signing up! Before getting started, please verify your email by clicking on the link we just emailed to you. If you didn't receive it, we'll gladly send another.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
        <button wire:click="sendVerification" class="w-full sm:w-auto bg-blue-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-blue-700 transition duration-150 shadow-md shadow-blue-300/50">
            Resend Verification Email
        </button>

        <button wire:click="logout" type="submit" class="text-sm text-gray-600 hover:text-gray-900">
            Log Out
        </button>
    </div>
</div>
