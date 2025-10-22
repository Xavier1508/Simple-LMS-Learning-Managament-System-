<x-app-layout>
    {{-- Data statis untuk forum --}}
    @php
        $threads = [
            'all' => [
                ['id' => 1, 'course' => 'COMP6873', 'course_color' => 'bg-blue-100 text-blue-800', 'title' => 'Clarification on Final Project Requirements', 'author' => 'Xavier Talie', 'replies' => 5, 'views' => 45, 'last_reply' => 'Lecturer Ahmad', 'last_reply_at' => '5m ago'],
                ['id' => 2, 'course' => 'COMP6062', 'course_color' => 'bg-orange-100 text-orange-800', 'title' => 'Parser Implementation stuck, need help!', 'author' => 'Budi Santoso', 'replies' => 12, 'views' => 102, 'last_reply' => 'Jane Doe', 'last_reply_at' => '30m ago'],
                ['id' => 3, 'course' => 'Announce', 'course_color' => 'bg-red-100 text-red-800', 'title' => 'Midterm Exam Schedule Update', 'author' => 'Admin Dept.', 'replies' => 0, 'views' => 210, 'last_reply' => null, 'last_reply_at' => '2h ago'],
                ['id' => 4, 'course' => 'COMP6646', 'course_color' => 'bg-green-100 text-green-800', 'title' => 'Tools for Memory Forensics', 'author' => 'Citra Lestari', 'replies' => 3, 'views' => 30, 'last_reply' => 'Xavier Talie', 'last_reply_at' => '1d ago'],
            ],
        ];
    @endphp

    <main class="flex-1 p-8" x-data="{ tab: 'all' }">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Forum</h1>
            <button class="bg-orange-500 text-white font-bold py-2 px-5 rounded-lg hover:bg-orange-600 transition duration-150 flex items-center space-x-2">
                <span data-lucide="plus" class="w-5 h-5"></span>
                <span>Start New Discussion</span>
            </button>
        </div>

        {{-- Navigasi Tabs menggunakan Alpine.js --}}
        <div class="border-b border-gray-200 mb-6">
            <nav class="flex -mb-px space-x-6">
                <button
                    @click="tab = 'all'"
                    :class="{ 'border-orange-500 text-orange-600': tab === 'all', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'all' }"
                    class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition-colors duration-150"
                >
                    All Discussions
                </button>
                <button
                    @click="tab = 'my_courses'"
                    :class="{ 'border-orange-500 text-orange-600': tab === 'my_courses', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'my_courses' }"
                    class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition-colors duration-150"
                >
                    My Courses
                </button>
                <button
                    @click="tab = 'announcements'"
                    :class="{ 'border-orange-500 text-orange-600': tab === 'announcements', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'announcements' }"
                    class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition-colors duration-150"
                >
                    Announcements
                </button>
            </nav>
        </div>

        {{-- Konten Tab --}}
        <div class="bg-white rounded-lg shadow-xl overflow-hidden border border-gray-100">
            <ul class="divide-y divide-gray-200">
                {{-- Tab "All" (Contoh) --}}
                <div x-show="tab === 'all'">
                    @foreach ($threads['all'] as $thread)
                        <li class="p-4 hover:bg-gray-50 transition duration-150 cursor-pointer">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    {{-- Avatar --}}
                                    <img class="w-10 h-10 rounded-full" src="https://placehold.co/100x100/D97706/FFFFFF?text={{ substr($thread['author'], 0, 1) }}" alt="Avatar">

                                    {{-- Info Thread --}}
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm font-bold px-2 py-0.5 rounded-full {{ $thread['course_color'] }}">{{ $thread['course'] }}</span>
                                            <a href="#" class="text-lg font-bold text-gray-900 hover:underline">{{ $thread['title'] }}</a>
                                        </div>
                                        <p class="text-sm text-gray-500 mt-1">
                                            Started by {{ $thread['author'] }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Stats --}}
                                <div class="flex items-center space-x-6 text-sm text-gray-500">
                                    <div class="flex items-center space-x-1" title="Replies">
                                        <span data-lucide="message-square" class="w-4 h-4"></span>
                                        <span>{{ $thread['replies'] }}</span>
                                    </div>
                                    <div class="flex items-center space-x-1" title="Views">
                                        <span data-lucide="eye" class="w-4 h-4"></span>
                                        <span>{{ $thread['views'] }}</span>
                                    </div>
                                Two</div>

                                {{-- Info Balasan Terakhir --}}
                                <div class="w-48 text-sm text-gray-600 text-right">
                                    @if ($thread['last_reply'])
                                        <p class="font-semibold text-gray-900">{{ $thread['last_reply'] }}</p>
                                        <p>{{ $thread['last_reply_at'] }}</p>
                                    @else
                                        <p>{{ $thread['last_reply_at'] }}</p>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @endforeach
                </div>

                {{-- Placeholder untuk tab lain --}}
                <div x-show="tab === 'my_courses'" style="display: none;">
                    <p class="text-center text-gray-500 p-10">Forum untuk mata kuliah Anda akan tampil di sini.</p>
                </div>
                <div x-show="tab === 'announcements'" style="display: none;">
                    <p class="text-center text-gray-500 p-10">Pengumuman penting akan tampil di sini.</p>
                </div>
            </ul>
        </div>
    </main>

    @push('scripts')
        <script>
            // PERBAIKAN: Bungkus lucide.createIcons() dengan DOMContentLoaded
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                } else {
                    console.error('ERROR: Lucide (forum) library is not loaded.');
                }
            });
        </script>
    @endpush
</x-app-layout>
