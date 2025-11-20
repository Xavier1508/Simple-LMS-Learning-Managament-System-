<div class="p-8 bg-gray-50 min-h-screen">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Gradebook Overview</h1>

    {{-- ================================================== --}}
    {{-- BAGIAN ATAS: STATISTIK CARDS (BEDA STUDENT/LECTURER) --}}
    {{-- ================================================== --}}

    @if($role === 'student')
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            {{-- Total Courses --}}
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 flex flex-col">
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Total Courses</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['totalCourses'] }}</p>
                <p class="text-xs text-gray-400 mt-1">Active Subjects</p>
            </div>

            {{-- Average Score --}}
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 flex flex-col">
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Avg. Score</p>
                <p class="text-3xl font-bold text-blue-600 mt-2">{{ $stats['avgScore'] }}</p>
                <p class="text-xs text-gray-400 mt-1">Across all subjects</p>
            </div>

            {{-- GPA (IPK) --}}
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 flex flex-col relative overflow-hidden">
                <div class="absolute top-0 right-0 p-2 opacity-10">
                     {{-- Icon Graduation Cap --}}
                     <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3L1 9l11 6 9-4.91V17h2V9M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/></svg>
                </div>
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Semester GPA</p>
                <p class="text-4xl font-bold text-{{ $stats['statusColor'] }}-600 mt-2">
                    {{ $stats['gpa'] }}
                </p>
                <div class="mt-2 inline-flex items-center px-2 py-1 rounded text-[10px] font-bold bg-{{ $stats['statusColor'] }}-100 text-{{ $stats['statusColor'] }}-700 border border-{{ $stats['statusColor'] }}-200">
                    {{ $stats['academicStatus'] }}
                </div>
            </div>
        </div>

    @else
        {{-- LECTURER STATS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-semibold uppercase tracking-wider">Classes Taught</p>
                    <p class="text-4xl font-bold text-gray-800 mt-2">{{ $stats['totalCourses'] }}</p>
                </div>
                <div class="p-4 bg-orange-50 text-orange-600 rounded-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
            </div>
        </div>
    @endif


    {{-- ================================================== --}}
    {{-- BAGIAN TENGAH: COURSE CARDS --}}
    {{-- ================================================== --}}

    <h2 class="text-lg font-bold text-gray-800 mb-4">Grade Report</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
        @foreach ($courses as $course)
            <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-300 border border-gray-200 overflow-hidden relative group flex flex-col h-full">

                {{-- Top Accent --}}
                @php
                    // Cek type untuk warna border (Safe check)
                    $type = $course->type ?? ($course->course->type ?? 'LEC');
                    $isLab = str_contains($type, 'LAB');
                @endphp
                <div class="absolute top-0 left-0 w-full h-1 {{ $isLab ? 'bg-blue-500' : 'bg-orange-500' }}"></div>

                <div class="flex justify-between items-start mb-2">
                    <h2 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-1">
                        {{-- Logic Judul: Prioritaskan property custom, fallback ke relasi --}}
                        {{ $course->title ?? ($course->course->title ?? 'Unknown Title') }}
                    </h2>
                    <span class="text-[10px] font-bold px-2 py-1 rounded border bg-gray-50 text-gray-600 border-gray-200">
                        {{ $type }}
                    </span>
                </div>

                <p class="text-xs text-gray-500 mb-4 font-mono">
                    {{ $course->code ?? ($course->course->code ?? '') }} -
                    {{ $course->class_code ?? '' }}
                </p>

                <div class="border-t border-gray-100 pt-4 mt-auto">
                    @if($role === 'student')
                        {{-- VIEW SISWA --}}
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Final Score</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $course->score ?? 0 }}</p>
                            </div>
                            <div class="text-center bg-gray-50 px-3 py-1 rounded-lg border border-gray-100">
                                <p class="text-xs text-gray-500 uppercase mb-1">Grade</p>
                                <p class="text-xl font-extrabold text-blue-600">{{ $course->grade_letter ?? '-' }}</p>
                            </div>
                        </div>

                        <a wire:navigate href="{{ route('courses.detail', ['id' => $course->id, 'tab' => 'gradebook']) }}" class="block w-full text-center text-xs font-bold text-white bg-blue-600 py-2.5 rounded hover:bg-blue-700 transition shadow-sm">
                            View Detail Grade
                        </a>

                    @else
                        {{-- VIEW DOSEN --}}
                        <div class="flex justify-between items-center mb-4 text-sm">
                             <span class="text-gray-500">Semester</span>
                             <span class="font-medium">{{ $course->semester ?? '-' }}</span>
                        </div>
                        <a wire:navigate href="{{ route('courses.detail', ['id' => $course->id, 'tab' => 'gradebook']) }}" class="block w-full text-center text-xs font-bold text-gray-600 border border-gray-300 py-2 rounded hover:bg-gray-50 transition">
                            Input Grades
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
