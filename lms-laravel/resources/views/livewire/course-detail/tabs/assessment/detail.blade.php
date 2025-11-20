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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- LEFT COLUMN: DETAIL SOAL (Lecturer & Student) --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                {{-- Header --}}
                <div class="p-6 border-b border-gray-100 bg-gray-50">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="text-xs font-bold text-orange-600 uppercase tracking-wider">Assessment Task</span>
                            <h1 class="text-2xl font-bold text-gray-900 mt-1">{{ $assignment->title }}</h1>
                        </div>
                        @if($assignment->is_lock || $assignment->isOverdue())
                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold uppercase border border-red-200">Locked / Closed</span>
                        @else
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold uppercase border border-green-200">Open</span>
                        @endif
                    </div>
                </div>

                {{-- Content --}}
                <div class="p-8">
                    <div class="prose text-gray-700 text-sm max-w-none mb-8">
                        {!! nl2br(e($assignment->description)) !!}
                    </div>

                    {{-- Attachment Soal --}}
                    @if($assignment->attachment_path)
                        <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="bg-white p-2 rounded shadow-sm mr-3 text-blue-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">Attachment</p>
                                    <p class="text-xs text-gray-500">{{ $assignment->attachment_name }}</p>
                                </div>
                            </div>
                            {{-- Note: Logic download file soal dosen bisa ditambahkan nanti --}}
                            <button class="text-blue-600 hover:text-blue-800 text-xs font-bold uppercase">Download</button>
                        </div>
                    @endif
                </div>
            </div>

            {{-- AREA KHUSUS DOSEN: OVERVIEW TABLE --}}
            @if(!$isStudent)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <h3 class="font-bold text-gray-800">Submission Overview</h3>
                        <span class="text-xs text-gray-500">{{ $assignment->submissions->count() }} / {{ $class->students->count() }} Submitted</span>
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
                                                {{-- Download File --}}
                                                @if($sub->file_path)
                                                    <button wire:click="downloadSubmissionFile({{ $sub->id }})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded border border-blue-200" title="Download File">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                    </button>
                                                @endif
                                                {{-- Download Text to TXT --}}
                                                @if($sub->text_content)
                                                    <button wire:click="downloadTextSubmission({{ $sub->id }})" class="p-1.5 text-gray-600 hover:bg-gray-100 rounded border border-gray-300" title="Download Text as .txt">
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

        {{-- RIGHT COLUMN: SUBMISSION AREA / DEADLINE --}}
        <div class="lg:col-span-1 space-y-6">

            {{-- Timer Card --}}
            <div class="bg-orange-500 rounded-xl p-6 text-white shadow-lg">
                <h3 class="text-sm font-bold uppercase tracking-wide mb-2 opacity-80">Deadline</h3>
                <div class="text-2xl font-bold mb-1">{{ $assignment->due_date->format('d M Y') }}</div>
                <div class="text-lg font-mono opacity-90">{{ $assignment->due_date->format('H:i') }} GMT+7</div>

                <div class="mt-6 pt-6 border-t border-white/20">
                    @if($assignment->isOverdue())
                        <div class="text-center bg-white/20 rounded-lg p-3 font-bold">DEADLINE PASSED</div>
                    @else
                        <p class="text-xs text-center opacity-75">Time Remaining</p>
                        {{-- Simple Counter --}}
                        <div class="text-center font-mono text-xl font-bold mt-1">
                            {{ \Carbon\Carbon::now()->diffForHumans($assignment->due_date, true) }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- AREA SUBMISSION SISWA --}}
            @if($isStudent)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-bold text-gray-800 mb-4">Your Submission</h3>

                    @if($submission)
                        {{-- SUDAH SUBMIT (Locked) --}}
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
                    @elseif($assignment->isOverdue() || $assignment->is_lock)
                        {{-- TELAT / DIKUNCI --}}
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                            <p class="text-red-600 font-bold text-sm">Submission Closed</p>
                            <p class="text-red-500 text-xs mt-1">You can no longer submit answers.</p>
                        </div>
                    @else
                        {{-- FORM SUBMIT --}}
                        <div class="space-y-4">
                            {{-- File Upload --}}
                            <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 transition cursor-pointer group">
                                <input type="file" wire:model="submissionFile" class="absolute inset-0 opacity-0 cursor-pointer">
                                <svg class="w-8 h-8 mx-auto text-gray-400 group-hover:text-orange-500 transition mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                <p class="text-sm text-gray-600 font-medium group-hover:text-orange-600">Drag & drop or click</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $submissionFile ? $submissionFile->getClientOriginalName() : 'PDF, Docx, Zip (Max 20MB)' }}
                                </p>
                            </div>
                            @error('submissionFile') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                            {{-- Text Input --}}
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

    {{-- MODAL KONFIRMASI SUBMIT --}}
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
