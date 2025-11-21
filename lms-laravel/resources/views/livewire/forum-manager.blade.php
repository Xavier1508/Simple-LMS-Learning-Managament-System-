<div class="flex-1 p-8 bg-gray-50 min-h-screen">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Forum Discussions</h1>

        {{-- Tombol ini bisa diarahkan ke halaman Course dulu karena create thread butuh konteks Session --}}
        <a href="{{ route('courses') }}" class="bg-orange-500 text-white font-bold py-2 px-5 rounded-lg hover:bg-orange-600 transition duration-150 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Go to Course to Post</span>
        </a>
    </div>

    {{-- Navigasi Tabs Filter --}}
    <div class="border-b border-gray-200 mb-6">
        <nav class="flex -mb-px space-x-6">
            <button wire:click="setFilter('all')"
                class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition-colors duration-150
                {{ $filter === 'all' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                All Discussions
            </button>
            <button wire:click="setFilter('my_threads')"
                class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition-colors duration-150
                {{ $filter === 'my_threads' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                My Threads
            </button>
        </nav>
    </div>

    {{-- List Thread --}}
    <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
        <ul class="divide-y divide-gray-100">
            @forelse ($threads as $thread)
                <li class="p-5 hover:bg-gray-50 transition duration-150 cursor-pointer group">
                    {{-- Link ke Detail Thread di dalam Course --}}
                    <a href="{{ route('courses.detail', ['id' => $thread->session->course_class_id, 'tab' => 'forum']) }}" class="block">
                        <div class="flex items-center justify-between">
                            <div class="flex items-start space-x-4">
                                {{-- Avatar --}}
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold border border-gray-200">
                                        {{ substr($thread->user->first_name, 0, 1) }}{{ substr($thread->user->last_name, 0, 1) }}
                                    </div>
                                </div>

                                {{-- Info Thread --}}
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        {{-- Badge Course Code --}}
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded border bg-blue-50 text-blue-600 border-blue-100">
                                            {{ $thread->session->class->course->code }}
                                        </span>
                                        <span class="text-xs text-gray-400">• Session {{ $thread->session->session_number }}</span>

                                        @if($thread->is_assessment)
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded border bg-red-50 text-red-600 border-red-100 uppercase">
                                                Assessment
                                            </span>
                                        @endif
                                    </div>

                                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-orange-600 transition-colors mb-1">
                                        {{ $thread->title }}
                                    </h3>

                                    <p class="text-xs text-gray-500">
                                        Started by <span class="font-semibold text-gray-700">{{ $thread->user->first_name }} {{ $thread->user->last_name }}</span>
                                        <span class="mx-1">•</span>
                                        {{ $thread->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>

                            {{-- Stats --}}
                            <div class="flex items-center space-x-6 text-sm text-gray-400">
                                <div class="flex items-center space-x-1" title="Replies">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                    <span class="font-medium">{{ $thread->posts->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
            @empty
                <div class="p-12 text-center">
                    <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                    </div>
                    <h3 class="text-gray-900 font-medium">No discussions found.</h3>
                    <p class="text-gray-500 text-sm mt-1">Check back later or start a new discussion in your course.</p>
                </div>
            @endforelse
        </ul>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $threads->links() }}
    </div>
</div>
