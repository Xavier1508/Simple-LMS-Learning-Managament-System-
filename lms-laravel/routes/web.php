<?php

use App\Livewire\AttendanceManager;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\GradebookController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('profile', 'profile')->name('profile');

    // INI SUDAH BENAR (Menggunakan Livewire Component)
    // Pastikan CourseManager.php ada di app/Livewire/CourseManager.php
    Route::get('courses', \App\Livewire\CourseManager::class)->name('courses');
    Route::get('courses/{id}', \App\Livewire\CourseDetail::class)->name('courses.detail');

    // Controller lain biarkan saja dulu jika belum diubah ke Livewire
    Route::get('gradebook', [GradebookController::class, 'index'])->name('gradebook');
    Route::get('attendance', \App\Livewire\AttendanceManager::class)->name('attendance');
    Route::get('forum', [ForumController::class, 'index'])->name('forum');
    Route::get('assessment', [AssessmentController::class, 'index'])->name('assessment');
    Route::get('schedule', [ScheduleController::class, 'index'])->name('schedule');

});

require __DIR__.'/auth.php';

Route::middleware('guest')->group(function () {
    // Registrasi Dosen
    Volt::route('register/lecturer', 'pages.auth.register-lecturer')->name('register.lecturer');

    // Halaman Sukses Pendaftaran Dosen BARU
    Volt::route('register/lecturer/success', 'pages.auth.register-lecturer-success')->name('register.lecturer.success');

    // Login Dosen
    Volt::route('login/lecturer', 'pages.auth.login-lecturer')->name('login.lecturer');
});
