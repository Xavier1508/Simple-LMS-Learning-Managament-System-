<?php

use App\Mail\LecturerCredentials;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
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
    public ?User $user = null;

    private function generateLecturerCode(string $firstName, string $lastName): string
    {
        $day = now()->format('d');
        $month = now()->format('m');
        $year = now()->format('y');
        $firstInitial = strtoupper(substr($firstName, 0, 1));
        $lastInitial = strtoupper(substr($lastName, -1, 1));
        return "LEC{$day}{$month}{$year}{$firstInitial}{$lastInitial}";
    }

    private function generatePrivateNumber(): string { return Str::random(16); }

    private function generateOtp(): string
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
            // 1. Verifikasi Recaptcha v3 (Action: register_lecturer)
            $this->verifyRecaptcha('register_lecturer');

            $validated = $this->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'phone_number' => ['required', 'string', 'max:15'],
                'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            ]);

            $code = $this->generateLecturerCode($validated['first_name'], $validated['last_name']);
            $privateNumber = $this->generatePrivateNumber();
            $otp = $this->generateOtp();

            $this->user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'],
                'password' => Hash::make($validated['password']),
                'role' => 'lecturer',
                'lecturer_code' => $code,
                'private_number' => $privateNumber,
                'otp_code' => $otp,
                'otp_expires_at' => Carbon::now()->addSeconds(90),
            ]);

            Mail::to($this->email)->send(new LecturerCredentials([
                'lecturer_code' => $code,
                'otp_code' => $otp
            ]));

            session()->flash('lecturer_registered', [
                'email' => $this->email,
                'lecturer_code' => $code
            ]);

            session()->flash('otp-sent', 'Kode Dosen dan OTP telah dikirim ke email Anda.');
            $this->register_step = 2;
        }
        elseif ($this->register_step === 2) {
            $this->validate(['otp_code' => 'required|string|size:9']);

            if (!$this->user) {
                 $this->user = User::where('email', $this->email)->first();
            }

            if (!$this->user || $this->user->otp_code !== $this->otp_code) {
                $this->addError('otp_code', 'Kode OTP salah.');
                return;
            }

            if (Carbon::now()->isAfter($this->user->otp_expires_at)) {
                $this->addError('otp_code', 'Kode OTP kedaluwarsa. Silakan daftar ulang.');
                return;
            }

            $this->user->forceFill(['otp_code' => null, 'otp_expires_at' => null])->save();
            $this->redirect(route('register.lecturer.success'), navigate: true);
        }
    }

    public function resendOtp(): void
    {
        if ($this->register_step === 2) {
            if (!$this->user) {
                $this->user = User::where('email', $this->email)->first();
            }
            if ($this->user) {
                $otp = $this->generateOtp();
                $this->user->forceFill([
                    'otp_code' => $otp,
                    'otp_expires_at' => Carbon::now()->addSeconds(90),
                ])->save();

                Mail::to($this->user->email)->send(new LecturerCredentials([
                    'lecturer_code' => $this->user->lecturer_code,
                    'otp_code' => $otp
                ]));

                session()->flash('otp-sent', 'Kode OTP baru telah dikirimkan.');
            }
        }
    }

}; ?>

