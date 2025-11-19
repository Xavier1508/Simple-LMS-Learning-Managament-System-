<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        // Langsung login tanpa step OTP
        $this->form->authenticate(expectedRole: 'student');

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row w-full max-w-6xl bg-white rounded-2xl shadow-2xl overflow-hidden">
        <div class="w-full md:w-1/2 p-8 md:p-16 flex flex-col justify-center space-y-8">
            <div class="space-y-2">
                <h2 class="text-4xl font-bold text-gray-900">Student Login</h2>
                <p class="text-gray-500">
                    Selamat datang kembali! Silakan login untuk melanjutkan.
                </p>
            </div>

            <form wire:submit="login" class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" id="email" wire:model="form.email" placeholder="Masukkan email Anda" required autofocus
                           class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150">
                    @error('form.email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="relative mt-1">
                        <input type="password" id="password" wire:model="form.password" placeholder="Masukkan password" required autocomplete="current-password"
                               class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150">
                    </div>
                     @error('form.password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="block">
                    <label for="remember" class="inline-flex items-center">
                        <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                        <span class="ms-2 text-sm text-gray-600">Ingat Saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold text-lg hover:bg-blue-700 transition duration-150 shadow-md shadow-blue-300/50">
                    Log in
                </button>
            </form>

            <div class="text-center pt-4">
                <span class="text-sm text-gray-500">
                    Don't have an account?
                    <a href="{{ route('register') }}" wire:navigate class="text-red-500 font-semibold hover:text-red-600">Sign up</a>
                </span>

                <p class="pt-2 text-sm text-gray-500">
                    Atau Login sebagai Dosen?
                    <a href="{{ route('login.lecturer') }}" wire:navigate class="text-blue-500 font-semibold hover:text-blue-600">Login Dosen</a>
                </p>
            </div>
        </div>

        <div class="hidden md:flex md:w-1/2 p-8 md:p-12 flex-col justify-between bg-[#FAA22F]">
            <div class="flex items-center space-x-2">
                <span class="text-xl font-extrabold tracking-tight text-white">Ascend <span class="font-bold text-yellow-300">LMS</span></span>
            </div>
            <div class="flex justify-center items-center">
                <img class="w-full rounded-2xl" src="{{ asset('images/login-1.jpg') }}" alt="[Ilustrasi Login]">
            </div>
            <div class="space-y-4">
                <h1 class="text-3xl md:text-4xl font-extrabold text-white font-black leading-tight">
                    The Grind Doesn't Stop.
                </h1>
                <p class="text-white text-md">
                    Akses materi, nilai, dan jadwal kuliah Anda dengan cepat dan mudah.
                </p>
            </div>
        </div>
    </div>
</div>
