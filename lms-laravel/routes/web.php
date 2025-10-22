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

// --- UBAH ROUTE untuk controller ---
// Semula: Route::view('courses', 'courses')
Route::get('courses', [CourseController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('courses');
// ---------------------------------

Route::view('forum', 'forum')
    ->middleware(['auth', 'verified'])
    ->name('forum');

Route::view('assessment', 'assessment')
    ->middleware(['auth', 'verified'])
    ->name('assessment');

Route::view('gradebook', 'gradebook')
    ->middleware(['auth', 'verified'])
    ->name('gradebook');

Route::view('attendance', 'attendance')
    ->middleware(['auth', 'verified'])
    ->name('attendance');

Route::view('schedule', 'schedule')
    ->middleware(['auth', 'verified'])
    ->name('schedule');

require __DIR__.'/auth.php';
