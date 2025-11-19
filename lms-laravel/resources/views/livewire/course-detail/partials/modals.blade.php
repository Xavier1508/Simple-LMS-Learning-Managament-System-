{{-- MODAL: Upload Material --}}
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

{{-- MODAL: Manual Attendance (Lecturer Override) --}}
@if($showManualAttendanceModal)
    <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm overflow-hidden animate-fade-in-up">
            <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Update Attendance</h3>
                <button wire:click="$set('showManualAttendanceModal', false)" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6">
                <div class="mb-4 text-center">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <p class="font-bold text-gray-900 text-lg">{{ $manualStudentName }}</p>
                    <p class="text-sm text-gray-500">{{ $manualSessionTitle }}</p>
                </div>

                <div class="space-y-3">
                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-green-50 transition {{ $manualStatus === 'present' ? 'border-green-500 bg-green-50 ring-1 ring-green-500' : 'border-gray-200' }}">
                        <input type="radio" wire:model="manualStatus" value="present" class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500">
                        <span class="ml-3 font-medium text-gray-700">Present</span>
                        <span class="ml-auto text-green-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </span>
                    </label>

                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-red-50 transition {{ $manualStatus === 'absent' ? 'border-red-500 bg-red-50 ring-1 ring-red-500' : 'border-gray-200' }}">
                        <input type="radio" wire:model="manualStatus" value="absent" class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500">
                        <span class="ml-3 font-medium text-gray-700">Absent</span>
                        <span class="ml-auto text-red-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </span>
                    </label>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                <button wire:click="$set('showManualAttendanceModal', false)" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900">Cancel</button>
                <button wire:click="saveManualAttendance" class="px-4 py-2 text-sm font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700 shadow-sm">Save Changes</button>
            </div>
        </div>
    </div>
@endif

{{-- MODAL: File Preview (AlpineJS) --}}
@push('modals')
    <div x-data="{
            show: false,
            url: '',
            type: '',
            name: ''
            }"
            @open-preview-modal.window="
            show = true;
            url = $event.detail.url;
            type = $event.detail.type;
            name = $event.detail.name;
            "
            x-show="show"
            style="display: none;"
            class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">

        <div class="absolute inset-0" @click="show = false"></div>

        <div class="relative z-10 flex flex-col w-full max-w-4xl h-[85vh] bg-white shadow-2xl rounded-xl overflow-hidden"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0">

            {{-- HEADER --}}
            <div class="flex items-center justify-between px-6 py-4 border-b bg-gray-50">
                <h3 class="pr-4 text-lg font-bold text-gray-800 truncate" x-text="name"></h3>
                <button @click="show = false" class="text-gray-400 transition hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            {{-- BODY --}}
            <div class="relative flex-1 w-full bg-gray-100 p-1">
                <template x-if="['pdf', 'jpg', 'jpeg', 'png', 'txt'].includes(type)">
                    <iframe :src="url" class="w-full h-full border-0 rounded"></iframe>
                </template>

                <template x-if="!['pdf', 'jpg', 'jpeg', 'png', 'txt'].includes(type)">
                    <div class="flex flex-col items-center justify-center h-full text-center p-10">
                        <div class="mb-4 text-gray-400">
                            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <p class="mb-6 font-medium text-gray-600">Preview not available for this file type.</p>
                        <a :href="url" download class="inline-flex items-center px-6 py-2.5 bg-orange-600 text-white rounded-lg hover:bg-orange-700 shadow transition">
                            Download File
                        </a>
                    </div>
                </template>
            </div>
        </div>
    </div>
@endpush
