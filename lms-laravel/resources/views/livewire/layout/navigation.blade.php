<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<aside class="w-64 flex-shrink-0 bg-white border-r border-gray-200 flex flex-col h-full z-20">
    {{-- Logo Area --}}
    <div class="p-6 flex-shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
            <div class="w-10 h-10 bg-gray-800 text-white rounded-lg flex items-center justify-center shadow-md group-hover:scale-105 transition-transform duration-300">
                <i class="fa-solid fa-graduation-cap text-lg"></i>
            </div>
            <div class="text-2xl font-extrabold tracking-tight">
                <span class="text-gray-800">Ascend</span><span class="text-orange-600">LMS</span>
            </div>
        </a>
    </div>

    {{-- Menu Area --}}
    <nav class="flex-1 overflow-y-auto px-4 pb-4 space-y-1">
        @php
            $menuItems = [
                ['route' => 'dashboard', 'icon' => 'fa-chart-line', 'label' => 'Dashboard'],
                ['route' => 'courses', 'icon' => 'fa-book-open', 'label' => 'Courses'],
                ['route' => 'forum', 'icon' => 'fa-comments', 'label' => 'Forum'],
                ['route' => 'assessment', 'icon' => 'fa-file-pen', 'label' => 'Assessment'],
                ['route' => 'gradebook', 'icon' => 'fa-medal', 'label' => 'Gradebook'],
                ['route' => 'attendance', 'icon' => 'fa-calendar-check', 'label' => 'Attendance'],
                ['route' => 'schedule', 'icon' => 'fa-calendar-days', 'label' => 'Schedule'],
            ];
        @endphp

        @foreach($menuItems as $item)
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all duration-200 group text-[15px] font-medium
                      {{ (request()->routeIs($item['route']) || request()->routeIs($item['route'].'.*'))
                          ? 'bg-orange-500 text-white shadow-md transform scale-[1.02]'
                          : 'text-gray-600 hover:bg-orange-50 hover:text-orange-600 hover:pl-6' }}">
                <i class="fa-solid {{ $item['icon'] }} w-5 text-center {{ (request()->routeIs($item['route']) || request()->routeIs($item['route'].'.*')) ? 'text-white' : 'text-gray-400 group-hover:text-orange-500' }}"></i>
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>

    {{-- Profile Mini (Bottom) --}}
    <div class="p-4 border-t border-gray-100 flex-shrink-0">
        <a href="{{ route('profile') }}" class="flex items-center gap-3 group cursor-pointer hover:bg-gray-50 p-2 rounded-xl transition-colors">
            <div class="relative">
                <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold text-sm border border-orange-200">
                    {{ substr(auth()->user()->first_name ?? 'U', 0, 1) }}{{ substr(auth()->user()->last_name ?? '', 0, 1) }}
                </div>
                <span class="absolute bottom-0 right-0 block h-3 w-3 rounded-full ring-2 ring-white bg-green-500"></span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-bold text-gray-800 truncate text-sm group-hover:text-orange-600 transition">
                    {{ auth()->user()->first_name ?? 'User' }}
                </p>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                    <span class="text-xs text-green-600 font-medium">Active</span>
                </div>
            </div>
            <i class="fa-solid fa-chevron-right text-xs text-gray-300 group-hover:text-gray-500"></i>
        </a>
    </div>
</aside>
