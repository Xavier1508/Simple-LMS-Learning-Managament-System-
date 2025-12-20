<div>
    {{-- SESSION NAVIGATION --}}
    <div class="flex space-x-2 pb-4 mb-5 mt-4 overflow-x-auto">
        @foreach($class->sessions as $session)
            <button
                wire:click="setActiveSession({{ $session->id }})"
                class="flex-shrink-0 px-4 py-2 rounded-full text-xs font-semibold transition duration-150 border
                {{ $activeSessionId == $session->id
                    ? 'bg-orange-500 text-white shadow-lg border-orange-500 cursor-default'
                    : 'bg-gray-200 text-gray-600 hover:bg-gray-300 border-transparent'
                }}">
                Session {{ $session->session_number }}
            </button>
        @endforeach
    </div>

    @php
        $safeSessionId = $activeSessionId ?? null;

        $currentSession = $class->sessions->firstWhere('id', $safeSessionId);
        if (!$currentSession && $class->sessions->count() > 0) {
            $currentSession = $class->sessions->first();
            $safeSessionId = $currentSession->id;
        }

        $grandTotalPost = 0;
        $totalThreads = 0;

        if ($safeSessionId) {
            $totalThreads = \App\Models\ForumThread::where('course_session_id', $safeSessionId)->count();

            $totalReplies = \App\Models\ForumPost::whereHas('thread', function($q) use ($safeSessionId) {
                $q->where('course_session_id', $safeSessionId);
            })->count();

            $grandTotalPost = $totalThreads + $totalReplies;
        }
    @endphp

    @if($currentSession)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8 animate-fade-in-down">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xl font-bold text-gray-800">{{ $currentSession->title }}</h2>
            <p class="text-sm text-gray-500 mt-1">Delivery: <span class="font-medium text-gray-700">{{ $currentSession->delivery_mode }}</span></p>
        </div>

        {{-- Area Informasi (Background Abu) --}}
        <div class="p-6 bg-gray-50">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide font-bold mb-1">Start Time</p>
                    <p class="font-medium text-gray-800">{{ $currentSession->start_time ? $currentSession->start_time->format('d M Y, H:i') : '-' }} GMT+7</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide font-bold mb-1">End Time</p>
                    <p class="font-medium text-gray-800">{{ $currentSession->end_time ? $currentSession->end_time->format('d M Y, H:i') : '-' }} GMT+7</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wide font-bold mb-1">Total Post</p>
                    <p class="font-bold text-gray-900 text-lg">{{ $grandTotalPost }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- TOOLBAR (CREATE & FILTER) --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">

        {{-- Tombol Create --}}
        <button wire:click="switchToCreateThread" class="w-full md:w-auto bg-orange-500 hover:bg-orange-600 text-white font-bold py-2.5 px-6 rounded shadow hover:shadow-md transition transform hover:-translate-y-0.5 text-sm uppercase tracking-wide">
            Create New Thread
        </button>

        {{-- Filter Controls --}}
        <div class="flex items-center space-x-4 w-full md:w-auto justify-end text-xs text-gray-500">
             <div class="flex items-center space-x-2">
                <span class="font-medium">Sort By:</span>
                <select class="border border-gray-300 rounded px-2 py-1.5 focus:ring-orange-500 focus:border-orange-500 bg-white text-gray-700 cursor-pointer">
                    <option value="latest">Latest Post</option>
                    <option value="oldest">Oldest Post</option>
                    <option value="replies">Most Replies</option>
                </select>
            </div>

            <div class="h-4 w-px bg-gray-300"></div>

            <div class="flex items-center space-x-2">
                {{-- Dynamic Result Count --}}
                <span>{{ isset($threads) ? $threads->count() : 0 }} Result(s)</span>
                <span class="ml-2">Show:</span>
                <select class="border border-gray-300 rounded px-2 py-1.5 focus:ring-orange-500 focus:border-orange-500 bg-white text-gray-700 cursor-pointer">
                    <option>10</option>
                    <option>25</option>
                </select>
            </div>

            {{-- Simple Pagination UI --}}
            <div class="flex items-center border border-gray-300 rounded bg-white">
                <button class="px-2 py-1.5 hover:bg-gray-100 border-r border-gray-300 disabled:opacity-50" disabled>«</button>
                <button class="px-2 py-1.5 hover:bg-gray-100 border-r border-gray-300 disabled:opacity-50" disabled>‹</button>
                <div class="px-2 font-medium text-gray-700">Page 1</div>
                <button class="px-2 py-1.5 hover:bg-gray-100 border-l border-gray-300 disabled:opacity-50" disabled>›</button>
                <button class="px-2 py-1.5 hover:bg-gray-100 border-l border-gray-300 disabled:opacity-50" disabled>»</button>
            </div>
        </div>
    </div>

    {{-- THREAD LIST --}}
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden min-h-[400px] flex flex-col">
        @php
            $threads = collect();
            if ($safeSessionId) {
                $threads = \App\Models\ForumThread::where('course_session_id', $safeSessionId)
                    ->with('user', 'posts')
                    ->latest()
                    ->get();
            }
        @endphp

        @if($threads->count() > 0)
            <div class="divide-y divide-gray-100">
                @foreach($threads as $thread)
                    <div wire:click="openThread({{ $thread->id }})" class="p-6 hover:bg-orange-50/30 cursor-pointer transition duration-150 group">
                        <div class="flex items-start justify-between gap-4">
                            {{-- Avatar --}}
                            <div class="flex-shrink-0">
                                @if($thread->user->profile_photo)
                                     <img class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm" src="{{ $thread->user->profile_photo }}" alt="">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold border-2 border-white shadow-sm text-xs">
                                        {{ substr($thread->user->first_name, 0, 1) }}{{ substr($thread->user->last_name, 0, 1) }}
                                    </div>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center flex-wrap gap-2 mb-1">
                                    <span class="font-bold text-sm text-gray-900 uppercase tracking-wide">{{ $thread->user->first_name }} {{ $thread->user->last_name }}</span>
                                    <span class="text-gray-300">•</span>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full border
                                        {{ $thread->user->role === 'lecturer' ? 'bg-orange-50 text-orange-600 border-orange-100' : 'bg-blue-50 text-blue-600 border-blue-100' }} font-bold uppercase tracking-wider">
                                        {{ $thread->user->role }}
                                    </span>
                                    <span class="text-xs text-gray-400 ml-auto md:ml-0">{{ $thread->created_at->format('d M Y, H:i') }} GMT+7</span>
                                </div>

                                <h3 class="text-lg font-bold text-gray-800 group-hover:text-orange-600 transition-colors mb-2 truncate">
                                    {{ $thread->title }}
                                </h3>

                                {{-- Badges --}}
                                <div class="flex flex-wrap gap-2 items-center">
                                    @if($thread->is_assessment)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-red-50 text-red-600 border border-red-200 uppercase tracking-wide">Assessment</span>
                                        @if($thread->deadline_at)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-orange-50 text-orange-700 border border-orange-200">
                                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Ends in {{ \Carbon\Carbon::now()->diffForHumans($thread->deadline_at, true) }}
                                            </span>
                                        @endif
                                    @endif

                                    @if($thread->is_hidden)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200" title="Replies are hidden">
                                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                            Hidden Replies
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Stats --}}
                            <div class="flex items-center bg-gray-50 px-3 py-1 rounded border border-gray-100 text-gray-500">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                                <span class="text-xs font-bold">{{ $thread->posts->count() }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination Footer --}}
            <div class="mt-auto border-t border-gray-200 p-4 bg-gray-50 flex justify-end">
                 <span class="text-xs text-gray-400">Showing 1-{{ $threads->count() }} results</span>
            </div>

        @else
            {{-- Placeholder Kosong --}}
            <div class="flex-1 flex flex-col items-center justify-center py-32 text-center">
                <div class="bg-gray-50 p-6 rounded-full mb-4">
                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">No discussions yet in this session.</h3>
                <p class="text-gray-500 text-sm">Be the first to start a conversation.</p>
            </div>
        @endif
    </div>
</div>
