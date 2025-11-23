<div class="p-8 bg-gray-50 min-h-screen">

    <!-- Header & Filter -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">My Courses</h1>

        <!-- Semester Filter -->
        <div class="flex items-center space-x-4 text-sm mt-4 md:mt-0">
            <label class="text-gray-600">Running Period</label>
            <div class="relative">
                <select wire:model.live="selectedSemester" class="appearance-none bg-white border border-gray-300 rounded-md py-2 pl-3 pr-10 focus:outline-none focus:ring-orange-500 focus:border-orange-500 cursor-pointer">
                    <option value="2025, Odd Semester">2025, Odd Semester</option>
                    <option value="2024, Even Semester">2024, Even Semester</option>
                </select>
                <svg class="w-4 h-4 absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex border-b border-gray-300 mb-6 text-sm font-medium">
        <button wire:click="$set('selectedType', 'ALL')" class="px-4 py-2 {{ $selectedType === 'ALL' ? 'text-orange-600 border-b-2 border-orange-600' : 'text-gray-500 hover:text-orange-600' }}">ALL</button>
        <button wire:click="$set('selectedType', 'LAB')" class="px-4 py-2 {{ $selectedType === 'LAB' ? 'text-orange-600 border-b-2 border-orange-600' : 'text-gray-500 hover:text-orange-600' }}">LAB</button>
        <button wire:click="$set('selectedType', 'LEC')" class="px-4 py-2 {{ $selectedType === 'LEC' ? 'text-orange-600 border-b-2 border-orange-600' : 'text-gray-500 hover:text-orange-600' }}">LEC</button>
    </div>

    <!-- Notification Message -->
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('message') }}
        </div>
    @endif

    <!-- COURSE GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        @forelse ($courses as $class)
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition duration-300 border border-gray-200 group text-left h-full" style="position: relative;">

                <!-- INVISIBLE LINK OVERLAY (untuk klik card) -->
                <a href="{{ route('courses.detail', $class->id) }}" wire:navigate class="absolute inset-0" style="z-index: 0; pointer-events: auto;"></a>

                <!-- CONTENT WRAPPER -->
                <div class="p-6 h-full w-full" style="position: relative; z-index: 10; pointer-events: none;">

                    <!-- DELETE BUTTON (bisa diklik, tidak trigger link) -->
                    @if(Auth::user()->role === 'lecturer')
                        <button wire:click="confirmDelete({{ $class->id }})" class="text-red-400 hover:text-red-600 opacity-0 group-hover:opacity-100 transition duration-200 p-1.5 bg-white rounded-full shadow-sm hover:bg-red-50" style="position: absolute; top: 8px; right: 8px; z-index: 30; pointer-events: auto;">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    @endif

                    <!-- CARD CONTENT -->
                    <div class="pr-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-1 leading-tight">{{ $class->course->title }}</h2>
                        <div class="flex items-center space-x-2 text-xs text-gray-500 mb-3 mt-2">
                            <span class="bg-gray-100 px-2 py-1 rounded font-mono">{{ $class->course->code }}</span>
                            <span class="font-bold text-gray-700">{{ $class->class_code }}</span>
                            <span class="text-orange-500 font-bold">{{ $class->type }}</span>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4 mt-2">
                        <p class="text-sm text-gray-600 truncate">{{ $class->course->description ?? 'No description available.' }}</p>
                        <div class="mt-4 w-full bg-gray-200 rounded-full h-1.5">
                            <div class="bg-orange-500 h-1.5 rounded-full" style="width: 0%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Class progress 0%</p>
                    </div>
                </div>
            </div>

        @empty
            @if(Auth::user()->role === 'student')
                <div class="col-span-full flex flex-col items-center justify-center py-12 text-center">
                    <img src="{{ asset('images/empty-state.png') }}" class="w-32 h-32 mb-4 opacity-50" alt="No Courses">
                    <h3 class="text-lg font-medium text-gray-900">You don't have any class yet</h3>
                    <p class="text-gray-500 text-sm mt-2">Please wait for your lecturer to invite you.</p>
                </div>
            @endif
        @endforelse

        <!-- ADD COURSE BUTTON -->
        @if(Auth::user()->role === 'lecturer')
            <div wire:click="$set('showAddModal', true)" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-300 border border-dashed border-gray-300 cursor-pointer flex flex-col justify-center items-center h-full text-center min-h-[200px] hover:bg-gray-50">
                <div class="bg-orange-100 p-3 rounded-full mb-3">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-700">Create New Class</h3>
                <p class="text-sm text-gray-500">Add a course and invite students</p>
            </div>
        @endif
    </div>

    <!-- ================= MODAL ADD COURSE ================= -->
    @if($showAddModal)
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg w-full max-w-2xl shadow-xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 z-10">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Create New Class</h3>
                    <button wire:click="$set('showAddModal', false)" class="text-gray-400 hover:text-gray-600 p-1 hover:bg-gray-100 rounded transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>

            <form wire:submit.prevent="saveCourse" class="p-6">
                <div class="space-y-5">
                    <!-- Course Title -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Course Title *</label>
                        <input wire:model="title" type="text" placeholder="e.g. Secure Programming" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm p-2.5 border">
                        @error('title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Major & Class Code -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Major / Department *</label>
                            <select wire:model="selectedMajorPrefix" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm p-2.5 border">
                                @foreach($majors as $prefix => $name)
                                    <option value="{{ $prefix }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-400 mt-1">Code auto-generated (e.g. COMPxxxxxxx)</p>
                            @error('selectedMajorPrefix') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Class Code *</label>
                            <input wire:model="class_code" type="text" placeholder="LA07" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm p-2.5 border">
                            @error('class_code') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                        <select wire:model="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm p-2.5 border">
                            <option value="LEC">LEC (Lecture)</option>
                            <option value="LAB">LAB (Laboratory)</option>
                        </select>
                    </div>

                    <!-- ================= STUDENT SEARCH & INVITE ================= -->
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 mt-2" x-data="{ searchFocused: false }">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                Invite Students (Optional)
                            </span>
                        </label>
                        <p class="text-xs text-gray-500 mb-3">Search and add students to this class. You can add more later.</p>

                        <!-- Search Input with Dropdown -->
                        <div class="relative mb-3" @click.away="searchFocused = false">
                            <div style="position: relative;">
                                <!-- Search Icon (FIXED - tidak double) -->
                                <div style="position: absolute; top: 0; bottom: 0; left: 0; display: flex; align-items: center; padding-left: 0.75rem; pointer-events: none; z-index: 1;">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>

                                <!-- Input Field -->
                                <input
                                    wire:model.live.debounce.500ms="studentSearchQuery"
                                    @focus="searchFocused = true"
                                    type="text"
                                    placeholder="Search by student name or email..."
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm bg-white"
                                    style="padding-left: 2.25rem; padding-right: 2.25rem; padding-top: 0.625rem; padding-bottom: 0.625rem;"
                                    autocomplete="off"
                                >

                                <!-- Right Side Icons Container (FIXED - hanya 1 yang muncul) -->
                                <div style="position: absolute; top: 0; bottom: 0; right: 0; display: flex; align-items: center; padding-right: 0.75rem; z-index: 2;">
                                    <div wire:loading wire:target="studentSearchQuery">
                                        <svg class="animate-spin h-4 w-4 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>

                                    <!-- Clear Button (hanya muncul jika ada text DAN tidak loading) -->
                                    @if(!empty($studentSearchQuery))
                                        <button
                                            type="button"
                                            wire:click="$set('studentSearchQuery', '')"
                                            wire:loading.remove
                                            wire:target="studentSearchQuery"
                                            class="text-gray-400 hover:text-gray-600 transition">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <!-- DROPDOWN RESULTS -->
                            @if(!empty($studentSearchQuery) && !empty($studentSearchResults))
                                <div
                                    x-show="searchFocused"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 translate-y-1"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    class="bg-white shadow-lg rounded-lg text-base ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm border border-gray-200"
                                    style="position: absolute; z-index: 50; margin-top: 0.25rem; width: 100%; max-height: 16rem; overflow: auto; padding-top: 0.25rem; padding-bottom: 0.25rem;">
                                    @foreach($studentSearchResults as $student)
                                        <button
                                            type="button"
                                            wire:click="selectStudent({{ $student['id'] }})"
                                            @click="searchFocused = false"
                                            class="w-full cursor-pointer select-none hover:bg-orange-50 transition duration-150 ease-in-out border-b border-gray-50 last:border-0 text-left"
                                            style="padding: 0.625rem 0.75rem;">
                                            <div class="flex items-center gap-3">
                                                <div class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-sm" style="background: linear-gradient(to bottom right, #fb923c, #f472b6);">
                                                    {{ $student['initials'] }}
                                                </div>
                                                <!-- Info -->
                                                <div class="flex flex-col min-w-0 flex-1">
                                                    <span class="font-medium text-gray-900 truncate text-sm">{{ $student['name'] }}</span>
                                                    <span class="text-xs text-gray-500 truncate">{{ $student['email'] }}</span>
                                                </div>
                                                <!-- Add Icon -->
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            @elseif(!empty($studentSearchQuery) && empty($studentSearchResults))
                                <div
                                    x-show="searchFocused"
                                    x-transition
                                    class="bg-white shadow-lg rounded-lg text-sm text-gray-500 border border-gray-200 text-center"
                                    style="position: absolute; z-index: 50; margin-top: 0.25rem; width: 100%; padding: 0.75rem 1rem;">
                                    <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    No students found matching "<strong>{{ $studentSearchQuery }}</strong>"
                                </div>
                            @endif
                        </div>

                        <!-- Selected Students -->
                        @if(!empty($selectedStudents))
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-xs text-gray-600 mb-2">
                                    <span class="font-medium">Selected Students ({{ count($selectedStudents) }})</span>
                                    @if(count($selectedStudents) > 0)
                                        <button
                                            type="button"
                                            wire:click="$set('selectedStudents', [])"
                                            class="text-red-500 hover:text-red-700 font-medium">
                                            Clear All
                                        </button>
                                    @endif
                                </div>
                                <div class="max-h-40 overflow-y-auto space-y-2 bg-white rounded-lg p-2 border border-gray-200">
                                    @foreach($selectedStudents as $student)
                                        <div class="flex items-center justify-between p-2 bg-gradient-to-r from-orange-50 to-pink-50 rounded-lg border border-orange-200 group hover:shadow-sm transition">
                                            <div class="flex items-center gap-3 flex-1 min-w-0">
                                                <div class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-sm" style="background: linear-gradient(to bottom right, #fb923c, #f472b6);">
                                                    {{ $student['initials'] }}
                                                </div>
                                                <!-- Info -->
                                                <div class="flex flex-col min-w-0 flex-1">
                                                    <span class="text-sm font-medium text-gray-900 truncate">{{ $student['name'] }}</span>
                                                    <span class="text-xs text-gray-600 truncate">{{ $student['email'] }}</span>
                                                </div>
                                            </div>
                                            <!-- Remove Button -->
                                            <button
                                                type="button"
                                                wire:click="removeStudent({{ $student['id'] }})"
                                                class="flex-shrink-0 text-gray-400 hover:text-red-500 p-1.5 hover:bg-white rounded transition ml-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="text-center py-6 text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <p class="text-sm">No students selected yet</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="mt-6 pt-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" wire:click="$set('showAddModal', false)" class="bg-white py-2.5 px-5 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex items-center justify-center py-2.5 px-5 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Create Class
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- ================= MODAL DELETE CONFIRMATION ================= -->
    @if($showDeleteModal)
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg w-full max-w-sm p-6 shadow-xl text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Class?</h3>
            <p class="text-sm text-gray-500 mt-2">Are you sure you want to delete this class? This action cannot be undone.</p>

            <div class="mt-5 sm:mt-6 flex justify-center space-x-3">
                <button wire:click="$set('showDeleteModal', false)" type="button" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:text-sm">Cancel</button>
                <button wire:click="deleteClass" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:text-sm">Delete</button>
            </div>
        </div>
    </div>
    @endif

</div>
