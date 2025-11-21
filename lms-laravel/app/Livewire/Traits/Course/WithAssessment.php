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

    // Submission (Student)
    public $submissionFile;

    public $submissionText; // Untuk input text yg akan di-convert ke TXT

    public $showSubmitConfirmModal = false; // Modal konfirmasi "Are you sure?"

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
        $this->assessmentState = 'detail';
    }

    // --- LECTURER: CREATE ASSIGNMENT ---
    public function createAssignment()
    {
        if (Auth::user()->role !== 'lecturer') {
            return;
        }

        $this->validate([
            'newAssessTitle' => 'required|string|max:255',
            'newAssessDesc' => 'required|string',
            'newAssessDue' => 'required|date|after:now',
            'newAssessFile' => 'nullable|file|max:10240|mimes:pdf,doc,docx,zip,rar,jpg,png', // Security Filter
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

    // --- STUDENT: SUBMIT TASK ---
    public function confirmSubmission()
    {
        $this->validate([
            'submissionFile' => 'nullable|file|max:20480', // Max 20MB
            'submissionText' => 'nullable|string',
        ]);

        if (! $this->submissionFile && empty($this->submissionText)) {
            $this->addError('submissionFile', 'Please upload a file or write a response.');

            return;
        }

        $this->showSubmitConfirmModal = true;
    }

    public function submitAssignment()
    {
        $assignment = Assignment::find($this->selectedAssignmentId);

        // Security Check: Deadline & Lock
        if ($assignment->isOverdue() || $assignment->is_lock) {
            session()->flash('error', 'Submission is closed or overdue.');

            return;
        }

        $path = null;
        $name = null;

        // Handle File Upload
        if ($this->submissionFile) {
            $name = $this->submissionFile->getClientOriginalName();
            // Store dengan nama acak (hashed) agar aman dari eksekusi script berbahaya
            $path = $this->submissionFile->store('submissions', 'public');
        }

        // Tentukan Status (Late/Submitted)
        $status = Carbon::now()->gt($assignment->due_date) ? 'late' : 'submitted';

        Submission::updateOrCreate(
            ['assignment_id' => $assignment->id, 'user_id' => Auth::id()],
            [
                'file_path' => $path,
                'file_name' => $name,
                'text_content' => $this->submissionText, // Simpan text mentah juga
                'submitted_at' => Carbon::now(),
                'status' => $status,
            ]
        );

        $this->showSubmitConfirmModal = false;
        $this->reset(['submissionFile', 'submissionText']);
        session()->flash('message', 'Assignment submitted successfully!');
    }

    // --- DOWNLOAD LOGIC ---

    // Download Text sebagai .txt (Fitur Request)
    public function downloadTextSubmission($submissionId)
    {
        $submission = Submission::find($submissionId);

        if ($submission && $submission->text_content) {
            $content = 'Student Name: '.$submission->user->name."\n";
            $content .= 'Submitted At: '.$submission->submitted_at."\n";
            $content .= "------------------------------------------------\n\n";
            $content .= $submission->text_content;

            $fileName = 'text_submission_'.Str::slug($submission->user->name).'.txt';

            return response()->streamDownload(function () use ($content) {
                echo $content;
            }, $fileName);
        }
    }

    // Download Attachment Siswa
    public function downloadSubmissionFile($submissionId)
    {
        $submission = Submission::find($submissionId);
        if ($submission && $submission->file_path) {
            return Storage::disk('public')->download($submission->file_path, $submission->file_name);
        }
    }

    private function resetAssessmentForm()
    {
        $this->reset(['newAssessTitle', 'newAssessDesc', 'newAssessFile', 'newAssessDue', 'submissionFile', 'submissionText', 'showSubmitConfirmModal']);
    }
}
