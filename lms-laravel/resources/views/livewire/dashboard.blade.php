<div class="p-8 bg-gray-50 min-h-screen">

    {{-- HEADER GREETING --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
                {{ $greeting }}, {{ explode(' ', $user->first_name)[0] }}
                <span class="text-2xl">👋</span>
            </h1>

            {{-- PERBAIKAN DI SINI: Tambahkan '?? []' agar aman dari error null --}}
            <p class="text-gray-500 mt-1">
                Welcome back! You have
                <span class="font-bold text-orange-500">
                    {{ count($todaysClasses ?? []) }} {{ $role === 'student' ? 'classes' : 'sessions' }}
                </span>
                today.
            </p>
        </div>
        <div class="text-right hidden md:block">
            <p class="text-sm font-bold text-gray-700">{{ \Carbon\Carbon::now()->format('l, d F Y') }}</p>
            <p class="text-xs text-gray-400">Academic Period 2025/2026</p>
        </div>
    </div>

    {{-- MAIN GRID --}}
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">

        {{-- LEFT COLUMN (8/12) --}}
        <div class="xl:col-span-8 space-y-8">
            {{-- 1. STATS CARDS --}}
            @include('livewire.dashboard.partials.stats')

            {{-- 2. TODAY'S SCHEDULE --}}
            @include('livewire.dashboard.partials.schedule')
        </div>

        {{-- RIGHT COLUMN (4/12) - SIDEBAR --}}
        <div class="xl:col-span-4 space-y-8">
            {{-- 3. ANNOUNCEMENTS & TASKS --}}
            @include('livewire.dashboard.partials.sidebar-right')
        </div>

    </div>
</div>
