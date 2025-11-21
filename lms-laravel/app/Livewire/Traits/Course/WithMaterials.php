<?php

namespace App\Livewire\Traits\Course;

use App\Models\CourseMaterial;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

trait WithMaterials
{
    // File Upload
    public $newFile;

    public $showUploadModal = false;

    public $uploadingForSessionId = null;

    // Preview File
    public $previewFileUrl = null;

    public $previewFileType = null;

    public $previewFileName = null;

    public $showPreviewModal = false;

    public function openUploadModal($sessionId)
    {
        $this->uploadingForSessionId = $sessionId;
        $this->showUploadModal = true;
    }

    public function saveMaterial()
    {
        $this->validate([
            'newFile' => [
                'required',
                'file',
                'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,jpg,jpeg,png,gif,svg',
                'max:10240', // Max 10MB
                function ($attribute, $value, $fail) {
                    $allowedMimes = [
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.ms-powerpoint',
                        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'text/plain',
                        'image/jpeg', 'image/png', 'image/gif', 'image/svg+xml',
                    ];

                    if (! in_array($value->getMimeType(), $allowedMimes)) {
                        $fail('The file type is not allowed for security reasons.');
                    }
                },
            ],
        ], [
            'newFile.required' => 'Please select a file to upload.',
            'newFile.mimes' => 'Only PDF, Office documents, text files, and images are allowed.',
            'newFile.max' => 'File size must not exceed 10MB.',
        ]);

        $originalName = $this->newFile->getClientOriginalName();
        $sanitizedName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
        $filename = time().'_'.$sanitizedName;

        $path = $this->newFile->storeAs('course_materials', $filename, 'public');

        CourseMaterial::create([
            'course_session_id' => $this->uploadingForSessionId,
            'file_name' => $originalName,
            'file_path' => $path,
            'file_type' => $this->newFile->extension(),
        ]);

        $this->reset(['newFile', 'showUploadModal', 'uploadingForSessionId']);
        session()->flash('message', 'Material uploaded successfully.');
    }

    public function downloadMaterial($materialId)
    {
        $material = CourseMaterial::find($materialId);

        if ($material && Storage::disk('public')->exists($material->file_path)) {
            return Storage::disk('public')->download($material->file_path, $material->file_name);
        } else {
            session()->flash('error', 'File not found on server.');
        }
    }

    public function deleteMaterial($materialId)
    {
        $material = CourseMaterial::find($materialId);
        if ($material && Auth::user()->role === 'lecturer') {
            Storage::disk('public')->delete($material->file_path);
            $material->delete();
            session()->flash('message', 'File deleted successfully.');
        }
    }

    public function previewMaterial($materialId)
    {
        $material = CourseMaterial::find($materialId);

        if ($material) {
            $this->dispatch('open-preview-modal',
                url: Storage::url($material->file_path),
                type: strtolower($material->file_type),
                name: $material->file_name
            );
        }
    }

    public function getFileIcon($fileType)
    {
        $fileType = strtolower($fileType);
        // (Icon SVG codingan tetap sama, saya ringkas disini agar tidak kepanjangan)
        // Pastikan Anda copy array icons yang panjang tadi kesini.
        // ... LOGIKA ICON DISINI ...

        // SAYA COPYKAN BAGIAN INI AGAR ANDA TIDAK BINGUNG:
        $icons = [
            'pdf' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M4 18h12V6h-4V2H4v16zm-2 1V0h12l4 4v16H2v-1z"/></svg>',
            'doc' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M4 2h8l4 4v10a2 2 0 01-2 2H4a2 2 0 01-2-2V4c0-1.1.9-2 2-2z"/></svg>',
            'docx' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M4 2h8l4 4v10a2 2 0 01-2 2H4a2 2 0 01-2-2V4c0-1.1.9-2 2-2z"/></svg>',
            'ppt' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/></svg>',
            'pptx' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/></svg>',
            'xls' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm2 1v10h10V5H5zm2 2h2v2H7V7zm4 0h2v2h-2V7zm-4 4h2v2H7v-2zm4 0h2v2h-2v-2z"/></svg>',
            'xlsx' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm2 1v10h10V5H5zm2 2h2v2H7V7zm4 0h2v2h-2V7zm-4 4h2v2H7v-2zm4 0h2v2h-2v-2z"/></svg>',
            'jpg' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/></svg>',
            'jpeg' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/></svg>',
            'png' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/></svg>',
            'gif' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/></svg>',
            'txt' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>',
        ];

        return $icons[$fileType] ?? '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>';
    }
}
