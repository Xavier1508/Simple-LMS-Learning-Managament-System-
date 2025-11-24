<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Validation\ValidationException;
use App\Livewire\Traits\Recaptcha\WithRecaptcha;

new #[Layout('layouts.guest')] class extends Component
{
    use WithRecaptcha;

    public string $lecturer_code = '';
    public string $password = '';
    public string $private_number = '';
    public bool $remember = false;

    protected function throttleKey(): string
    {
        return strtolower($this->lecturer_code).'|'.request()->ip();
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }
        event(new Lockout(request()));
        $seconds = RateLimiter::availableIn($this->throttleKey());
        throw ValidationException::withMessages([
            'lecturer_code' => trans('auth.throttle', ['seconds' => $seconds, 'minutes' => ceil($seconds / 60)]),
        ]);
    }

    public function login(): void
    {
        $this->verifyRecaptcha('login_lecturer');

        $this->ensureIsNotRateLimited();

        $this->validate([
            'lecturer_code' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string'],
            'private_number' => ['required', 'string', 'max:16'],
        ]);

        $user = User::where('lecturer_code', $this->lecturer_code)
                    ->where('role', 'lecturer')
                    ->first();

        if (!$user || !Hash::check($this->password, $user->password)) {
             RateLimiter::hit($this->throttleKey());
             $this->addError('lecturer_code', trans('auth.failed'));
             return;
        }

        if (empty($user->private_number)) {
            $this->addError('private_number', 'Private Number belum diset. Tunggu konfirmasi admin.');
            RateLimiter::hit($this->throttleKey());
            return;
        }

        if ($user->private_number !== $this->private_number) {
            $this->addError('private_number', 'Private Number salah.');
            RateLimiter::hit($this->throttleKey());
            return;
        }

        RateLimiter::clear($this->throttleKey());
        Auth::login($user, $this->remember);
        if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
        }
        Session::regenerate();
        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row w-full max-w-6xl bg-white rounded-2xl shadow-2xl overflow-hidden">

        {{-- FORM SECTION (LEFT) --}}
        <div class="w-full md:w-1/2 p-8 md:p-16 flex flex-col justify-center space-y-8">
            <div class="space-y-2">
                <h2 class="text-4xl font-bold text-gray-900">Lecturer Login</h2>
                <p class="text-gray-500">
                    Area khusus Dosen & Staf Pengajar.
                </p>
            </div>

            {{-- ALPINE.JS Handler for Lecturer --}}
            <form class="space-y-6"
                  wire:ignore.self
                  x-data="{
                      loading: false,
                      submitLecturer() {
                          this.loading = true;
                          const siteKey = '{{ config('services.recaptcha.site_key') }}';

                          if (typeof grecaptcha === 'undefined' || !siteKey) {
                               alert('Google reCAPTCHA belum siap.');
                               this.loading = false;
                               return;
                          }

                          grecaptcha.ready(() => {
                              grecaptcha.execute(siteKey, {action: 'login_lecturer'})
                                  .then((token) => {
                                      // Perhatikan: Property ada di root component, bukan di form object
                                      @this.set('recaptchaToken', token);
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
                  x-on:submit.prevent="submitLecturer">

                {{-- Lecturer Code --}}
                <div>
                    <label for="lecturer_code" class="block text-sm font-medium text-gray-700 mb-1">Kode Dosen</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <x-heroicon-o-identification class="h-5 w-5 text-gray-400" />
                        </div>
                        <input type="text" id="lecturer_code" wire:model="lecturer_code" placeholder="Contoh: LEC191125JD" required autofocus
                               class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150 uppercase tracking-wider font-semibold">
                    </div>
                    @error('lecturer_code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <x-heroicon-o-lock-closed class="h-5 w-5 text-gray-400" />
                        </div>
                        <input type="password" id="password" wire:model="password" placeholder="Masukkan password" required autocomplete="current-password"
                               class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150">
                    </div>
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Private Number --}}
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label for="private_number" class="block text-sm font-medium text-gray-700">Private Number</label>
                        <span class="text-xs font-bold text-red-500 bg-red-50 px-2 py-0.5 rounded-full border border-red-100">RAHASIA</span>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <x-heroicon-o-key class="h-5 w-5 text-gray-400" />
                        </div>
                        <input type="password" id="private_number" wire:model="private_number" placeholder="Kunci Keamanan Tambahan" required
                               class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150 font-mono">
                    </div>
                    @error('private_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Error Message Recaptcha --}}
                @error('recaptchaToken')
                    <div class="p-2 bg-red-50 text-red-500 text-xs rounded text-center font-bold">
                        {{ $message }}
                    </div>
                @enderror

                {{-- Remember Me & Forgot Password --}}
                <div class="flex items-center justify-between">
                    <label for="remember" class="inline-flex items-center">
                        <input wire:model="remember" id="remember" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                        <span class="ms-2 text-sm text-gray-600">Ingat Saya</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request', ['type' => 'lecturer']) }}" wire:navigate class="text-sm font-medium text-gray-600 hover:text-blue-600 transition duration-150">
                            Lupa Password?
                        </a>
                    @endif
                </div>

                {{-- Button Login Lecturer --}}
                <button type="submit"
                        class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold text-lg hover:bg-blue-700 transition duration-150 shadow-md shadow-blue-300/50 flex justify-center items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed"
                        x-bind:disabled="loading">

                    <span x-show="!loading" class="flex items-center gap-2">
                        Masuk Portal Dosen <x-heroicon-m-arrow-right class="w-5 h-5" />
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
                    <a href="{{ route('register.lecturer') }}" wire:navigate class="text-blue-600 font-semibold hover:text-blue-700">Sign up</a>
                </span>

                <p class="pt-4 text-sm text-gray-500 border-t border-gray-100 mt-4">
                    Atau Login sebagai Mahasiswa?
                    <a href="{{ route('login') }}" wire:navigate class="text-orange-600 font-semibold hover:text-orange-700">Login Mahasiswa</a>
                </p>
            </div>
        </div>

        {{-- IMAGE SECTION --}}
        <div class="hidden md:flex md:w-1/2 p-8 md:p-12 flex-col justify-between bg-[#307de2]">
            <div class="flex items-center space-x-2">
                <span class="text-xl font-extrabold tracking-tight text-white">Ascend <span class="font-bold text-yellow-300">LMS</span></span>
            </div>
            <div class="flex justify-center items-center">
                <img class="w-full rounded-2xl shadow-lg transform hover:scale-105 transition duration-500" src="{{ asset('images/login-1.jpg') }}" alt="[Ilustrasi Login]">
            </div>
            <div class="space-y-4">
                <h1 class="text-3xl md:text-4xl font-extrabold text-white font-black leading-tight">
                    Empowering Educators.
                </h1>
                <p class="text-white text-md">
                    Kelola kelas, nilai, dan materi pembelajaran dengan mudah dan efisien.
                </p>
            </div>
        </div>
    </div>
</div>
