<div class="bg-gray-50 min-h-screen p-4">
    @php
        $thread = \App\Models\ForumThread::with('user')->find($selectedThreadId);
    @endphp

    {{-- Back Button --}}
    <button wire:click="switchToForumList" class="flex items-center text-gray-500 hover:text-gray-900 mb-4 text-sm font-bold">
        <div class="w-8 h-8 rounded-full bg-white shadow flex items-center justify-center mr-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </div>
        Session {{ $thread->session->session_number }}
    </button>

    {{-- THREAD CONTENT CARD --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">

        {{-- Author Header --}}
        <div class="p-6 border-b border-gray-100 flex justify-between items-start">
            <div class="flex gap-4">
                <img class="w-12 h-12 rounded-full border-2 border-white shadow-sm" src="https://placehold.co/100x100/orange/white?text={{ substr($thread->user->first_name, 0, 1) }}" alt="">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-gray-900 uppercase">{{ $thread->user->first_name }} {{ $thread->user->last_name }}</span>
                        <span class="text-[10px] px-1.5 rounded {{ $thread->user->role === 'lecturer' ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600' }} font-bold uppercase">
                            {{ $thread->user->role }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $thread->created_at->format('d M Y, H:i') }} GMT+7</p>
                </div>
            </div>

            {{-- Delete Thread (Owner/Lecture) --}}
            @if(Auth::id() == $thread->user_id || Auth::user()->role === 'lecturer')
                <button wire:click="deleteThread({{ $thread->id }})" wire:confirm="Are you sure?" class="text-gray-400 hover:text-red-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            @endif
        </div>

        {{-- Special Banners --}}
        <div class="px-6 pt-4 space-y-2">
            @if($thread->is_hidden)
                <div class="bg-gray-100 text-gray-600 text-sm px-4 py-3 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                    Responses to this thread are hidden. Students won't be able to see each other posts.
                </div>
            @endif

            @if($thread->is_assessment)
                <div class="flex gap-2">
                    <span class="bg-white border border-red-500 text-red-600 px-3 py-1 rounded text-xs font-bold uppercase">Assessment</span>
                    <span class="bg-orange-500 text-white px-3 py-1 rounded text-xs font-bold flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @if(\Carbon\Carbon::now()->gt($thread->deadline_at))
                            Ended {{ $thread->deadline_at->format('d M Y, H:i') }}
                        @else
                            Ends in {{ \Carbon\Carbon::now()->diffForHumans($thread->deadline_at, true) }}
                        @endif
                    </span>
                </div>
            @endif
        </div>

        {{-- Body --}}
        <div class="p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $thread->title }}</h1>
            <div class="prose text-gray-700 max-w-none text-sm mb-6">
                {!! nl2br(e($thread->content)) !!}
            </div>

            {{-- Attachment (Thread) --}}
            @if($thread->attachment_path)
                <div class="border border-gray-200 rounded p-3 flex items-center justify-between max-w-md bg-gray-50">
                    <div class="flex items-center text-sm text-gray-600 truncate">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        {{ $thread->attachment_name }}
                    </div>
                    <button wire:click="downloadMaterial({{ $thread->id }})" class="text-gray-400 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- DISCUSSIONS --}}
    <div>
        <div class="flex justify-between items-end mb-4 px-2">
            <h3 class="font-bold text-sm text-gray-500 uppercase">Discussions</h3>
        </div>

        {{-- Reply Form --}}
        @if($thread->isLocked() && Auth::user()->role === 'student')
            <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded text-center text-sm mb-6">
                This assessment is locked. Deadline passed.
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-8 flex gap-4">
                <img class="w-10 h-10 rounded-full bg-gray-200" src="https://placehold.co/100x100/blue/white?text={{ substr(Auth::user()->first_name, 0, 1) }}">
                <div class="flex-1">
                    <textarea wire:model="replyContent" rows="3" placeholder="Write a comment..."
                        class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-blue-500 focus:border-blue-500"></textarea>

                    <div class="flex justify-between items-center mt-2">
                        <div class="flex items-center gap-2">
                            <input type="file" wire:model="replyAttachment" class="text-xs text-gray-500">
                        </div>
                        <button wire:click="postReply" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-1.5 px-4 rounded text-xs uppercase transition">
                            Comment
                        </button>
                    </div>
                    @error('replyContent') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
        @endif

        {{-- Filter Bar --}}
        <div class="flex items-center justify-between text-xs text-gray-500 mb-4 border-b pb-2">
            <span>Sort by: <button class="text-blue-600 font-bold">Latest Comment</button></span>
        </div>

        {{-- Comments List --}}
        <div class="space-y-4">
            @php
                // Logic Query Reply (Hidden/Normal)
                $postsQuery = $thread->posts()->with('user')->latest();
                $posts = $postsQuery->get();
            @endphp

            @forelse($posts as $post)
                {{-- Hidden Logic Check --}}
                @php
                    $isVisible = true;
                    if ($thread->is_hidden && Auth::user()->role === 'student') {
                        // Siswa hanya bisa lihat punya sendiri ATAU punya Dosen
                        if ($post->user_id !== Auth::id() && $post->user->role !== 'lecturer') {
                            $isVisible = false;
                        }
                    }
                @endphp

                @if($isVisible)
                <div class="bg-white p-5 rounded-lg border border-gray-100 shadow-sm group">
                    <div class="flex justify-between items-start">
                        <div class="flex gap-3">
                            {{-- Avatar --}}
                            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500">
                                {{ substr($post->user->first_name, 0, 1) }}{{ substr($post->user->last_name, 0, 1) }}
                            </div>

                            <div>
                                <div class="flex items-baseline gap-2">
                                    <span class="font-bold text-sm text-gray-900 uppercase">{{ $post->user->first_name }} {{ $post->user->last_name }}</span>
                                    <span class="text-xs text-gray-400">{{ $post->created_at->format('d M Y, H:i') }}</span>
                                </div>
                                <div class="text-xs text-gray-500 mb-2">{{ $post->user->email }}</div>

                                <div class="text-sm text-gray-800 leading-relaxed">
                                    {!! nl2br(e($post->content)) !!}
                                </div>

                                {{-- Attachment (Reply) --}}
                                @if($post->attachment_path)
                                    <div class="mt-3 inline-flex items-center px-3 py-1.5 bg-gray-50 border border-gray-200 rounded text-xs text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                        {{ $post->attachment_name }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Delete Comment --}}
                        @if(Auth::id() == $post->user_id || Auth::user()->role === 'lecturer')
                            <button wire:click="deletePost({{ $post->id }})" wire:confirm="Delete this comment?" class="opacity-0 group-hover:opacity-100 text-gray-300 hover:text-red-500 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        @endif
                    </div>
                </div>
                @endif
            @empty
                <p class="text-center text-gray-400 text-sm">No comments yet.</p>
            @endforelse
        </div>
    </div>
</div>
