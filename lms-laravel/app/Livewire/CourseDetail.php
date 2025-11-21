<?php

namespace App\Livewire;

use App\Models\CourseClass;
use App\Models\CourseSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

// Import Traits (Fitur Modular)
use App\Livewire\Traits\Course\WithAttendance;
use App\Livewire\Traits\Course\WithMaterials;
use App\Livewire\Traits\Course\WithForum;
use App\Livewire\Traits\Course\WithAssessment;
use App\Livewire\Traits\Course\WithGradebook;
use App\Livewire\Traits\Course\WithPeople;

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
    use WithPeople;

    public int $courseClassId;
    public string $activeTab = 'session'; // Default tab
    public ?int $activeSessionId = null;

    public function mount(int $id): void
    {
        $this->courseClassId = $id;
        // Menangkap parameter '?tab=' dari URL
        $reqTab = request()->query('tab');

        if (is_string($reqTab)) {
            // Validasi agar hanya tab yang valid yang diproses
            if (in_array($reqTab, ['session', 'attendance', 'gradebook', 'forum', 'assessment', 'people'])) {
                $this->activeTab = $reqTab;
            }
        }

        // --- 2. LOGIC SELECT SESSION (SCHEDULE) ---
        // Menangkap parameter '?session_id=' dari URL
        $reqSession = request()->query('session_id');

        // Cari session terdekat berdasarkan waktu sekarang (Default behavior)
        $now = Carbon::now();
        /** @var CourseSession|null $closestSession */
        $closestSession = CourseSession::where('course_class_id', $id)
            ->orderByRaw("ABS(TIMESTAMPDIFF(SECOND, start_time, ?))", [$now])
            ->first();

        // PENENTUAN SESSION ID (PRIORITY LEVEL)
        if ($reqSession && is_numeric($reqSession)) {
            // PRIORITAS 1: Jika ada request spesifik dari Jadwal/Kalender
            $this->activeSessionId = (int) $reqSession;

            // Opsional: Jika tab tidak diset spesifik, paksa ke tab session
            if (!$reqTab) {
                $this->activeTab = 'session';
            }
        } elseif ($closestSession) {
            // PRIORITAS 2: Session yang sedang berlangsung / terdekat waktunya
            $this->activeSessionId = $closestSession->id;
        } else {
            // PRIORITAS 3: Fallback ke session pertama jika belum ada jadwal
            /** @var CourseSession|null $first */
            $first = CourseSession::where('course_class_id', $id)->first();
            $this->activeSessionId = $first ? $first->id : null;
        }

        // Pastikan method ini ada di trait WithGradebook
        if (method_exists($this, 'ensureGradeComponentsExist')) {
            $this->ensureGradeComponentsExist();
        }
    }

    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function toggleSession(int $sessionId): void
    {
        $this->activeSessionId = ($this->activeSessionId === $sessionId) ? null : $sessionId;
    }

    public function render(): View
    {
        /** @var CourseClass $courseClass */
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
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->role === 'student') {
            // Hitung kehadiran siswa login
            $myAttendances = \App\Models\Attendance::where('user_id', $user->id)
                ->whereIn('course_session_id', $courseClass->sessions->pluck('id'))
                ->where('status', 'present')
                ->count();

            $totalSessions = $courseClass->sessions->count();

            $summary = [
                'total_sessions' => $totalSessions,
                'total_attended' => $myAttendances,
                'min_attendance' => 11,
                'percentage' => $totalSessions > 0
                    ? round(($myAttendances / $totalSessions) * 100)
                    : 0
            ];
        } else {
            // Hitung rata-rata kehadiran seluruh kelas (Lecturer)
            $totalStudents = $courseClass->students->count();
            $totalSessions = $courseClass->sessions->count();

            $totalSlots = $totalStudents * $totalSessions;

            $totalPresents = \App\Models\Attendance::whereIn('course_session_id', $courseClass->sessions->pluck('id'))
                ->where('status', 'present')
                ->count();

            $summary = [
                'total_students' => $totalStudents,
                'total_sessions' => $totalSessions,
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
