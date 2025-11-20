<?php

namespace App\Livewire;

use App\Models\Attendance;
use App\Models\CourseClass;
use App\Models\CourseSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Livewire\Traits\Course\WithForum;

// Import Traits yang baru dibuat
use App\Livewire\Traits\Course\WithAttendance;
use App\Livewire\Traits\Course\WithMaterials;

#[Layout('layouts.app')]
class CourseDetail extends Component
{
    use WithFileUploads;

    // Gunakan Traits disini
    use WithAttendance;
    use WithMaterials;
    use WithForum;

    public $courseClassId;
    public $activeTab = 'session';
    public $activeSessionId = null;

    public function mount($id)
    {
        $this->courseClassId = $id;

        // UPDATE: Tangkap query parameter 'tab' dari URL jika ada
        if (request()->query('tab') === 'attendance') {
            $this->activeTab = 'attendance';
        }

        // Otomatis buka sesi yang paling relevan
        $now = Carbon::now();
        $closestSession = CourseSession::where('course_class_id', $id)
            ->orderByRaw("ABS(TIMESTAMPDIFF(SECOND, start_time, ?))", [$now])
            ->first();

        if ($closestSession) {
            $this->activeSessionId = $closestSession->id;
        } else {
            $first = CourseSession::where('course_class_id', $id)->first();
            $this->activeSessionId = $first ? $first->id : null;
        }
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function toggleSession($sessionId)
    {
        $this->activeSessionId = ($this->activeSessionId === $sessionId) ? null : $sessionId;
    }

    public function render()
    {
        $courseClass = CourseClass::with([
            'course',
            'lecturer',
            'sessions.materials',
            'sessions.attendances',
            'students' => function($query) {
                $query->orderBy('first_name'); // Urutkan nama siswa A-Z
            }
        ])->findOrFail($this->courseClassId);

        // --- LOGIKA SUMMARY STATISTIK ---
        $summary = [];

        if (Auth::user()->role === 'student') {
            // Statistik Siswa Login
            $myAttendances = Attendance::where('user_id', Auth::id())
                ->whereIn('course_session_id', $courseClass->sessions->pluck('id'))
                ->where('status', 'present')
                ->count();

            $summary = [
                'total_sessions' => $courseClass->sessions->count(),
                'total_attended' => $myAttendances,
                'min_attendance' => 11, // Hardcoded sesuai request
                'percentage' => $courseClass->sessions->count() > 0
                    ? round(($myAttendances / $courseClass->sessions->count()) * 100)
                    : 0
            ];
        } else {
            // Statistik Global Kelas (Untuk Dosen)
            $totalSlots = $courseClass->students->count() * $courseClass->sessions->count();
            $totalPresents = Attendance::whereIn('course_session_id', $courseClass->sessions->pluck('id'))
                ->where('status', 'present')
                ->count();

            $summary = [
                'total_students' => $courseClass->students->count(),
                'total_sessions' => $courseClass->sessions->count(),
                'total_presents' => $totalPresents,
                'average_attendance' => $totalSlots > 0
                    ? round(($totalPresents / $totalSlots) * 100) . '%'
                    : '0%'
            ];
        }

        return view('livewire.course-detail', [
            'class' => $courseClass,
            'attendanceSummary' => $summary
        ]);
    }
}
