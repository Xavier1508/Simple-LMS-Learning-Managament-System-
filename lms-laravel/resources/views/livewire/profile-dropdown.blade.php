<div x-data="{ open: false }" class="relative">

    <button @click="open = !open" class="flex items-center gap-2 cursor-pointer">
        <img src="https://placehold.co/100x100/D97706/FFFFFF?text={{ substr(Auth::user()->name, 0, 1) }}" class="w-8 h-8 rounded-full" alt="Avatar">
        <span class="hidden sm:block">{{ Auth::user()->name }} ▼</span>
    </button>

    <div x-show="open"
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="absolute right-0 mt-2 w-72 bg-white rounded-md shadow-lg overflow-hidden z-20"
         style="display: none;">

        <div class="flex items-center gap-3 p-4 border-b border-gray-200">
            <img src="https://placehold.co/100x100/D97706/FFFFFF?text={{ substr(Auth::user()->name, 0, 1) }}" class="w-12 h-12 rounded-full" alt="Avatar">
            <div>
                <div class="font-semibold text-gray-900">{{ Auth::user()->name }}</div>
                <div class="text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>
        </div>

        <nav class="py-2">
            <a href="{{ route('profile') }}" wire:navigate
               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <i class="fa-solid fa-key w-5 text-center text-gray-500"></i>
                Change Password
            </a>
            <a href="#"
               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <i class="fa-solid fa-circle-question w-5 text-center text-gray-500"></i>
                Help and Support
            </a>
        </nav>

        <div class="border-t border-gray-200 p-2">
            <button wire:click="logout"
               class="w-full flex items-center gap-3 px-4 py-2 text-sm text-left text-red-600 hover:bg-red-50 rounded-md">
                <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i>
                Logout
            </button>
        </div>
    </div>
</div>
