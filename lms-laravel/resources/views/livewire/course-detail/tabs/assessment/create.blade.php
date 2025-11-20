<div class="bg-gray-50 min-h-screen p-6">
    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">

        {{-- Header --}}
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-orange-50">
            <h2 class="text-xl font-bold text-orange-800">Create New Assessment</h2>
            <button wire:click="switchToAssessmentList" class="text-gray-500 hover:text-gray-800 text-sm flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                Cancel
            </button>
        </div>

        <div class="p-8 space-y-6">
            {{-- Title --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Assessment Title</label>
                <input type="text" wire:model="newAssessTitle" placeholder="e.g., Final Project: Web Security"
                    class="w-full border border-gray-300 rounded-lg p-3 focus:ring-orange-500 focus:border-orange-500 transition">
                @error('newAssessTitle') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Instructions / Description</label>
                <textarea wire:model="newAssessDesc" rows="5" placeholder="Describe the task details here..."
                    class="w-full border border-gray-300 rounded-lg p-3 focus:ring-orange-500 focus:border-orange-500 transition"></textarea>
                @error('newAssessDesc') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Layout Grid 2 Kolom --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Due Date --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Due Date & Time</label>
                    <input type="datetime-local" wire:model="newAssessDue"
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-orange-500 focus:border-orange-500 transition">
                    @error('newAssessDue') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Attachment --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Attachment (Optional)</label>
                    <div class="relative border-dashed border-2 border-gray-300 rounded-lg p-3 bg-gray-50 hover:bg-gray-100 transition text-center cursor-pointer">
                        <input type="file" wire:model="newAssessFile" class="absolute inset-0 opacity-0 cursor-pointer">
                        <div class="flex flex-col items-center justify-center pointer-events-none">
                            <svg class="w-6 h-6 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                            <span class="text-xs text-gray-500">
                                {{ $newAssessFile ? $newAssessFile->getClientOriginalName() : 'Click to upload file' }}
                            </span>
                        </div>
                    </div>
                    @error('newAssessFile') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Submit Action --}}
            <div class="flex justify-end pt-6 border-t border-gray-100">
                <button wire:click="createAssignment" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition transform hover:-translate-y-1 flex items-center">
                    <span wire:loading.remove wire:target="createAssignment">POST ASSESSMENT</span>
                    <span wire:loading wire:target="createAssignment">Posting...</span>
                </button>
            </div>
        </div>
    </div>
</div>
