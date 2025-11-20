@if (session()->has('message'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
        {{ session('message') }}
    </div>
@endif
@if (session()->has('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
        {{ session('error') }}
    </div>
@endif

{{-- SESSION LIST (NAVIGASI) --}}
<div class="flex space-x-2 pb-4 mb-5 mt-4 overflow-x-auto">
    @foreach($class->sessions as $session)
        <button wire:click="toggleSession({{ $session->id }})"
            class="flex-shrink-0 px-4 py-2 rounded-full text-xs font-semibold transition duration-150
            {{ $activeSessionId === $session->id ? 'bg-orange-500 text-white shadow-lg' : 'bg-gray-200 text-gray-600 hover:bg-gray-300' }}">
            Session {{ $session->session_number }}
        </button>
    @endforeach
</div>

{{-- GRID CONTENT --}}
{{-- PERBAIKAN: Gunakan lg:grid-cols-3 (2/3 kiri, 1/3 kanan) agar lebih aman daripada cols-10 --}}
<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

    {{-- LEFT COLUMN: Session Detail (Span 2 dari 3) --}}
    <div class="lg:col-span-3 space-y-6 min-w-0">
        @foreach($class->sessions as $session)
            @if($activeSessionId === $session->id)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden animate-fade-in-down">
                    <div class="p-6 border-b border-gray-100">
                        <h2 class="text-xl font-bold text-gray-800">{{ $session->title }}</h2>
                        <p class="text-sm text-gray-500 mt-1">Delivery: <span class="font-medium text-gray-700">{{ $session->delivery_mode }}</span></p>
                    </div>

                    <div class="p-6 space-y-4">
                        <div>
                            <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-2">Learning Outcome</h4>
                            <div class="prose text-sm text-gray-600">
                                {{ $session->learning_outcome }}
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mt-4 bg-gray-50 p-4 rounded-lg">
                            <div>
                                <p class="text-gray-500 text-xs">Start Time</p>
                                <p class="font-medium text-gray-800">{{ $session->start_time->format('d M Y, H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-xs">End Time</p>
                                <p class="font-medium text-gray-800">{{ $session->end_time->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    {{-- RIGHT COLUMN: Action Sidebar (Span 1 dari 3) --}}
    <div class="lg:col-span-1 overflow-visible min-w-0">
        @foreach($class->sessions as $session)
            @if($activeSessionId === $session->id)
                @php
                    $myRecord = $session->attendances->where('user_id', Auth::id())->first();
                    $statusData = $this->getStatusDisplay($session, $myRecord);
                @endphp

                {{-- PERBAIKAN: Hapus sticky jika mengganggu layout, atau pastikan parent relatif --}}
                <div class="bg-orange-500 rounded-2xl shadow-xl text-white p-6">
                    <h3 class="font-bold text-xl mb-6 tracking-tight">Things to do in this session</h3>

                    {{-- ATTENDANCE CARD --}}
                    <div class="flex items-start justify-between mb-8 group">
                        <div class="flex items-center gap-4">
                            <div class="bg-white w-12 h-12 rounded-xl flex items-center justify-center text-orange-600 shadow-sm flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p class="font-bold text-base leading-tight">Attendance</p>
                                <p class="text-xs text-white/80 mt-1">
                                    @if($statusData['type'] === 'present')
                                        <span class="text-green-200 font-bold bg-green-900/20 px-1 rounded">✓ Recorded</span>
                                    @elseif($statusData['type'] === 'cancelled_lecturer')
                                        <span class="text-red-200 font-bold bg-red-900/20 px-1 rounded">✕ Cancelled</span>
                                    @elseif($statusData['type'] === 'cancelled_system')
                                        <span class="text-red-200 font-bold bg-red-900/20 px-1 rounded">✕ Error</span>
                                    @else
                                        Must be done in 15 mins
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div>
                            @if(Auth::user()->role === 'student')
                                @if($statusData['type'] === 'present')
                                @elseif($statusData['type'] === 'cancelled_lecturer')
                                     <button disabled class="bg-white/10 text-white/50 border border-white/20 px-3 py-1.5 rounded-lg text-xs font-bold cursor-not-allowed">Locked</button>
                                @else
                                    <button wire:click="attend({{ $session->id }})" class="bg-white/20 hover:bg-white hover:text-orange-600 text-white border border-white/40 px-3 py-1.5 rounded-lg text-xs font-bold transition-all duration-200">
                                        {{ $statusData['type'] === 'cancelled_system' ? 'Retry' : 'Check In' }}
                                    </button>
                                @endif
                            @else
                                <button wire:click="switchTab('attendance')" class="text-xs font-medium hover:underline opacity-90 hover:opacity-100">View Report</button>
                            @endif
                        </div>
                    </div>

                    {{-- ONLINE MEETING CARD --}}
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-4">
                            <div class="bg-white w-12 h-12 rounded-xl flex items-center justify-center text-blue-600 shadow-sm flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <p class="font-bold text-base leading-tight">Online Meeting</p>
                                @if($session->zoom_link)
                                    <p class="text-xs text-white/80 mt-1">Zoom / GMeet</p>
                                @else
                                    <p class="text-xs text-white/50 mt-1 italic">No link set</p>
                                @endif
                            </div>
                        </div>

                        @if($session->zoom_link)
                            <a href="{{ $session->zoom_link }}" target="_blank" class="bg-white/20 hover:bg-white hover:text-orange-600 text-white border border-white/40 px-3 py-1.5 rounded-lg text-xs font-bold transition-all duration-200">Join</a>
                        @else
                            <button disabled class="bg-white/5 text-white/30 border border-white/10 px-3 py-1.5 rounded-lg text-xs font-bold cursor-not-allowed">No Link</button>
                        @endif
                    </div>

                    {{-- MATERIALS CARD --}}
                    <div>
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex items-center gap-4">
                                <div class="bg-white w-12 h-12 rounded-xl flex items-center justify-center text-gray-700 shadow-sm flex-shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-base leading-tight">Materials</p>
                                    <p class="text-xs text-white/80 mt-1">{{ $session->materials->count() }} Files Attached</p>
                                </div>
                            </div>

                            @if(Auth::user()->role === 'lecturer')
                                <button wire:click="openUploadModal({{ $session->id }})" class="w-8 h-8 rounded-full bg-white text-orange-600 flex items-center justify-center hover:bg-orange-50 hover:scale-105 transition shadow-sm" title="Add Material">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </button>
                            @endif
                        </div>

                        <div class="border-t border-white/30 ml-16 mt-4 mb-4"></div>

                        @if($session->materials->count() > 0)
                            <div class="space-y-4 ml-16">
                                @foreach($session->materials as $material)
                                    <div class="flex justify-between items-center group">
                                        <button wire:click="previewMaterial({{ $material->id }})" class="text-sm text-white/90 hover:text-white hover:underline truncate text-left flex-1 flex items-center mr-2">
                                            <span class="mr-3 opacity-80 flex-shrink-0">{!! $this->getFileIcon($material->file_type) !!}</span>
                                            <span class="truncate">{{ $material->file_name }}</span>
                                        </button>
                                        <div class="flex items-center space-x-1 flex-shrink-0">
                                            <button wire:click="downloadMaterial({{ $material->id }})" class="p-1.5 text-white/70 hover:text-white hover:bg-white/20 rounded transition" title="Download">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            </button>
                                            @if(Auth::user()->role === 'lecturer')
                                                <button wire:click="deleteMaterial({{ $material->id }})" class="p-1.5 text-red-200 hover:text-white hover:bg-red-500/50 rounded transition" title="Delete">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-white/50 italic ml-16">No materials uploaded yet.</p>
                        @endif
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
