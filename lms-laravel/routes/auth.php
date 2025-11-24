<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// GROUP TAMU (GUEST) - User belum login
Route::middleware('guest')->group(function () {

    // --- Authentication Mahasiswa ---
    Volt::route('register', 'pages.auth.register')->name('register');
    Volt::route('login', 'pages.auth.login')->name('login');
    Volt::route('forgot-password', 'pages.auth.forgot-password')->name('password.request');
    Volt::route('reset-password/{token}', 'pages.auth.reset-password')->name('password.reset');

    // --- Authentication Dosen (Dipindahkan dari web.php) ---
    Volt::route('register/lecturer', 'pages.auth.register-lecturer')->name('register.lecturer');
    Volt::route('register/lecturer/success', 'pages.auth.register-lecturer-success')->name('register.lecturer.success');
    Volt::route('login/lecturer', 'pages.auth.login-lecturer')->name('login.lecturer');
});

// GROUP MEMBER (AUTH) - User sudah login
Route::middleware('auth')->group(function () {
    Volt::route('verify-email', 'pages.auth.verify-email')->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'pages.auth.confirm-password')->name('password.confirm');
});
