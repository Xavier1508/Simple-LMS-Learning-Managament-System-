<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Assignment;
use App\Models\CourseSession;
use App\Models\GradeComponent;
use App\Models\StudentGrade;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function render()
    {
        $user = Auth::user();
        $today = Carbon::today();
        $now = Carbon::now();

        // --- DATA UMUM ---
        $greeting = $this->getGreeting();
        $announcements = $this->getDummyAnnouncements();

        // --- DATA SPESIFIK ROLE ---
        if ($user->role === 'student') {
            $data = $this->getStudentData($user, $today, $now);
        } else {
            $data = $this->getLecturerData($user, $today);
        }

        return view('livewire.dashboard', array_merge([
            'greeting' => $greeting,
            'announcements' => $announcements,
            'user' => $user
        ], $data));
    }

    private function getStudentData($user, $today, $now)
    {
        $enrolledClasses = $user->enrolledClasses()->with('course')->get();
        $classIds = $enrolledClasses->pluck('id');

        // 1. Calculate GPA
        $totalSKS = 0;
        $totalPoints = 0;
        foreach ($enrolledClasses as $class) {
            $sks = $class->course->credits ?? 3;
            $components = GradeComponent::where('course_id', $class->course_id)->get();
            $score = 0;
            foreach ($components as $comp) {
                $grade = StudentGrade::where('grade_component_id', $comp->id)->where('user_id', $user->id)->first();
                if ($grade) $score += ($grade->score * ($comp->weight / 100));
            }
            $gp = $score >= 85 ? 4.0 : ($score >= 75 ? 3.0 : ($score >= 65 ? 2.0 : 1.0));
            $totalSKS += $sks;
            $totalPoints += ($gp * $sks);
        }
        $gpa = $totalSKS > 0 ? round($totalPoints / $totalSKS, 2) : 0.00;

        // 2. Upcoming Tasks
        $upcomingTasks = Assignment::whereIn('course_class_id', $classIds)
            ->where('due_date', '>', $now)
            ->orderBy('due_date', 'asc')
            ->take(3)
            ->with('class.course')
            ->get();

        // 3. Today's Schedule
        $todaysClasses = CourseSession::whereIn('course_class_id', $classIds)
            ->whereDate('start_time', $today)
            ->orderBy('start_time')
            ->with('class.course', 'class.lecturer')
            ->get();

        return [
            'role' => 'student',
            'gpa' => $gpa,
            'total_sks' => $totalSKS,
            'active_courses' => $enrolledClasses->count(),
            'pending_tasks_count' => Assignment::whereIn('course_class_id', $classIds)->where('due_date', '>', $now)->count(),
            'upcoming_tasks' => $upcomingTasks,
            'todaysClasses' => $todaysClasses // <--- PERBAIKAN: Ganti jadi camelCase
        ];
    }

    private function getLecturerData($user, $today)
    {
        $teachingClasses = $user->teachingClasses()->with('course')->get();
        $classIds = $teachingClasses->pluck('id');

        // 1. Total Students
        $totalStudents = 0;
        foreach($teachingClasses as $class) {
            $totalStudents += $class->students()->count();
        }

        // 2. Classes Today
        $todaysClasses = CourseSession::whereIn('course_class_id', $classIds)
            ->whereDate('start_time', $today)
            ->orderBy('start_time')
            ->with('class.course')
            ->get();

        // 3. Recent Submissions
        $tasksToGrade = Assignment::whereIn('course_class_id', $classIds)
            ->orderByDesc('created_at')
            ->take(3)
            ->with('class.course')
            ->get();

        return [
            'role' => 'lecturer',
            'total_students' => $totalStudents,
            'active_classes' => $teachingClasses->count(),
            'todaysClasses' => $todaysClasses, // <--- PERBAIKAN: Ganti jadi camelCase
            'tasks_to_grade' => $tasksToGrade
        ];
    }

    private function getGreeting()
    {
        $hour = date('H');
        if ($hour < 12) return 'Good Morning';
        if ($hour < 18) return 'Good Afternoon';
        return 'Good Evening';
    }

    private function getDummyAnnouncements()
    {
        return [
            [
                'title' => 'System Maintenance Schedule',
                'date' => '25 Nov 2025',
                'color' => 'bg-red-100 text-red-600',
                'icon' => 'server'
            ],
            [
                'title' => 'Midterm Exam Guidelines',
                'date' => '28 Nov 2025',
                'color' => 'bg-blue-100 text-blue-600',
                'icon' => 'file-text'
            ],
            [
                'title' => 'Holiday Announcement',
                'date' => '01 Dec 2025',
                'color' => 'bg-green-100 text-green-600',
                'icon' => 'calendar'
            ]
        ];
    }
}
