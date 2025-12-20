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
        $this->form->authenticate(expectedRole: 'student');

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row w-full max-w-6xl bg-white rounded-2xl shadow-2xl overflow-hidden">

        {{-- FORM SECTION (LEFT) --}}
        <div class="w-full md:w-1/2 p-8 md:p-16 flex flex-col justify-center space-y-8">
            <div class="space-y-2">
                <h2 class="text-4xl font-bold text-gray-900">Student Login</h2>
                <p class="text-gray-500">
                    Selamat datang kembali! Silakan login untuk melanjutkan.
                </p>
            </div>

            {{-- ALPINE.JS Form Handler --}}
            <form class="space-y-6"
                  wire:ignore.self
                  x-data="{
                      loading: false,
                      showPassword: false,
                      submitLogin() {
                          this.loading = true;
                          const siteKey = '{{ config('services.recaptcha.site_key') }}';

                          if (typeof grecaptcha === 'undefined' || !siteKey) {
                               alert('Google reCAPTCHA belum siap. Periksa koneksi internet.');
                               this.loading = false;
                               return;
                          }

                          grecaptcha.ready(() => {
                              grecaptcha.execute(siteKey, {action: 'login_student'})
                                  .then((token) => {
                                      // Set token ke LoginForm property
                                      @this.set('form.recaptchaToken', token);
                                      // Panggil method login Livewire
                                      @this.login().then(() => {
                                          this.loading = false;
                                      });
                                  })
                                  .catch((error) => {
                                      console.error(error);
                                      this.loading = false;
                                  });
                          });
                      }
                  }"
                  x-on:submit.prevent="submitLogin">

                {{-- Email Input --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <x-heroicon-o-envelope class="h-5 w-5 text-gray-400" />
                        </div>
                        <input type="email" id="email" wire:model="form.email" placeholder="Masukkan email Anda" required autofocus
                               class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500 outline-none transition duration-150">
                    </div>
                    @error('form.email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Password Input --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <x-heroicon-o-lock-closed class="h-5 w-5 text-gray-400" />
                        </div>

                        {{-- Ubah type jadi dinamis pakai Alpine (:type) --}}
                        <input :type="showPassword ? 'text' : 'password'"
                            id="password"
                            wire:model="form.password"
                            placeholder="Masukkan password"
                            required
                            autocomplete="current-password"
                            class="w-full pl-10 pr-10 p-3 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500 outline-none transition duration-150">

                        {{-- Tombol Mata --}}
                        <button type="button" @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">

                            {{-- Icon Mata Terbuka (Show) --}}
                            <x-heroicon-o-eye x-show="!showPassword" class="h-5 w-5" />

                            {{-- Icon Mata Tertutup (Hide) --}}
                            <x-heroicon-o-eye-slash x-show="showPassword" style="display: none;" class="h-5 w-5" />
                        </button>
                    </div>
                    @error('form.password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Error Message Recaptcha (Hidden Logic) --}}
                @error('form.recaptchaToken')
                    <div class="p-2 bg-red-50 text-red-500 text-xs rounded text-center font-bold">
                        {{ $message }}
                    </div>
                @enderror

                {{-- Remember Me & Forgot Password --}}
                <div class="flex items-center justify-between">
                    <label for="remember" class="inline-flex items-center">
                        <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-gray-300 text-orange-600 shadow-sm focus:ring-orange-500" name="remember">
                        <span class="ms-2 text-sm text-gray-600">Ingat Saya</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm font-medium text-gray-600 hover:text-orange-600 transition duration-150">
                            Lupa Password?
                        </a>
                    @endif
                </div>

                {{-- Login Button dengan Loading State --}}
                <button type="submit"
                        class="w-full bg-orange-600 text-white py-3 rounded-lg font-bold text-lg hover:bg-orange-700 transition duration-150 shadow-md shadow-orange-300/50 flex justify-center items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed"
                        x-bind:disabled="loading">

                    <span x-show="!loading" class="flex items-center gap-2">
                        Log in <x-heroicon-m-arrow-right class="w-5 h-5" />
                    </span>

                    <span x-show="loading" style="display: none;" class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses...
                    </span>
                </button>

                <p class="text-xs text-gray-400 text-center mt-4">
                    This site is protected by reCAPTCHA and the Google
                    <a href="https://policies.google.com/privacy" class="text-blue-600 hover:underline">Privacy Policy</a> and
                    <a href="https://policies.google.com/terms" class="text-blue-600 hover:underline">Terms of Service</a> apply.
                </p>
            </form>

            <div class="text-center pt-4">
                <span class="text-sm text-gray-500">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-orange-600 font-semibold hover:text-orange-700">Sign up</a>
                </span>

                <p class="pt-4 text-sm text-gray-500 border-t border-gray-100 mt-4">
                    Atau Login sebagai Dosen?
                    <a href="{{ route('login.lecturer') }}" class="text-blue-600 font-semibold hover:text-blue-700">Login Dosen</a>
                </p>
            </div>
        </div>

        {{-- IMAGE SECTION --}}
        <div class="hidden md:flex md:w-1/2 p-8 md:p-12 flex-col justify-between bg-[#FAA22F]">
            <div class="flex items-center space-x-2">
                <span class="text-xl font-extrabold tracking-tight text-white">Ascend <span class="font-bold text-yellow-300">LMS</span></span>
            </div>
            <div class="flex justify-center items-center">
                <img class="w-full rounded-2xl shadow-lg transform hover:scale-105 transition duration-500" src="{{ asset('images/login-1.jpg') }}" alt="[Ilustrasi Login]">
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
