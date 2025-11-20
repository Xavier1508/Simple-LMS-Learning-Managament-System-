<?php

namespace App\Livewire\Traits\Course;

use App\Models\CourseClass;
use App\Models\ForumThread;
use App\Models\ForumPost;
use App\Models\CourseSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

trait WithForum
{
    // --- STATE MANAGEMENT ---
    // 'list' = Daftar Thread, 'create' = Form Baru, 'detail' = Baca Thread
    public $forumState = 'list';
    public $selectedThreadId = null;

    // --- CREATE THREAD FORM ---
    public $newThreadTitle = '';
    public $newThreadContent = '';
    public $newThreadAttachment; // File temporary Livewire

    // Lecture Options
    public $isThreadHidden = false;
    public $isThreadAssessment = false;
    public $threadDeadline = null;
    public $targetSessionId = null; // Dropdown session

    // Cross Posting (Lecture)
    public $crossPostClassIds = []; // Array ID kelas lain
    public $availableCrossClasses = []; // Data kelas lain dg course sama

    // --- REPLY FORM ---
    public $replyContent = '';
    public $replyAttachment;
    public $replySort = 'oldest'; // 'oldest' or 'newest'

    // --- NAVIGASI ---
    public function switchToForumList()
    {
        $this->forumState = 'list';
        $this->resetForumForm();
    }

    public function switchToCreateThread()
    {
        $this->forumState = 'create';
        $this->resetForumForm();

        // Default session = active session
        $this->targetSessionId = $this->activeSessionId;

        // Logic Load Cross Classes (Lecture Only)
        if (Auth::user()->role === 'lecturer') {
            $currentClass = CourseClass::find($this->courseClassId);
            // Cari kelas lain yang diajar dosen ini dengan Course ID yang sama, tapi bukan kelas ini
            $this->availableCrossClasses = CourseClass::where('lecturer_id', Auth::id())
                ->where('course_id', $currentClass->course_id)
                ->where('id', '!=', $this->courseClassId)
                ->with('course')
                ->get();
        }
    }

    public function openThread($threadId)
    {
        $this->selectedThreadId = $threadId;
        $this->forumState = 'detail';
        $this->replySort = 'oldest'; // Reset sort
    }

    // --- ACTIONS: CREATE THREAD ---
    public function createThread()
    {
        // 1. Validasi Dasar
        $rules = [
            'newThreadTitle' => 'required|string|min:5|max:255',
            'newThreadContent' => 'required|string|min:10',
            'targetSessionId' => 'required|exists:course_sessions,id',
            'newThreadAttachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx,zip,rar',
        ];

        // Validasi Tambahan Dosen
        if (Auth::user()->role === 'lecturer' && $this->isThreadAssessment) {
            $rules['threadDeadline'] = 'required|date|after:now';
        }

        $this->validate($rules);

        // 2. Sanitasi Content (Mencegah XSS tapi boleh styling basic)
        $cleanContent = $this->sanitizeContent($this->newThreadContent);

        // 3. Handle File Upload (Jika ada)
        $attachmentData = $this->handleFileUpload($this->newThreadAttachment);

        // 4. Simpan ke Database (Kelas Saat Ini)
        $this->saveThreadToDatabase(
            $this->targetSessionId,
            $cleanContent,
            $attachmentData
        );

        // 5. Handle Cross Posting (Lecture Only)
        if (Auth::user()->role === 'lecturer' && !empty($this->crossPostClassIds)) {
            // Ambil session number dari session target saat ini
            $currentSession = CourseSession::find($this->targetSessionId);
            $sessionNum = $currentSession->session_number;

            foreach ($this->crossPostClassIds as $classId) {
                // Cari session dengan nomor yang sama di kelas target
                $targetSession = CourseSession::where('course_class_id', $classId)
                    ->where('session_number', $sessionNum)
                    ->first();

                if ($targetSession) {
                    $this->saveThreadToDatabase(
                        $targetSession->id,
                        $cleanContent,
                        $attachmentData // File path sama (shared)
                    );
                }
            }
        }

        session()->flash('message', 'Thread posted successfully!');
        $this->switchToForumList();
    }

