<?php

namespace App\Livewire;

use App\Models\ForumThread;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ForumManager extends Component
{
    use WithPagination;

    public string $filter = 'all'; // 'all', 'my_courses'

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
    }

    public function render(): View
    {
        /** @var User $user */
        $user = Auth::user();

        // 1. Ambil ID kelas dimana user terdaftar (Siswa) atau mengajar (Dosen)
        $classIds = [];
        if ($user->role === 'student') {
            $classIds = $user->enrolledClasses()->pluck('course_classes.id')->toArray();
        } else {
            $classIds = $user->teachingClasses()->pluck('id')->toArray();
        }

        // 2. Query Thread berdasarkan sesi dari kelas-kelas tersebut
        $query = ForumThread::with(['user', 'session.class.course', 'posts'])
            ->whereHas('session', function ($q) use ($classIds) {
                $q->whereIn('course_class_id', $classIds);
            });

        // Filter tambahan jika perlu (misal 'my_courses' bisa difokuskan ke thread yg dibuat user tsb)
        if ($this->filter === 'my_threads') {
            $query->where('user_id', $user->id);
        }

        // Urutkan dari yang terbaru (balasan terakhir atau dibuat terakhir)
        $threads = $query->latest()->paginate(10);

        return view('livewire.forum-manager', [
            'threads' => $threads,
        ]);
    }
}
