<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\CourseClass;
use App\Models\Assignment;
use App\Models\ForumThread;

class GlobalSearch extends Component
{
    public $query = '';
    public $results = [];

    // Reset hasil jika query kosong
    public function updatedQuery()
    {
        $this->search();
    }

    public function search()
    {
        if (strlen($this->query) < 2) {
            $this->results = [];
            return;
        }

        $user = Auth::user();
        $searchResults = [];

        // 1. SEARCH COURSES
        // Logika: Cari di kelas yang diambil (Siswa) atau diajar (Dosen)
        $courseQuery = ($user->role === 'student')
            ? $user->enrolledClasses()->with('course')
            : $user->teachingClasses()->with('course');

        $courses = $courseQuery->whereHas('course', function ($q) {
                $q->where('title', 'like', '%' . $this->query . '%')
                  ->orWhere('code', 'like', '%' . $this->query . '%');
            })
            ->take(3)
            ->get()
            ->map(function ($class) {
                return [
                    'type' => 'Course',
                    'title' => $class->course->title,
                    'subtitle' => $class->course->code . ' - ' . $class->class_code,
                    'url' => route('courses.detail', $class->id),
                    'icon' => 'book'
                ];
            });

        // 2. SEARCH ASSIGNMENTS
        // Cari tugas yang relevan dengan user
        $classIds = $courses->pluck('id')->toArray(); // Optimasi sederhana: cari di kelas yg ketemu dulu, atau ambil semua kelas user
        if(empty($classIds)) {
             $classIds = ($user->role === 'student')
                ? $user->enrolledClasses()->pluck('course_classes.id')
                : $user->teachingClasses()->pluck('id');
        }

        $assignments = Assignment::whereIn('course_class_id', $classIds)
            ->where('title', 'like', '%' . $this->query . '%')
            ->with('class.course')
            ->take(3)
            ->get()
            ->map(function ($task) {
                return [
                    'type' => 'Assessment',
                    'title' => $task->title,
                    'subtitle' => 'Due: ' . $task->due_date->format('d M'),
                    'url' => route('courses.detail', ['id' => $task->course_class_id, 'tab' => 'assessment']),
                    'icon' => 'clipboard'
                ];
            });

        // 3. SEARCH FORUM THREADS
        $threads = ForumThread::whereHas('session', function($q) use ($classIds){
                $q->whereIn('course_class_id', $classIds);
            })
            ->where('title', 'like', '%' . $this->query . '%')
            ->take(3)
            ->get()
            ->map(function ($thread) {
                return [
                    'type' => 'Forum',
                    'title' => $thread->title,
                    'subtitle' => 'By: ' . $thread->user->first_name,
                    'url' => route('courses.detail', ['id' => $thread->session->course_class_id, 'tab' => 'forum']), // Bisa ditambah anchor ke thread id nanti
                    'icon' => 'message-circle'
                ];
            });

        // Gabungkan Hasil
        if ($courses->isNotEmpty()) $searchResults['Courses'] = $courses;
        if ($assignments->isNotEmpty()) $searchResults['Assignments'] = $assignments;
        if ($threads->isNotEmpty()) $searchResults['Forum Discussions'] = $threads;

        $this->results = $searchResults;
    }

    public function render()
    {
        return view('livewire.global-search');
    }
}
