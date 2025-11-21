<div class="p-8 bg-gray-50 min-h-screen">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Schedule & Calendar</h1>
            <p class="text-sm text-gray-500">Manage your academic timeline.</p>
        </div>

        {{-- Legend Warna --}}
        <div class="flex items-center space-x-4 bg-white p-2 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <span class="w-3 h-3 bg-gray-400 rounded-full mr-2"></span>
                <span class="text-xs text-gray-600 font-medium">Finished</span>
            </div>
            <div class="flex items-center">
                <span class="w-3 h-3 rounded-full mr-2 bg-gradient-to-r from-blue-400 to-purple-500"></span>
                <span class="text-xs text-gray-600 font-medium">Upcoming (Course Color)</span>
            </div>
        </div>
    </div>

    {{-- CALENDAR CONTAINER --}}
    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 relative mb-10">
        <div id="calendar" wire:ignore></div>
    </div>

    {{-- ================================================== --}}
    {{-- SECTION BARU: TODAY ACTIVITIES --}}
    {{-- ================================================== --}}

    <div>
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800">
                    {{ $role === 'student' ? 'Daily Activities' : 'Teaching Activities' }}
                </h2>
                <p class="text-sm text-gray-500">
                    {{ $role === 'student' ? 'Your classes for' : 'Your teaching schedule for' }}
                    <span class="font-bold text-orange-600">{{ \Carbon\Carbon::parse($viewDate)->format('d F Y') }}</span>
                </p>
            </div>

            {{-- Date Toggle (Prev | Today | Next) --}}
            <div class="flex bg-white rounded-lg shadow-sm border border-gray-200 p-1">
                <button wire:click="setDay('prev')" class="px-4 py-2 text-xs font-bold text-gray-500 hover:bg-gray-50 hover:text-gray-700 border-r border-gray-100 transition">
                    &larr; Prev
                </button>
                <button wire:click="setDay('today')" class="px-6 py-2 text-xs font-bold {{ $viewLabel === 'Today' ? 'bg-orange-50 text-orange-600' : 'text-gray-600 hover:bg-gray-50' }} transition">
                    {{ $viewLabel }}
                </button>
                <button wire:click="setDay('next')" class="px-4 py-2 text-xs font-bold text-gray-500 hover:bg-gray-50 hover:text-gray-700 border-l border-gray-100 transition">
                    Next &rarr;
                </button>
            </div>
        </div>

        {{-- Activity Cards Grid --}}
        <div class="grid grid-cols-1 gap-4">
            @forelse($dailyActivities as $activity)
                <a href="{{ route('courses.detail', ['id' => $activity->class_id, 'tab' => 'session', 'session_id' => $activity->id]) }}"
                   class="group bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition duration-300 overflow-hidden relative flex flex-col md:flex-row">

                    {{-- Color Strip (Matching Calendar) --}}
                    <div class="w-full md:w-3 h-2 md:h-auto" style="background-color: {{ $activity->is_past ? '#9CA3AF' : $activity->color }}"></div>

                    <div class="p-5 flex-1 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        {{-- Time Section --}}
                        <div class="min-w-[120px] text-center md:text-left">
                            <p class="text-2xl font-bold {{ $activity->is_past ? 'text-gray-400' : 'text-gray-800' }}">
                                {{ $activity->start_time->format('H:i') }}
                            </p>
                            <p class="text-xs text-gray-500 uppercase font-semibold">
                                Until {{ $activity->end_time->format('H:i') }}
                            </p>
                        </div>

                        {{-- Details Section --}}
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded border {{ $activity->is_past ? 'bg-gray-100 text-gray-500 border-gray-200' : 'bg-orange-50 text-orange-600 border-orange-100' }} uppercase">
                                    {{ $activity->course_code }}
                                </span>
                                <span class="text-xs text-gray-400 font-mono">{{ $activity->class_code }}</span>
                            </div>
                            <h3 class="text-lg font-bold {{ $activity->is_past ? 'text-gray-500' : 'text-gray-800 group-hover:text-orange-600 transition' }}">
                                {{ $activity->course_title }}
                            </h3>
                            <p class="text-sm text-gray-500 mt-1 flex items-center gap-2">
                                <span class="font-medium text-gray-700">Session {{ $activity->session_number }}:</span>
                                {{ \Illuminate\Support\Str::limit($activity->title, 50) }}
                            </p>
                        </div>

                        {{-- Meta Section --}}
                        <div class="text-right min-w-[150px] flex flex-row md:flex-col justify-between w-full md:w-auto items-center md:items-end gap-2 border-t md:border-t-0 border-gray-100 pt-3 md:pt-0 mt-2 md:mt-0">
                            <div class="flex items-center gap-1 text-xs font-medium text-gray-600 bg-gray-50 px-2 py-1 rounded">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                {{ $activity->delivery_mode }}
                            </div>

                            @if($role === 'student')
                                <div class="text-xs text-gray-400 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    {{ $activity->lecturer_name }}
                                </div>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                {{-- Empty State --}}
                <div class="bg-white rounded-xl border border-dashed border-gray-300 p-10 text-center flex flex-col items-center justify-center">
                    <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-gray-900 font-medium text-lg">No Activities Today</h3>
                    <p class="text-gray-500 text-sm mt-1">You have no classes or sessions scheduled for {{ $viewLabel }}.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- STYLES & SCRIPTS --}}
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
        <style>
            .fc { font-family: 'Figtree', sans-serif; }
            .fc .fc-toolbar-title { font-size: 1.25rem; font-weight: 800; color: #1f2937; }
            .fc .fc-button-primary { background-color: white; border-color: #e5e7eb; color: #374151; font-weight: 600; text-transform: capitalize; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
            .fc .fc-button-primary:hover { background-color: #f9fafb; border-color: #d1d5db; color: #111827; }
            .fc .fc-button-primary:not(:disabled).fc-button-active { background-color: #f97316; border-color: #f97316; color: white; }
            .fc-daygrid-event { border-radius: 4px; font-size: 0.75rem; font-weight: 600; padding: 2px 4px; cursor: pointer; }
            .fc-event-title { font-weight: 600; }
            .tippy-box[data-theme~='light-border'] { background-color: white; color: #333; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); border: 1px solid #e5e7eb; border-radius: 8px; font-size: 0.8rem; }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
        <script src="https://unpkg.com/@popperjs/core@2"></script>
        <script src="https://unpkg.com/tippy.js@6"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');
                var eventsData = @json($events);

                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,listWeek' },
                    events: eventsData,
                    eventTimeFormat: { hour: '2-digit', minute: '2-digit', meridiem: false },
                    dayMaxEvents: true,

                    // --- LOGIC KLIK KALENDER (REDIRECT) ---
                    eventClick: function(info) {
                        // Redirect ke Course Detail -> Tab Session -> Open Specific Session ID
                        var props = info.event.extendedProps;
                        // Format URL: /courses/{course_class_id}?tab=session&session_id={session_id}
                        window.location.href = '/courses/' + props.class_id + '?tab=session&session_id=' + info.event.id;
                    },

                    // --- LOGIC HOVER (TOOLTIP) ---
                    eventDidMount: function(info) {
                        var props = info.event.extendedProps;
                        var content = `
                            <div class="text-left p-2">
                                <div class="font-bold text-orange-600 mb-1 text-[10px] uppercase tracking-wide">${props.status}</div>
                                <div class="font-bold text-gray-900 text-sm leading-tight">${props.course_name}</div>
                                <div class="text-xs text-gray-500 mb-2 font-mono mt-1">${props.course_code} - ${props.class_code}</div>
                                <div class="border-t border-gray-100 pt-2 mt-1 flex justify-between gap-4 text-xs">
                                    <div><span class="text-gray-400 block text-[9px] uppercase">Session</span><span class="font-medium text-gray-700">${props.session_no}</span></div>
                                    <div><span class="text-gray-400 block text-[9px] uppercase">Time</span><span class="font-medium text-gray-700">${props.time_range}</span></div>
                                </div>
                            </div>
                        `;
                        tippy(info.el, { content: content, allowHTML: true, theme: 'light-border', placement: 'top', animation: 'shift-away' });
                    }
                });
                calendar.render();
            });
        </script>
    @endpush
</div>
