<div class="p-8 bg-gray-50 min-h-screen">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Attendance Overview</h1>

    {{-- ================================================== --}}
    {{-- BAGIAN ATAS: STATISTIK CARDS (BEDA STUDENT/LECTURER) --}}
    {{-- ================================================== --}}

    @if($role === 'student')
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
            {{-- Total Courses --}}
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 flex flex-col">
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Total Courses</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['totalCourses'] }}</p>
                <p class="text-xs text-gray-400 mt-1">Enrolled this semester</p>
            </div>

            {{-- Total Sessions --}}
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 flex flex-col">
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Total Sessions</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['totalSessions'] }}</p>
                <p class="text-xs text-gray-400 mt-1">Across all courses</p>
            </div>

            {{-- Sessions Attended --}}
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 flex flex-col">
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Sessions Attended</p>
                <p class="text-3xl font-bold text-emerald-600 mt-2">{{ $stats['totalAttended'] }}</p>
                <p class="text-xs text-gray-400 mt-1">Successfully checked in</p>
            </div>

            {{-- Late/Absent --}}
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 flex flex-col">
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Sessions Late/Absent</p>
                <p class="text-3xl font-bold text-red-600 mt-2">{{ $stats['totalLateAbsent'] }}</p>
                <p class="text-xs text-gray-400 mt-1">Missed check-ins</p>
            </div>

            {{-- Attendance Rate (Smart Logic) --}}
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 flex flex-col relative overflow-hidden">
                <div class="absolute top-0 right-0 p-2 opacity-10">
                    <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20"><path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path><path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path></svg>
                </div>
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Attendance Rate</p>
                <p class="text-3xl font-bold text-{{ $stats['rateStatus']['color'] }}-600 mt-2">
                    {{ $stats['attendanceRate'] }}%
                </p>
                <div class="mt-2 inline-flex items-center px-2 py-1 rounded text-[10px] font-bold bg-{{ $stats['rateStatus']['bg'] }}-100 text-{{ $stats['rateStatus']['color'] }}-700 border border-{{ $stats['rateStatus']['bg'] }}-200">
                    {{ $stats['rateStatus']['text'] }}
                </div>
            </div>
        </div>

    @else
        {{-- LECTURER STATS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-semibold uppercase tracking-wider">Total Courses Taught</p>
                    <p class="text-4xl font-bold text-gray-800 mt-2">{{ $stats['totalCourses'] }}</p>
                </div>
                <div class="p-4 bg-blue-50 text-blue-600 rounded-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-semibold uppercase tracking-wider">Total Sessions Managed</p>
                    <p class="text-4xl font-bold text-gray-800 mt-2">{{ $stats['totalSessionsToTeach'] }}</p>
                </div>
                <div class="p-4 bg-orange-50 text-orange-600 rounded-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>
    @endif


    {{-- ================================================== --}}
    {{-- BAGIAN TENGAH: COURSE CARDS (MIRIP PAGE COURSE) --}}
    {{-- ================================================== --}}

    <h2 class="text-lg font-bold text-gray-800 mb-4">My Course Attendance</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
        @foreach ($courses as $courseClass)
            {{-- Link ke Course Detail Tab Attendance --}}
            <a wire:navigate href="{{ route('courses.detail', ['id' => $courseClass->id, 'tab' => 'attendance']) }}"
               class="bg-white p-6 rounded-lg shadow hover:shadow-lg hover:-translate-y-1 transition duration-300 border border-gray-200 cursor-pointer group relative overflow-hidden">

                {{-- Top Accent --}}
                <div class="absolute top-0 left-0 w-full h-1 {{ $courseClass->type == 'LAB' ? 'bg-blue-500' : 'bg-orange-500' }}"></div>

                <div class="flex justify-between items-start mb-2">
                    <h2 class="text-lg font-semibold text-gray-900 group-hover:text-orange-600 transition-colors line-clamp-1">
                        {{ $courseClass->course->title }}
                    </h2>
                    <span class="text-[10px] font-bold px-2 py-1 rounded border {{ $courseClass->type == 'LAB' ? 'bg-blue-50 text-blue-600 border-blue-100' : 'bg-orange-50 text-orange-600 border-orange-100' }}">
                        {{ $courseClass->type }}
                    </span>
                </div>

                <p class="text-xs text-gray-500 mb-4 font-mono">{{ $courseClass->course->code }} - {{ $courseClass->class_code }}</p>

                <div class="border-t border-gray-100 pt-4 mt-2">
                    @if($role === 'student')
                        @php
                            // Hitung Attendance Per Course
                            $attended = $courseClass->sessions->flatMap->attendances
                                ->where('user_id', Auth::id())
                                ->where('status', 'present')
                                ->count();
                            $total = $courseClass->sessions->count();
                            $percent = $total > 0 ? round(($attended/$total)*100) : 0;
                        @endphp
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 font-medium">{{ $attended }} of {{ $total }} Sessions Attended</span>
                            <span class="font-bold {{ $percent < 50 ? 'text-red-500' : 'text-green-600' }}">{{ $percent }}%</span>
                        </div>
                        {{-- Progress Bar Kecil --}}
                        <div class="w-full bg-gray-100 rounded-full h-1.5 mt-2">
                            <div class="bg-orange-500 h-1.5 rounded-full" style="width: {{ $percent }}%"></div>
                        </div>
                    @else
                        {{-- Tampilan Dosen: Last Session Attendance --}}
                        @php
                            // Ambil session terakhir yg sudah lewat
                            $lastSession = $courseClass->sessions->where('start_time', '<=', \Carbon\Carbon::now())->sortByDesc('start_time')->first();
                            $presentCount = $lastSession ? $lastSession->attendances->where('status', 'present')->count() : 0;
                            $totalStudent = $courseClass->students->count();
                        @endphp
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Last Session Attendance</p>
                        <div class="flex items-center text-sm font-medium text-gray-800">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            {{ $presentCount }} of {{ $totalStudent }} Student Attended
                        </div>
                    @endif
                </div>
            </a>
        @endforeach
    </div>


    {{-- ================================================== --}}
    {{-- BAGIAN BAWAH: DETAILED LOG TABLE (REALTIME LOG) --}}
    {{-- ================================================== --}}

    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h2 class="text-lg font-bold text-gray-800">Detailed Attendance Log</h2>
            <span class="text-xs text-gray-500 italic">Realtime updates</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-white border-b border-gray-200 text-gray-500 uppercase text-xs tracking-wider">
                        <th class="px-6 py-4 font-semibold">Date</th>
                        <th class="px-6 py-4 font-semibold">Time</th>
                        <th class="px-6 py-4 font-semibold">Course Code</th>
                        <th class="px-6 py-4 font-semibold">Class</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        {{-- Kolom Remarks hanya untuk siswa --}}
                        @if($role === 'student')
                            <th class="px-6 py-4 font-semibold text-right">Remarks</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($logs as $log)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $log->date }}</td>
                            <td class="px-6 py-4 text-gray-600 font-mono text-xs">
                                {{ $log->start_time }} - {{ $log->end_time }}
                            </td>
                            <td class="px-6 py-4 text-gray-800">{{ $log->course_code }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $log->class_code }}</td>

                            {{-- STATUS BADGE --}}
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $log->css_class }}">
                                    {{ $log->status }}
                                </span>
                            </td>

                            @if($role === 'student')
                                <td class="px-6 py-4 text-right text-gray-500 italic">
                                    {{ $log->remark }}
                                </td>
                            @endif
                        </tr>
                    @endforeach

                    @if($logs->isEmpty())
                        <tr>
                            <td colspan="{{ $role === 'student' ? 6 : 5 }}" class="px-6 py-12 text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                No attendance logs available yet.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
