<?php

use Illuminate\Support\Facades\Route;

// Landing Page
Route::get('/', function () {
    return view('landing');
})->name('landing');

// GROUP UTAMA: Hanya bisa diakses user yang sudah login & verifikasi email
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('dashboard', \App\Livewire\Dashboard::class)->name('dashboard');
    Route::view('profile', 'profile')->name('profile');

    // Livewire / Volt Managers
    Route::get('courses', \App\Livewire\CourseManager::class)->name('courses');
    Route::get('courses/{id}', \App\Livewire\CourseDetail::class)->name('courses.detail');
    Route::get('gradebook', \App\Livewire\GradebookManager::class)->name('gradebook');
    Route::get('attendance', \App\Livewire\AttendanceManager::class)->name('attendance');

    // Standard Controllers
    Route::get('forum', \App\Livewire\ForumManager::class)->name('forum');
    Route::get('assessment', \App\Livewire\AssessmentManager::class)->name('assessment');
    Route::get('schedule', \App\Livewire\ScheduleManager::class)->name('schedule');
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

Route::get('/fix-sessions', function () {
    $classes = \App\Models\CourseClass::with('sessions')->get();

    foreach ($classes as $class) {
        // Jika kelas belum punya sesi, buatkan!
        if ($class->sessions->count() == 0) {
            $startDate = \Carbon\Carbon::now()->subWeeks(2)->next('Monday')->setTime(10, 0); // Mulai dari 2 minggu lalu biar ada history

            for ($i = 1; $i <= 13; $i++) {
                $isOnsite = $i % 2 != 0;
                \App\Models\CourseSession::create([
                    'course_class_id' => $class->id,
                    'session_number' => $i,
                    'title' => "Session $i: Fundamentals of ".$class->course->title,
                    'learning_outcome' => "Students are expected to master the key elements of {$class->course->title} part $i.",
                    'start_time' => $startDate->copy(),
                    'end_time' => $startDate->copy()->addMinutes(100),
                    'delivery_mode' => $isOnsite ? 'Onsite - Class' : 'Online - GSLC',
                    'zoom_link' => $isOnsite ? null : 'https://zoom.us/j/generated-link',
                ]);
                $startDate->addWeek();
            }
        }
    }

    return 'DONE! All empty classes now have 13 sessions.';
});
