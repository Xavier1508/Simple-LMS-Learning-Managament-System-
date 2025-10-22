<x-app-layout>
    @php
        $calendarEvents = [
            [
                'title' => 'COMP6873: Blockchain',
                'start' => '2025-10-27T09:00:00',
                'end' => '2025-10-27T11:00:00',
                'description' => 'Sesi Online via Zoom',
                'className' => 'event-online'
            ],
            [
                'title' => 'COMP6062: Compilation',
                'start' => '2025-10-28T13:00:00',
                'end' => '2025-10-28T15:00:00',
                'description' => 'Kelas Offline di A-0803',
                'className' => 'event-offline'
            ],
            [
                'title' => 'COMP6646: Forensic',
                'start' => '2025-10-29T09:00:00',
                'end' => '2025-10-29T11:00:00',
                'description' => 'Sesi Online via Zoom',
                'className' => 'event-online'
            ],
            [
                'title' => 'COMP6873: Blockchain',
                'start' => '2025-11-03T09:00:00',
                'end' => '2025-11-03T11:00:00',
                'description' => 'Sesi Online via Zoom',
                'className' => 'event-online'
            ],
            [
                'title' => 'COMP6062: Compilation',
                'start' => '2025-11-04T13:00:00',
                'end' => '2025-11-04T15:00:00',
                'description' => 'Kelas Offline di A-0803',
                'className' => 'event-offline'
            ],
        ];
    @endphp

    <style>
        @import url('https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css');

        .fc {
            background-color: white;
            padding: 1.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            border: none;
        }
        .fc .fc-toolbar-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
        }
        .fc .fc-button-primary {
            background-color: #F97316; /* Orange */
            border-color: #F97316;
            font-weight: 600;
        }
        .fc .fc-button-primary:hover, .fc .fc-button-primary:active {
            background-color: #EA580C;
            border-color: #EA580C;
        }
        .fc .fc-daygrid-day.fc-day-today { background-color: #FFFBEB; }
        .fc-daygrid-day-frame { position: relative; }
        .fc-daygrid-event-dot { border-width: 4px; margin-top: 2px; }
        .event-online .fc-daygrid-event-dot { border-color: #3B82F6; }
        .event-offline .fc-daygrid-event-dot { border-color: #F97316; }
    </style>

    <main class="flex-1 p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">My Schedule</h1>

            <div class="flex items-center space-x-4">
                <div class="flex items-center">
                    <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                    <span class="text-sm text-gray-600">Online Class</span>
                </div>
                <div class="flex items-center">
                    <span class="w-3 h-3 bg-orange-500 rounded-full mr-2"></span>
                    <span class="text-sm text-gray-600">Offline Class</span>
                </div>
            </div>
        </div>

        <div id="calendar"></div>
    </main>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
        <script>
          (function initCalendar() {
            var calendarEl = document.getElementById('calendar');
            if (!calendarEl || typeof window.FullCalendar === 'undefined') {
              console.error('FullCalendar global not available or #calendar missing');
              return;
            }
            var calendar = new window.FullCalendar.Calendar(calendarEl, {
              initialView: 'dayGridMonth',
              headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
              },
              events: @json($calendarEvents),
              displayEventTime: false,
              eventDisplay: 'dot',
              eventMouseEnter: function(info) {
                info.el.setAttribute('title', info.event.extendedProps.description);
              }
            });
            calendar.render();

            if (window.lucide && typeof window.lucide.createIcons === 'function') {
              window.lucide.createIcons();
            }
          })();
        </script>
    @endpush
</x-app-layout>
