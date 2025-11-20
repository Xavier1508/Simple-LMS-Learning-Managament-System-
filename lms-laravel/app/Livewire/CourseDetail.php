<?php

namespace App\Livewire;

use App\Models\CourseClass;
use App\Models\CourseSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

// Import Traits (Fitur Modular)
use App\Livewire\Traits\Course\WithAttendance;
use App\Livewire\Traits\Course\WithMaterials;
use App\Livewire\Traits\Course\WithForum;
use App\Livewire\Traits\Course\WithAssessment;
use App\Livewire\Traits\Course\WithGradebook;

#[Layout('layouts.app')]
class CourseDetail extends Component
{
    use WithFileUploads;

    // Gunakan Semua Trait
    use WithAttendance;
    use WithMaterials;
    use WithForum;
    use WithAssessment;
    use WithGradebook;

    public $courseClassId;
    public $activeTab = 'session'; // Default tab
    public $activeSessionId = null;

    public function mount($id)
    {
        $this->courseClassId = $id;

        // --- LOGIC REDIRECT DARI SIDEBAR ---
        // Menangkap parameter '?tab=' dari URL
        $reqTab = request()->query('tab');

        if ($reqTab === 'attendance') {
            $this->activeTab = 'attendance';
        } elseif ($reqTab === 'gradebook') {
            $this->activeTab = 'gradebook'; // <-- Fix: Menangani redirect gradebook
        } elseif ($reqTab === 'forum') {
            $this->activeTab = 'forum';
        } elseif ($reqTab === 'assessment') {
            $this->activeTab = 'assessment';
        }

        // --- LOGIC AUTO-OPEN SESSION ---
        // Membuka sesi yang sedang berlangsung atau yang paling dekat waktunya
        $now = Carbon::now();
        $closestSession = CourseSession::where('course_class_id', $id)
            ->orderByRaw("ABS(TIMESTAMPDIFF(SECOND, start_time, ?))", [$now])
            ->first();

        if ($closestSession) {
            $this->activeSessionId = $closestSession->id;
        } else {
            // Fallback ke sesi pertama jika tidak ada yang dekat
            $first = CourseSession::where('course_class_id', $id)->first();
            $this->activeSessionId = $first ? $first->id : null;
        }

        $this->ensureGradeComponentsExist();
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
        // Load Data Kelas beserta relasinya
        $courseClass = CourseClass::with([
            'course',
            'lecturer',
            'sessions.materials',
            'sessions.attendances',
            'students' => function($query) {
                $query->orderBy('first_name'); // Urutkan siswa A-Z
            }
        ])->findOrFail($this->courseClassId);

        // --- LOGIC SUMMARY ATTENDANCE (Untuk Tab Attendance) ---
        $summary = [];
        if (Auth::user()->role === 'student') {
            // Hitung kehadiran siswa login
            $myAttendances = \App\Models\Attendance::where('user_id', Auth::id())
                ->whereIn('course_session_id', $courseClass->sessions->pluck('id'))
                ->where('status', 'present')
                ->count();

            $summary = [
                'total_sessions' => $courseClass->sessions->count(),
                'total_attended' => $myAttendances,
                'min_attendance' => 11,
                'percentage' => $courseClass->sessions->count() > 0
                    ? round(($myAttendances / $courseClass->sessions->count()) * 100)
                    : 0
            ];
        } else {
            // Hitung rata-rata kehadiran seluruh kelas (Lecturer)
            $totalSlots = $courseClass->students->count() * $courseClass->sessions->count();
            $totalPresents = \App\Models\Attendance::whereIn('course_session_id', $courseClass->sessions->pluck('id'))
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
