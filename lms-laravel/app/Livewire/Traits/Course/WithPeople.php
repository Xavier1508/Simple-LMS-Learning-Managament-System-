<?php

namespace App\Livewire\Traits\Course;

use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

trait WithPeople
{
    // Filter Lokal (Pencarian dalam list yang sudah ada)
    public $peopleSearch = '';

    // Filter Global (Pencarian database untuk Add Member)
    public $addMemberSearch = '';

    // State Dropdown
    public $isDropdownOpen = false;

    // --- COMPUTED PROPERTY: HASIL PENCARIAN GLOBAL ---
    // Ini akan dipanggil saat Dosen mengetik di dropdown "Add People"
    public function getSearchableUsersProperty()
    {
        if (empty($this->addMemberSearch)) {
            return [];
        }

        // Ambil ID siswa yang SUDAH ada di kelas ini agar tidak muncul lagi
        $existingUserIds = $this->class->students->pluck('id')->toArray();
        $existingUserIds[] = $this->class->lecturer_id; // Exclude dosen juga

        return User::query()
            ->whereNotIn('id', $existingUserIds) // Jangan tampilkan yang sudah join
            ->where(function($query) {
                $query->where('first_name', 'like', '%' . $this->addMemberSearch . '%')
                      ->orWhere('last_name', 'like', '%' . $this->addMemberSearch . '%')
                      ->orWhere('email', 'like', '%' . $this->addMemberSearch . '%')
                      ->orWhere('lecturer_code', 'like', '%' . $this->addMemberSearch . '%'); // Bisa cari kode dosen/nim
            })
            ->take(10) // Limit 10 row sesuai request
            ->get();
    }

    // --- ACTION: ADD MEMBER ---
    public function addMember($userId)
    {
        if (Auth::user()->role !== 'lecturer') return;

        $user = User::find($userId);

        if ($user) {
            // Cek Role user yang mau ditambahkan
            if ($user->role === 'student') {
                // Masukkan ke tabel Enrollment
                Enrollment::firstOrCreate([
                    'user_id' => $user->id,
                    'course_class_id' => $this->courseClassId
                ]);
                session()->flash('message', "Student {$user->first_name} added successfully.");
            } else {
                // Jika logic mengizinkan multiple lecturer/TA, tambahkan disini.
                // Untuk sekarang kita anggap add people = add student.
                session()->flash('error', "Cannot add another Lecturer to this class (System Restriction).");
            }
        }

        // Reset
        $this->addMemberSearch = '';
        $this->dispatch('member-added'); // Event untuk tutup dropdown di frontend
    }

    // --- ACTION: REMOVE MEMBER (Opsional tapi penting) ---
    public function removeMember($userId)
    {
        if (Auth::user()->role !== 'lecturer') return;

        Enrollment::where('user_id', $userId)
            ->where('course_class_id', $this->courseClassId)
            ->delete();

        session()->flash('message', 'Student removed from class.');
    }
}
