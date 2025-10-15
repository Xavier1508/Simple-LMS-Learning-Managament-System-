<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);
        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'student';

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col md:flex-row w-full max-w-6xl bg-white rounded-2xl shadow-2xl overflow-hidden">
    <div class="w-full md:w-1/2 p-8 md:p-16 flex flex-col justify-start space-y-5">
        <div class="space-y-2">
            <h2 class="text-4xl font-bold text-gray-900">Great to have you here!</h2>
            <p class="text-gray-500">
                Let's start turning your ambitions into action!
            </p>
        </div>

        <form wire:submit="register" class="space-y-3">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" id="name" wire:model="name" placeholder="Enter your username" required autofocus autocomplete="name"
                       class="mt-1 w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150">
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                <input type="email" id="email" wire:model="email" placeholder="Example@gmail.com" required autocomplete="username"
                       class="mt-1 w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="relative mt-1">
                    <input type="password" id="password" wire:model="password" placeholder="Enter password" required autocomplete="new-password"
                           class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150">
                </div>
                 <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="relative mt-1">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 sr-only">Confirm Password</label>
                <input type="password" id="password_confirmation" wire:model="password_confirmation" placeholder="Confirm password" required autocomplete="new-password"
                       class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-start pt-2">
                <input id="terms" name="terms" type="checkbox" required
                    class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-1 cursor-pointer">
                <label for="terms" class="ml-3 block text-sm text-gray-700">
                    I agree to the
                    <a href="#" class="font-medium text-blue-600 hover:text-blue-800">Terms & Conditions</a>
                    and
                    <a href="#" class="font-medium text-blue-600 hover:text-blue-800">Privacy Policy</a>.
                </label>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold text-lg hover:bg-blue-700 transition duration-150 shadow-md shadow-blue-300/50">
                Start now!
            </button>
        </form>

        <div class="text-center pt-4">
            <span class="text-sm text-gray-500">
                Already have an account?
                <a href="{{ route('login') }}" wire:navigate class="text-red-500 font-semibold hover:text-red-600">Login</a>
            </span>
        </div>

    </div>

    <div class="hidden md:flex md:w-1/2 p-8 md:p-12 flex-col justify-between bg-[#FAA22F]">
        <div class="flex items-center space-x-2">
            <span class="text-xl font-extrabold tracking-tight">Ascend <span class="font-bold text-yellow-300">LMS</span></span>
        </div>
        <div class="flex justify-center items-center">
            <img class="w-full rounded-2xl" src="{{ asset('images/signup-1.jpg') }}" alt="Signup Illustration">
        </div>
        <div class="space-y-4">
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray font-black leading-tight">
                The Grind Starts Here.
            </h1>
            <p class="text-gray font-semibold text-lg">
                Don't Wish for Success. Lock In and Work for It.
            </p>
        </div>
    </div>
</div>
