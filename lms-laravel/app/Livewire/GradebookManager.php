<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\GradeComponent;
use App\Models\StudentGrade;

#[Layout('layouts.app')]
class GradebookManager extends Component
{
    public function render()
    {
        $user = Auth::user();

        if ($user->role === 'student') {
            return $this->renderStudentDashboard($user);
        } else {
            return $this->renderLecturerDashboard($user);
        }
    }

    private function renderStudentDashboard($user)
    {
        // Ambil semua kelas, Eager load Course
        $enrolledClasses = $user->enrolledClasses()->with('course')->get();

        // Grouping berdasarkan Course ID (Agar LEC & LAB jadi 1 entry)
        $groupedCourses = $enrolledClasses->groupBy('course_id');

        $totalCourses = $groupedCourses->count();
        $totalSKS = 0;
        $totalQualityPoints = 0;
        $totalScoreSum = 0;

        $processedCourses = [];

        foreach ($groupedCourses as $courseId => $classes) {
            // Ambil data course dari salah satu kelas (sama saja)
            $courseRef = $classes->first()->course;

            // Hitung Nilai Total (Gabungan Komponen LEC & LAB)
            // Kita cari semua komponen penilaian milik Course ID ini
            $components = GradeComponent::where('course_id', $courseId)->get();
            $courseScore = 0;

            foreach ($components as $comp) {
                $grade = StudentGrade::where('grade_component_id', $comp->id)
                    ->where('user_id', $user->id)
                    ->first();
                if ($grade) {
                    $courseScore += ($grade->score * ($comp->weight / 100));
                }
            }
            $courseScore = round($courseScore, 2);

            // Hitung Grade Point (4.0 Scale)
            $gradePoint = 0.0;
            $gradeLetter = 'F';

            if ($courseScore >= 90) { $gradePoint = 4.0; $gradeLetter = 'A'; }
            elseif ($courseScore >= 85) { $gradePoint = 3.67; $gradeLetter = 'A-'; }
            elseif ($courseScore >= 80) { $gradePoint = 3.33; $gradeLetter = 'B+'; }
            elseif ($courseScore >= 75) { $gradePoint = 3.0; $gradeLetter = 'B'; }
            elseif ($courseScore >= 70) { $gradePoint = 2.5; $gradeLetter = 'B-'; }
            elseif ($courseScore >= 65) { $gradePoint = 2.0; $gradeLetter = 'C'; }
            elseif ($courseScore >= 50) { $gradePoint = 1.0; $gradeLetter = 'D'; }

            $sks = $courseRef->credits ?? 0;

            // Hanya hitung ke GPA jika SKS > 0
            if($sks > 0) {
                $totalSKS += $sks;
                $totalQualityPoints += ($gradePoint * $sks);
            }

            $totalScoreSum += $courseScore;

            // Siapkan data untuk View (Ambil kelas pertama sbg link representatif)
            $processedCourses[] = (object) [
                'id' => $classes->first()->id, // Link ke salah satu kelas detail
                'title' => $courseRef->title,
                'code' => $courseRef->code,
                'class_code' => $classes->pluck('class_code')->implode(' / '), // LA01 / LB01
                'type' => $classes->pluck('type')->implode(' & '), // LEC & LAB
                'score' => $courseScore,
                'grade_letter' => $gradeLetter,
                'semester' => $classes->first()->semester
            ];
        }

        $gpa = $totalSKS > 0 ? round($totalQualityPoints / $totalSKS, 2) : 0.00;
        $avgScore = $totalCourses > 0 ? round($totalScoreSum / $totalCourses, 1) : 0;

        // Status Akademik
        $academicStatus = 'Good Standing';
        $statusColor = 'green';
        if ($gpa < 2.0) {
            $academicStatus = 'Probation';
            $statusColor = 'red';
        } elseif ($gpa < 2.5) {
            $academicStatus = 'Warning';
            $statusColor = 'yellow';
        }

        return view('livewire.gradebook-manager', [
            'role' => 'student',
            'stats' => compact('totalCourses', 'gpa', 'avgScore', 'academicStatus', 'statusColor', 'totalSKS'),
            'courses' => $processedCourses
        ]);
    }

    private function renderLecturerDashboard($user)
    {
        $teachingClasses = $user->teachingClasses()->with('course')->get();
        $totalCourses = $teachingClasses->count();

        return view('livewire.gradebook-manager', [
            'role' => 'lecturer',
            'stats' => compact('totalCourses'),
            'courses' => $teachingClasses
        ]);
    }
}
