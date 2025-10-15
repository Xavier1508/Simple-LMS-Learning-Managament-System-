<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col md:flex-row w-full max-w-6xl bg-white rounded-2xl shadow-2xl overflow-hidden">
    <div class="hidden md:flex md:w-1/2 p-8 md:p-12 flex-col justify-between bg-[#FAA22F]">
        <div class="flex items-center space-x-2">
            <span class="text-xl font-extrabold tracking-tight">Ascend <span class="font-bold text-yellow-300">LMS</span></span>
        </div>
        <div class="flex justify-center items-center">
            <img class="w-full rounded-2xl" src="{{ asset('images/login-1.jpg') }}" alt="Login Illustration">
        </div>
        <div class="space-y-4">
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray font-black leading-tight">
                The Grind Doesn't Stop.
            </h1>
            <p class="text-gray font-semibold text-lg">
                Stop Coasting. Your Next Breakthrough Awaits!
            </p>
        </div>
    </div>

    <div class="w-full md:w-1/2 p-8 md:p-16 flex flex-col justify-start space-y-8">
        <div class="space-y-2">
            <h2 class="text-4xl font-bold text-gray-900">Welcome back!</h2>
            <p class="text-gray-500">
                Ready to pick up where you left off?
            </p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form wire:submit="login" class="space-y-6">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                <input type="email" id="email" wire:model="form.email" placeholder="Example@gmail.com" required autofocus autocomplete="username"
                       class="mt-1 w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150">
                <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
            </div>

            <div>
                <div class="flex justify-between items-center">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" wire:navigate class="text-sm font-medium text-blue-600 hover:text-blue-800 transition duration-150">
                            Forgot password?
                        </a>
                    @endif
                </div>
                <div class="relative mt-1">
                    <input type="password" id="password" wire:model="form.password" placeholder="Enter password" required autocomplete="current-password"
                           class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150">
                </div>
                 <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
            </div>

            <div class="block">
                <label for="remember" class="inline-flex items-center">
                    <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold text-lg hover:bg-blue-700 transition duration-150 shadow-md shadow-blue-300/50">
                Continue now!
            </button>
        </form>

        <div class="text-center pt-4">
            <span class="text-sm text-gray-500">
                Don't have an account?
                <a href="{{ route('register') }}" wire:navigate class="text-red-500 font-semibold hover:text-red-600">Sign up</a>
            </span>
        </div>
    </div>
</div>
