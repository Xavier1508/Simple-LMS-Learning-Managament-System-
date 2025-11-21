<?php

namespace App\Livewire\Traits\Course;

use App\Models\Attendance;
use App\Models\CourseSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

trait WithAttendance
{
    // --- VARIABEL ATTENDANCE MANUAL (DOSEN) ---
    public $showManualAttendanceModal = false;

    public $manualStudentName = '';

    public $manualSessionTitle = '';

    public $manualSessionId = null;

    public $manualStudentId = null;

    public $manualStatus = 'present';

    // --- FITUR ATTENDANCE (STUDENT) ---
    public function attend($sessionId)
    {
        $session = CourseSession::find($sessionId);
        $user = Auth::user();

        if (! $session || $user->role !== 'student') {
            return;
        }

        $now = Carbon::now();
        $openTime = $session->start_time->copy()->subMinutes(30);
        $closeTime = $session->start_time->copy()->addMinutes(15);

        // 1. VALIDASI WAKTU KETAT
        if ($now->lt($openTime)) {
            session()->flash('error', 'Attendance not yet open.');

            return;
        }

        if ($now->gt($closeTime)) {
            // Jika terlambat, paksa record menjadi 'absent' dan recorded_by = SYSTEM (null)
            // Ini agar statusnya "Cancelled by System/Late"
            Attendance::updateOrCreate(
                ['course_session_id' => $session->id, 'user_id' => $user->id],
                [
                    'status' => 'absent',
                    'attended_at' => $now,
                    'recorded_by' => null, // Null artinya System/Auto
                ]
            );
            session()->flash('error', 'Attendance closed. You are marked as absent/late.');

            return;
        }

        // 2. CREATE RECORD (PRESENT)
        Attendance::updateOrCreate(
            ['course_session_id' => $session->id, 'user_id' => $user->id],
            [
                'status' => 'present',
                'attended_at' => $now,
                'recorded_by' => $user->id, // Self recorded
            ]
        );

        session()->flash('message', 'Attendance recorded successfully!');
    }

    // --- FITUR MANUAL OVERRIDE (DOSEN) ---
    public function openManualAttendance($studentId, $sessionId)
    {
        $student = User::find($studentId);
        $session = CourseSession::find($sessionId);

        if ($student && $session) {
            $this->manualStudentId = $studentId;
            $this->manualSessionId = $sessionId;
            $this->manualStudentName = $student->first_name.' '.$student->last_name;
            $this->manualSessionTitle = 'Session '.$session->session_number;

            // Cek status sekarang
            $currentAttendance = Attendance::where('course_session_id', $sessionId)
                ->where('user_id', $studentId)
                ->first();

            $this->manualStatus = $currentAttendance ? $currentAttendance->status : 'absent';
            $this->showManualAttendanceModal = true;
        }
    }

    public function saveManualAttendance()
    {
        if (Auth::user()->role !== 'lecturer') {
            return;
        }

        // FIX ERROR SQL: Pastikan kolom recorded_by diisi ID Dosen
        Attendance::updateOrCreate(
            [
                'course_session_id' => $this->manualSessionId,
                'user_id' => $this->manualStudentId,
            ],
            [
                'status' => $this->manualStatus,
                'attended_at' => Carbon::now(),
                'recorded_by' => Auth::id(), // PENTING: ID Dosen
            ]
        );

        $this->showManualAttendanceModal = false;

        // Refresh data agar UI update real-time
        $this->dispatch('attendance-updated');
        session()->flash('message', 'Attendance updated manually.');
    }

    // HELPER UTAMA: Menentukan Status Visual
    public function getStatusDisplay($session, $attendance)
    {
        $now = Carbon::now();

        // Skenario 1: Belum ada record sama sekali
        if (! $attendance) {
            // Jika waktu sudah lewat -> Absent (Merah)
            if ($now->gt($session->start_time->copy()->addMinutes(15))) {
                return ['type' => 'absent', 'label' => 'Missed / Absent', 'color' => 'red', 'icon' => 'x'];
            }
            // Jika belum mulai -> Upcoming (Kuning)
            if ($now->lt($session->start_time)) {
                return ['type' => 'upcoming', 'label' => 'Waiting for session', 'color' => 'yellow', 'icon' => 'clock'];
            }

            // Sedang berlangsung -> Open (Biru/Orange)
            return ['type' => 'open', 'label' => 'Check-In Open', 'color' => 'blue', 'icon' => 'play'];
        }

        // Skenario 2: Ada Record

        // CASE A: Present (Hadir Normal)
        if ($attendance->status === 'present') {
            return ['type' => 'present', 'label' => 'Classroom Check-In', 'color' => 'green', 'icon' => 'check'];
        }

        // CASE B: Absent (Tidak Hadir) - Cek Siapa yang membatalkan?
        if ($attendance->status === 'absent') {
            // Jika recorded_by adalah Dosen (bukan user itu sendiri, bukan null)
            // Asumsi: Jika recorded_by != user_id dan recorded_by != null, maka itu Lecturer Override
            if ($attendance->recorded_by && $attendance->recorded_by != $attendance->user_id) {
                return ['type' => 'cancelled_lecturer', 'label' => 'Cancelled by Lecturer', 'color' => 'red', 'icon' => 'user-x'];
            }

            // Jika recorded_by null (System) atau user itu sendiri (salah pencet/late logic)
            return ['type' => 'cancelled_system', 'label' => 'Cancelled by System', 'color' => 'red', 'icon' => 'server-off'];
        }

        return ['type' => 'unknown', 'label' => 'Unknown', 'color' => 'gray', 'icon' => 'question'];
    }
}
