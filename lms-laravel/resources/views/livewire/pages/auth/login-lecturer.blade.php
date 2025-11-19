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

new #[Layout('layouts.guest')] class extends Component
{
    public string $lecturer_code = '';
    public string $password = '';
    public string $private_number = '';
    public bool $remember = false;

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return strtolower($this->lecturer_code).'|'.request()->ip();
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'lecturer_code' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function login(): void
    {
        $this->ensureIsNotRateLimited(); // 1. Cek Rate Limit

        $this->validate([
            'lecturer_code' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string'],
            'private_number' => ['required', 'string', 'max:16'],
        ]);

        // 2. Cari user
        $user = User::where('lecturer_code', $this->lecturer_code)
                    ->where('role', 'lecturer') // Memastikan hanya Dosen
                    ->first();

        // 3. Cek kombinasi user dan password
        if (!$user || !Hash::check($this->password, $user->password)) {
             RateLimiter::hit($this->throttleKey()); // Tambahkan hit pada Rate Limit
             $this->addError('lecturer_code', trans('auth.failed')); // Pesan error umum
             return;
        }

        // 4. Cek Private Number
        if (empty($user->private_number)) {
            $this->addError('private_number', 'Private Number belum diset oleh admin. Silakan tunggu email konfirmasi.');
            RateLimiter::hit($this->throttleKey()); // Tambahkan hit pada Rate Limit
            return;
        }

        if ($user->private_number !== $this->private_number) {
            $this->addError('private_number', 'Private Number yang Anda masukkan salah.');
            RateLimiter::hit($this->throttleKey()); // Tambahkan hit pada Rate Limit
            return;
        }


        // 5. Sukses: Bersihkan Rate Limit, Login, dan Redirect
        RateLimiter::clear($this->throttleKey());

        Auth::login($user, $this->remember);

        Session::regenerate();

        // Redirect Dosen ke dashboard
        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row w-full max-w-6xl bg-white rounded-2xl shadow-2xl overflow-hidden">
        <div class="w-full md:w-1/2 p-8 md:p-16 flex flex-col justify-center space-y-8">
            <div class="space-y-2">
                <h2 class="text-4xl font-bold text-gray-900">Lecturer Login</h2>
                <p class="text-gray-500">
                    Selamat datang kembali! Silakan login untuk melanjutkan.
                </p>
            </div>

            <form wire:submit="login" class="space-y-6">

                <!-- Lecturer Code -->
                <div>
                    <label for="lecturer_code" class="block text-sm font-medium text-gray-700">Kode Dosen</label>
                    <input type="text" id="lecturer_code" wire:model="lecturer_code" placeholder="Contoh: LEC191125JD" required autofocus
                           class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150 uppercase">
                    @error('lecturer_code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="relative mt-1">
                        <input type="password" id="password" wire:model="password" placeholder="Masukkan password" required autocomplete="current-password"
                               class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150">
                    </div>
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Private Number -->
                <div>
                    <div class="flex justify-between items-center">
                        <label for="private_number" class="block text-sm font-medium text-gray-700">Private Number</label>
                        <span class="text-xs font-medium text-red-600">Dari Admin</span>
                    </div>
                    <div class="relative mt-1">
                        <input type="password" id="private_number" wire:model="private_number" placeholder="16 karakter rahasia" required
                               class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150 font-mono">
                    </div>
                    @error('private_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="block">
                    <label for="remember" class="inline-flex items-center">
                        <input wire:model="remember" id="remember" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                        <span class="ms-2 text-sm text-gray-600">Ingat Saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold text-lg hover:bg-blue-700 transition duration-150 shadow-md shadow-blue-300/50">
                    Continue now!
                </button>
            </form>

            <div class="text-center pt-4">
                <span class="text-sm text-gray-500">
                    Don't have an account?
                    <a href="{{ route('register.lecturer') }}" wire:navigate class="text-red-500 font-semibold hover:text-red-600">Sign up</a>
                </span>

                <p class="pt-2 text-sm text-gray-500">
                    Atau Login sebagai Mahasiswa?
                    <a href="{{ route('login') }}" wire:navigate class="text-blue-500 font-semibold hover:text-blue-600">Login Mahasiswa</a>
                </p>
            </div>

        </div>

        <div class="hidden md:flex md:w-1/2 p-8 md:p-12 flex-col justify-between bg-[#307de2]">
            <div class="flex items-center space-x-2">
                <span class="text-xl font-extrabold tracking-tight text-white">Ascend <span class="font-bold text-yellow-300">LMS</span></span>
            </div>
            <div class="flex justify-center items-center">
                <img class="w-full rounded-2xl" src="{{ asset('images/login-1.jpg') }}" alt="[Ilustrasi Login]">
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
