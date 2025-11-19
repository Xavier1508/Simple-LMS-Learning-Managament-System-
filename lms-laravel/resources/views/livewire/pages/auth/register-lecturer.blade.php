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

new #[Layout('layouts.guest')] class extends Component
{
    // Properti input
    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $phone_number = '';
    public string $password = '';
    public string $password_confirmation = '';

    // Properti OTP
    public int $register_step = 1; // <--- Variabel ini sekarang pasti terinisialisasi
    public string $otp_code = '';
    public ?User $user = null;

    private function generatePrivateNumber(): string
    {
        return Str::random(16);
    }

    private function generateLecturerCode(string $firstName, string $lastName): string
    {
        $day = now()->format('d');
        $month = now()->format('m');
        $year = now()->format('y');
        $firstInitial = strtoupper(substr($firstName, 0, 1));
        $lastInitial = strtoupper(substr($lastName, -1, 1));
        return "LEC{$day}{$month}{$year}{$firstInitial}{$lastInitial}";
    }

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
        // STEP 1: Data, Generate Credentials, Kirim OTP Sekaligus
        if ($this->register_step === 1) {
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
        // STEP 2: Verifikasi OTP
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

            if (!$this->user) {
                $this->addError('otp_code', 'User tidak ditemukan. Silakan daftar ulang.');
                return;
            }

            $otp = $this->generateOtp();

            $this->user->forceFill([
                'otp_code' => $otp,
                'otp_expires_at' => Carbon::now()->addSeconds(90),
            ])->save();

            Mail::to($this->user->email)->send(new LecturerCredentials([
                'lecturer_code' => $this->user->lecturer_code,
                'otp_code' => $otp
            ]));

            session()->flash('otp-sent', 'Kode OTP baru telah dikirimkan ke email Anda.');
        }
    }

}; ?>

<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row w-full max-w-6xl bg-white rounded-2xl shadow-2xl overflow-hidden">
        <div class="w-full md:w-1/2 p-8 md:p-16 flex flex-col justify-center space-y-8">
            <div class="space-y-2">
                <h2 class="text-4xl font-bold text-gray-900">Lecturer Registration</h2>
                <p class="text-gray-500">
                    Ayo bergabung dengan Ascend LMS dan mulai mengajar.
                </p>
            </div>

            @if ($register_step === 2 && session()->has('otp-sent'))
                <div class="p-3 mb-4 text-sm text-green-700 bg-green-100 rounded-lg border border-green-300" role="alert">
                    {{ session('otp-sent') }}
                </div>
            @endif

            <form wire:submit="register" class="space-y-6">

                @if ($register_step === 1)
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" id="first_name" wire:model="first_name" placeholder="Masukkan nama depan" required autofocus
                               class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150">
                        @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" id="last_name" wire:model="last_name" placeholder="Masukkan nama belakang" required
                               class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150">
                        @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" id="email" wire:model="email" placeholder="Masukkan email aktif" required
                               class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="text" id="phone_number" wire:model="phone_number" placeholder="Masukkan nomor telepon" required
                               class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150">
                        @error('phone_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" id="password" wire:model="password" placeholder="Buat password" required autocomplete="new-password"
                               class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150">
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input type="password" id="password_confirmation" wire:model="password_confirmation" placeholder="Konfirmasi password" required autocomplete="new-password"
                               class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150">
                        @error('password_confirmation') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold text-lg hover:bg-blue-700 transition duration-150 shadow-md shadow-blue-300/50">
                        Register & Dapatkan Kode
                    </button>

                @elseif ($register_step === 2)
                    <div class="text-center p-4 border rounded-lg bg-gray-50">
                        <p class="text-lg font-bold text-gray-800">Verifikasi Kode Dosen</p>
                        <p class="text-sm text-gray-600 mt-1">Kode OTP (9 karakter) telah dikirimkan ke email Anda bersama Kode Dosen.</p>
                    </div>

                    <div>
                        <label for="otp_code" class="block text-sm font-medium text-gray-700">Kode OTP (9 Karakter)</label>
                        <div class="mt-1">
                            <input wire:model="otp_code" id="otp_code" name="otp_code" type="text" maxlength="9" required
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150 text-lg text-center tracking-widest">
                            @error('otp_code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex flex-col space-y-3 pt-4">
                        <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold text-lg hover:bg-blue-700 transition duration-150 shadow-md shadow-blue-300/50">
                            Verifikasi & Selesai
                        </button>
                        <button type="button" wire:click="resendOtp" class="w-full text-blue-600 py-3 rounded-lg font-bold hover:text-blue-800 transition duration-150 bg-white border border-blue-600">
                            Kirim Ulang OTP
                        </button>
                    </div>
                @endif
            </form>

            <div class="text-center pt-4">
                <span class="text-sm text-gray-500">
                    Already have an account?
                    <a href="{{ route('login.lecturer') }}" wire:navigate class="text-red-500 font-semibold hover:text-red-600">Login Dosen</a>
                </span>

                <p class="pt-2 text-sm text-gray-500">
                    Atau Daftar sebagai Mahasiswa?
                    <a href="{{ route('register') }}" wire:navigate class="text-blue-500 font-semibold hover:text-blue-600">Daftar Mahasiswa</a>
                </p>
            </div>

        </div>

        <div class="hidden md:flex md:w-1/2 p-8 md:p-12 flex-col justify-between bg-[#307de2]">
            <div class="flex items-center space-x-2">
                <span class="text-xl font-extrabold tracking-tight text-white">Ascend <span class="font-bold text-yellow-300">LMS</span></span>
            </div>
            <div class="flex justify-center items-center">
                <img class="w-full rounded-2xl" src="{{ asset('images/signup-1.jpg') }}" alt="[Ilustrasi Pendaftaran]">
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
