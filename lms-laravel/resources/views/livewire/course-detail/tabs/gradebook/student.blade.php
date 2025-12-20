@php
    $calc = $this->calculateTotalScore(Auth::id(), $class->course_id);
    $finalScore = $calc['score'];
    $gradeLetter = $this->getGradeLetter($finalScore);
    $components = \App\Models\GradeComponent::where('course_id', $class->course_id)->get();
@endphp

<div>
    <div class="bg-indigo-600 rounded-xl shadow-lg text-white p-6 mb-8 flex flex-col md:flex-row justify-between items-center relative overflow-hidden">
        <div class="relative z-10">
            <h2 class="text-2xl font-bold">Final Score</h2>
            <p class="text-indigo-200 text-xs mt-1">Last updated: {{ \Carbon\Carbon::now()->format('d M Y, H:i') }} GMT+7</p>
        </div>

        <div class="flex items-center gap-8 mt-4 md:mt-0 relative z-10">
            <div class="text-center">
                <p class="text-xs uppercase font-bold text-indigo-300 mb-1">Weight</p>
                <p class="text-xl font-bold">100%</p>
            </div>
            <div class="text-center">
                <p class="text-xs uppercase font-bold text-indigo-300 mb-1">Score</p>
                <p class="text-3xl font-bold">{{ $finalScore > 0 ? $finalScore : 'N/A' }}</p>
            </div>
            <div class="text-center">
                <p class="text-xs uppercase font-bold text-indigo-300 mb-1">Grade</p>
                <p class="text-4xl font-extrabold">{{ $finalScore > 0 ? $gradeLetter : '-' }}</p>
            </div>
        </div>
        <div class="absolute right-0 top-0 h-full w-1/3 bg-white/5 skew-x-12"></div>
    </div>

    {{-- COMPONENT LIST --}}
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
        <div class="divide-y divide-gray-100">
            @foreach($components as $comp)
                @php
                    $myGrade = \App\Models\StudentGrade::where('grade_component_id', $comp->id)
                        ->where('user_id', Auth::id())
                        ->first();
                @endphp
                <div class="p-6 hover:bg-gray-50 transition flex items-center justify-between group">
                    <div>
                        <p class="text-sm font-bold text-gray-800 uppercase tracking-wide">
                            {{ strtoupper($comp->type) }}: {{ $comp->name }}
                        </p>
                        <div class="mt-1 flex items-center gap-2">
                            <span class="text-xs font-medium px-2 py-0.5 rounded bg-gray-100 text-gray-600">Weight: {{ $comp->weight }}%</span>
                            @if(!$myGrade)
                                <span class="text-xs italic text-gray-400">Not graded yet</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        @if($myGrade)
                            <span class="text-xl font-bold text-gray-900">{{ $myGrade->score }}</span>
                        @else
                            <span class="text-lg font-medium text-gray-300">N/A</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if($components->isEmpty())
             <div class="p-8 text-center text-gray-400">
                No grading components defined for this course.
            </div>
        @endif
    </div>
</div>
