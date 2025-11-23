<?php

namespace App\Livewire\Traits\Course;

use App\Models\Assignment;
use App\Models\Submission;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait WithAssessment
{
    // State Management
    public $assessmentState = 'list'; // list, create, detail

    public $selectedAssignmentId = null;

    // Create Assessment (Lecturer)
    public $newAssessTitle;
    public $newAssessDesc;
    public $newAssessFile;
    public $newAssessDue;

    // Update Assessment (Lecturer)
    public $editAssessDue; // Variable baru untuk update deadline

    // Submission (Student)
    public $submissionFile;
    public $submissionText;
    public $showSubmitConfirmModal = false;

    // --- NAVIGASI ---
    public function switchToAssessmentList()
    {
        $this->assessmentState = 'list';
        $this->resetAssessmentForm();
    }

    public function openCreateAssessment()
    {
        $this->assessmentState = 'create';
        $this->resetAssessmentForm();
    }

    public function openAssessmentDetail($id)
    {
        $this->selectedAssignmentId = $id;

        // Populate data edit deadline saat membuka detail
        $assignment = Assignment::find($id);
        if ($assignment) {
            $this->editAssessDue = $assignment->due_date ? $assignment->due_date->format('Y-m-d\TH:i') : null;
        }

        $this->assessmentState = 'detail';
    }

    // --- LECTURER: MANAGE ASSESSMENT (NEW FEATURES) ---

    // 1. Toggle Lock / Always Open
    public function toggleLockStatus($id)
    {
        if (Auth::user()->role !== 'lecturer') return;

        $assignment = Assignment::find($id);
        if (!$assignment) return;

        // Toggle Logic
        $assignment->is_lock = !$assignment->is_lock;
        $assignment->save();

        // Feedback Logic
        $status = $assignment->is_lock ? 'LOCKED' : 'UNLOCKED';
        session()->flash('message', "Assessment is now {$status}. " . ($assignment->is_lock ? "Students cannot submit." : "Students can submit (if time allows)."));
    }

    // 2. Update Deadline (Smart Detection)
    public function updateDeadline($id)
    {
        if (Auth::user()->role !== 'lecturer') return;

        $this->validate([
            'editAssessDue' => 'required|date',
        ]);

        $assignment = Assignment::find($id);
        if (!$assignment) return;

        $newDate = Carbon::parse($this->editAssessDue);
        $now = Carbon::now();
        $createdAt = $assignment->created_at;

        // VALIDASI PINTAR (SMART DETECTION)

        // Kasus 1: Deadline baru di masa lalu (Mundur dari hari ini)
        if ($newDate->lt($now)) {
            $this->addError('editAssessDue', 'The new deadline is in the past. Please set a future date to re-open submissions effectively.');
            return;
        }

        // Kasus 2: Deadline lebih lama dari tanggal pembuatan (Tidak Logis secara historis, tapi mungkin typo user)
        if ($newDate->lt($createdAt)) {
            $this->addError('editAssessDue', 'Error: New deadline cannot be earlier than when the assessment was created.');
            return;
        }

        // Update
        $assignment->due_date = $newDate;

        // Otomatis Unlock jika user update deadline agar siswa bisa submit
        if ($assignment->is_lock) {
            $assignment->is_lock = false;
            session()->flash('message', 'Deadline updated & Assessment UNLOCKED automatically.');
        } else {
            session()->flash('message', 'Deadline updated successfully.');
        }

        $assignment->save();
    }

    // 3. Delete Assessment
    public function deleteAssessment($id)
    {
        if (Auth::user()->role !== 'lecturer') return;

        $assignment = Assignment::find($id);
        if (!$assignment) return;

        // Syarat Delete: Harus Locked ATAU Deadline sudah lewat (Overdue)
        // Ini mencegah dosen menghapus tugas yang sedang aktif dikerjakan siswa
        if (!$assignment->is_lock && !$assignment->isOverdue()) {
            session()->flash('error', 'Cannot delete Active Assessment. Please LOCK it first or wait until it is Overdue.');
            return;
        }

        // Delete Logic (Termasuk file & submission)
        // Hapus file soal
        if ($assignment->attachment_path) {
            Storage::disk('public')->delete($assignment->attachment_path);
        }

        // Hapus semua submission files
        foreach ($assignment->submissions as $sub) {
            if ($sub->file_path) {
                Storage::disk('public')->delete($sub->file_path);
            }
        }

        $assignment->delete(); // Cascade delete submissions biasanya di handle DB, tapi aman di sini

        session()->flash('message', 'Assessment deleted successfully.');
        $this->switchToAssessmentList();
    }

    // --- LECTURER: CREATE ASSIGNMENT (EXISTING) ---
    public function createAssignment()
    {
        if (Auth::user()->role !== 'lecturer') return;

        $this->validate([
            'newAssessTitle' => 'required|string|max:255',
            'newAssessDesc' => 'required|string',
            'newAssessDue' => 'required|date|after:now',
            'newAssessFile' => 'nullable|file|max:10240|mimes:pdf,doc,docx,zip,rar,jpg,png',
        ]);

        $path = null;
        $name = null;

        if ($this->newAssessFile) {
            $name = $this->newAssessFile->getClientOriginalName();
            $path = $this->newAssessFile->store('assignment_materials', 'public');
        }

        Assignment::create([
            'course_class_id' => $this->courseClassId,
            'title' => $this->newAssessTitle,
            'description' => $this->newAssessDesc,
            'due_date' => $this->newAssessDue,
            'attachment_path' => $path,
            'attachment_name' => $name,
        ]);

        session()->flash('message', 'Assessment posted successfully.');
        $this->switchToAssessmentList();
    }

    // --- STUDENT: SUBMIT TASK (UPDATED LOGIC) ---
    public function confirmSubmission()
    {
        $this->validate([
            'submissionFile' => 'nullable|file|max:20480',
            'submissionText' => 'nullable|string',
        ]);

        if (!$this->submissionFile && empty($this->submissionText)) {
            $this->addError('submissionFile', 'Please upload a file or write a response.');
            return;
        }

        $this->showSubmitConfirmModal = true;
    }

    public function submitAssignment()
    {
        $assignment = Assignment::find($this->selectedAssignmentId);

        // Logic Validasi Submission Baru
        // 1. Cek Override Lock dulu
        if ($assignment->is_lock) {
            session()->flash('error', 'Submission is LOCKED by Lecturer.');
            $this->showSubmitConfirmModal = false;
            return;
        }

        // 2. Cek Deadline (Hanya jika tidak dilock manual)
        // Tapi dosen bisa saja unlock assessment yang sudah overdue (Special case: Extension)
        // Jadi logic isOverdue() tetap berlaku KECUALI dosen secara eksplisit mengubah deadline
        // atau kita anggap "Unlocked" = "Allow Late Submission".
        // Disini kita pakai standar strict: Lewat deadline = Late (tapi boleh submit kalau sistem izinkan),
        // atau Close total. Di sistem ini kita pakai Close total kalau overdue.

        if ($assignment->isOverdue()) {
             session()->flash('error', 'Deadline has passed. Submission Closed.');
             $this->showSubmitConfirmModal = false;
             return;
        }

        $path = null;
        $name = null;

        if ($this->submissionFile) {
            $name = $this->submissionFile->getClientOriginalName();
            $path = $this->submissionFile->store('submissions', 'public');
        }

        $status = Carbon::now()->gt($assignment->due_date) ? 'late' : 'submitted';

        Submission::updateOrCreate(
            ['assignment_id' => $assignment->id, 'user_id' => Auth::id()],
            [
                'file_path' => $path,
                'file_name' => $name,
                'text_content' => $this->submissionText,
                'submitted_at' => Carbon::now(),
                'status' => $status,
            ]
        );

        $this->showSubmitConfirmModal = false;
        $this->reset(['submissionFile', 'submissionText']);
        session()->flash('message', 'Assignment submitted successfully!');
    }

    // --- DOWNLOAD LOGIC ---
    public function downloadTextSubmission($submissionId)
    {
        $submission = Submission::find($submissionId);
        if ($submission && $submission->text_content) {
            $content = 'Student Name: '.$submission->user->name."\n";
            $content .= 'Submitted At: '.$submission->submitted_at."\n";
            $content .= "------------------------------------------------\n\n";
            $content .= $submission->text_content;
            $fileName = 'text_submission_'.Str::slug($submission->user->name).'.txt';
            return response()->streamDownload(function () use ($content) { echo $content; }, $fileName);
        }
    }

    public function downloadSubmissionFile($submissionId)
    {
        $submission = Submission::find($submissionId);
        if ($submission && $submission->file_path) {
            return Storage::disk('public')->download($submission->file_path, $submission->file_name);
        }
    }

    private function resetAssessmentForm()
    {
        $this->reset(['newAssessTitle', 'newAssessDesc', 'newAssessFile', 'newAssessDue', 'editAssessDue', 'submissionFile', 'submissionText', 'showSubmitConfirmModal']);
    }
}
