<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @if($role === 'student')
        {{-- GPA Card --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 relative overflow-hidden group hover:shadow-md transition">
            <div class="absolute right-0 top-0 w-24 h-24 bg-orange-50 rounded-bl-full -mr-4 -mt-4 transition group-hover:scale-110"></div>
            <div class="relative z-10">
                <div class="w-10 h-10 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                </div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Current GPA</p>
                <h3 class="text-3xl font-extrabold text-gray-800 mt-1">{{ $gpa }}</h3>
                <p class="text-xs text-gray-400 mt-1">{{ $total_sks }} Credits Taken</p>
            </div>
        </div>

        {{-- Courses Card --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 relative overflow-hidden group hover:shadow-md transition">
            <div class="absolute right-0 top-0 w-24 h-24 bg-blue-50 rounded-bl-full -mr-4 -mt-4 transition group-hover:scale-110"></div>
            <div class="relative z-10">
                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Active Courses</p>
                <h3 class="text-3xl font-extrabold text-gray-800 mt-1">{{ $active_courses }}</h3>
                <p class="text-xs text-gray-400 mt-1">Subjects this semester</p>
            </div>
        </div>

        {{-- Tasks Card --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 relative overflow-hidden group hover:shadow-md transition">
            <div class="absolute right-0 top-0 w-24 h-24 bg-red-50 rounded-bl-full -mr-4 -mt-4 transition group-hover:scale-110"></div>
            <div class="relative z-10">
                <div class="w-10 h-10 bg-red-100 text-red-600 rounded-xl flex items-center justify-center mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Pending Tasks</p>
                <h3 class="text-3xl font-extrabold text-gray-800 mt-1">{{ $pending_tasks_count }}</h3>
                <p class="text-xs text-gray-400 mt-1">Assignments Due</p>
            </div>
        </div>
    @else
        {{-- LECTURER STATS --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 flex flex-col">
            <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Total Students</p>
            <h3 class="text-3xl font-extrabold text-gray-800 mt-1">{{ $total_students }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 flex flex-col">
            <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Active Classes</p>
            <h3 class="text-3xl font-extrabold text-gray-800 mt-1">{{ $active_classes }}</h3>
        </div>
    @endif
</div>
