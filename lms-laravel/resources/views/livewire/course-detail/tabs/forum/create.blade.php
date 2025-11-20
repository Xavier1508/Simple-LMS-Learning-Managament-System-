<div class="bg-gray-50 p-6 min-h-screen">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Create New Thread</h2>
        <button wire:click="switchToForumList" class="text-gray-500 hover:text-gray-800 text-sm flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            Cancel
        </button>
    </div>

    {{-- Main Form --}}
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 space-y-6">

        {{-- Top Info --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Course</label>
                <div class="text-sm text-gray-800 font-medium p-2 bg-gray-50 rounded border border-gray-200">
                    {{ $class->course->code }} - {{ $class->type }} - {{ $class->course->title }}
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Session</label>
                <select wire:model="targetSessionId" class="w-full text-sm border border-gray-300 rounded p-2 focus:ring-orange-500 focus:border-orange-500">
                    @foreach($class->sessions as $session)
                        <option value="{{ $session->id }}">Session {{ $session->session_number }} - {{ \Illuminate\Support\Str::limit($session->title, 30) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Thread To (Lecturer Only) --}}
        @if(Auth::user()->role === 'lecturer')
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Thread To</label>
            <div class="border border-gray-300 rounded p-3 space-y-2">
                <div class="flex items-center">
                    <input type="checkbox" checked disabled class="text-orange-500 rounded focus:ring-orange-500 cursor-not-allowed">
                    <span class="ml-2 text-sm text-gray-700 font-medium">{{ $class->class_code }} (Current Class)</span>
                </div>

                {{-- Cross Posting Options --}}
                @if(count($availableCrossClasses) > 0)
                    <p class="text-xs text-gray-400 mt-2 mb-1 border-t pt-2">Also post to other classes (Same Course):</p>
                    @foreach($availableCrossClasses as $crossClass)
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="crossPostClassIds" value="{{ $crossClass->id }}" class="text-orange-500 rounded focus:ring-orange-500 cursor-pointer">
                            <span class="ml-2 text-sm text-gray-700">{{ $crossClass->class_code }} - {{ $crossClass->semester }}</span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        @endif

        {{-- Title --}}
        <div>
            <input type="text" wire:model="newThreadTitle" placeholder="Write title here..."
                class="w-full text-lg font-bold border-0 border-b border-gray-300 px-0 py-2 focus:ring-0 focus:border-orange-500 placeholder-gray-300">
            @error('newThreadTitle') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        {{-- Editor Area --}}
        <div class="border border-gray-300 rounded-lg overflow-hidden">
            <textarea wire:model="newThreadContent" rows="8" placeholder="Write something inspiring..."
                class="w-full border-0 p-4 focus:ring-0 text-sm text-gray-700 resize-none"></textarea>

            {{-- Toolbar (Visual) --}}
            <div class="bg-gray-50 border-t border-gray-200 p-2 flex items-center gap-2 text-gray-500">
                <button class="p-1.5 hover:bg-gray-200 rounded"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg></button>
                <button class="p-1.5 hover:bg-gray-200 rounded"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10h-10a8 8 0 00-8 8v2M21 10l-6 6m6-6l-6-6"></path></svg></button>
                <div class="w-px h-4 bg-gray-300 mx-1"></div>
                <button class="p-1.5 hover:bg-gray-200 rounded font-bold serif">B</button>
                <button class="p-1.5 hover:bg-gray-200 rounded italic serif">I</button>
                <button class="p-1.5 hover:bg-gray-200 rounded underline serif">U</button>
                <div class="w-px h-4 bg-gray-300 mx-1"></div>
                <button class="p-1.5 hover:bg-gray-200 rounded relative">
                    <input type="file" wire:model="newThreadAttachment" class="absolute inset-0 opacity-0 cursor-pointer" title="Attach File">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                </button>
                @if($newThreadAttachment)
                    <span class="text-xs text-green-600 truncate max-w-[150px]">{{ $newThreadAttachment->getClientOriginalName() }}</span>
                @endif
            </div>
        </div>
        @error('newThreadContent') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

        {{-- LECTURER OPTIONS --}}
        @if(Auth::user()->role === 'lecturer')
            <div class="bg-orange-50 border border-orange-100 p-4 rounded space-y-4">
                <h4 class="text-xs font-bold text-orange-800 uppercase">Lecturer Options</h4>

                <div class="flex items-center gap-8">
                    {{-- Toggle Hidden --}}
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="isThreadHidden" class="sr-only peer">
                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-600"></div>
                        <span class="ms-3 text-sm font-medium text-gray-700">Hidden Replies Mode</span>
                    </label>

                    {{-- Toggle Assessment --}}
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="isThreadAssessment" class="sr-only peer">
                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                        <span class="ms-3 text-sm font-medium text-gray-700">Set as Assessment</span>
                    </label>
                </div>

                {{-- Deadline Input (If Assessment) --}}
                @if($isThreadAssessment)
                    <div class="mt-2 animate-fade-in-down">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Deadline</label>
                        <input type="datetime-local" wire:model="threadDeadline" class="border border-gray-300 rounded p-2 text-sm focus:ring-red-500 focus:border-red-500">
                        @error('threadDeadline') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                    </div>
                @endif
            </div>
        @endif

        {{-- Submit Button --}}
        <div class="flex justify-end">
            <button wire:click="createThread" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-8 rounded shadow-lg transition transform hover:-translate-y-0.5">
                POST
            </button>
        </div>
    </div>
</div>
