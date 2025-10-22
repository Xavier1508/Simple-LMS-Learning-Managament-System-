<x-app-layout>
    @php
        $gradeData = [
            [
                'id' => 'COMP6873', 'course' => 'Blockchain Fundamental', 'section' => 'LA07', 'credits' => 3, 'score' => 92.5, 'grade' => 'A',
                'details' => [
                    ['component' => 'Midterm Test', 'weight' => '30%', 'score' => 95],
                    ['component' => 'Final Project', 'weight' => '35%', 'score' => 90],
                    ['component' => 'AOL', 'weight' => '10%', 'score' => 98],
                    ['component' => 'Assignments', 'weight' => '15%', 'score' => 85],
                    ['component' => 'Quizzes', 'weight' => '10%', 'score' => 99],
                ]
            ],
            [
                'id' => 'COMP6062', 'course' => 'Compilation Techniques', 'section' => 'LA07', 'credits' => 4, 'score' => 88.0, 'grade' => 'B+',
                'details' => [
                    ['component' => 'Midterm Test', 'weight' => '30%', 'score' => 85],
                    ['component' => 'Final Project', 'weight' => '35%', 'score' => 92],
                    ['component' => 'AOL', 'weight' => '10%', 'score' => 80],
                    ['component' => 'Assignments', 'weight' => '15%', 'score' => 90],
                    ['component' => 'Quizzes', 'weight' => '10%', 'score' => 95],
                ]
            ],
            [
                'id' => 'COMP6646', 'course' => 'Computer Forensic', 'section' => 'LA07', 'credits' => 3, 'score' => 75.2, 'grade' => 'C',
                'details' => [
                    ['component' => 'Midterm Test', 'weight' => '30%', 'score' => 68],
                    ['component' => 'Final Project', 'weight' => '35%', 'score' => 78],
                    ['component' => 'AOL', 'weight' => '10%', 'score' => 80],
                    ['component' => 'Assignments', 'weight' => '15%', 'score' => 75],
                    ['component' => 'Quizzes', 'weight' => '10%', 'score' => 72],
                ]
            ],
        ];

        // Style untuk grade, agar bisa dipakai di @class
        $gradeClasses = [
            'A' => 'grade-A',
            'B' => 'grade-B',
            'C' => 'grade-C',
        ];
    @endphp

    <style>
        .grade-table th, .grade-table td { padding: 12px 16px; text-align: left; }
        .grade-table th { font-weight: 600; color: #374151; background-color: #f9fafb; }
        .grade-A { color: #10B981; font-weight: 600; }
        .grade-B { color: #F59E0B; font-weight: 600; }
        .grade-C { color: #EF4444; font-weight: 600; }
    </style>

    <main class="flex-1 p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Gradebook</h1>

        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-6 mb-8">
            <div class="flex items-center space-x-4 text-sm">
                <label class="text-gray-600 font-medium">Semester View</label>
                <div class="relative">
                    <select class="appearance-none bg-white border border-gray-300 rounded-md py-2 pl-3 pr-10 focus:outline-none focus:ring-orange-500 focus:border-orange-500 cursor-pointer">
                        <option>2025, Odd Semester</option>
                        <option>2024, Even Semester</option>
                    </select>
                    <span data-lucide="chevron-down" class="w-4 h-4 absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 pointer-events-none"></span>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4">
                <div class="bg-white p-4 rounded-lg shadow-md border border-gray-200 flex items-center space-x-3 w-full">
                    <span data-lucide="zap" class="w-6 h-6 text-orange-600"></span>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Total Credits Taken</p>
                        <p class="text-xl font-bold text-gray-900">17</p>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md border border-gray-200 flex items-center space-x-3 w-full">
                    <span data-lucide="award" class="w-6 h-6 text-emerald-600"></span>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Semester GPA</p>
                        <p class="text-xl font-bold text-emerald-600">3.74</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-xl overflow-x-auto">
            <table class="grade-table w-full text-sm text-gray-700">
                <thead class="text-xs uppercase">
                    <tr>
                        <th class="rounded-tl-lg">Course Name</th>
                        <th>ID</th>
                        <th>Section</th>
                        <th>Credits</th>
                        <th>Final Score</th>
                        <th class="rounded-tr-lg">Letter Grade</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($gradeData as $course)
                        <tr id="row-{{ $course['id'] }}" class="hover:bg-gray-50 transition duration-100 cursor-pointer" onclick="toggleDetails('{{ $course['id'] }}')">
                            <td class="font-medium text-gray-900">{{ $course['course'] }}</td>
                            <td>{{ $course['id'] }}</td>
                            <td>{{ $course['section'] }}</td>
                            <td>{{ $course['credits'] }}</td>
                            <td>{{ $course['score'] }}</td>
                            <td class="flex justify-between items-center pr-4">
                                <span @class($gradeClasses[substr($course['grade'], 0, 1)])>{{ $course['grade'] }}</span>
                                <span data-lucide="chevron-down" class="w-4 h-4 text-gray-400 transform transition-transform duration-300" id="icon-{{ $course['id'] }}"></span>
                            </td>
                        </tr>
                        <tr id="details-{{ $course['id'] }}" class="hidden border-b border-gray-100">
                            <td colspan="6" class="p-0">
                                <div class="px-6 py-4 bg-gray-50">
                                    <h4 class="text-sm font-semibold mb-3 text-gray-700 border-b pb-2">Assessment Breakdown:</h4>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                                        @foreach ($course['details'] as $detail)
                                            <div class="p-3 bg-white border border-gray-200 rounded-lg shadow-sm">
                                                <p class="text-xs font-medium text-gray-500">{{ $detail['component'] }}</p>
                                                <p class="text-xs font-medium text-gray-400 mb-1">Weight: {{ $detail['weight'] }}</p>
                                                <p class="text-xl font-bold text-orange-600">{{ $detail['score'] }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 border-t border-gray-200">
                    <tr>
                        <td colspan="4" class="text-right font-bold text-gray-800">Total GPA (Cumulative):</td>
                        <td colspan="2" class="font-bold text-emerald-600">3.74 / 4.00</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <p class="text-xs text-gray-500 mt-4 px-2">*Grades are calculated based on weighted average and may not reflect manual adjustments.</p>
    </main>

    {{-- Kirim JavaScript khusus untuk halaman ini ke layout utama --}}
    @push('scripts')
        <script>
            function toggleDetails(courseId) {
                const detailRow = document.getElementById(`details-${courseId}`);
                const icon = document.getElementById(`icon-${courseId}`);
                const mainRow = document.getElementById(`row-${courseId}`);

                if (detailRow.classList.contains('hidden')) {
                    detailRow.classList.remove('hidden');
                    icon.classList.add('rotate-180');
                    mainRow.classList.add('bg-gray-100');
                } else {
                    detailRow.classList.add('hidden');
                    icon.classList.remove('rotate-180');
                    mainRow.classList.remove('bg-gray-100');
                }
            }
            lucide.createIcons();
        </script>
    @endpush
</x-app-layout>
