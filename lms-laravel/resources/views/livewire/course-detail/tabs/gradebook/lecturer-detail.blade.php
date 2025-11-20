<div class="bg-gray-50 min-h-screen p-4">
    @php
        $student = \App\Models\User::find($selectedStudentId);
        $components = \App\Models\GradeComponent::where('course_id', $class->course_id)->get();
    @endphp

    {{-- Header Navigation --}}
    <button wire:click="backToGradebookList" class="mb-6 flex items-center text-gray-500 hover:text-gray-900 text-sm font-bold transition">
        <div class="w-8 h-8 rounded-full bg-white shadow flex items-center justify-center mr-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </div>
        Back to Student List
    </button>

    <div class="max-w-2xl mx-auto">
        {{-- Student Info Card --}}
        <div class="bg-white rounded-t-xl p-6 border-b border-gray-100 flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xl">
                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</h2>
                <p class="text-sm text-gray-500">{{ $student->email }}</p>
            </div>
        </div>

        {{-- Input Form --}}
        <div class="bg-white rounded-b-xl shadow-sm border border-gray-200 p-6 space-y-6">
            @if($components->isEmpty())
                <div class="text-center p-6 bg-yellow-50 rounded-lg text-yellow-700 text-sm">
                    Warning: No grading components (Mid/Final/etc) defined for this course yet. Please contact Admin.
                </div>
            @else
                @foreach($components as $comp)
                    <div>
                        <div class="flex justify-between mb-1">
                            <label class="text-sm font-bold text-gray-700 uppercase">{{ $comp->name }}</label>
                            <span class="text-xs font-bold text-gray-400 bg-gray-100 px-2 py-0.5 rounded">{{ $comp->type }} - {{ $comp->weight }}%</span>
                        </div>
                        <div class="relative">
                            <input type="number" step="0.01" min="0" max="100"
                                wire:model="inputGrades.{{ $comp->id }}"
                                class="w-full border border-gray-300 rounded-lg p-3 pl-4 focus:ring-indigo-500 focus:border-indigo-500 font-mono text-lg"
                                placeholder="0 - 100">
                            <div class="absolute right-4 top-3.5 text-gray-400 text-sm font-bold">/ 100</div>
                        </div>
                    </div>
                @endforeach

                <div class="pt-6 border-t border-gray-100 flex justify-end">
                    <button wire:click="saveGrades" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition transform hover:-translate-y-1 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Save Grades
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