<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row w-full max-w-6xl bg-white rounded-2xl shadow-2xl overflow-hidden">

        {{-- FORM SECTION --}}
        <div class="w-full md:w-1/2 p-8 md:p-16 flex flex-col justify-center space-y-6">
            <div class="space-y-2">
                <h2 class="text-4xl font-bold text-gray-900">Lecturer Registration</h2>
                <p class="text-gray-500">
                    Ayo bergabung dengan Ascend LMS dan mulai mengajar.
                </p>
            </div>

            @if ($register_step === 2 && session()->has('otp-sent'))
                <div class="p-3 mb-4 text-sm text-green-700 bg-green-100 rounded-lg border border-green-300 flex items-center gap-2">
                    <x-heroicon-m-check-circle class="w-5 h-5" />
                    {{ session('otp-sent') }}
                </div>
            @endif

            {{-- ALPINE.JS Handler for Lecturer Register --}}
            <form class="space-y-6"
                  wire:ignore.self
                  x-data="{
                      loading: false,
                      submitLecturerReg() {
                          if ({{ $register_step }} !== 1) return;

                          this.loading = true;
                          const siteKey = '{{ config('services.recaptcha.site_key') }}';

                          if (typeof grecaptcha === 'undefined' || !siteKey) {
                               alert('Google reCAPTCHA belum siap.');
                               this.loading = false;
                               return;
                          }

                          grecaptcha.ready(() => {
                              grecaptcha.execute(siteKey, {action: 'register_lecturer'})
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
                  x-on:submit.prevent="submitLecturerReg">

                @if ($register_step === 1)
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                            <input type="text" id="first_name" wire:model="first_name" placeholder="Nama Depan" required autofocus
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150">
                            @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                            <input type="text" id="last_name" wire:model="last_name" placeholder="Nama Belakang" required
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150">
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
                                   class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150">
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
                                   class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150">
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
                                   class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150">
                        </div>
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <x-heroicon-o-check-circle class="h-5 w-5 text-gray-400" />
                            </div>
                            <input type="password" id="password_confirmation" wire:model="password_confirmation" placeholder="Konfirmasi password" required autocomplete="new-password"
                                   class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150">
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
                            class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold text-lg hover:bg-blue-700 transition duration-150 shadow-md shadow-blue-300/50 flex justify-center items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed"
                            x-bind:disabled="loading">

                        <span x-show="!loading" class="flex items-center gap-2">
                            Lanjut & Dapatkan Kode <x-heroicon-m-arrow-right class="w-5 h-5" />
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
                    {{-- STEP 2 UI (OTP) --}}
                    <div class="text-center p-6 border-2 border-dashed border-blue-200 rounded-xl bg-blue-50">
                        <x-heroicon-o-identification class="w-12 h-12 text-blue-500 mx-auto mb-2" />
                        <p class="text-lg font-bold text-gray-800">Verifikasi Kode Dosen</p>
                        <p class="text-sm text-gray-600 mt-1">Kode OTP telah dikirim ke <strong>{{ $email }}</strong>.</p>
                    </div>

                    <div>
                        <label for="otp_code" class="block text-sm font-medium text-gray-700 mb-1">Kode OTP</label>
                        <input wire:model="otp_code" id="otp_code" name="otp_code" type="text" maxlength="9" placeholder="X1Y2Z3..." required
                            class="w-full p-4 border-2 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150 text-2xl text-center tracking-[0.5em] font-mono uppercase">
                        @error('otp_code') <p class="text-red-500 text-xs mt-1 text-center">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-col space-y-3 pt-4">
                        {{-- Button Step 2 (Direct Wire Click) --}}
                        <button type="button" wire:click="register" class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold text-lg hover:bg-blue-700 transition duration-150 shadow-md shadow-blue-300/50">
                            Verifikasi & Selesai
                        </button>
                        <button type="button" wire:click="resendOtp" class="w-full text-blue-600 py-3 rounded-lg font-bold hover:text-blue-800 transition duration-150 bg-white border border-blue-600 hover:bg-blue-50">
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
                    <a href="{{ route('login.lecturer') }}" wire:navigate class="text-blue-600 font-semibold hover:text-blue-700">Login Dosen</a>
                </span>
                <p class="pt-4 text-sm text-gray-500 border-t border-gray-100 mt-4">
                    Daftar sebagai Mahasiswa?
                    <a href="{{ route('register') }}" wire:navigate class="text-orange-600 font-semibold hover:text-orange-700">Daftar Mahasiswa</a>
                </p>
            </div>

        </div>

        {{-- IMAGE SECTION --}}
        <div class="hidden md:flex md:w-1/2 p-8 md:p-12 flex-col justify-between bg-[#307de2]">
            <div class="flex items-center space-x-2">
                <span class="text-xl font-extrabold tracking-tight text-white">Ascend <span class="font-bold text-yellow-300">LMS</span></span>
            </div>
            <div class="flex justify-center items-center">
                <img class="w-full rounded-2xl shadow-lg transform hover:scale-105 transition duration-500" src="{{ asset('images/signup-1.jpg') }}" alt="[Ilustrasi Pendaftaran]">
            </div>
            <div class="space-y-4">
                <h1 class="text-3xl md:text-4xl font-extrabold text-white font-black leading-tight">
                    Inspire the Next Generation.
                </h1>
                <p class="text-white text-md">
                    Daftar sekarang dan mulai berbagi ilmu dengan sistem yang terintegrasi.
                </p>
            </div>
        </div>
    </div>
</div>
