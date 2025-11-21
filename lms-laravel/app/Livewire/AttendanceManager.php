<?php

namespace App\Livewire;

use App\Models\Attendance;
use App\Models\CourseClass;
use App\Models\CourseSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AttendanceManager extends Component
{
    public function render(): View
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->role === 'student') {
            return $this->renderStudentView($user);
        } else {
            return $this->renderLecturerView($user);
        }
    }

    // --- LOGIC VIEW MAHASISWA ---
    private function renderStudentView(User $user): View
    {
        // 1. Ambil semua kelas yang diambil mahasiswa
        $enrolledClasses = $user->enrolledClasses()->with(['course', 'sessions.attendances'])->get();

        // 2. Hitung Statistik Global
        $totalCourses = $enrolledClasses->count();
        $totalSessions = 0;
        $totalAttended = 0;

        foreach ($enrolledClasses as $class) {
            /** @var CourseClass $class */
            $totalSessions += $class->sessions->count();
            // Hitung kehadiran valid (present) di semua sesi
            $totalAttended += $class->sessions->flatMap->attendances
                ->where('user_id', $user->id)
                ->where('status', 'present')
                ->count();
        }

        $totalLateAbsent = $totalSessions - $totalAttended;

        // Rate Calculation
        $attendanceRate = $totalSessions > 0 ? ($totalAttended / $totalSessions) * 100 : 0;
        $attendanceRate = round($attendanceRate, 1);

        // 3. Logic Status Passing Grade
        $rateStatus = [
            'color' => 'black',
            'text' => 'Drop Out / Failed',
            'bg' => 'gray',
        ];

        if ($attendanceRate >= 83) {
            $rateStatus = ['color' => 'green', 'text' => 'You can pass this semester', 'bg' => 'green'];
        } elseif ($attendanceRate >= 70) {
            $rateStatus = ['color' => 'yellow', 'text' => 'Warning: Performance Review', 'bg' => 'yellow'];
        } elseif ($attendanceRate >= 50) {
            $rateStatus = ['color' => 'red', 'text' => 'Must retake semester', 'bg' => 'red'];
        }

        // 4. Detailed Log Data
        $logs = CourseSession::whereIn('course_class_id', $enrolledClasses->pluck('id'))
            ->with(['class.course', 'attendances'])
            ->where('start_time', '<=', Carbon::now())
            ->orderBy('start_time', 'desc')
            ->get()
            ->map(function ($session) use ($user) {
                /** @var CourseSession $session */
                /** @var Attendance|null $attendance */
                $attendance = $session->attendances->where('user_id', $user->id)->first();

                $status = 'Absent';
                $remark = 'Late Attendance';
                $cssClass = 'text-red-600 bg-red-50 border-red-200';

                if ($attendance && $attendance->status === 'present') {
                    $status = 'Present';
                    $remark = 'On Time';
                    $cssClass = 'text-green-600 bg-green-50 border-green-200';
                }

                return (object) [
                    'date' => $session->start_time ? $session->start_time->format('d M Y') : '-',
                    'start_time' => $session->start_time ? $session->start_time->format('H:i') : '-',
                    'end_time' => $session->end_time ? $session->end_time->format('H:i') : '-',
                    'course_code' => $session->class->course->code,
                    'class_code' => $session->class->class_code.' - '.$session->class->type,
                    'status' => $status,
                    'remark' => $remark,
                    'css_class' => $cssClass,
                ];
            });

        return view('livewire.attendance-manager', [
            'role' => 'student',
            'stats' => compact('totalCourses', 'totalSessions', 'totalAttended', 'totalLateAbsent', 'attendanceRate', 'rateStatus'),
            'courses' => $enrolledClasses,
            'logs' => $logs,
        ]);
    }

    // --- LOGIC VIEW DOSEN ---
    private function renderLecturerView(User $user): View
    {
        // 1. Ambil kelas yang diajar
        $teachingClasses = $user->teachingClasses()->with(['course', 'sessions.attendances', 'students'])->get();

        // 2. Hitung Statistik Global
        $totalCourses = $teachingClasses->count();
        $totalSessionsToTeach = 0;

        foreach ($teachingClasses as $class) {
            /** @var CourseClass $class */
            $totalSessionsToTeach += $class->sessions->count();
        }

        // 3. Detailed Log Data
        $logs = CourseSession::whereIn('course_class_id', $teachingClasses->pluck('id'))
            ->with(['class.course'])
            ->where('start_time', '<=', Carbon::now())
            ->orderBy('start_time', 'desc')
            ->get()
            ->map(function ($session) {
                /** @var CourseSession $session */
                $now = Carbon::now();

                $status = '';
                $cssClass = '';

                if ($session->end_time && $now->gt($session->end_time)) {
                    $status = 'Class Finish';
                    $cssClass = 'text-red-600 bg-red-50 border-red-200';
                } elseif ($session->start_time && $session->end_time && $now->gte($session->start_time) && $now->lte($session->end_time)) {
                    $status = 'Class On Going';
                    $cssClass = 'text-yellow-600 bg-yellow-50 border-yellow-200';
                } else {
                    $status = 'Class Start';
                    $cssClass = 'text-green-600 bg-green-50 border-green-200';
                }

                return (object) [
                    'date' => $session->start_time ? $session->start_time->format('d M Y') : '-',
                    'start_time' => $session->start_time ? $session->start_time->format('H:i') : '-',
                    'end_time' => $session->end_time ? $session->end_time->format('H:i') : '-',
                    'course_code' => $session->class->course->code,
                    'class_code' => $session->class->class_code.' - '.$session->class->type,
                    'status' => $status,
                    'css_class' => $cssClass,
                ];
            });

        return view('livewire.attendance-manager', [
            'role' => 'lecturer',
            'stats' => compact('totalCourses', 'totalSessionsToTeach'),
            'courses' => $teachingClasses,
            'logs' => $logs,
        ]);
    }
}
