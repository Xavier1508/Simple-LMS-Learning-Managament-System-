<?php

use App\Models\User;
use App\Mail\RegisterOtpMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
// Import Trait
use App\Livewire\Traits\Recaptcha\WithRecaptcha;

new #[Layout('layouts.guest')] class extends Component
{
    use WithRecaptcha;

    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $phone_number = '';
    public string $password = '';
    public string $password_confirmation = '';

    public int $register_step = 1;
    public string $otp_code = '';

    protected function generateOtp(): string
    {
        $char = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $num = '0123456789';
        $lower = 'abcdefghijklmnopqrstuvwxyz';
        $otp = Str::random(3, $char) . Str::random(3, $num) . Str::random(3, $lower);
        return substr(str_shuffle($otp), 0, 9);
    }

    public function register(): void
    {
        if ($this->register_step === 1) {
            $this->verifyRecaptcha('register_student');

            $validated = $this->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'phone_number' => ['required', 'string', 'max:15'],
                'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            ]);

            $otp = $this->generateOtp();

            User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'],
                'password' => Hash::make($validated['password']),
                'role' => 'student',
                'otp_code' => $otp,
                'otp_expires_at' => Carbon::now()->addSeconds(90),
            ]);

            Mail::to($validated['email'])->send(new RegisterOtpMail($otp, 1.5));

            $this->register_step = 2;
            session()->flash('otp-sent', 'Kode OTP telah dikirim ke email Anda.');
        }
        elseif ($this->register_step === 2) {
            $this->validate(['otp_code' => 'required|string|size:9']);

            $user = User::where('email', $this->email)->first();

            if (!$user) {
                $this->addError('otp_code', 'User tidak ditemukan. Silakan daftar ulang.');
                return;
            }

            if (trim($user->otp_code) !== trim($this->otp_code)) {
                $this->addError('otp_code', 'Kode OTP salah.');
                return;
            }

            if (Carbon::now()->isAfter($user->otp_expires_at)) {
                $this->addError('otp_code', 'Kode OTP kedaluwarsa. Silakan kirim ulang.');
                return;
            }

            $user->forceFill(['otp_code' => null, 'otp_expires_at' => null])->save();

            session()->flash('status', 'Registrasi berhasil! Silakan login untuk memverifikasi email Anda.');
            $this->redirect(route('login'), navigate: true);
        }
    }

    public function resendOtp(): void
    {
        if ($this->register_step === 2) {
            $user = User::where('email', $this->email)->first();
            if ($user) {
                $otp = $this->generateOtp();
                $user->forceFill([
                    'otp_code' => $otp,
                    'otp_expires_at' => Carbon::now()->addSeconds(90),
                ])->save();

                Mail::to($user->email)->send(new RegisterOtpMail($otp, 1.5));
                session()->flash('otp-sent', 'Kode OTP baru telah dikirimkan.');
            }
        }
    }
}; ?>

