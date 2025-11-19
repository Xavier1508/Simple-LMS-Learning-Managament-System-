<div class="min-h-screen bg-gray-50">
    <div class="bg-white border-b shadow-sm px-8 py-6">
        <div class="max-w-7xl mx-auto">
            <div class="mb-4">
                <a href="{{ route('courses') }}" class="inline-flex items-center text-gray-500 hover:text-orange-500 transition-colors text-sm font-medium group" wire:navigate>
                    <svg class="w-4 h-4 mr-1 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Courses
                </a>
            </div>

            <h1 class="text-3xl font-bold text-gray-800">{{ $class->course->title }}</h1>

            <div class="flex flex-wrap items-center gap-6 mt-3 text-sm text-gray-600">
                <span class="flex items-center"><svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg> {{ $class->course->code }}</span>
                <span class="flex items-center"><svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg> {{ $class->class_code }} - {{ $class->type }}</span>
                <span class="flex items-center"><svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> {{ $class->semester }}</span>
            </div>

            <div class="flex items-center mt-6 p-4 bg-blue-50 rounded-lg border border-blue-100">
                <div class="flex-shrink-0 mr-4">
                    @if(isset($class->lecturer->profile_photo) && $class->lecturer->profile_photo)
                        <img src="{{ $class->lecturer->profile_photo }}" alt="Lecturer" class="w-12 h-12 rounded-full object-cover border-2 border-blue-200">
                    @else
                        <div class="w-12 h-12 rounded-full bg-blue-200 flex items-center justify-center text-blue-600 font-bold text-lg border-2 border-blue-100">
                            {{ substr($class->lecturer->first_name, 0, 1) }}{{ substr($class->lecturer->last_name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Primary Instructor</p>
                    <p class="text-gray-900 font-medium">{{ $class->lecturer->lecturer_code }} - {{ $class->lecturer->first_name }} {{ $class->lecturer->last_name }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white border-b sticky top-0 z-20">
        <div class="max-w-7xl mx-auto px-8">
            <nav class="-mb-px flex space-x-8 overflow-x-auto" aria-label="Tabs">
                @foreach(['Session', 'Syllabus', 'Forum', 'Assessment', 'Gradebook', 'People', 'Attendance'] as $tab)
                    <button wire:click="switchTab('{{ strtolower($tab) }}')"
                        class="{{ $activeTab === strtolower($tab) ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}
                        whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition duration-150">
                        {{ $tab }}
                    </button>
                @endforeach
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-8 py-8">
        @if($activeTab === 'session')

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

            <div class="flex space-x-2 overflow-x-auto pb-4 mb-6 mt-8">
                @foreach($class->sessions as $session)
                    <button wire:click="toggleSession({{ $session->id }})"
                        class="flex-shrink-0 px-4 py-2 rounded-full text-xs font-semibold transition duration-150
                        {{ $activeSessionId === $session->id ? 'bg-orange-500 text-white shadow-lg' : 'bg-gray-200 text-gray-600 hover:bg-gray-300' }}">
                        Session {{ $session->session_number }}
                    </button>
                @endforeach
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-10 gap-8">

                <div class="lg:col-span-7 space-y-6">
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

                                    <div class="grid grid-cols-2 gap-4 text-sm mt-4 bg-gray-50 p-4 rounded-lg">
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

                <div class="lg:col-span-3">
                    @foreach($class->sessions as $session)
                        @if($activeSessionId === $session->id)

                        <div class="bg-orange-500 rounded-2xl shadow-xl text-white p-6 sticky top-24">
                            <h3 class="font-bold text-xl mb-6 tracking-tight">Things to do in this session</h3>

                            <div class="flex items-start justify-between mb-8 group">
                                <div class="flex items-center gap-4">
                                    <div class="bg-white w-12 h-12 rounded-xl flex items-center justify-center text-orange-600 shadow-sm flex-shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-base leading-tight">Attendance</p>
                                        <p class="text-xs text-white/80 mt-1">
                                            @if($session->isAttendedBy(Auth::id()))
                                                <span class="text-green-200 font-bold bg-green-900/20 px-1 rounded">✓ Recorded</span>
                                            @else
                                                Must be done in 15 mins
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    @if(Auth::user()->role === 'student')
                                        @if(!$session->isAttendedBy(Auth::id()))
                                            <button wire:click="attend({{ $session->id }})" class="bg-white/20 hover:bg-white hover:text-orange-600 text-white border border-white/40 px-3 py-1.5 rounded-lg text-xs font-bold transition-all duration-200">
                                                Check In
                                            </button>
                                        @endif
                                    @else
                                        <button class="text-xs font-medium hover:underline opacity-90 hover:opacity-100">View Report</button>
                                    @endif
                                </div>
                            </div>

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
                                    <a href="{{ $session->zoom_link }}" target="_blank" class="bg-white/20 hover:bg-white hover:text-orange-600 text-white border border-white/40 px-3 py-1.5 rounded-lg text-xs font-bold transition-all duration-200">
                                        Join
                                    </a>
                                @else
                                    <button disabled class="bg-white/5 text-white/30 border border-white/10 px-3 py-1.5 rounded-lg text-xs font-bold cursor-not-allowed">
                                        No Link
                                    </button>
                                @endif
                            </div>

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
                                                <span class="mr-3 opacity-80 flex-shrink-0">
                                                    {!! $this->getFileIcon($material->file_type) !!}
                                                </span>
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

        @else
            <div class="text-center py-20 text-gray-400">
                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <h3 class="text-xl font-semibold">Content for {{ ucfirst($activeTab) }}</h3>
                <p>Coming soon.</p>
            </div>
        @endif
    </div>

    @if($showUploadModal)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-2xl">
            <h3 class="text-lg font-bold mb-4 text-gray-800">Upload Material</h3>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Select File</label>
                <input type="file" wire:model="newFile" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 transition"/>
                @error('newFile') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div class="flex justify-end space-x-2 pt-4 border-t">
                <button wire:click="$set('showUploadModal', false)" class="px-4 py-2 text-gray-600 text-sm hover:text-gray-800">Cancel</button>
                <button wire:click="saveMaterial" class="px-4 py-2 bg-orange-600 text-white rounded text-sm hover:bg-orange-700 shadow flex items-center">
                    <span wire:loading.remove wire:target="saveMaterial">Upload</span>
                    <span wire:loading wire:target="saveMaterial">Uploading...</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    @if($showPreviewModal)
    <div class="fixed inset-0 bg-black/80 flex items-center justify-center z-50 p-4 backdrop-blur-sm">
        <div class="bg-white rounded-lg w-full max-w-4xl h-[85vh] flex flex-col shadow-2xl">
            <div class="p-4 border-b flex justify-between items-center bg-gray-50 rounded-t-lg">
                <h3 class="font-bold text-gray-800 truncate pr-4">{{ $previewFileName }}</h3>
                <button wire:click="$set('showPreviewModal', false)" class="text-gray-400 hover:text-gray-700 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="flex-1 bg-gray-100 p-4 flex items-center justify-center overflow-hidden relative">
                <div wire:loading wire:target="previewMaterial" class="absolute inset-0 flex items-center justify-center bg-white/50 z-10">
                    <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-orange-600"></div>
                </div>
                @if(in_array($previewFileType, ['jpg', 'jpeg', 'png', 'pdf']))
                    <iframe src="{{ $previewFileUrl }}" class="w-full h-full border-0 rounded shadow-sm bg-white"></iframe>
                @else
                    <div class="text-center">
                        <div class="mb-4 text-gray-400">
                            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <p class="mb-6 text-gray-600 font-medium">Preview not available for this file type.</p>
                        <a href="{{ $previewFileUrl }}" download class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow transition inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download File
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
