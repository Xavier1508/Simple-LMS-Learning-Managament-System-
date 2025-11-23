<div class="p-8 bg-gray-50 min-h-screen">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Assessments</h1>
            <p class="text-sm text-gray-500">Track your tasks and deadlines.</p>
        </div>

        {{-- Filter Tabs --}}
        <div class="bg-white p-1 rounded-lg border border-gray-200 flex shadow-sm">
            <button wire:click="setFilter('upcoming')"
                class="px-6 py-2 rounded-md text-sm font-bold transition {{ $filter === 'upcoming' ? 'bg-orange-500 text-white shadow' : 'text-gray-500 hover:bg-gray-50' }}">
                {{ $role === 'student' ? 'To Do / Upcoming' : 'Active Tasks' }}
            </button>
            <button wire:click="setFilter('history')"
                class="px-6 py-2 rounded-md text-sm font-bold transition {{ $filter === 'history' ? 'bg-orange-500 text-white shadow' : 'text-gray-500 hover:bg-gray-50' }}">
                {{ $role === 'student' ? 'History / Past' : 'Past Deadline' }}
            </button>
        </div>
    </div>

    {{-- Grid Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($assignments as $task)
            @php
                $isOverdue = $task->isOverdue();
                $mySubmission = $role === 'student' ? $task->submissions->first() : null;

                // Logic Status Warna & Text
                $borderColor = 'border-gray-200';
                $statusBadge = '';

                if ($role === 'student') {
                    if ($mySubmission) {
                        $statusClass = $mySubmission->status == 'late' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700';
                        $statusLabel = $mySubmission->status == 'late' ? 'Submitted Late' : 'Submitted';
                        $icon = 'check';
                        $borderColor = 'border-green-200';
                    } elseif ($isOverdue) {
                        $statusClass = 'bg-red-100 text-red-700';
                        $statusLabel = 'Missing';
                        $icon = 'x';
                        $borderColor = 'border-red-200';
                    } else {
                        $statusClass = 'bg-blue-100 text-blue-700';
                        $statusLabel = 'To Do';
                        $icon = 'clock';
                        $borderColor = 'border-blue-200';
                    }
                } else {
                    // Logic Dosen
                    $submissionCount = $task->submissions->count();
                    // Asumsi relasi students ada via class, fallback ke 0 jika null
                    $totalStudents = $task->class->students->count() ?? 0;
                    $statusClass = $isOverdue ? 'bg-gray-100 text-gray-600' : 'bg-green-100 text-green-700';
                    $statusLabel = $isOverdue ? 'Closed' : 'Open';
                }
            @endphp

            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition duration-300 border {{ $borderColor }} overflow-hidden flex flex-col h-full group relative">

                {{-- Top Strip --}}
                <div class="absolute top-0 left-0 w-full h-1 {{ $isOverdue ? 'bg-gray-300' : 'bg-orange-500' }}"></div>

                <div class="p-6 flex-1">
                    {{-- Course Badge & Status --}}
                    <div class="flex justify-between items-start mb-3">
                        <span class="text-[10px] font-bold px-2 py-1 rounded bg-orange-50 text-orange-600 border border-orange-100 uppercase tracking-wider">
                            {{ $task->class->course->code }}
                        </span>

                        <span class="text-[10px] font-bold px-2 py-1 rounded flex items-center gap-1 {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    {{-- Title --}}
                    <h3 class="text-lg font-bold text-gray-800 mb-2 group-hover:text-orange-600 transition line-clamp-2">
                        {{ $task->title }}
                    </h3>

                    {{-- Deadline --}}
                    <div class="flex items-center text-sm text-gray-500 mb-4">
                        <svg class="w-4 h-4 mr-2 {{ $isOverdue ? 'text-red-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="{{ $isOverdue && !$mySubmission ? 'text-red-600 font-bold' : '' }}">
                            Due {{ $task->due_date->format('d M Y, H:i') }}
                        </span>
                    </div>

                    {{-- Description Preview --}}
                    <p class="text-xs text-gray-400 line-clamp-2 mb-4">
                        {{ \Illuminate\Support\Str::limit($task->description, 80) }}
                    </p>
                </div>

                {{-- Footer Action --}}
                <div class="bg-gray-50 p-4 border-t border-gray-100 mt-auto">

                    {{-- [PERBAIKAN UTAMA: LINK DENGAN open_assessment] --}}
                    <a wire:navigate href="{{ route('courses.detail', [
                        'id' => $task->class->id,
                        'tab' => 'assessment',
                        'open_assessment' => $task->id
                    ]) }}" class="block w-full">

                        @if($role === 'student')
                            <div class="text-center text-xs font-bold text-white bg-orange-500 py-2.5 rounded hover:bg-orange-600 transition shadow-sm">
                                {{ $mySubmission ? 'View Submission' : ($isOverdue ? 'View Details' : 'Submit Assignment') }}
                            </div>
                        @else
                            {{-- Dosen Stats --}}
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-xs text-gray-500 font-medium">Submissions</span>
                                <span class="text-xs font-bold text-gray-700">{{ $submissionCount }} Students</span>
                            </div>

                            {{-- Progress Bar Dosen --}}
                            <div class="w-full bg-gray-200 rounded-full h-1.5 mb-3">
                                @php $percent = ($totalStudents > 0) ? ($submissionCount / $totalStudents) * 100 : 0; @endphp
                                <div class="h-1.5 rounded-full bg-blue-500" style="width: {{ $percent }}%"></div>
                            </div>

                            <div class="text-center text-xs font-bold text-gray-600 border border-gray-300 bg-white py-2 rounded hover:bg-gray-50 transition">
                                Grade / Manage
                            </div>
                        @endif
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center">
                <div class="bg-white w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm border border-gray-100">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">No tasks found</h3>
                <p class="text-gray-500 text-sm mt-1">
                    {{ $filter === 'upcoming' ? 'You are all caught up! No pending assignments.' : 'No past assignments history found.' }}
                </p>
            </div>
        @endforelse
    </div>
</div>