<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
   <div class="flex flex-col md:flex-row w-full max-w-6xl bg-white rounded-2xl shadow-2xl overflow-hidden">

        {{-- FORM SECTION (LEFT) --}}
        <div class="w-full md:w-1/2 p-8 md:p-16 flex flex-col justify-center space-y-6">
            <div class="space-y-2">
                <h2 class="text-4xl font-bold text-gray-900">Student Registration</h2>
                <p class="text-gray-500">
                    Ayo bergabung dengan Ascend LMS dan mulai proses belajar Anda.
                </p>
            </div>

            @if ($register_step === 2 && session()->has('otp-sent'))
                <div class="p-3 mb-4 text-sm text-green-700 bg-green-100 rounded-lg border border-green-300 flex items-center gap-2">
                    <x-heroicon-m-check-circle class="w-5 h-5" />
                    {{ session('otp-sent') }}
                </div>
            @endif

            {{-- ALPINE.JS Form Handler --}}
            <form class="space-y-6"
                  wire:ignore.self
                  x-data="{
                      loading: false,
                      submitRegister() {
                          // Hanya jalankan Recaptcha pada Step 1
                          if ({{ $register_step }} !== 1) return;

                          this.loading = true;
                          const siteKey = '{{ config('services.recaptcha.site_key') }}';

                          if (typeof grecaptcha === 'undefined' || !siteKey) {
                               alert('Google reCAPTCHA belum siap.');
                               this.loading = false;
                               return;
                          }

                          grecaptcha.ready(() => {
                              grecaptcha.execute(siteKey, {action: 'register_student'})
                                  .then((token) => {
                                      @this.set('recaptchaToken', token);
                                      @this.register().then(() => {
                                          this.loading = false;
                                      });
                                  })
                                  .catch((e) => {
                                      console.error(e);
                                      this.loading = false;
                                  });
                          });
                      }
                  }"
                  x-on:submit.prevent="submitRegister">

                @if ($register_step === 1)
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                            <input type="text" id="first_name" wire:model="first_name" placeholder="Nama Depan" required autofocus
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500 outline-none transition duration-150">
                            @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                            <input type="text" id="last_name" wire:model="last_name" placeholder="Nama Belakang" required
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500 outline-none transition duration-150">
                            @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <x-heroicon-o-envelope class="h-5 w-5 text-gray-400" />
                            </div>
                            <input type="email" id="email" wire:model="email" placeholder="email@contoh.com" required
                                   class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500 outline-none transition duration-150">
                        </div>
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <x-heroicon-o-phone class="h-5 w-5 text-gray-400" />
                            </div>
                            <input type="text" id="phone_number" wire:model="phone_number" placeholder="0812..." required
                                   class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500 outline-none transition duration-150">
                        </div>
                        @error('phone_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <x-heroicon-o-lock-closed class="h-5 w-5 text-gray-400" />
                            </div>
                            <input type="password" id="password" wire:model="password" placeholder="Buat password kuat" required autocomplete="new-password"
                                   class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500 outline-none transition duration-150">
                        </div>
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <x-heroicon-o-check-circle class="h-5 w-5 text-gray-400" />
                            </div>
                            <input type="password" id="password_confirmation" wire:model="password_confirmation" placeholder="Ulangi password" required autocomplete="new-password"
                                   class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500 outline-none transition duration-150">
                        </div>
                        @error('password_confirmation') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Error Message Recaptcha --}}
                    @error('recaptchaToken')
                        <div class="p-2 bg-red-50 text-red-500 text-xs rounded text-center font-bold">
                            {{ $message }}
                        </div>
                    @enderror

                    {{-- Button Step 1 (Trigger Submit Alpine) --}}
                    <button type="submit"
                            class="w-full bg-orange-600 text-white py-3 rounded-lg font-bold text-lg hover:bg-orange-700 transition duration-150 shadow-md shadow-orange-300/50 flex justify-center items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed"
                            x-bind:disabled="loading">

                        <span x-show="!loading" class="flex items-center gap-2">
                            Lanjutkan & Kirim OTP <x-heroicon-m-arrow-right class="w-5 h-5" />
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

                @elseif ($register_step === 2)
                    <div class="text-center p-6 border-2 border-dashed border-orange-200 rounded-xl bg-orange-50">
                        <x-heroicon-o-chat-bubble-bottom-center-text class="w-12 h-12 text-orange-500 mx-auto mb-2" />
                        <p class="text-lg font-bold text-gray-800">Verifikasi Email</p>
                        <p class="text-sm text-gray-600 mt-1">Kode OTP (9 karakter) telah dikirimkan ke <strong>{{ $email }}</strong>.</p>
                    </div>

                    <div>
                        <label for="otp_code" class="block text-sm font-medium text-gray-700 mb-1">Kode OTP</label>
                        <input wire:model="otp_code" id="otp_code" name="otp_code" type="text" maxlength="9" placeholder="X1Y2Z3..." required
                            class="w-full p-4 border-2 border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500 outline-none transition duration-150 text-2xl text-center tracking-[0.5em] font-mono uppercase">
                        @error('otp_code') <p class="text-red-500 text-xs mt-1 text-center">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-col space-y-3 pt-4">
                        {{-- Button Step 2 (Direct Wire Click) --}}
                        <button type="button" wire:click="register" class="w-full bg-orange-600 text-white py-3 rounded-lg font-bold text-lg hover:bg-orange-700 transition duration-150 shadow-md shadow-orange-300/50">
                            Verifikasi & Login
                        </button>
                        <button type="button" wire:click="resendOtp" class="w-full text-orange-600 py-3 rounded-lg font-bold hover:text-orange-800 transition duration-150 bg-white border border-orange-600 hover:bg-orange-50">
                            Kirim Ulang OTP
                        </button>
                    </div>
                    <p class="text-xs text-gray-400 text-center mt-4">
                        This site is protected by reCAPTCHA and the Google
                        <a href="https://policies.google.com/privacy" class="text-blue-600 hover:underline">Privacy Policy</a> and
                        <a href="https://policies.google.com/terms" class="text-blue-600 hover:underline">Terms of Service</a> apply.
                    </p>
                @endif
            </form>

            <div class="text-center pt-4">
                <span class="text-sm text-gray-500">
                    Already have an account?
                    <a href="{{ route('login') }}" wire:navigate class="text-orange-600 font-semibold hover:text-orange-700">Login Mahasiswa</a>
                </span>
                <p class="pt-4 text-sm text-gray-500 border-t border-gray-100 mt-4">
                    Daftar sebagai Dosen?
                    <a href="{{ route('register.lecturer') }}" wire:navigate class="text-blue-600 font-semibold hover:text-blue-700">Daftar Dosen</a>
                </p>
            </div>
        </div>

        {{-- IMAGE SECTION (RIGHT) --}}
        <div class="hidden md:flex md:w-1/2 p-8 md:p-12 flex-col justify-between bg-[#FAA22F]">
            <div class="flex items-center space-x-2">
                <span class="text-xl font-extrabold tracking-tight text-white">Ascend <span class="font-bold text-yellow-300">LMS</span></span>
            </div>
            <div class="flex justify-center items-center">
                <img class="w-full rounded-2xl shadow-lg transform hover:scale-105 transition duration-500" src="{{ asset('images/signup-1.jpg') }}" alt="[Ilustrasi Pendaftaran]">
            </div>
            <div class="space-y-4">
                <h1 class="text-3xl md:text-4xl text-white font-black leading-tight">
                    Mulai Perjalanan Akademik Anda.
                </h1>
                <p class="text-white text-md">
                    Daftar sekarang dan akses semua materi serta jadwal kuliah Anda.
                </p>
            </div>
        </div>
    </div>
</div>
