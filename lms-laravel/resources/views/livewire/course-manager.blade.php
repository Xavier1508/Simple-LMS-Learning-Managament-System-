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

    <!-- Tabs (LAB / LEC) -->
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
            <!-- [PERUBAHAN DI SINI] -->
            <!-- Mengganti DIV menjadi A (Anchor/Link) agar bisa diklik -->
            <!-- Menambahkan wire:navigate agar transisi halaman mulus tanpa reload -->
            <a href="{{ route('courses.detail', $class->id) }}" wire:navigate class="block bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-300 border border-gray-200 cursor-pointer relative group text-left">

                <!-- Header Card -->
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 mb-1">{{ $class->course->title }}</h2>
                        <div class="flex items-center space-x-2 text-xs text-gray-500 mb-3">
                            <span class="bg-gray-100 px-2 py-1 rounded font-mono">{{ $class->course->code }}</span>
                            <span class="font-bold text-gray-700">{{ $class->class_code }}</span>
                            <span class="text-orange-500 font-bold">{{ $class->type }}</span>
                        </div>
                    </div>

                    <!-- Delete Button (HANYA LECTURER) -->
                    <!-- Kita gunakan event.prevent agar saat klik delete tidak masuk ke detail course -->
                    @if(Auth::user()->role === 'lecturer')
                        <button wire:click.prevent="confirmDelete({{ $class->id }})" class="text-red-400 hover:text-red-600 opacity-0 group-hover:opacity-100 transition z-10 relative">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    @endif
                </div>

                <div class="border-t border-gray-100 pt-4 mt-4">
                    <p class="text-sm text-gray-600 truncate">{{ $class->course->description ?? 'No description available.' }}</p>
                    <div class="mt-4 w-full bg-gray-200 rounded-full h-1.5">
                        <div class="bg-orange-500 h-1.5 rounded-full" style="width: 0%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Class progress 0%</p>
                </div>
            </a>
            <!-- [AKHIR PERUBAHAN] -->

        @empty
            <!-- EMPTY STATE (Untuk Siswa) -->
            @if(Auth::user()->role === 'student')
                <div class="col-span-full flex flex-col items-center justify-center py-12 text-center">
                    <img src="{{ asset('images/empty-state.png') }}" class="w-32 h-32 mb-4 opacity-50" alt="No Courses">
                    <h3 class="text-lg font-medium text-gray-900">You don't have any class yet</h3>
                    <p class="text-gray-500 text-sm mt-2">Please wait for your lecturer to invite you.</p>
                </div>
            @endif
        @endforelse

        <!-- ADD COURSE BUTTON (HANYA LECTURER) -->
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
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg w-full max-w-md p-6 shadow-xl">
            <h3 class="text-lg font-bold mb-4">Create New Class</h3>

            <form wire:submit.prevent="saveCourse">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Course Title</label>
                        <input wire:model="title" type="text" placeholder="e.g. Secure Programming" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm p-2 border">
                        @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Major / Department</label>
                            <select wire:model="selectedMajorPrefix" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm p-2 border">
                                @foreach($majors as $prefix => $name)
                                    <option value="{{ $prefix }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-400 mt-1">Code auto-generated (e.g. COMPxxxxxxx)</p>
                            @error('selectedMajorPrefix') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Class Code</label>
                            <input wire:model="class_code" type="text" placeholder="LA07" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm p-2 border">
                            @error('class_code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Type</label>
                        <select wire:model="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm p-2 border">
                            <option value="LEC">LEC (Lecture)</option>
                            <option value="LAB">LAB (Laboratory)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Invite Student (Email)</label>
                        <input wire:model="student_email_invite" type="email" placeholder="student@binus.ac.id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm p-2 border">
                        <p class="text-xs text-gray-500 mt-1">Leave empty to invite later.</p>
                        @error('student_email_invite') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" wire:click="$set('showAddModal', false)" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700">Create Class</button>
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
