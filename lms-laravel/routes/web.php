<?php

use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Schedule page
Route::view('schedule', 'schedule')
    ->middleware(['auth', 'verified'])
    ->name('schedule');

// --- UBAH ROUTE untuk controller ---
// Semula: Route::view('courses', 'courses')
Route::get('courses', [CourseController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('courses');
// ---------------------------------

require __DIR__.'/auth.php';
