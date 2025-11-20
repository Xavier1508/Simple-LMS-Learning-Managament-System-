<div>
    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Student Grades</h2>
        <div class="flex items-center gap-2">
             {{-- Pastikan $class ada. Jika dari CourseDetail, ini harusnya aman --}}
             <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full">Total Students: {{ $class->students->count() }}</span>
        </div>
    </div>

    {{-- Student Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($class->students as $student)
            @php
                // PERBAIKAN: Pastikan akses $class->course_id aman
                // Gunakan $this->courseClassId jika $class bermasalah di scope PHP murni
                $currentCourseId = $class->course_id;

                $calc = $this->calculateTotalScore($student->id, $currentCourseId);
                $score = $calc['score'];
                $grade = $this->getGradeLetter($score);
                $color = $this->getGradeColor($grade);

                $totalComp = \App\Models\GradeComponent::where('course_id', $currentCourseId)->count();
                $filledComp = \App\Models\StudentGrade::where('user_id', $student->id)
                    ->whereHas('component', fn($q) => $q->where('course_id', $currentCourseId))
                    ->count();
                $progress = $totalComp > 0 ? ($filledComp / $totalComp) * 100 : 0;
            @endphp

            <div wire:click="openStudentGrade({{ $student->id }})" class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition cursor-pointer group relative overflow-hidden">
                {{-- Progress Bar Top --}}
                <div class="absolute top-0 left-0 h-1 bg-gray-100 w-full">
                    <div class="h-full bg-indigo-500" style="width: {{ $progress }}%"></div>
                </div>

                <div class="flex justify-between items-start mt-2">
                    <div class="flex gap-3">
                         {{-- Avatar --}}
                         <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold border-2 border-white shadow-sm">
                            {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 group-hover:text-indigo-600 transition">{{ $student->first_name }} {{ $student->last_name }}</h4>
                            <p class="text-xs text-gray-500">{{ $student->email }}</p>
                            <div class="mt-1 text-[10px] text-gray-400">
                                {{ $filledComp }} / {{ $totalComp }} Fields Filled
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <div class="text-2xl font-bold text-gray-900">{{ $score > 0 ? $score : '-' }}</div>
                        @if($score > 0)
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold {{ $color }}">
                                Grade {{ $grade }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
