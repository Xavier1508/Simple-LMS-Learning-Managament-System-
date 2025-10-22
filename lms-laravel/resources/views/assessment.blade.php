<x-app-layout>
    {{-- Data statis untuk daftar tugas --}}
    @php
        $assessments = [
            'upcoming' => [
                ['course' => 'COMP6873: Blockchain', 'title' => 'Final Project - Whitepaper', 'due' => \Carbon\Carbon::now()->addDays(7), 'type' => 'AOL'],
                ['course' => 'COMP6062: Compilation', 'title' => 'Lexical Analyzer Implementation', 'due' => \Carbon\Carbon::now()->addDays(12), 'type' => 'Final Report'],
                ['course' => 'COMP6646: Forensic', 'title' => 'Case Analysis Report', 'due' => \Carbon\Carbon::now()->addDays(20), 'type' => 'Final Report'],
            ],
            'completed' => [
                ['course' => 'COMP6873: Blockchain', 'title' => 'Midterm Test', 'due' => \Carbon\Carbon::now()->subDays(30), 'status' => 'Graded', 'score' => '95/100'],
                ['course' => 'COMP6062: Compilation', 'title' => 'Quiz 1', 'due' => \Carbon\Carbon::now()->subDays(45), 'status' => 'Graded', 'score' => '88/100'],
            ]
        ];
    @endphp

    <main class="flex-1 p-8" x-data="{ tab: 'upcoming' }">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Assessment</h1>

        {{-- Navigasi Tabs menggunakan Alpine.js --}}
        <div class="border-b border-gray-200 mb-6">
            <nav class="flex -mb-px space-x-6">
                <button
                    @click="tab = 'upcoming'"
                    :class="{
                        'border-orange-500 text-orange-600': tab === 'upcoming',
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'upcoming'
                    }"
                    class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition-colors duration-150"
                >
                    Upcoming ({{ count($assessments['upcoming']) }})
                </button>
                <button
                    @click="tab = 'completed'"
                    :class="{
                        'border-orange-500 text-orange-600': tab === 'completed',
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'completed'
                    }"
                    class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition-colors duration-150"
                >
                    Completed ({{ count($assessments['completed']) }})
                </button>
            </nav>
        </div>

        {{-- Konten Tab "Upcoming" --}}
        <div x-show="tab === 'upcoming'" class="space-y-4">
            @forelse ($assessments['upcoming'] as $task)
                <div class="bg-white rounded-lg shadow-md border border-gray-100 p-5 flex items-center justify-between transition-all hover:shadow-lg">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0 bg-orange-100 rounded-full p-3 flex items-center justify-center">
                            <span data-lucide="file-text" class="w-5 h-5 text-orange-600"></span>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-orange-600">{{ $task['course'] }}</p>
                            <h3 class="text-lg font-bold text-gray-900">{{ $task['title'] }}</h3>
                            <p class="text-sm text-red-600 font-medium">
                                <span data-lucide="alert-circle" class="w-4 h-4 inline-block -mt-px mr-1"></span>
                                Due {{ $task['due']->format('M d, Y') }} ({{ $task['due']->diffForHumans(null, false, true) }})
                            </p>
                        </div>
                    </div>
                    <button class="bg-orange-500 text-white font-bold py-2 px-5 rounded-lg hover:bg-orange-600 transition duration-150">
                        Submit
                    </button>
                </div>
            @empty
                <div class="text-center text-gray-500 p-10 bg-white rounded-lg shadow">
                    <span data-lucide="check-circle" class="w-12 h-12 mx-auto text-green-500 mb-2"></span>
                    No upcoming assessments. You're all caught up!
                </div>
            @endforelse
        </div>

        {{-- Konten Tab "Completed" --}}
        <div x-show="tab === 'completed'" class="space-y-4" style="display: none;">
            @foreach ($assessments['completed'] as $task)
                <div class="bg-white rounded-lg shadow-md border border-gray-100 p-5 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0 bg-green-100 rounded-full p-3 flex items-center justify-center">
                            <span data-lucide="check" class="w-5 h-5 text-green-600"></span>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500">{{ $task['course'] }}</p>
                            <h3 class="text-lg font-bold text-gray-900">{{ $task['title'] }}</h3>
                            <p class="text-sm text-gray-600">
                                Submitted {{ $task['due']->format('M d, Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">{{ $task['status'] }}</p>
                        <p class="text-xl font-bold text-green-600">{{ $task['score'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </main>

    @push('scripts')
        <script>
            // PERBAIKAN: Bungkus lucide.createIcons() dengan DOMContentLoaded
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                } else {
                    console.error('ERROR: Lucide (assessment) library is not loaded.');
                }
            });
        </script>
    @endpush
</x-app-layout>
