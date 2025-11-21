<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\CourseClass;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;
use App\Models\CourseSession;

#[Layout('layouts.app')]
class CourseManager extends Component
{
    // Filter Variables
    public $selectedSemester = '2025, Odd Semester';
    public $selectedType = 'ALL';

    // Modal Variables
    public $showAddModal = false;
    public $showDeleteModal = false;
    public $classToDeleteId = null;

    // Input Form Variables
    public $title;
    // public $code; // <-- HAPUS INI (Tidak lagi diinput manual)
    public $class_code;
    public $type = 'LEC';
    public $description;
    public $student_email_invite;

    // VARIABEL BARU: Dropdown Jurusan
    public $selectedMajorPrefix = 'COMP'; // Default

    // Daftar Jurusan (Bisa ditambah nanti)
    public $majors = [
        'COMP' => 'Computer Science / IT',
        'DKV'  => 'Desain Komunikasi Visual',
        'ACCT' => 'Accounting',
        'LAW'  => 'Law / Hukum',
        'MGMT' => 'Management',
        'ENG'  => 'Engineering',
        'COMM' => 'Communication',
        'PSYC' => 'Psychology'
    ];

    // Rules Validasi (Code dihapus, ganti selectedMajorPrefix)
    protected $rules = [
        'title' => 'required|string',
        'selectedMajorPrefix' => 'required|string', // Validasi baru
        'class_code' => 'required|string',
        'type' => 'required|in:LEC,LAB',
        'student_email_invite' => 'nullable|email|exists:users,email',
    ];

    // Helper untuk generate 7 angka random
    private function generateAutoCode($prefix)
    {
        do {
            // Generate COMP + 7 angka random (e.g., COMP8291023)
            $randomNumbers = str_pad(mt_rand(0, 9999999), 7, '0', STR_PAD_LEFT);
            $code = $prefix . $randomNumbers;
        } while (Course::where('code', $code)->exists()); // Pastikan unik, generate lagi kalau kembar

        return $code;
    }

    public function saveCourse()
    {
        $this->validate();

        // 1. Generate Kode Otomatis (COMP + Angka)
        $generatedCode = $this->generateAutoCode($this->selectedMajorPrefix);

        // 2. Cari atau Buat Mata Kuliah Utama (Parent Course)
        $course = Course::firstOrCreate(
            ['code' => $generatedCode],
            [
                'title' => $this->title,
                'description' => $this->description
            ]
        );

        // 3. Buat Kelas Spesifik (Instance)
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
            // Tentukan Mode (Ganjil = Onsite, Genap = Online) - Contoh saja
            $isOnsite = $i % 2 != 0;

            CourseSession::create([
                'course_class_id' => $newClass->id,
                'session_number' => $i,
                'title' => "Session $i: Topic about " . \Illuminate\Support\Str::limit($course->title, 20), // Judul Dinamis
                'learning_outcome' => "Students will understand the fundamental concepts of topic $i in {$course->title} and apply them in real-world scenarios.",
                'start_time' => $startDate->copy(),
                'end_time' => $startDate->copy()->addMinutes(100), // Durasi 100 menit
                'delivery_mode' => $isOnsite ? 'Onsite - Class' : 'Online - GSLC',
                'zoom_link' => $isOnsite ? null : 'https://zoom.us/j/dummy-meeting-link',
            ]);

            // Tambah 1 minggu untuk sesi berikutnya
            $startDate->addWeek();
        }
        // ------------------------------------------------

        // 4. Invite Siswa (Jika email diisi)
        if ($this->student_email_invite) {
            $student = User::where('email', $this->student_email_invite)->first();
            if ($student && $student->role === 'student') {
                Enrollment::create([
                    'user_id' => $student->id,
                    'course_class_id' => $newClass->id
                ]);
            }
        }

        // Reset form
        $this->reset(['title', 'selectedMajorPrefix', 'class_code', 'description', 'student_email_invite', 'showAddModal']);

        session()->flash('message', 'Class created successfully with 13 Sessions!');
    }

    public function confirmDelete($id)
    {
        $this->classToDeleteId = $id;
        $this->showDeleteModal = true;
    }

    public function deleteClass()
    {
        $class = CourseClass::find($this->classToDeleteId);

        if ($class && $class->lecturer_id == Auth::id()) {
            $class->delete();
            session()->flash('message', 'Class deleted successfully.');
        }

        $this->showDeleteModal = false;
        $this->classToDeleteId = null;
    }

    public function render()
    {
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
            'courses' => $query->get()
        ]);
    }
}
