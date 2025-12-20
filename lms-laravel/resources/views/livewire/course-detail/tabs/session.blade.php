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

{{-- CONTENT AREA --}}
<div class="grid grid-cols-1 lg:grid-cols-[1fr_24rem] gap-8 items-start">

    {{-- LEFT COLUMN: Session Detail --}}
    <div class="min-w-0">
        @foreach($class->sessions as $session)
            @if($activeSessionId == $session->id)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden animate-fade-in-down h-full">
                    <div class="p-6 border-b border-gray-100">
                        <h2 class="text-2xl font-bold text-gray-800">{{ $session->title }}</h2>
                        <p class="text-sm text-gray-500 mt-1">Delivery: <span class="font-medium text-gray-700">{{ $session->delivery_mode }}</span></p>
                    </div>

                    <div class="p-6 space-y-6">
                        <div>
                            <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-3">Learning Outcome</h4>
                            <div class="prose text-sm text-gray-600 max-w-none leading-relaxed">
                                {{ $session->learning_outcome }}
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 bg-gray-50 p-5 rounded-lg border border-gray-100">
                            <div>
                                <p class="text-gray-500 text-xs uppercase font-bold mb-1">Start Time</p>
                                <p class="font-medium text-gray-800 text-lg">{{ $session->start_time ? $session->start_time->format('d M Y, H:i') : '-' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-xs uppercase font-bold mb-1">End Time</p>
                                <p class="font-medium text-gray-800 text-lg">{{ $session->end_time ? $session->end_time->format('d M Y, H:i') : '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    {{-- RIGHT COLUMN: Action Sidebar --}}
    <div class="w-full">
        @foreach($class->sessions as $session)
            @if($activeSessionId == $session->id)
                @php
                    $myRecord = $session->attendances->where('user_id', Auth::id())->first();
                    $statusData = method_exists($this, 'getStatusDisplay')
                        ? $this->getStatusDisplay($session, $myRecord)
                        : ['type' => 'default', 'label' => 'Check Status'];
                @endphp

                <div class="bg-orange-500 rounded-2xl shadow-xl text-white p-6 sticky top-24">
                    <h3 class="font-bold text-xl mb-6 tracking-tight border-b border-white/20 pb-4">Things to do in this session</h3>

                    {{-- ATTENDANCE CARD --}}
                    <div class="flex items-start justify-between mb-6 group">
                        <div class="flex items-center gap-4">
                            <div class="bg-white w-12 h-12 rounded-xl flex items-center justify-center text-orange-600 shadow-sm flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p class="font-bold text-base leading-tight">Attendance</p>
                                <p class="text-xs text-white/80 mt-1 font-medium">
                                    @if($statusData['type'] === 'present')
                                        <span class="text-green-100 bg-green-800/30 px-1.5 py-0.5 rounded">✓ Recorded</span>
                                    @elseif($statusData['type'] === 'cancelled_lecturer')
                                        <span class="text-red-100 bg-red-800/30 px-1.5 py-0.5 rounded">✕ Cancelled by Lecturer</span>
                                    @elseif($statusData['type'] === 'cancelled_system')
                                        <span class="text-red-100 bg-red-800/30 px-1.5 py-0.5 rounded">✕ Error / Cancelled</span>
                                    @else
                                        Must be done in 15 mins
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div>
                            @if(Auth::user()->role === 'student')
                                @if($statusData['type'] === 'present')
                                    {{-- Already present --}}
                                @elseif($statusData['type'] === 'cancelled_lecturer')
                                     <button disabled class="bg-white/10 text-white/50 border border-white/20 px-3 py-1.5 rounded-lg text-xs font-bold cursor-not-allowed">
                                        Locked
                                    </button>
                                @else
                                    <button wire:click="attend({{ $session->id }})" class="bg-white/20 hover:bg-white hover:text-orange-600 text-white border border-white/40 px-3 py-1.5 rounded-lg text-xs font-bold transition-all duration-200 shadow-sm">
                                        {{ $statusData['type'] === 'cancelled_system' ? 'Retry' : 'Check In' }}
                                    </button>
                                @endif
                            @else
                                <button wire:click="switchTab('attendance')" class="text-xs font-medium hover:bg-white/10 px-2 py-1 rounded transition">View Report</button>
                            @endif
                        </div>
                    </div>

                    {{-- ONLINE MEETING CARD --}}
                    <div class="flex items-center justify-between mb-6">
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
                            <a href="{{ $session->zoom_link }}" target="_blank" class="bg-white/20 hover:bg-white hover:text-orange-600 text-white border border-white/40 px-3 py-1.5 rounded-lg text-xs font-bold transition-all duration-200 shadow-sm">
                                Join
                            </a>
                        @else
                            <button disabled class="bg-white/10 text-white/40 border border-white/10 px-3 py-1.5 rounded-lg text-xs font-bold cursor-not-allowed">
                                No Link
                            </button>
                        @endif
                    </div>

                    {{-- MATERIALS CARD --}}
                    <div>
                        <div class="flex items-start justify-between mb-3">
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

                        <div class="border-t border-white/20 my-4"></div>

                        @if($session->materials->count() > 0)
                            <div class="space-y-3 pl-2">
                                @foreach($session->materials as $material)
                                    <div class="flex justify-between items-center group hover:bg-white/5 p-1 rounded transition">
                                        {{-- PREVIEW --}}
                                        <button wire:click="previewMaterial({{ $material->id }})" class="text-sm text-white/90 hover:text-white truncate text-left flex-1 flex items-center mr-2">
                                            <span class="mr-3 opacity-80 flex-shrink-0">
                                                {{-- Jika getFileIcon adalah method di trait/component --}}
                                                @if(method_exists($this, 'getFileIcon'))
                                                    {!! $this->getFileIcon($material->file_type) !!}
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                @endif
                                            </span>
                                            <span class="truncate font-medium">{{ $material->file_name }}</span>
                                        </button>

                                        <div class="flex items-center space-x-1 flex-shrink-0 opacity-70 group-hover:opacity-100 transition">
                                            {{-- DOWNLOAD --}}
                                            <button wire:click="downloadMaterial({{ $material->id }})" class="p-1.5 text-white hover:bg-white/20 rounded" title="Download">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            </button>

                                            {{-- DELETE (Lecturer Only) --}}
                                            @if(Auth::user()->role === 'lecturer')
                                                <button wire:click="deleteMaterial({{ $material->id }})" class="p-1.5 text-red-200 hover:text-white hover:bg-red-500/50 rounded" title="Delete">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-white/50 italic text-center py-2">No materials uploaded yet.</p>
                        @endif
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
