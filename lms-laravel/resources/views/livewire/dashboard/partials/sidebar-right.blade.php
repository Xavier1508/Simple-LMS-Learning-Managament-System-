{{-- 1. ANNOUNCEMENTS --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-5 border-b border-gray-100 bg-gray-50">
        <h3 class="font-bold text-gray-800 text-sm uppercase tracking-wide">Announcements</h3>
    </div>
    <div class="divide-y divide-gray-100">
        @foreach($announcements as $ann)
            <div class="p-5 hover:bg-gray-50 transition cursor-pointer">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center {{ $ann['color'] }}">
                        {{-- Icon Dynamic --}}
                        @if($ann['icon'] == 'server') <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>
                        @elseif($ann['icon'] == 'calendar') <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        @else <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        @endif
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-800 leading-tight">{{ $ann['title'] }}</h4>
                        <p class="text-xs text-gray-400 mt-1">{{ $ann['date'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- 2. UPCOMING TASKS (STUDENT) OR GRADING (LECTURER) --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h3 class="font-bold text-gray-800 text-sm uppercase tracking-wide">
            {{ $role === 'student' ? 'Upcoming Deadlines' : 'Recent Assessments' }}
        </h3>
        <a href="{{ route('assessment') }}" class="text-xs font-bold text-orange-600 hover:underline">View All</a>
    </div>

    <div class="divide-y divide-gray-100">
        @php
            $tasks = $role === 'student' ? $upcoming_tasks : $tasks_to_grade;
        @endphp

        @forelse($tasks as $task)
            <a href="{{ route('courses.detail', ['id' => $task->course_class_id, 'tab' => 'assessment']) }}" class="block p-5 hover:bg-gray-50 transition">
                <div class="flex justify-between items-start mb-1">
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-gray-100 text-gray-600 border border-gray-200">
                        {{ $task->class->course->code }}
                    </span>
                    @if($role === 'student')
                        <span class="text-[10px] font-bold text-red-500">
                            {{ $task->due_date->format('d M') }}
                        </span>
                    @endif
                </div>
                <h4 class="text-sm font-bold text-gray-800 mb-1 line-clamp-1">{{ $task->title }}</h4>

                @if($role === 'student')
                    <p class="text-xs text-gray-500">Due {{ $task->due_date->diffForHumans() }}</p>
                @else
                    <p class="text-xs text-gray-500">{{ $task->submissions->count() }} Submissions</p>
                @endif
            </a>
        @empty
            <div class="p-8 text-center text-gray-400 text-xs">
                No active tasks found.
            </div>
        @endforelse
    </div>
</div>
