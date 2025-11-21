<div class="relative w-full max-w-xl" x-data="{ focused: false }" @click.away="focused = false">

    {{-- Search Input --}}
    <div class="relative">
        <input
            type="text"
            wire:model.live.debounce.300ms="query"
            @focus="focused = true"
            placeholder="Search courses, assignments, or discussions..."
            class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition shadow-sm text-sm"
        >
        {{-- Search Icon --}}
        <div class="absolute left-3 top-2.5 text-gray-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>

        {{-- Loading Indicator (Spinner Kecil di Kanan) --}}
        <div wire:loading class="absolute right-3 top-3">
            <svg class="animate-spin h-4 w-4 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>

    {{-- Dropdown Results --}}
    @if(strlen($query) >= 2 && $focused)
        <div class="absolute top-full left-0 w-full bg-white rounded-xl shadow-xl border border-gray-200 mt-2 z-50 overflow-hidden max-h-96 overflow-y-auto"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0">

            @forelse($results as $category => $items)
                <div class="py-2">
                    <div class="px-4 py-1 text-xs font-bold text-gray-400 uppercase tracking-wider bg-gray-50">
                        {{ $category }}
                    </div>
                    <ul>
                        @foreach($items as $item)
                            <li>
                                <a href="{{ $item['url'] }}" class="flex items-center px-4 py-3 hover:bg-orange-50 transition group">
                                    {{-- Icon Box --}}
                                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-gray-100 text-gray-500 flex items-center justify-center group-hover:bg-orange-100 group-hover:text-orange-600 transition">
                                        @if($item['icon'] == 'book')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                        @elseif($item['icon'] == 'clipboard')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                                        @endif
                                    </div>

                                    {{-- Text --}}
                                    <div class="ml-3 overflow-hidden">
                                        <p class="text-sm font-bold text-gray-800 truncate group-hover:text-orange-600">{{ $item['title'] }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $item['subtitle'] }}</p>
                                    </div>

                                    {{-- Arrow --}}
                                    <div class="ml-auto text-gray-300 group-hover:text-orange-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">
                    <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <p class="text-sm">No results found for "{{ $query }}"</p>
                </div>
            @endforelse
        </div>
    @endif
</div>
