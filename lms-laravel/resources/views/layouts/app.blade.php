<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Ascend LMS') }}</title>

     <link rel="icon" href="{{ asset('logo.png') }}" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">

    <div class="flex h-screen w-full overflow-hidden">
        {{-- SIDEBAR --}}
        <livewire:layout.navigation />

        {{-- MAIN CONTENT --}}
        <div class="flex-1 flex flex-col h-full min-w-0 overflow-hidden bg-gray-50 relative">

            {{-- HEADER --}}
            <header class="sticky top-0 z-30 flex-shrink-0 bg-white border-b border-gray-200 px-8 py-4 shadow-sm flex items-center justify-between">
                <div class="w-full max-w-2xl">
                    @if(View::exists('livewire.global-search'))
                        <livewire:global-search />
                    @else
                        <input type="text" placeholder="Search..." class="w-full border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                    @endif
                </div>

                <div class="ml-6 flex items-center gap-5 flex-shrink-0">
                    <button class="text-gray-400 hover:text-orange-500 transition relative p-1">
                        <i class="fa-regular fa-bell text-xl"></i>
                        <span class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full ring-2 ring-white bg-red-500"></span>
                    </button>
                    <div class="h-8 w-px bg-gray-200"></div>

                    @if(View::exists('livewire.profile-dropdown'))
                        <livewire:profile-dropdown />
                    @else
                         <div class="font-bold text-gray-700">{{ auth()->user()->first_name ?? 'User' }}</div>
                    @endif
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-0 scroll-smooth">
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
