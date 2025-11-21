<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

#[Layout('layouts.app')]
class ScheduleManager extends Component
{
    // State untuk Filter Harian
    public $viewDate; // Tanggal yang sedang dilihat (Y-m-d)
    public $viewLabel = 'Today'; // 'Yesterday', 'Today', 'Tomorrow'

    public function mount()
    {
        // Default start hari ini
        $this->viewDate = Carbon::today()->format('Y-m-d');
    }

    public function setDay($type)
    {
        $today = Carbon::today();

        if ($type === 'prev') {
            $this->viewDate = Carbon::parse($this->viewDate)->subDay()->format('Y-m-d');
        } elseif ($type === 'next') {
            $this->viewDate = Carbon::parse($this->viewDate)->addDay()->format('Y-m-d');
        } else {
            // Reset to today
            $this->viewDate = $today->format('Y-m-d');
        }

        // Update Label Pivot
        $current = Carbon::parse($this->viewDate);

        if ($current->isToday()) {
            $this->viewLabel = 'Today';
        } elseif ($current->isYesterday()) {
            $this->viewLabel = 'Yesterday';
        } elseif ($current->isTomorrow()) {
            $this->viewLabel = 'Tomorrow';
        } else {
            $this->viewLabel = $current->format('D, d M'); // Tanggal biasa jika jauh
        }
    }

    public function render()
    {
        $user = Auth::user();

        // --- 1. DATA FETCHING ---
        $classes = ($user->role === 'student')
            ? $user->enrolledClasses()->with(['course', 'sessions', 'lecturer'])->get()
            : $user->teachingClasses()->with(['course', 'sessions', 'students'])->get();

        $calendarEvents = [];
        $dailyActivities = []; // Data untuk card di bawah kalender

        // --- 2. PROCESSING LOOP ---
        foreach ($classes as $class) {
            // Generate Warna Unik Konsisten (Hash dari Kode Kelas + Course)
            $identifier = $class->course->code . $class->class_code;
            $baseColor = $this->stringToColorCode($identifier);

            foreach ($class->sessions as $session) {
                $start = Carbon::parse($session->start_time);
                $end = Carbon::parse($session->end_time);
                $now = Carbon::now();
                $isPast = $end->lt($now);

                // A. Data untuk Kalender (FullCalendar)
                $calendarEvents[] = [
                    'id' => $session->id,
                    'title' => $class->course->code . ': ' . $session->title,
                    'start' => $start->toIso8601String(),
                    'end' => $end->toIso8601String(),
                    'backgroundColor' => $isPast ? '#9CA3AF' : $baseColor, // Gray jika lewat
                    'borderColor' => $isPast ? '#9CA3AF' : $baseColor,
                    'textColor' => '#ffffff',
                    'extendedProps' => [
                        'class_id' => $class->id, // Penting untuk Redirect Link
                        'course_name' => $class->course->title,
                        'course_code' => $class->course->code,
                        'class_code' => $class->class_code,
                        'session_no' => 'Session ' . $session->session_number,
                        'delivery' => $session->delivery_mode,
                        'time_range' => $start->format('H:i') . ' - ' . $end->format('H:i'),
                        'status' => $isPast ? 'Finished' : 'Upcoming'
                    ]
                ];

                // B. Data untuk Daily Activities (Card Bawah)
                // Filter: Apakah sesi ini terjadi pada tanggal yang sedang dipilih ($this->viewDate)?
                if ($start->format('Y-m-d') === $this->viewDate) {
                    $dailyActivities[] = (object) [
                        'id' => $session->id,
                        'class_id' => $class->id,
                        'title' => $session->title,
                        'course_title' => $class->course->title,
                        'course_code' => $class->course->code,
                        'class_code' => $class->class_code,
                        'session_number' => $session->session_number,
                        'start_time' => $start,
                        'end_time' => $end,
                        'delivery_mode' => $session->delivery_mode,
                        'color' => $baseColor, // Warna sama persis dengan kalender
                        'is_past' => $isPast,
                        'lecturer_name' => $class->lecturer->first_name . ' ' . $class->lecturer->last_name
                    ];
                }
            }
        }

        // Sort Daily Activities berdasarkan jam mulai
        $dailyActivities = collect($dailyActivities)->sortBy('start_time');

        return view('livewire.schedule-manager', [
            'events' => $calendarEvents,
            'dailyActivities' => $dailyActivities,
            'role' => $user->role
        ]);
    }

    // Helper Warna Konsisten
    private function stringToColorCode($str) {
        $code = dechex(crc32($str));
        $code = substr($code, 0, 6);
        return "#" . $code;
    }
}
