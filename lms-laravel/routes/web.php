<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\ScheduleController;

// Landing Page
Route::get('/', function () {
    return view('landing');
})->name('landing');

// GROUP UTAMA: Hanya bisa diakses user yang sudah login & verifikasi email
Route::middleware(['auth', 'verified'])->group(function () {

    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('profile', 'profile')->name('profile');

    // Livewire / Volt Managers
    Route::get('courses', \App\Livewire\CourseManager::class)->name('courses');
    Route::get('courses/{id}', \App\Livewire\CourseDetail::class)->name('courses.detail');
    Route::get('gradebook', \App\Livewire\GradebookManager::class)->name('gradebook');
    Route::get('attendance', \App\Livewire\AttendanceManager::class)->name('attendance');

    // Standard Controllers
    Route::get('forum', [ForumController::class, 'index'])->name('forum');
    Route::get('assessment', [AssessmentController::class, 'index'])->name('assessment');
    Route::get('schedule', [ScheduleController::class, 'index'])->name('schedule');
});

// Include Auth Routes
require __DIR__.'/auth.php';

// GROUP TAMU: Khusus user yang BELUM login
Route::middleware('guest')->group(function () {
    // Registrasi & Login Dosen
    \Livewire\Volt\Volt::route('register/lecturer', 'pages.auth.register-lecturer')->name('register.lecturer');
    \Livewire\Volt\Volt::route('register/lecturer/success', 'pages.auth.register-lecturer-success')->name('register.lecturer.success');
    \Livewire\Volt\Volt::route('login/lecturer', 'pages.auth.login-lecturer')->name('login.lecturer');
});
