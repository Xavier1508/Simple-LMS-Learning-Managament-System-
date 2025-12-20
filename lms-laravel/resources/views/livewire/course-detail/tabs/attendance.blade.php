<div class="space-y-8 animate-fade-in">
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm mt-4">
        <div class="bg-amber-600 px-6 py-3">
            <div class="flex items-center gap-2 text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-medium text-sm">
                    @if(Auth::user()->role === 'student')
                        You almost reached the maximum limit of absent.
                    @else
                        Class Attendance Overview
                    @endif
                </span>
            </div>
        </div>

        <div class="p-6">
            @if(Auth::user()->role === 'student')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center divide-x divide-gray-100">
                    <div>
                        <p class="text-gray-500 text-sm mb-1">Total Session</p>
                        <p class="text-4xl font-light text-gray-800">{{ $attendanceSummary['total_sessions'] }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm mb-1">Total Attendance</p>
                        <p class="text-4xl font-light text-gray-800">{{ $attendanceSummary['total_attended'] }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm mb-1">Minimal Attendance</p>
                        <p class="text-4xl font-light text-gray-800">{{ $attendanceSummary['min_attendance'] }}</p>
                    </div>
                </div>
            @else
                {{-- TAMPILAN SUMMARY DOSEN (BARU - SESUAI REQUEST) --}}
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                    <div class="lg:col-span-1 flex flex-col justify-center items-center text-center border-r border-gray-100">
                        <div class="p-4 bg-orange-50 rounded-full mb-3">
                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <p class="text-gray-500 text-sm font-semibold uppercase tracking-wider">Total Students</p>
                        <p class="text-5xl font-bold text-gray-800 mt-2">{{ $attendanceSummary['total_students'] }}</p>
                        <p class="text-xs text-gray-400 mt-1">Registered in this class</p>
                    </div>

                    {{-- KANAN: Tabel Statistik Per Sesi (Scrollable) --}}
                    <div class="lg:col-span-3 overflow-x-auto">
                        <h4 class="text-sm font-bold text-gray-700 mb-3">Session Performance</h4>
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="border-b border-gray-200 bg-gray-50 text-gray-500 uppercase text-xs">
                                    <th class="px-4 py-2 font-semibold">Session</th>
                                    <th class="px-4 py-2 font-semibold">Date & Time</th>
                                    <th class="px-4 py-2 font-semibold text-center">Present</th>
                                    <th class="px-4 py-2 font-semibold text-center">Avg %</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($class->sessions as $sess)
                                    @php
                                        $presentCount = $sess->attendances->where('status', 'present')->count();
                                        $totalStudents = $class->students->count();
                                        $percentage = $totalStudents > 0 ? round(($presentCount / $totalStudents) * 100) : 0;
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 font-medium text-gray-900">S{{ $sess->session_number }}</td>
                                        <td class="px-4 py-2 text-gray-500">
                                            {{ $sess->start_time->format('d M Y') }} <span class="text-xs text-gray-400">({{ $sess->start_time->format('H:i') }})</span>
                                        </td>
                                        <td class="px-4 py-2 text-center text-gray-800 font-medium">
                                            {{ $presentCount }}
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <span class="font-bold {{ $percentage < 50 ? 'text-red-600' : ($percentage < 80 ? 'text-yellow-600' : 'text-green-600') }}">
                                                    {{ $percentage }}%
                                                </span>
                                                {{-- Mini Bar --}}
                                                <div class="w-12 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                                    <div class="h-full {{ $percentage < 50 ? 'bg-red-500' : ($percentage < 80 ? 'bg-yellow-500' : 'bg-green-500') }}" style="width: {{ $percentage }}%"></div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if(Auth::user()->role === 'student')
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 text-xs uppercase tracking-wide text-gray-500 bg-gray-50">
                        <th class="px-6 py-4 font-semibold">Session</th>
                        <th class="px-6 py-4 font-semibold">Delivery</th>
                        <th class="px-6 py-4 font-semibold">Date & Time</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold">Requirement</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($class->sessions as $session)
                        @php
                            $myRecord = $session->attendances->where('user_id', Auth::id())->first();
                            $statusData = $this->getStatusDisplay($session, $myRecord);
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-900">Session {{ $session->session_number }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $session->delivery_mode }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    <p class="text-gray-900 font-medium">{{ $session->start_time->format('d M Y') }}</p>
                                    <p class="text-gray-500 text-xs">{{ $session->start_time->format('H:i') }} - {{ $session->end_time->format('H:i') }} GMT+7</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{-- DYNAMIC ICON BASED ON NEW LOGIC --}}
                                <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-{{ $statusData['color'] }}-100 text-{{ $statusData['color'] }}-600" title="{{ $statusData['label'] }}">
                                    @if($statusData['icon'] === 'check')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    @elseif($statusData['icon'] === 'clock')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @elseif($statusData['icon'] === 'play')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @elseif($statusData['icon'] === 'user-x') {{-- Cancelled by Lecturer --}}
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 2.172V17h10.228a4 4 0 011.34-1.426M19.414 14.414l-3.828 3.828m3.828 0l-3.828-3.828"></path></svg>
                                    @else {{-- X (Absent / System Error) --}}
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2 text-sm">
                                    <span class="font-medium text-{{ $statusData['color'] }}-500">
                                        {{ $statusData['label'] }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    {{-- LECTURER VIEW: MATRIX GRID (TETAP SAMA TAPI FETCH DATA BARU) --}}
    @else
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-max">
                <thead>
                    <tr class="border-b border-gray-200 text-xs uppercase tracking-wide text-gray-500 bg-gray-50">
                        <th class="px-4 py-4 font-semibold sticky left-0 bg-gray-50 z-10 shadow-sm border-r">Student Name</th>
                        @foreach($class->sessions as $session)
                            <th class="px-2 py-4 font-semibold text-center min-w-[80px]">
                                <div>S{{ $session->session_number }}</div>
                                <div class="text-[10px] text-gray-400 mt-1 normal-case font-normal">{{ $session->start_time->format('d/m') }}</div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($class->students as $student)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            {{-- STICKY LEFT COLUMN: Student Name --}}
                            <td class="px-4 py-3 sticky left-0 bg-white group-hover:bg-gray-50 border-r z-10">
                                <div>
                                    <p class="font-medium text-sm text-gray-900 truncate max-w-[150px]">{{ $student->first_name }} {{ $student->last_name }}</p>
                                    <p class="text-xs text-gray-400">{{ $student->email }}</p>
                                </div>
                            </td>

                            {{-- SESSION COLUMNS: Attendance Matrix --}}
                            @foreach($class->sessions as $session)
                                @php
                                    $record = $session->attendances->where('user_id', $student->id)->first();
                                    $statusData = $this->getStatusDisplay($session, $record);
                                @endphp
                                <td class="px-2 py-3 text-center">
                                    <button wire:click="openManualAttendance({{ $student->id }}, {{ $session->id }})"
                                        class="relative group/icon focus:outline-none transition-transform hover:scale-110 active:scale-95"
                                        title="{{ $statusData['label'] }}">

                                        <div class="w-7 h-7 rounded-full bg-{{ $statusData['color'] }}-100 text-{{ $statusData['color'] }}-600 flex items-center justify-center mx-auto border border-{{ $statusData['color'] }}-200">
                                            @if($statusData['icon'] === 'check')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                            @elseif($statusData['icon'] === 'user-x')
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 2.172V17h10.228a4 4 0 011.34-1.426M19.414 14.414l-3.828 3.828m3.828 0l-3.828-3.828"></path></svg>
                                            @elseif($statusData['icon'] === 'clock')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            @endif
                                        </div>

                                        <div class="text-[9px] text-gray-400 mt-1 font-mono">
                                            {{ $session->start_time->format('H:i') }}
                                        </div>
                                    </button>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
