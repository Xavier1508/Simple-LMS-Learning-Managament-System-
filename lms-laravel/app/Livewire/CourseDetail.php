<?php

namespace App\Livewire;

use App\Livewire\Traits\Course\WithAssessment;
use App\Livewire\Traits\Course\WithAttendance;
use App\Livewire\Traits\Course\WithForum;
use App\Livewire\Traits\Course\WithGradebook;
use App\Livewire\Traits\Course\WithMaterials;
use App\Livewire\Traits\Course\WithPeople;
use App\Models\Assignment;
use App\Models\CourseClass;
use App\Models\CourseSession;
use App\Models\ForumThread;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

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
        $reqSession = request()->query('session_id');
        $reqThread = request()->query('open_thread');
        $reqAssessment = request()->query('open_assessment'); // <--- Tangkap Assessment ID

        // 2. Set Active Tab
        if (is_string($reqTab)) {
            if (in_array($reqTab, ['session', 'attendance', 'gradebook', 'forum', 'assessment', 'people'])) {
                $this->activeTab = $reqTab;
            }
        }

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

        // [DIRECT LINK: FORUM]
        if ($reqThread && is_numeric($reqThread)) {
            if (method_exists($this, 'openThread')) {
                $thread = ForumThread::find($reqThread);
                if ($thread && $thread->session->course_class_id == $this->courseClassId) {
                    $this->activeSessionId = $thread->course_session_id;
                    $this->openThread((int) $reqThread);
                }
            }
        }

        // [DIRECT LINK: ASSESSMENT]
        if ($reqAssessment && is_numeric($reqAssessment)) {
            if (method_exists($this, 'openAssessmentDetail')) {
                $assign = Assignment::find($reqAssessment);
                if ($assign && $assign->course_class_id == $this->courseClassId) {
                    $this->activeTab = 'assessment'; // Paksa pindah tab
                    $this->openAssessmentDetail((int) $reqAssessment);
                }
            }
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

    public function setActiveSession(int $sessionId): void
    {
        if ($this->activeSessionId === $sessionId) {
            return;
        }
        $this->activeSessionId = $sessionId;
    }

    public function render(): View
    {
        $courseClass = $this->class;
        $summary = [];
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
