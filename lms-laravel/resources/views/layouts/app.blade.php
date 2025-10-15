<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/lucide/dist/lucide.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800 font-sans">
    <div class="flex h-screen">

        <aside class="fixed top-0 left-0 h-screen w-60 bg-white border-r border-gray-200 flex flex-col p-5">
            <a href="/" class="text-2xl font-bold mb-8">Ascend LMS</a> <nav class="flex flex-col gap-3">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-orange-500 text-white font-medium' : 'text-gray-600' }} flex items-center gap-3 px-3 py-2 rounded-md  hover:text-orange-500">
                    <i class="fa-solid fa-chart-line w-5 text-center"></i> Dashboard
                </a>
                <a href="{{ route('courses') }}" class="{{ request()->routeIs('courses') ? 'bg-orange-500 text-white font-medium' : 'text-gray-600' }} flex items-center gap-3 px-3 py-2 rounded-md hover:text-orange-500">
                    <i class="fa-solid fa-book-open w-5 text-center"></i> Courses
                </a>
            </nav>
        </aside>

        <main class="flex-1 flex flex-col ml-60 h-screen overflow-y-auto">
            <div class="sticky top-0 z-10 flex items-center justify-between bg-white border-b border-gray-200 px-6 py-4">
                <div class="flex items-center gap-3 w-1/2">
                    <div class="relative w-full">
                        <input type="text" placeholder="Search"
                               class="w-full border border-gray-300 rounded-md pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-400">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-400"></i>
                    </div>
                </div>
                <div class="flex items-center gap-5">
                    <button class="text-xl text-gray-500 hover:text-orange-500">🔔</button>
                    <div class="relative">
                        <button class="flex items-center gap-2 cursor-pointer">
                            <img src="https://placehold.co/100x100/D97706/FFFFFF?text=X" class="w-8 h-8 rounded-full" alt="Avatar">
                            <span>{{ Auth::user()->name }} ▼</span>
                        </button>
                    </div>
                </div>
            </div>

            {{ $slot }}

        </main>
    </div>

    <script>
      lucide.createIcons();
    </script>
</body>
</html>
