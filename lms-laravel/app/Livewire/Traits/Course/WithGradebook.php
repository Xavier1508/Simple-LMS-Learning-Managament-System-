<?php

namespace App\Livewire\Traits\Course;

use App\Models\GradeComponent;
use App\Models\StudentGrade;
use App\Models\User;
use App\Models\CourseClass;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

trait WithGradebook
{
    // State
    public $gradebookState = 'list';
    public $selectedStudentId = null;

    // Input Form (Lecturer)
    public $inputGrades = [];

    // --- INITIALIZATION (LOGIKA BARU) ---
    // Dipanggil otomatis saat mount di CourseDetail
    public function ensureGradeComponentsExist()
    {
        $courseId = \App\Models\CourseClass::find($this->courseClassId)->course_id;

        // 1. Cek apakah komponen nilai sudah ada? Jika sudah, stop.
        if (GradeComponent::where('course_id', $courseId)->exists()) {
            return;
        }

        // 2. Deteksi apakah Course ini punya kelas tipe LAB?
        // Cari kelas lain dengan course_id yang sama TAPI typenya 'LAB'
        $hasLab = CourseClass::where('course_id', $courseId)
            ->where('type', 'LAB')
            ->exists();

        // 3. Buat Komponen Default (Total harus 100%)
        $components = [];

        if ($hasLab) {
            // SKENARIO A: ADA LAB (Nilai dipecah)
            $components = [
                ['name' => 'Mid Exam', 'weight' => 20, 'type' => 'theory'],
                ['name' => 'Final Exam', 'weight' => 25, 'type' => 'theory'],
                ['name' => 'Assignment', 'weight' => 15, 'type' => 'theory'],
                ['name' => 'Project / AOL', 'weight' => 20, 'type' => 'theory'],
                ['name' => 'Lab Assessment', 'weight' => 20, 'type' => 'lab'], // Field khusus Lab
            ];
        } else {
            // SKENARIO B: THEORY ONLY (Standard)
            $components = [
                ['name' => 'Mid Exam', 'weight' => 30, 'type' => 'theory'],
                ['name' => 'Final Exam', 'weight' => 35, 'type' => 'theory'],
                ['name' => 'Assignment', 'weight' => 20, 'type' => 'theory'],
                ['name' => 'Project / AOL', 'weight' => 15, 'type' => 'theory'],
            ];
        }

        // 4. Insert ke Database
        foreach ($components as $comp) {
            GradeComponent::create([
                'course_id' => $courseId,
                'name' => $comp['name'],
                'weight' => $comp['weight'],
                'type' => $comp['type']
            ]);
        }
    }

    // --- NAVIGASI ---
    public function openStudentGrade($studentId)
    {
        $this->selectedStudentId = $studentId;
        $this->gradebookState = 'detail';
        $this->loadStudentGrades($studentId);
    }

    public function backToGradebookList()
    {
        $this->gradebookState = 'list';
        $this->selectedStudentId = null;
        $this->inputGrades = [];
    }

    // --- LOGIC LOAD NILAI ---
    public function loadStudentGrades($studentId)
    {
        // Pastikan komponen ada dulu sebelum load
        $this->ensureGradeComponentsExist();

        $course = \App\Models\CourseClass::find($this->courseClassId)->course;
        $components = GradeComponent::where('course_id', $course->id)->get();

        foreach ($components as $comp) {
            $grade = StudentGrade::where('grade_component_id', $comp->id)
                ->where('user_id', $studentId)
                ->first();

            // Load nilai yang sudah ada ke input form
            $this->inputGrades[$comp->id] = $grade ? $grade->score : null;
        }
    }

    // --- LOGIC SIMPAN NILAI ---
    public function saveGrades()
    {
        if (Auth::user()->role !== 'lecturer') return;

        foreach ($this->inputGrades as $compId => $score) {
            // Simpan jika tidak null (bisa 0)
            if ($score !== null && $score !== '') {
                $score = max(0, min(100, floatval($score)));

                StudentGrade::updateOrCreate(
                    [
                        'grade_component_id' => $compId,
                        'user_id' => $this->selectedStudentId,
                        'course_class_id' => $this->courseClassId
                    ],
                    [
                        'score' => $score,
                        'graded_by' => Auth::id(),
                        'graded_at' => Carbon::now()
                    ]
                );
            }
        }

        session()->flash('message', 'Grades updated successfully.');
        $this->backToGradebookList();
    }

    // --- HELPER PERHITUNGAN ---
    public function calculateTotalScore($studentId, $courseId)
    {
        // Pastikan komponen ter-load saat hitung score siswa juga
        // (Kita panggil ini manual di view/controller jika perlu, tapi biasanya data sudah ada)

        $components = GradeComponent::where('course_id', $courseId)->get();

        // Fallback: Jika komponen belum ada (baru pertama kali load via view student), return 0
        if ($components->isEmpty()) return ['score' => 0, 'total_weight' => 0];

        $totalScore = 0;
        $totalWeight = 0;

        foreach ($components as $comp) {
            $grade = StudentGrade::where('grade_component_id', $comp->id)
                ->where('user_id', $studentId)
                ->first();

            if ($grade) {
                $totalScore += ($grade->score * ($comp->weight / 100));
            }
            $totalWeight += $comp->weight;
        }

        return [
            'score' => round($totalScore, 2),
            'total_weight' => $totalWeight
        ];
    }

    public function getGradeLetter($score)
    {
        if ($score >= 90) return 'A';
        if ($score >= 85) return 'A-';
        if ($score >= 80) return 'B+';
        if ($score >= 75) return 'B';
        if ($score >= 70) return 'B-';
        if ($score >= 65) return 'C';
        if ($score >= 50) return 'D';
        return 'F';
    }

    public function getGradeColor($grade)
    {
        if (in_array($grade, ['A', 'A-'])) return 'bg-green-100 text-green-700';
        if (in_array($grade, ['B+', 'B', 'B-'])) return 'bg-blue-100 text-blue-700';
        if ($grade === 'C') return 'bg-yellow-100 text-yellow-700';
        return 'bg-red-100 text-red-700';
    }
}
