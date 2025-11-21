<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\CourseClass;
use App\Models\CourseSession;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class CourseManager extends Component
{
    // Filter Variables
    public string $selectedSemester = '2025, Odd Semester';

    public string $selectedType = 'ALL';

    // Modal Variables
    public bool $showAddModal = false;

    public bool $showDeleteModal = false;

    public ?int $classToDeleteId = null;

    // Input Form Variables
    public string $title = '';

    public string $class_code = '';

    public string $type = 'LEC';

    public string $description = '';

    public ?string $student_email_invite = null;

    // VARIABEL BARU: Dropdown Jurusan
    public string $selectedMajorPrefix = 'COMP'; // Default

    // Daftar Jurusan
    /** @var array<string, string> */
    public array $majors = [
        'COMP' => 'Computer Science / IT',
        'DKV' => 'Desain Komunikasi Visual',
        'ACCT' => 'Accounting',
        'LAW' => 'Law / Hukum',
        'MGMT' => 'Management',
        'ENG' => 'Engineering',
        'COMM' => 'Communication',
        'PSYC' => 'Psychology',
    ];

    // Rules Validasi
    protected array $rules = [
        'title' => 'required|string',
        'selectedMajorPrefix' => 'required|string',
        'class_code' => 'required|string',
        'type' => 'required|in:LEC,LAB',
        'student_email_invite' => 'nullable|email|exists:users,email',
    ];

    // Helper untuk generate 7 angka random
    private function generateAutoCode(string $prefix): string
    {
        do {
            $randomNumbers = str_pad((string) mt_rand(0, 9999999), 7, '0', STR_PAD_LEFT);
            $code = $prefix.$randomNumbers;
        } while (Course::where('code', $code)->exists());

        return $code;
    }

    public function saveCourse(): void
    {
        $this->validate();

        // 1. Generate Kode Otomatis (COMP + Angka)
        $generatedCode = $this->generateAutoCode($this->selectedMajorPrefix);

        // 2. Cari atau Buat Mata Kuliah Utama (Parent Course)
        /** @var Course $course */
        $course = Course::firstOrCreate(
            ['code' => $generatedCode],
            [
                'title' => $this->title,
                'description' => $this->description,
            ]
        );

        // 3. Buat Kelas Spesifik (Instance)
        /** @var CourseClass $newClass */
        $newClass = CourseClass::create([
            'course_id' => $course->id,
            'lecturer_id' => Auth::id(),
            'class_code' => $this->class_code,
            'semester' => $this->selectedSemester,
            'type' => $this->type,
        ]);

        // --- LOGIKA BARU: GENERATE 13 SESI OTOMATIS ---
        $startDate = \Carbon\Carbon::now()->next('Monday')->setTime(13, 0); // Mulai Senin depan jam 13:00

        for ($i = 1; $i <= 13; $i++) {
            $isOnsite = $i % 2 != 0;

            CourseSession::create([
                'course_class_id' => $newClass->id,
                'session_number' => $i,
                'title' => "Session $i: Topic about ".Str::limit($course->title, 20),
                'learning_outcome' => "Students will understand the fundamental concepts of topic $i in {$course->title} and apply them in real-world scenarios.",
                'start_time' => $startDate->copy(),
                'end_time' => $startDate->copy()->addMinutes(100),
                'delivery_mode' => $isOnsite ? 'Onsite - Class' : 'Online - GSLC',
                'zoom_link' => $isOnsite ? null : 'https://zoom.us/j/dummy-meeting-link',
            ]);

            $startDate->addWeek();
        }

        // 4. Invite Siswa (Jika email diisi)
        if ($this->student_email_invite) {
            /** @var User|null $student */
            $student = User::where('email', $this->student_email_invite)->first();
            if ($student && $student->role === 'student') {
                Enrollment::create([
                    'user_id' => $student->id,
                    'course_class_id' => $newClass->id,
                ]);
            }
        }

        $this->reset(['title', 'selectedMajorPrefix', 'class_code', 'description', 'student_email_invite', 'showAddModal']);

        session()->flash('message', 'Class created successfully with 13 Sessions!');
    }

    public function confirmDelete(int $id): void
    {
        $this->classToDeleteId = $id;
        $this->showDeleteModal = true;
    }

    public function deleteClass(): void
    {
        if ($this->classToDeleteId) {
            $class = CourseClass::find($this->classToDeleteId);

            if ($class && $class->lecturer_id == Auth::id()) {
                $class->delete();
                session()->flash('message', 'Class deleted successfully.');
            }
        }

        $this->showDeleteModal = false;
        $this->classToDeleteId = null;
    }

    public function render(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $query = null;

        if ($user->role === 'lecturer') {
            $query = $user->teachingClasses()->with('course');
        } else {
            $query = $user->enrolledClasses()->with('course');
        }

        $query->where('semester', $this->selectedSemester);

        if ($this->selectedType !== 'ALL') {
            $query->where('type', $this->selectedType);
        }

        return view('livewire.course-manager', [
            'courses' => $query->get(),
        ]);
    }
}
