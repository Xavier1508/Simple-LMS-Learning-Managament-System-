<div>
    {{-- Header & Toolbar --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Assessments</h2>
            <p class="text-sm text-gray-500">Manage tasks, projects, and quizzes.</p>
        </div>

        @if(Auth::user()->role === 'lecturer')
            <button wire:click="openCreateAssessment" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2.5 px-6 rounded shadow hover:shadow-md transition transform hover:-translate-y-0.5 text-sm uppercase tracking-wide flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Create New Assessment
            </button>
        @endif
    </div>

    {{-- Assessment Grid --}}
    @php
        $assignments = \App\Models\Assignment::where('course_class_id', $courseClassId)
            ->orderBy('due_date', 'desc')
            ->get();
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($assignments as $assess)
            @php
                $isOverdue = $assess->isOverdue();
                $mySubmission = null;
                if(Auth::user()->role === 'student') {
                    $mySubmission = $assess->submissions->where('user_id', Auth::id())->first();
                }

                $statusColor = 'gray';
                $statusText = 'Assigned';
                $progress = 0;

                if ($mySubmission) {
                    $statusColor = 'green';
                    $statusText = 'Submitted';
                    $progress = 100;
                    if($mySubmission->status === 'late') {
                        $statusColor = 'yellow';
                        $statusText = 'Submitted Late';
                    }
                } elseif ($isOverdue) {
                    $statusColor = 'red';
                    $statusText = 'Missing / Overdue';
                    $progress = 0;
                }
            @endphp

            <div wire:click="openAssessmentDetail({{ $assess->id }})" class="group bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-lg transition duration-300 cursor-pointer overflow-hidden relative flex flex-col h-full">

                {{-- Top Border Accent --}}
                <div class="h-1 w-full {{ $statusColor === 'green' ? 'bg-green-500' : ($statusColor === 'red' ? 'bg-red-500' : 'bg-orange-500') }}"></div>

                <div class="p-6 flex-1">
                    {{-- Header --}}
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 rounded-full {{ $statusColor === 'green' ? 'bg-green-50 text-green-600' : ($statusColor === 'red' ? 'bg-red-50 text-red-600' : 'bg-orange-50 text-orange-600') }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </div>
                        @if($assess->is_lock)
                            <span class="text-xs font-bold text-gray-400 uppercase flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Locked
                            </span>
                        @endif
                    </div>

                    <h3 class="text-lg font-bold text-gray-800 group-hover:text-orange-600 transition-colors line-clamp-2 mb-2">
                        {{ $assess->title }}
                    </h3>

                    <p class="text-xs text-gray-500 mb-4">
                        Due: {{ $assess->due_date->format('d M Y, H:i') }}
                    </p>
                </div>

                {{-- Footer Status (Student Only) --}}
                @if(Auth::user()->role === 'student')
                    <div class="bg-gray-50 p-4 border-t border-gray-100">
                        <div class="flex justify-between items-end mb-1">
                            <span class="text-xs font-bold uppercase text-{{ $statusColor }}-600">{{ $statusText }}</span>
                            <span class="text-xs font-bold text-gray-400">{{ $progress }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                            <div class="h-1.5 rounded-full {{ $statusColor === 'green' ? 'bg-green-500' : ($statusColor === 'red' ? 'bg-red-500' : 'bg-orange-500') }}" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>
                @else
                    {{-- Footer Dosen --}}
                    <div class="bg-gray-50 p-4 border-t border-gray-100 flex justify-between items-center text-xs text-gray-500">
                        <span>{{ $assess->submissions->count() }} Submission(s)</span>
                        <span class="text-orange-600 font-bold hover:underline">View Details &rarr;</span>
                    </div>
                @endif
            </div>
        @empty
            <div class="col-span-full py-20 text-center">
                <div class="bg-gray-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-gray-900 font-medium">No assessments available.</h3>
                <p class="text-gray-500 text-sm">Great! You have no pending tasks.</p>
            </div>
        @endforelse
    </div>
</div>
