<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url; // Tambahkan Attribute URL
use Livewire\Volt\Component;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

new #[Layout('layouts.guest')] class extends Component
{
    // Tangkap parameter ?type= dari URL
    #[Url]
    public ?string $type = null;

    public string $email = '';
    public string $recaptchaToken = '';

    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'email'],
            'recaptchaToken' => ['required'],
        ], [
            'recaptchaToken.required' => 'Sedang memuat keamanan... Tunggu sebentar dan klik lagi.',
        ]);

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret'),
            'response' => $this->recaptchaToken,
            'remoteip' => request()->ip(),
        ]);

        $data = $response->json();

        if (!($data['success'] ?? false)) {
            // Log error tapi jangan berhentikan total di local jika config salah, beri pesan user
            Log::error('reCAPTCHA Failed:', $data);

            // Bypass jika error code invalid-input-secret (biasanya salah copas di .env saat dev)
            $errors = $data['error-codes'] ?? [];
            if (!in_array('invalid-input-secret', $errors)) {
                 $this->addError('email', 'Gagal verifikasi Google. Silakan refresh halaman.');
                 return;
            }
        }

        $minScore = app()->isLocal() ? 0.1 : 0.5;
        if (($data['score'] ?? 0) < $minScore) {
            $this->addError('email', 'Terdeteksi aktivitas tidak wajar. Coba refresh halaman.');
            return;
        }

        $throttleKey = 'forgot-password:'.request()->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->addError('email', "Terlalu banyak percobaan. Tunggu $seconds detik.");
            return;
        }

        $status = Password::sendResetLink(['email' => $this->email]);

        RateLimiter::hit($throttleKey, 3600);

        // Flash Message
        session()->flash('status', 'Jika akun dengan email tersebut ada, kami telah mengirimkan link reset password.');

        $this->email = '';
        $this->recaptchaToken = '';
    }
}; ?>

<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8 relative">

    <style>
        .grecaptcha-badge {
            visibility: visible !important;
            z-index: 9999;
            bottom: 20px !important;
            right: 20px !important;
        }
    </style>

    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>

    {{-- SETUP WARNA DINAMIS --}}
    @php
        $isLecturer = $type === 'lecturer';
        $themeColor = $isLecturer ? 'blue' : 'orange'; // Base color name

        // Kelas CSS Dinamis
        $bgIcon = $isLecturer ? 'bg-blue-100' : 'bg-orange-100';
        $textIcon = $isLecturer ? 'text-blue-600' : 'text-orange-600';
        $focusRing = $isLecturer ? 'focus:ring-blue-500 focus:border-blue-500' : 'focus:ring-orange-500 focus:border-orange-500';
        $btnBg = $isLecturer ? 'bg-blue-600 hover:bg-blue-700 shadow-blue-300/50' : 'bg-orange-600 hover:bg-orange-700 shadow-orange-300/50';
        $hoverText = $isLecturer ? 'hover:text-blue-600' : 'hover:text-orange-600';
        $loginRoute = $isLecturer ? route('login.lecturer') : route('login');
    @endphp

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden p-8 space-y-6 z-10">

        <div class="text-center">
            <div class="mx-auto {{ $bgIcon }} w-16 h-16 rounded-full flex items-center justify-center mb-4">
                <x-heroicon-o-key class="h-8 w-8 {{ $textIcon }}" />
            </div>
            <h2 class="text-2xl font-bold text-gray-900">
                {{ $isLecturer ? 'Dosen Lupa Password?' : 'Lupa Password?' }}
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Masukkan email {{ $isLecturer ? 'resmi dosen' : '' }} Anda dan kami akan mengirimkan link reset.
            </p>
        </div>

        {{-- UPDATE FLASH MESSAGE: Tahan 10 Detik & Ada Tombol Close --}}
        @if (session('status'))
            <div x-data="{ show: true }"
                 x-show="show"
                 x-init="setTimeout(() => show = false, 10000)"
                 x-transition.duration.500ms
                 class="p-4 rounded-lg bg-green-50 border border-green-200 flex items-start justify-between gap-3 relative">

                <div class="flex gap-3">
                    <x-heroicon-s-check-circle class="w-5 h-5 text-green-600 mt-0.5 shrink-0" />
                    <p class="text-sm text-green-700 font-medium leading-relaxed">
                        {{ session('status') }}
                    </p>
                </div>

                {{-- Tombol Close Manual --}}
                <button @click="show = false" class="text-green-500 hover:text-green-700">
                    <x-heroicon-m-x-mark class="w-5 h-5" />
                </button>
            </div>
        @endif

        <form class="space-y-6"
              wire:ignore
              x-data="{
                  loading: false,
                  submitForm() {
                      this.loading = true;
                      const siteKey = '{{ config('services.recaptcha.site_key') }}';

                      if (!siteKey || typeof grecaptcha === 'undefined') {
                          // Fallback jika recaptcha gagal load (misal offline/adblock)
                          @this.set('recaptchaToken', 'bypass-local');
                          @this.sendPasswordResetLink().then(() => this.loading = false);
                          return;
                      }

                      grecaptcha.ready(() => {
                          grecaptcha.execute(siteKey, {action: 'submit'})
                              .then((token) => {
                                  @this.set('recaptchaToken', token);
                                  @this.sendPasswordResetLink().then(() => {
                                      this.loading = false;
                                      @this.set('recaptchaToken', '');
                                  });
                              })
                              .catch((e) => {
                                  console.error(e);
                                  this.loading = false;
                                  alert('Gagal koneksi Google Recaptcha.');
                              });
                      });
                  }
              }"
              x-on:submit.prevent="submitForm">

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <x-heroicon-o-envelope class="h-5 w-5 text-gray-400" />
                    </div>
                    <input type="email" id="email" wire:model="email" placeholder="contoh@email.com" required autofocus
                           class="w-full pl-10 p-3 border border-gray-300 rounded-lg {{ $focusRing }} outline-none transition duration-150">
                </div>
            </div>

            <button type="submit"
                    class="w-full {{ $btnBg }} text-white py-3 rounded-lg font-bold text-lg transition duration-150 shadow-md flex justify-center items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                    x-bind:disabled="loading">

                <span x-show="!loading">Kirim Link Reset</span>
                <span x-show="loading" class="flex items-center gap-2" style="display: none;">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memproses...
                </span>
                <x-heroicon-m-paper-airplane class="w-5 h-5" x-show="!loading" />
            </button>
        </form>

        <div class="text-center">
             <x-input-error :messages="$errors->get('email')" class="mt-2" />
             <x-input-error :messages="$errors->get('recaptchaToken')" class="mt-2" />
        </div>

        <div class="text-center border-t border-gray-100 pt-4">
            <a href="{{ $loginRoute }}" wire:navigate class="inline-flex items-center gap-1 text-sm font-medium text-gray-600 {{ $hoverText }} transition duration-150">
                <x-heroicon-m-arrow-left class="w-4 h-4" />
                Kembali ke Login {{ $isLecturer ? 'Dosen' : '' }}
            </a>
        </div>
    </div>
</div>