    private function saveThreadToDatabase($sessionId, $content, $attachment)
    {
        ForumThread::create([
            'course_session_id' => $sessionId,
            'user_id' => Auth::id(),
            'title' => $this->newThreadTitle,
            'content' => $content,
            'is_hidden' => Auth::user()->role === 'lecturer' ? $this->isThreadHidden : false,
            'is_assessment' => Auth::user()->role === 'lecturer' ? $this->isThreadAssessment : false,
            'deadline_at' => (Auth::user()->role === 'lecturer' && $this->isThreadAssessment) ? $this->threadDeadline : null,
            'attachment_path' => $attachment['path'],
            'attachment_name' => $attachment['name'],
            'attachment_type' => $attachment['type'],
        ]);
    }

    // --- ACTIONS: REPLY ---
    public function postReply()
    {
        $this->validate([
            'replyContent' => 'required|string|min:2',
            'replyAttachment' => 'nullable|file|max:5120',
        ]);

        $thread = ForumThread::find($this->selectedThreadId);

        // Cek Deadline Assessment untuk Siswa
        if (Auth::user()->role === 'student' && $thread->isLocked()) {
            session()->flash('error', 'This assessment is locked. Deadline passed.');
            return;
        }

        $cleanContent = $this->sanitizeContent($this->replyContent);
        $attachmentData = $this->handleFileUpload($this->replyAttachment);

        ForumPost::create([
            'forum_thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'content' => $cleanContent,
            'attachment_path' => $attachmentData['path'],
            'attachment_name' => $attachmentData['name'],
            'attachment_type' => $attachmentData['type'],
        ]);

        $this->reset(['replyContent', 'replyAttachment']);
        session()->flash('message', 'Comment posted.');
    }

    // --- HELPERS ---

    // Simpan file dengan nama acak (Aman) tapi simpan nama asli di DB
    private function handleFileUpload($file)
    {
        if (!$file) return ['path' => null, 'name' => null, 'type' => null];

        $originalName = $file->getClientOriginalName();
        $type = $file->extension();
        // Simpan fisik file dengan hash name agar aman dari eksekusi script
        $path = $file->store('forum_attachments', 'public');

        return [
            'path' => $path,
            'name' => $originalName,
            'type' => $type
        ];
    }

    // Sanitasi HTML agar aman dari XSS
    private function sanitizeContent($html)
    {
        // Hanya izinkan tag basic formatting. Hapus script, iframe, dll.
        return strip_tags($html, '<b><i><u><strong><em><p><br><ul><ol><li><h1><h2><h3><blockquote>');
    }

    private function resetForumForm()
    {
        $this->reset([
            'newThreadTitle', 'newThreadContent', 'newThreadAttachment',
            'isThreadHidden', 'isThreadAssessment', 'threadDeadline',
            'crossPostClassIds', 'selectedThreadId'
        ]);
    }

    public function deleteThread($id)
    {
        $thread = ForumThread::find($id);
        // Cek Hak: Pemilik atau Dosen
        if ($thread && (Auth::id() == $thread->user_id || Auth::user()->role === 'lecturer')) {
            // Hapus file jika ada
            if ($thread->attachment_path) Storage::disk('public')->delete($thread->attachment_path);
            $thread->delete();
            session()->flash('message', 'Thread deleted.');
            $this->switchToForumList();
        }
    }

    public function deletePost($id)
    {
        $post = ForumPost::find($id);
        if ($post && (Auth::id() == $post->user_id || Auth::user()->role === 'lecturer')) {
             if ($post->attachment_path) Storage::disk('public')->delete($post->attachment_path);
            $post->delete();
            session()->flash('message', 'Comment deleted.');
        }
    }
}
