<div class="min-h-screen bg-gray-50">
    {{-- HEADER SECTION --}}
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

    {{-- NAVIGATION TABS --}}
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

    {{-- MAIN CONTENT AREA --}}
    <div class="max-w-7xl mx-auto px-8 py-8">

        {{-- SESSION TAB --}}
        @if($activeTab === 'session')
            @include('livewire.course-detail.tabs.session')

        {{-- ATTENDANCE TAB --}}
        @elseif($activeTab === 'attendance')
            @include('livewire.course-detail.tabs.attendance')

        {{-- OTHER TABS: Placeholder --}}
        @else
            @include('livewire.course-detail.tabs.empty')
        @endif
    </div>

    {{-- LOAD MODALS --}}
    @include('livewire.course-detail.partials.modals')
</div>
