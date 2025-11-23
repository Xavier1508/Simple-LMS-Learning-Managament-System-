@php
    $assignment = \App\Models\Assignment::find($selectedAssignmentId);
    $isStudent = Auth::user()->role === 'student';
    $submission = $isStudent ? $assignment->submissions->where('user_id', Auth::id())->first() : null;
@endphp

<div class="min-h-screen">
    {{-- Back Button --}}
    <button wire:click="switchToAssessmentList" class="mb-6 flex items-center text-gray-500 hover:text-gray-900 text-sm font-bold transition">
        <div class="w-8 h-8 rounded-full bg-white shadow flex items-center justify-center mr-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </div>
        Back to Assessments
    </button>

    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm animate-fade-in-down" role="alert">
            <p class="font-bold">Success</p>
            <p>{{ session('message') }}</p>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm animate-fade-in-down" role="alert">
            <p class="font-bold">Error</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- LEFT COLUMN --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- LECTURER CONTROL PANEL --}}
            @if(!$isStudent)
                <div class="bg-white rounded-xl shadow-md border border-orange-200 overflow-hidden relative">
                    <div class="absolute top-0 left-0 w-1 h-full bg-orange-500"></div>
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Lecturer Controls
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- 1. LOCK TOGGLE --}}
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 flex flex-col justify-between">
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <label class="text-sm font-bold text-gray-700">Submission Status</label>

                                        @if($assignment->is_lock)
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-red-100 text-red-600 border border-red-200 uppercase tracking-wide">
                                                LOCKED
                                            </span>
                                        @else
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-green-100 text-green-600 border border-green-200 uppercase tracking-wide">
                                                OPEN
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500 mb-4">
                                        Override deadline. If Locked, no one can submit.
                                    </p>
                                </div>

                                {{-- TOMBOL DIPISAH AGAR CLASS JELAS --}}
                                @if($assignment->is_lock)
                                    <button wire:click="toggleLockStatus({{ $assignment->id }})"
                                        class="w-full py-2.5 rounded-lg text-sm font-bold shadow-sm flex items-center justify-center bg-green-600 hover:bg-green-700 text-white transition-colors border border-green-700">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                                        Unlock / Re-open
                                    </button>
                                @else
                                    <button wire:click="toggleLockStatus({{ $assignment->id }})"
                                        class="w-full py-2.5 rounded-lg text-sm font-bold shadow-sm flex items-center justify-center bg-red-600 hover:bg-red-700 text-white transition-colors border border-red-700">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        Lock Submission
                                    </button>
                                @endif
                            </div>

                            {{-- 2. UPDATE DEADLINE --}}
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 flex flex-col justify-between">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Update Deadline</label>
                                    <div class="flex gap-2 mb-1">
                                        <div class="flex-1">
                                            <input type="datetime-local" wire:model="editAssessDue"
                                                class="w-full text-sm border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 p-2">
                                        </div>
                                        <button wire:click="updateDeadline({{ $assignment->id }})" class="bg-blue-600 text-white px-4 rounded-md hover:bg-blue-700 text-sm font-bold transition shadow-sm">
                                            Update
                                        </button>
                                    </div>
                                </div>

                                @error('editAssessDue')
                                    <p class="text-red-500 text-xs font-bold bg-red-50 p-2 rounded border border-red-100 mt-2">
                                        ⚠ {{ $message }}
                                    </p>
                                @enderror

                                @if(!$assignment->is_lock && $assignment->isOverdue())
                                    <p class="text-orange-600 text-xs mt-2 font-bold animate-pulse bg-orange-50 p-2 rounded border border-orange-100">
                                        ⚠ Warning: Status UNLOCKED but deadline passed. Please update deadline to allow submissions.
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- 3. DELETE BUTTON --}}
                        <div class="mt-6 pt-4 border-t border-gray-200 flex justify-between items-center">
                            <p class="text-xs text-gray-400 italic">
                                To delete, assessment must be <strong>Locked</strong> or <strong>Overdue</strong>.
                            </p>

                            @if($assignment->is_lock || $assignment->isOverdue())
                                <button wire:click="deleteAssessment({{ $assignment->id }})"
                                    wire:confirm="Are you sure? All student submissions will be deleted!"
                                    class="text-red-600 hover:text-white border border-red-600 hover:bg-red-600 px-4 py-2 rounded-lg text-sm font-bold transition flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Delete Assessment
                                </button>
                            @else
                                <button disabled class="text-gray-400 border border-gray-300 bg-gray-50 px-4 py-2 rounded-lg text-sm font-bold cursor-not-allowed flex items-center">
                                    <svg class="w-4 h-4 mr-2 grayscale" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Delete (Active)
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- DETAIL CONTENT --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                {{-- Header --}}
                <div class="p-6 border-b border-gray-100 bg-gray-50">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="text-xs font-bold text-orange-600 uppercase tracking-wider">Assessment Task</span>
                            <h1 class="text-2xl font-bold text-gray-900 mt-1">{{ $assignment->title }}</h1>
                        </div>

                        {{-- STATUS BADGE UTAMA --}}
                        @if($assignment->is_lock)
                            <span class="bg-red-600 text-white px-3 py-1 rounded-full text-xs font-bold uppercase border border-red-700 shadow-sm flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Locked
                            </span>
                        @elseif($assignment->isOverdue())
                            <span class="bg-gray-600 text-white px-3 py-1 rounded-full text-xs font-bold uppercase border border-gray-700 shadow-sm flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Closed (Deadline)
                            </span>
                        @else
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold uppercase border border-green-200 animate-pulse flex items-center">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                Open
                            </span>
                        @endif
                    </div>
                </div>

                <div class="p-8">
                    <div class="prose text-gray-700 text-sm max-w-none mb-8">
                        {!! nl2br(e($assignment->description)) !!}
                    </div>

                    @if($assignment->attachment_path)
                        <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 flex items-center justify-between hover:bg-blue-100 transition">
                            <div class="flex items-center">
                                <div class="bg-white p-2 rounded shadow-sm mr-3 text-blue-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">Attachment</p>
                                    <p class="text-xs text-gray-500">{{ $assignment->attachment_name }}</p>
                                </div>
                            </div>
                            <button class="text-blue-600 hover:text-blue-800 text-xs font-bold uppercase">Download</button>
                        </div>
                    @endif
                </div>
            </div>

            {{-- SUBMISSION OVERVIEW (LECTURER ONLY) --}}
            @if(!$isStudent)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <h3 class="font-bold text-gray-800">Submission Overview</h3>
                        <span class="text-xs text-gray-500 font-bold bg-white px-3 py-1 rounded-full border border-gray-200">
                            {{ $assignment->submissions->count() }} / {{ $class->students->count() }} Submitted
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-white text-gray-500 border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4 font-semibold">Student Name</th>
                                    <th class="px-6 py-4 font-semibold">Status</th>
                                    <th class="px-6 py-4 font-semibold">Submitted At</th>
                                    <th class="px-6 py-4 font-semibold text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($class->students as $student)
                                    @php
                                        $sub = $assignment->submissions->where('user_id', $student->id)->first();
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</td>
                                        <td class="px-6 py-4">
                                            @if($sub)
                                                <span class="px-2 py-1 rounded text-xs font-bold {{ $sub->status == 'late' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
                                                    {{ ucfirst($sub->status) }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-xs italic">Not Submitted</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-gray-500 text-xs">
                                            {{ $sub ? $sub->submitted_at->format('d M Y, H:i') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-right flex justify-end gap-2">
                                            @if($sub)
                                                @if($sub->file_path)
                                                    <button wire:click="downloadSubmissionFile({{ $sub->id }})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded border border-blue-200 hover:text-white hover:bg-blue-600 transition" title="Download File">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                    </button>
                                                @endif
                                                @if($sub->text_content)
                                                    <button wire:click="downloadTextSubmission({{ $sub->id }})" class="p-1.5 text-gray-600 hover:bg-gray-100 rounded border border-gray-300 transition" title="Download Text as .txt">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    </button>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        {{-- RIGHT COLUMN --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Timer Card --}}
            <div class="bg-orange-500 rounded-xl p-6 text-white shadow-lg transition-all {{ $assignment->is_lock ? 'grayscale' : '' }}">
                <h3 class="text-sm font-bold uppercase tracking-wide mb-2 opacity-80">Deadline</h3>
                <div class="text-2xl font-bold mb-1">{{ $assignment->due_date->format('d M Y') }}</div>
                <div class="text-lg font-mono opacity-90">{{ $assignment->due_date->format('H:i') }} GMT+7</div>

                <div class="mt-6 pt-6 border-t border-white/20">
                    @if($assignment->is_lock)
                        <div class="text-center bg-black/20 rounded-lg p-3 font-bold flex flex-col items-center">
                            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            SUBMISSION LOCKED
                        </div>
                    @elseif($assignment->isOverdue())
                        <div class="text-center bg-white/20 rounded-lg p-3 font-bold">DEADLINE PASSED</div>
                    @else
                        <p class="text-xs text-center opacity-75">Time Remaining</p>
                        <div class="text-center font-mono text-xl font-bold mt-1">
                            {{ \Carbon\Carbon::now()->diffForHumans($assignment->due_date, true) }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Submission Area (Student) --}}
            @if($isStudent)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-bold text-gray-800 mb-4">Your Submission</h3>

                    @if($submission)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center mb-4">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2 text-green-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <h4 class="text-green-800 font-bold">Submitted</h4>
                            <p class="text-green-600 text-xs">{{ $submission->submitted_at->format('d M Y, H:i') }}</p>
                        </div>
                        @if($submission->file_name)
                            <div class="flex items-center text-sm text-gray-600 bg-gray-50 p-2 rounded border border-gray-200">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span class="truncate">{{ $submission->file_name }}</span>
                            </div>
                        @endif
                    @elseif($assignment->is_lock)
                        <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                            <svg class="w-10 h-10 mx-auto text-red-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            <p class="text-red-600 font-bold text-sm">Assessment Locked</p>
                            <p class="text-red-500 text-xs mt-1">The lecturer has closed submissions for this task.</p>
                        </div>
                    @elseif($assignment->isOverdue())
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center">
                            <svg class="w-10 h-10 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-gray-600 font-bold text-sm">Submission Closed</p>
                            <p class="text-gray-500 text-xs mt-1">The deadline has passed.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 transition cursor-pointer group">
                                <input type="file" wire:model="submissionFile" class="absolute inset-0 opacity-0 cursor-pointer">
                                <svg class="w-8 h-8 mx-auto text-gray-400 group-hover:text-orange-500 transition mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                <p class="text-sm text-gray-600 font-medium group-hover:text-orange-600">Drag & drop or click</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $submissionFile ? $submissionFile->getClientOriginalName() : 'PDF, Docx, Zip (Max 20MB)' }}
                                </p>
                            </div>
                            @error('submissionFile') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                            <textarea wire:model="submissionText" rows="3" placeholder="Add optional text response..." class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-orange-500 focus:border-orange-500"></textarea>

                            <button wire:click="confirmSubmission" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-2.5 rounded-lg shadow transition">
                                SUBMIT ANSWER
                            </button>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    @if($showSubmitConfirmModal)
        <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-fade-in">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm p-6 text-center">
                <div class="w-16 h-16 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Are you sure?</h3>
                <p class="text-sm text-gray-500 mb-6">Once submitted, you cannot edit or unsubmit your work. Make sure your files are correct.</p>

                <div class="flex gap-3 justify-center">
                    <button wire:click="$set('showSubmitConfirmModal', false)" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-bold">Cancel</button>
                    <button wire:click="submitAssignment" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg text-sm font-bold shadow-lg">Yes, Submit</button>
                </div>
            </div>
        </div>
    @endif
</div>
