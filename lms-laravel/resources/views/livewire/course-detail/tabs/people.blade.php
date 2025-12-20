<div class="animate-fade-in min-h-screen">
    {{-- HEADER & TOOLBAR --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">People</h2>
            <p class="text-sm text-gray-500">{{ $class->students->count() + 1 }} people in this course</p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">

            {{-- LOCAL SEARCH (Untuk mencari orang di list) --}}
            <div class="relative w-full md:w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" wire:model.live.debounce.300ms="peopleSearch"
                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-orange-500 focus:border-orange-500 sm:text-sm transition duration-150 ease-in-out"
                    placeholder="Search by name or email...">
            </div>

            {{-- ADD PEOPLE DROPDOWN (Lecturer Only) --}}
            @if(Auth::user()->role === 'lecturer')
                <div x-data="{ open: false }" @click.away="open = false" @member-added.window="open = false" class="relative">

                    {{-- Trigger Button --}}
                    <button @click="open = !open" type="button" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-bold rounded-lg shadow-sm text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        Add People
                    </button>

                    {{-- Dropdown Content --}}
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-xl shadow-2xl ring-1 ring-black ring-opacity-5 z-50 overflow-hidden origin-top-right">

                        <div class="p-4 border-b border-gray-100 bg-gray-50">
                            <h3 class="text-sm font-bold text-gray-700 mb-2">Add Student to Class</h3>
                            <div class="relative">
                                <input type="text" wire:model.live.debounce.300ms="addMemberSearch"
                                    class="w-full border-gray-300 rounded-md text-sm focus:ring-orange-500 focus:border-orange-500 pl-8"
                                    placeholder="Search global database..." autofocus>
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                            </div>
                        </div>

                        <div class="max-h-80 overflow-y-auto divide-y divide-gray-100">
                            @forelse($this->searchableUsers as $user)
                                <button wire:click="addMember({{ $user->id }})" class="w-full flex items-center px-4 py-3 hover:bg-orange-50 transition text-left group">
                                    {{-- Avatar --}}
                                    <div class="flex-shrink-0 mr-3">
                                        <img class="h-10 w-10 rounded-full object-cover border border-gray-200 group-hover:border-orange-200"
                                             src="{{ $user->profile_photo ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=7F9CF5&background=EBF4FF' }}"
                                             alt="">
                                    </div>
                                    {{-- Info --}}
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-bold text-gray-900 truncate">{{ $user->first_name }} {{ $user->last_name }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                                    </div>
                                    {{-- Icon Plus --}}
                                    <div class="ml-2 text-gray-300 group-hover:text-orange-500">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    </div>
                                </button>
                            @empty
                                @if(strlen($addMemberSearch) > 0)
                                    <div class="px-4 py-8 text-center">
                                        <p class="text-sm text-gray-500">No users found matching "{{ $addMemberSearch }}"</p>
                                    </div>
                                @else
                                    <div class="px-4 py-8 text-center">
                                        <p class="text-sm text-gray-400">Type to search students...</p>
                                    </div>
                                @endif
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- LIST PEOPLE CARD --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <ul class="divide-y divide-gray-100">

            {{-- LECTURER (Always Top) --}}
            <li class="p-5 hover:bg-gray-50 transition flex items-center justify-between group">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="relative">
                            @if($class->lecturer->profile_photo)
                                <img class="h-12 w-12 rounded-full object-cover border-2 border-white shadow-sm" src="{{ $class->lecturer->profile_photo }}" alt="">
                            @else
                                <div class="h-12 w-12 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold text-lg border-2 border-white shadow-sm">
                                    {{ substr($class->lecturer->first_name, 0, 1) }}{{ substr($class->lecturer->last_name, 0, 1) }}
                                </div>
                            @endif
                            <span class="absolute bottom-0 right-0 block h-3 w-3 rounded-full ring-2 ring-white bg-green-400" title="Online"></span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="flex items-center gap-2">
                            <h3 class="text-base font-bold text-gray-900">
                                {{ $class->lecturer->first_name }} {{ $class->lecturer->last_name }}
                            </h3>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-orange-100 text-orange-700 border border-orange-200 uppercase tracking-wide">
                                Lecturer
                            </span>
                        </div>
                        <p class="text-sm text-gray-500">{{ $class->lecturer->email }}</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <a href="mailto:{{ $class->lecturer->email }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </a>
                </div>
            </li>

            {{-- STUDENTS --}}
            @foreach($class->students as $student)
                <li class="p-5 hover:bg-gray-50 transition flex items-center justify-between group">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-12 w-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-bold text-lg border-2 border-white shadow-sm">
                                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-bold text-gray-900">
                                    {{ $student->first_name }} {{ $student->last_name }}
                                </h3>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100 uppercase tracking-wide">
                                    Student
                                </span>
                            </div>
                            <p class="text-sm text-gray-500">{{ $student->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="mailto:{{ $student->email }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </a>

                        {{-- Remove Button (Lecturer Only) --}}
                        @if(Auth::user()->role === 'lecturer')
                            <button wire:click="removeMember({{ $student->id }})" wire:confirm="Remove {{ $student->first_name }} from this class?" class="p-2 text-gray-300 hover:text-red-600 hover:bg-red-50 rounded-full transition opacity-0 group-hover:opacity-100" title="Remove Student">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        @endif
                    </div>
                </li>
            @endforeach

            @if($class->students->isEmpty())
                <li class="p-10 text-center text-gray-400">
                    @if($peopleSearch)
                        No students found matching "{{ $peopleSearch }}"
                    @else
                        No students enrolled in this class yet.
                    @endif
                </li>
            @endif

        </ul>
    </div>
</div>
