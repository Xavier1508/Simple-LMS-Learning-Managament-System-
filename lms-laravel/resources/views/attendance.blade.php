<x-app-layout>
    {{-- Data statis sementara, nanti akan diganti dari Controller --}}
    @php
        $attendanceLog = [
            ['date' => '2025-09-02', 'topic' => 'Lesson on Network Protocols', 'status' => 'Present', 'remarks' => 'On time'],
            ['date' => '2025-09-09', 'topic' => 'Cryptography Basics', 'status' => 'Late', 'remarks' => 'Joined 15 minutes late'],
            ['date' => '2025-09-16', 'topic' => 'Firewall Configuration', 'status' => 'Present', 'remarks' => 'On time'],
            ['date' => '2025-09-23', 'topic' => 'Intro to SQL Injection', 'status' => 'Absent', 'remarks' => 'Unexcused absence'],
            ['date' => '2025-09-30', 'topic' => 'Midterm Review', 'status' => 'Present', 'remarks' => 'On time'],
            ['date' => '2025-10-07', 'topic' => 'Vulnerability Scanning', 'status' => 'Present', 'remarks' => 'On time'],
            ['date' => '2025-10-14', 'topic' => 'Forensics Tools', 'status' => 'Present', 'remarks' => 'On time'],
            ['date' => '2025-10-21', 'topic' => 'Incident Response', 'status' => 'Absent', 'remarks' => 'Medical leave'],
        ];

        $totalSessions = count($attendanceLog);
        $presentCount = count(array_filter($attendanceLog, fn($a) => $a['status'] === 'Present'));
        $lateCount = count(array_filter($attendanceLog, fn($a) => $a['status'] === 'Late'));
        $absentCount = count(array_filter($attendanceLog, fn($a) => $a['status'] === 'Absent'));
        $absenceRate = $totalSessions > 0 ? (($absentCount / $totalSessions) * 100) : 0;

        $statusStyles = [
            'Present' => 'status-Present',
            'Late' => 'status-Late',
            'Absent' => 'status-Absent',
        ];
        $statusIcons = [
            'Present' => 'check-circle',
            'Late' => 'clock-history',
            'Absent' => 'x-circle',
        ];
    @endphp

    <style>
        .grade-table th, .grade-table td { padding: 12px 16px; text-align: left; }
        .grade-table th { font-weight: 600; color: #374151; background-color: #f9fafb; }
        .status-Present { color: #10B981; font-weight: 600; }
        .status-Late { color: #F59E0B; font-weight: 600; }
        .status-Absent { color: #EF4444; font-weight: 600; }
    </style>

    <main class="flex-1 p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Attendance Overview</h1>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-5 rounded-lg shadow-md border border-gray-200 flex flex-col items-start">
                <p class="text-xs text-gray-500 font-medium">Total Sessions</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalSessions }}</p>
            </div>
            <div class="bg-white p-5 rounded-lg shadow-md border border-gray-200 flex flex-col items-start">
                <p class="text-xs text-gray-500 font-medium">Sessions Present</p>
                <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $presentCount }}</p>
            </div>
            <div class="bg-white p-5 rounded-lg shadow-md border border-gray-200 flex flex-col items-start">
                <p class="text-xs text-gray-500 font-medium">Sessions Late</p>
                <p class="text-2xl font-bold text-orange-500 mt-1">{{ $lateCount }}</p>
            </div>
            <div class="bg-white p-5 rounded-lg shadow-md border border-gray-200 flex flex-col items-start">
                <p class="text-xs text-gray-500 font-medium">Absence Rate</p>
                <p class="text-2xl font-bold text-red-600 mt-1">{{ number_format($absenceRate, 1) }}%</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-xl overflow-x-auto">
            <h2 class="text-lg font-semibold text-gray-800 p-4 border-b">Detailed Attendance Log</h2>
            <table class="grade-table w-full text-sm text-gray-700">
                <thead class="text-xs uppercase">
                    <tr>
                        <th class="rounded-tl-lg">Date</th>
                        <th>Session Topic</th>
                        <th>Status</th>
                        <th class="rounded-tr-lg">Remarks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($attendanceLog as $log)
                        <tr class="hover:bg-gray-50 transition duration-100">
                            <td class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($log['date'])->format('M d, Y') }}</td>
                            <td>{{ $log['topic'] }}</td>
                            <td @class($statusStyles[$log['status']])>
                                <div class="flex items-center">
                                    <span data-lucide="{{ $statusIcons[$log['status']] }}" class="w-4 h-4 mr-2"></span>
                                    {{ $log['status'] }}
                                </div>
                            </td>
                            <td class="text-gray-600">{{ $log['remarks'] ?: 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>

    @push('scripts')
        <script>
            lucide.createIcons();
        </script>
    @endpush
</x-app-layout>
