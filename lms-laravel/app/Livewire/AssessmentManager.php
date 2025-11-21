<?php

namespace App\Livewire;

use App\Models\Assignment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AssessmentManager extends Component
{
    public string $filter = 'upcoming'; // 'upcoming', 'history'

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
    }

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

    private function renderStudentView(User $user): View
    {
        // 1. Ambil ID semua kelas yang diambil siswa
        $classIds = $user->enrolledClasses()->pluck('course_classes.id');

        // 2. Query Assignment
        $query = Assignment::whereIn('course_class_id', $classIds)
            ->with(['class.course', 'submissions' => function ($q) use ($user) {
                $q->where('user_id', $user->id); // Eager load submission user ini saja
            }]);

        // 3. Filter Logic
        $now = Carbon::now();

        // Kita ambil collection dulu
        $assignmentsCollection = $query->get();

        $assignments = $assignmentsCollection->filter(function ($assign) use ($now) {
            /** @var Assignment $assign */
            $mySubmission = $assign->submissions->first(); // Karena sudah difilter di eager load
            $isSubmitted = $mySubmission != null;
            $isOverdue = $assign->due_date && $now->gt($assign->due_date);

            if ($this->filter === 'upcoming') {
                // Tampilkan jika BELUM submit DAN BELUM telat
                return ! $isSubmitted && ! $isOverdue;
            } else {
                // History: SUDAH submit ATAU SUDAH telat
                return $isSubmitted || $isOverdue;
            }
        });

        // Sorting
        if ($this->filter === 'upcoming') {
            $assignments = $assignments->sortBy('due_date');
        } else {
            $assignments = $assignments->sortByDesc('due_date');
        }

        return view('livewire.assessment-manager', [
            'role' => 'student',
            'assignments' => $assignments,
        ]);
    }

    private function renderLecturerView(User $user): View
    {
        // 1. Ambil ID kelas yang diajar
        $classIds = $user->teachingClasses()->pluck('id');

        // 2. Ambil semua tugas yang dibuat dosen ini
        $query = Assignment::whereIn('course_class_id', $classIds)
            ->with(['class.course', 'submissions']); // Load submissions untuk hitung stats

        $assignmentsCollection = $query->get();

        // 3. Filter Sederhana untuk Dosen (Active vs Past)
        $now = Carbon::now();
        $assignments = $assignmentsCollection->filter(function ($assign) use ($now) {
            /** @var Assignment $assign */
            $isOverdue = $assign->due_date && $now->gt($assign->due_date);

            if ($this->filter === 'upcoming') {
                return ! $isOverdue; // Masih aktif
            } else {
                return $isOverdue; // Sudah lewat deadline
            }
        });

        $assignments = $assignments->sortByDesc('created_at');

        return view('livewire.assessment-manager', [
            'role' => 'lecturer',
            'assignments' => $assignments,
        ]);
    }
}
