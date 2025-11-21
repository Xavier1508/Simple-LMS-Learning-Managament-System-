<div>
    {{-- MAIN SEARCH BAR --}}
    {{-- Wrapper ini tetap ada di dalam root div --}}
    <div class="relative w-full max-w-xl" x-data="{ focused: false }" @click.away="focused = false">

        {{-- Search Input Container --}}
        <div class="relative">
            <input
                type="text"
                wire:model.live.debounce.300ms="query"
                @focus="focused = true"
                @keydown.escape="focused = false"
                @keydown.enter="if($wire.query.length >= 2) { $wire.openAdvancedSearch(); focused = false; }"
                placeholder="Search courses, assignments, or discussions..."
                class="w-full border border-gray-300 rounded-lg pl-10 pr-24 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition shadow-sm text-sm"
            >

            {{-- Search Icon (Kiri) --}}
            <div class="absolute left-3 top-2.5 text-gray-400 pointer-events-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>

            {{-- Action Buttons Container (Kanan) --}}
            <div class="absolute right-2 top-1.5 flex items-center gap-1">
                {{-- Loading Spinner --}}
                <div wire:loading wire:target="query" class="px-2">
                    <svg class="animate-spin h-4 w-4 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>

                {{-- Clear Button --}}
                @if(!empty($query))
                    <button
                        wire:click="$set('query', '')"
                        wire:loading.remove
                        wire:target="query"
                        type="button"
                        class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                @endif

                {{-- Advanced Search Button --}}
                <button
                    wire:click="openAdvancedSearch"
                    type="button"
                    class="px-3 py-1.5 bg-orange-500 text-white text-xs font-semibold rounded-md hover:bg-orange-600 transition flex items-center gap-1 shadow-sm"
                    title="Advanced Search & Filters">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    <span class="hidden sm:inline">Filter</span>
                </button>
            </div>
        </div>

        {{-- DROPDOWN QUICK RESULTS --}}
        @if(strlen($query) >= 2)
            <div x-show="focused"
                 x-cloak
                 class="absolute top-full left-0 w-full bg-white rounded-xl shadow-2xl border border-gray-200 mt-2 z-50 overflow-hidden max-h-[500px] overflow-y-auto"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-2">

                @if(!empty($results))
                    @foreach($results as $category => $items)
                        <div class="py-2 border-b border-gray-100 last:border-0">
                            {{-- Kategori Header --}}
                            <div class="px-4 py-1.5 bg-gray-50 flex items-center justify-between sticky top-0 z-10">
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-2">
                                    @if($category == 'Courses')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    @elseif($category == 'Assignments')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                    @elseif($category == 'Forum Discussions')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                                    @endif
                                    {{ $category }}
                                </span>
                                <span class="text-[10px] bg-white px-1.5 py-0.5 rounded border border-gray-200 text-gray-400">{{ count($items) }}</span>
                            </div>

                            <ul>
                                @foreach($items as $item)
                                    <li>
                                        <a href="{{ $item['url'] }}"
                                           @click="focused = false"
                                           class="flex items-center px-4 py-3 hover:bg-orange-50 transition group border-l-4 border-transparent hover:border-orange-500">
                                            {{-- Icon Box --}}
                                            <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-gray-100 text-gray-500 flex items-center justify-center group-hover:bg-white group-hover:text-orange-600 group-hover:shadow-sm transition">
                                                @if($item['type'] == 'Course')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                                @elseif($item['type'] == 'Assessment')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                                                @endif
                                            </div>

                                            {{-- Text Content --}}
                                            <div class="ml-3 overflow-hidden flex-1">
                                                <p class="text-sm font-bold text-gray-800 truncate group-hover:text-orange-600 transition-colors">{{ $item['title'] }}</p>
                                                <p class="text-xs text-gray-500 truncate">{{ $item['subtitle'] }}</p>
                                            </div>

                                            {{-- Arrow Icon --}}
                                            <div class="ml-auto text-gray-300 group-hover:text-orange-400 transform group-hover:translate-x-1 transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach

                    {{-- View All Results Link --}}
                    <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                        <button
                            wire:click="openAdvancedSearch"
                            @click="focused = false"
                            class="w-full text-center text-sm font-semibold text-orange-600 hover:text-orange-700 flex items-center justify-center gap-2 py-2 hover:bg-orange-50 rounded transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            View All Results & Apply Filters
                        </button>
                    </div>
                @else
                    {{-- EMPTY STATE --}}
                    <div class="p-8 text-center">
                        <div class="bg-gray-50 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <h4 class="text-gray-900 font-medium text-sm">No results found</h4>
                        <p class="text-gray-500 text-xs mt-1">We couldn't find anything matching "<span class="font-bold">{{ $query }}</span>"</p>
                        <button
                            wire:click="openAdvancedSearch"
                            @click="focused = false"
                            class="mt-3 text-xs text-orange-600 hover:text-orange-700 font-medium">
                            Try Advanced Search →
                        </button>
                    </div>
                @endif
            </div>
        @endif
    </div>

    {{-- ADVANCED SEARCH MODAL --}}
    {{-- Modal dan Style sekarang berada DI DALAM root div utama --}}
    @if($showAdvancedSearch)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-[100] flex items-start justify-center pt-10 px-4 overflow-y-auto"
             x-data="{ show: @entangle('showAdvancedSearch') }"
             x-show="show"
             x-cloak
             @click.self="$wire.closeAdvancedSearch()"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl mb-10"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 @click.stop>

                {{-- Modal Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-orange-50 to-orange-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Advanced Search</h3>
                            <p class="text-xs text-gray-600">Filter and refine your search results</p>
                        </div>
                    </div>
                    <button
                        wire:click="closeAdvancedSearch"
                        class="text-gray-400 hover:text-gray-600 p-2 hover:bg-gray-100 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="p-6">
                    {{-- Search Input --}}
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Search Query</label>
                        <input
                            type="text"
                            wire:model.live.debounce.500ms="query"
                            placeholder="Enter keywords to search..."
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition">
                    </div>

                    {{-- Filter Options --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        {{-- Content Type Filter --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Content Type</label>
                            <select class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-400 text-sm">
                                <option value="">All Types</option>
                                <option value="courses">Courses Only</option>
                                <option value="assignments">Assignments Only</option>
                                <option value="discussions">Discussions Only</option>
                            </select>
                        </div>

                        {{-- Date Range Filter --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Date Range</label>
                            <select class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-400 text-sm">
                                <option value="">Any Time</option>
                                <option value="today">Today</option>
                                <option value="week">This Week</option>
                                <option value="month">This Month</option>
                                <option value="semester">This Semester</option>
                            </select>
                        </div>

                        {{-- Sort By Filter --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Sort By</label>
                            <select class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-orange-400 text-sm">
                                <option value="relevance">Relevance</option>
                                <option value="date_desc">Newest First</option>
                                <option value="date_asc">Oldest First</option>
                                <option value="title">Title (A-Z)</option>
                            </select>
                        </div>
                    </div>

                    {{-- Search Results --}}
                    <div class="border border-gray-200 rounded-xl overflow-hidden bg-gray-50">
                        <div class="px-4 py-3 bg-white border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h4 class="font-semibold text-gray-900">Search Results</h4>
                                @if(!empty($results))
                                    <span class="text-xs text-gray-500">{{ collect($results)->flatten(1)->count() }} results found</span>
                                @endif
                            </div>
                        </div>

                        <div class="max-h-96 overflow-y-auto p-4">
                            @if(!empty($results))
                                @foreach($results as $category => $items)
                                    <div class="mb-4 last:mb-0">
                                        <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 px-2">{{ $category }}</h5>
                                        <div class="space-y-2">
                                            @foreach($items as $item)
                                                <a href="{{ $item['url'] }}"
                                                   wire:click="closeAdvancedSearch"
                                                   class="block bg-white border border-gray-200 rounded-lg p-4 hover:border-orange-300 hover:shadow-md transition group">
                                                    <div class="flex items-start gap-3">
                                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center group-hover:bg-orange-500 group-hover:text-white transition">
                                                            @if($item['type'] == 'Course')
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                                            @elseif($item['type'] == 'Assessment')
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                                            @else
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                                                            @endif
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <h6 class="font-semibold text-gray-900 group-hover:text-orange-600 transition mb-1">{{ $item['title'] }}</h6>
                                                            <p class="text-xs text-gray-500">{{ $item['subtitle'] }}</p>
                                                        </div>
                                                        <svg class="w-5 h-5 text-gray-300 group-hover:text-orange-500 transform group-hover:translate-x-1 transition-all flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-12">
                                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    <p class="text-gray-500 text-sm">No results found. Try different keywords or filters.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end gap-3">
                    <button
                        wire:click="$set('query', '')"
                        class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition">
                        Clear All
                    </button>
                    <button
                        wire:click="closeAdvancedSearch"
                        class="px-6 py-2 bg-orange-500 text-white text-sm font-semibold rounded-lg hover:bg-orange-600 transition shadow-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif

    <style>
        [x-cloak] { display: none !important; }
    </style>
</div>
