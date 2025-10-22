<?php

use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\GradebookController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('profile', 'profile')->name('profile');
    Route::get('courses', [CourseController::class, 'index'])->name('courses');
    Route::get('gradebook', [GradebookController::class, 'index'])->name('gradebook');
    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance');
    Route::get('forum', [ForumController::class, 'index'])->name('forum');
    Route::get('assessment', [AssessmentController::class, 'index'])->name('assessment');
    Route::get('schedule', [ScheduleController::class, 'index'])->name('schedule');

    // Rute 'announcement' sudah dihapus sesuai permintaan

});

// File Route untuk Autentikasi (Login, Register, dll.)
require __DIR__.'/auth.php';
