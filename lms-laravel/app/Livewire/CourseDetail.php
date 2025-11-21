<?php

namespace App\Livewire;

use App\Livewire\Traits\Course\WithAssessment;
use App\Livewire\Traits\Course\WithAttendance;
use App\Livewire\Traits\Course\WithForum;
use App\Livewire\Traits\Course\WithGradebook;
use App\Livewire\Traits\Course\WithMaterials;
use App\Livewire\Traits\Course\WithPeople;
use App\Models\CourseClass;
use App\Models\CourseSession;
use Carbon\Carbon;
// Import Traits
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Mendefinisikan properti magic untuk PHPStan
 *
 * @property CourseClass $class
 */
#[Layout('layouts.app')]
class CourseDetail extends Component
{
    use WithAssessment;
    use WithAttendance;
    use WithFileUploads;
    use WithForum;
    use WithGradebook;
    use WithMaterials;
    use WithPeople;

    public int $courseClassId;

    public string $activeTab = 'session';

    public ?int $activeSessionId = null;

    public function mount(int $id): void
    {
        $this->courseClassId = $id;

        $reqTab = request()->query('tab');
        if (is_string($reqTab)) {
            if (in_array($reqTab, ['session', 'attendance', 'gradebook', 'forum', 'assessment', 'people'])) {
                $this->activeTab = $reqTab;
            }
        }

        $reqSession = request()->query('session_id');
        $now = Carbon::now();

        /** @var CourseSession|null $closestSession */
        $closestSession = CourseSession::where('course_class_id', $id)
            ->orderByRaw('ABS(TIMESTAMPDIFF(SECOND, start_time, ?))', [$now])
            ->first();

        if ($reqSession && is_numeric($reqSession)) {
            $this->activeSessionId = (int) $reqSession;
            if (! $reqTab) {
                $this->activeTab = 'session';
            }
        } elseif ($closestSession) {
            $this->activeSessionId = $closestSession->id;
        } else {
            /** @var CourseSession|null $first */
            $first = CourseSession::where('course_class_id', $id)->first();
            $this->activeSessionId = $first ? $first->id : null;
        }

        if (method_exists($this, 'ensureGradeComponentsExist')) {
            $this->ensureGradeComponentsExist();
        }
    }

    #[Computed]
    public function class(): CourseClass
    {
        /** @var CourseClass $class */
        $class = CourseClass::with([
            'course',
            'lecturer',
            'sessions.materials',
            'sessions.attendances',
            'students' => function ($query) {
                $query->orderBy('first_name');
            },
        ])->findOrFail($this->courseClassId);

        return $class;
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
        // Kita akses Computed Property via $this->class
        $courseClass = $this->class;

        $summary = [];
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->role === 'student') {
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
                    : 0,
            ];
        } else {
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
                    ? round(($totalPresents / $totalSlots) * 100).'%'
                    : '0%',
            ];
        }

        return view('livewire.course-detail', [
            'class' => $courseClass,
            'attendanceSummary' => $summary,
        ]);
    }
}
