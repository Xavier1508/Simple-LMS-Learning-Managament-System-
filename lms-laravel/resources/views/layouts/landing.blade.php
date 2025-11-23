<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Ascend LMS') }} - Elevate Your Learning</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-white text-gray-800 antialiased">

    {{-- NAVBAR --}}
    <nav class="fixed w-full z-50 top-0 bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                {{-- Logo & Brand --}}
                <div class="flex items-center">
                    <a href="/" class="flex items-center gap-3 group">
                        {{-- PERBAIKAN: Menggunakan h-10 w-auto agar proporsional & object-contain agar tidak gepeng --}}
                        <img src="{{ asset('favicon.png') }}"
                             alt="Ascend LMS Logo"
                             class="h-10 w-auto object-contain transition-transform duration-300 group-hover:scale-110">
                        <span class="text-xl font-bold text-gray-900 tracking-tight group-hover:text-orange-600 transition-colors">
                            Ascend LMS
                        </span>
                    </a>
                </div>

                {{-- Desktop Menu --}}
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#home" class="text-sm font-semibold text-gray-700 hover:text-orange-600 transition-colors">Beranda</a>
                    <a href="#features" class="text-sm font-semibold text-gray-700 hover:text-orange-600 transition-colors">Fitur</a>
                    <a href="#about" class="text-sm font-semibold text-gray-700 hover:text-orange-600 transition-colors">Tentang</a>
                    <a href="#blog" class="text-sm font-semibold text-gray-700 hover:text-orange-600 transition-colors">Artikel</a>
                </div>

                {{-- Auth Buttons --}}
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="bg-orange-600 hover:bg-orange-700 text-white text-sm font-bold py-2.5 px-6 rounded-full transition-all duration-300 shadow-md hover:shadow-lg">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-700 hover:text-orange-600 transition-colors">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="bg-orange-600 hover:bg-orange-700 text-white text-sm font-bold py-2.5 px-6 rounded-full transition-all duration-300 shadow-md hover:shadow-lg">
                            Daftar Gratis
                        </a>
                    @endauth
                </div>

                {{-- Mobile Menu Button --}}
                <div class="md:hidden">
                    <button id="mobile-menu-button" type="button" class="text-gray-700 hover:text-orange-600 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-200">
            <div class="px-4 pt-2 pb-4 space-y-2">
                <a href="#home" class="block px-3 py-2 text-base font-semibold text-gray-700 hover:text-orange-600 hover:bg-gray-50 rounded-md transition-colors">Beranda</a>
                <a href="#features" class="block px-3 py-2 text-base font-semibold text-gray-700 hover:text-orange-600 hover:bg-gray-50 rounded-md transition-colors">Fitur</a>
                <a href="#about" class="block px-3 py-2 text-base font-semibold text-gray-700 hover:text-orange-600 hover:bg-gray-50 rounded-md transition-colors">Tentang</a>
                <a href="#blog" class="block px-3 py-2 text-base font-semibold text-gray-700 hover:text-orange-600 hover:bg-gray-50 rounded-md transition-colors">Artikel</a>

                @auth
                    <a href="{{ url('/dashboard') }}" class="block px-3 py-2 text-center bg-orange-600 text-white font-bold rounded-full hover:bg-orange-700 transition-colors mt-4">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 text-center text-gray-700 font-semibold border-2 border-gray-300 rounded-full hover:border-orange-600 hover:text-orange-600 transition-colors mt-4">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 text-center bg-orange-600 text-white font-bold rounded-full hover:bg-orange-700 transition-colors">
                        Daftar Gratis
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <main class="pt-16">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-gray-900 text-gray-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

            {{-- Footer Content --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">

                {{-- Brand Section --}}
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        {{-- PERBAIKAN: Menggunakan h-8 w-auto object-contain untuk footer --}}
                        <img src="{{ asset('favicon.png') }}" alt="Logo" class="h-8 w-auto object-contain brightness-200">
                        <span class="text-xl font-bold text-white">Ascend LMS</span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed max-w-md">
                        Platform pembelajaran online yang membantu Anda mencapai potensi maksimal melalui pendidikan berkualitas yang dapat diakses kapan saja, di mana saja.
                    </p>
                </div>

                {{-- Resources --}}
                <div>
                    <h4 class="text-white font-bold mb-4">Resources</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-orange-400 transition-colors">Dokumentasi</a></li>
                        <li><a href="#" class="hover:text-orange-400 transition-colors">Pusat Bantuan</a></li>
                        <li><a href="#" class="hover:text-orange-400 transition-colors">Komunitas</a></li>
                        <li><a href="#" class="hover:text-orange-400 transition-colors">Blog</a></li>
                    </ul>
                </div>

                {{-- Company --}}
                <div>
                    <h4 class="text-white font-bold mb-4">Company</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#about" class="hover:text-orange-400 transition-colors">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-orange-400 transition-colors">Karir</a></li>
                        <li><a href="#" class="hover:text-orange-400 transition-colors">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-orange-400 transition-colors">Syarat Layanan</a></li>
                    </ul>
                </div>
            </div>

            {{-- Footer Bottom --}}
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-500 text-sm">
                    &copy; {{ date('Y') }} Ascend LMS. All rights reserved.
                </p>

                {{-- Social Media --}}
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors" aria-label="Facebook">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors" aria-label="Twitter">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors" aria-label="Instagram">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Mobile Menu Toggle Script --}}
    <script>
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });

        // Close mobile menu when clicking menu links
        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', function() {
                document.getElementById('mobile-menu').classList.add('hidden');
            });
        });
    </script>

</body>
</html>
