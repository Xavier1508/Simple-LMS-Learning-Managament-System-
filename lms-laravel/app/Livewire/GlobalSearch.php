<?php

namespace App\Livewire;

use App\Models\Assignment;
use App\Models\CourseClass;
use App\Models\ForumThread;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class GlobalSearch extends Component
{
    public string $query = '';

    public array $results = [];

    public bool $showAdvancedSearch = false;

    public function updatedQuery(): void
    {
        $this->search();
    }

    public function openAdvancedSearch(): void
    {
        $this->showAdvancedSearch = true;
        if (strlen($this->query) >= 2) {
            $this->search();
        }
    }

    public function closeAdvancedSearch(): void
    {
        $this->showAdvancedSearch = false;
    }

    public function search(): void
    {
        if (strlen($this->query) < 2) {
            $this->results = [];

            return;
        }

        /** @var User $user */
        $user = Auth::user();
        $searchResults = [];

        // 1. SEARCH COURSES
        $courseQuery = ($user->role === 'student')
            ? $user->enrolledClasses()->with('course')
            : $user->teachingClasses()->with('course');

        $courses = $courseQuery->whereHas('course', function ($q) {
            $q->where('title', 'like', '%'.$this->query.'%')
                ->orWhere('code', 'like', '%'.$this->query.'%');
        })
            ->take(3)
            ->get()
            ->map(function ($class) {
                /** @var CourseClass $class */
                return [
                    'type' => 'Course',
                    'title' => $class->course->title,
                    'subtitle' => $class->course->code.' - '.$class->class_code,
                    'url' => route('courses.detail', $class->id),
                    'icon' => 'book',
                ];
            });

        // 2. SEARCH ASSIGNMENTS
        $classIds = $courses->pluck('id')->toArray();
        if (empty($classIds)) {
            $classIds = ($user->role === 'student')
               ? $user->enrolledClasses()->pluck('course_classes.id')
               : $user->teachingClasses()->pluck('id');
        }

        $assignments = Assignment::whereIn('course_class_id', $classIds)
            ->where('title', 'like', '%'.$this->query.'%')
            ->with('class.course')
            ->take(3)
            ->get()
            ->map(function ($task) {
                /** @var Assignment $task */
                return [
                    'type' => 'Assessment',
                    'title' => $task->title,
                    'subtitle' => $task->due_date ? 'Due: '.$task->due_date->format('d M') : 'No Due Date',
                    'url' => route('courses.detail', ['id' => $task->course_class_id, 'tab' => 'assessment']),
                    'icon' => 'clipboard',
                ];
            });

        // 3. SEARCH FORUM THREADS
        $threads = ForumThread::whereHas('session', function ($q) use ($classIds) {
            $q->whereIn('course_class_id', $classIds);
        })
            ->where('title', 'like', '%'.$this->query.'%')
            ->take(3)
            ->get()
            ->map(function ($thread) {
                /** @var ForumThread $thread */
                return [
                    'type' => 'Forum',
                    'title' => $thread->title,
                    'subtitle' => 'By: '.$thread->user->first_name,
                    'url' => route('courses.detail', ['id' => $thread->session->course_class_id, 'tab' => 'forum']),
                    'icon' => 'message-circle',
                ];
            });

        // Gabungkan Hasil
        if ($courses->isNotEmpty()) {
            $searchResults['Courses'] = $courses;
        }
        if ($assignments->isNotEmpty()) {
            $searchResults['Assignments'] = $assignments;
        }
        if ($threads->isNotEmpty()) {
            $searchResults['Forum Discussions'] = $threads;
        }

        $this->results = $searchResults;
    }

    public function render(): View
    {
        return view('livewire.global-search');
    }
}
