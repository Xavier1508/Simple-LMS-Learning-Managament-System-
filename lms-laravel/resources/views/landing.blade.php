@extends('layouts.landing')

@section('content')
    {{-- HERO SECTION --}}
    <div id="home" class="relative bg-gradient-to-b from-white to-gray-50 overflow-hidden py-20 lg:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

                {{-- Left Content --}}
                <div class="text-center lg:text-left">
                    {{-- Badge --}}
                    <div class="inline-flex items-center px-4 py-2 rounded-full bg-orange-50 border border-orange-200 text-orange-600 text-xs font-bold uppercase tracking-wide mb-6 shadow-sm">
                        <span class="relative flex h-2 w-2 mr-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-orange-600"></span>
                        </span>
                        Platform LMS #1 Indonesia
                    </div>

                    {{-- Headline --}}
                    <h1 class="text-4xl md:text-5xl xl:text-6xl font-extrabold text-gray-900 leading-tight mb-6">
                        Revolusi Cara <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-red-600">Belajar Anda</span>
                    </h1>

                    {{-- Subheadline --}}
                    <p class="text-lg text-gray-600 leading-relaxed mb-8 max-w-xl mx-auto lg:mx-0">
                        Akses ribuan materi pembelajaran berkualitas tinggi, dipandu oleh instruktur ahli. Tingkatkan karir dan keahlian Anda dengan fleksibilitas penuh.
                    </p>

                    {{-- Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('register') }}" class="group inline-flex items-center justify-center px-8 py-4 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-full transition-all duration-300 shadow-lg hover:shadow-orange-200 transform hover:-translate-y-1">
                            <span>Mulai Sekarang</span>
                            <x-heroicon-m-rocket-launch class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" />
                        </a>
                        <a href="#features" class="inline-flex items-center justify-center px-8 py-4 bg-white hover:bg-gray-50 text-gray-900 font-bold rounded-full border-2 border-gray-300 hover:border-orange-600 hover:text-orange-600 transition-all duration-300">
                            Lihat Fitur
                        </a>
                    </div>
                </div>

                {{-- Right Image --}}
                <div class="relative">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl border-4 border-white transform rotate-1 hover:rotate-0 transition-transform duration-500">
                        <img class="w-full h-auto object-cover" src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80" alt="Student learning">
                    </div>
                    {{-- Decorative Dots (Optional) --}}
                    <div class="absolute -bottom-4 -left-4 w-24 h-24 bg-orange-100 rounded-full -z-10 blur-xl opacity-70"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- STATS SECTION --}}
    <div class="bg-white py-16 border-y border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-x divide-gray-100">
                <div>
                    <div class="text-4xl lg:text-5xl font-extrabold text-gray-900 mb-2">25k+</div>
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Pelajar Aktif</div>
                </div>
                <div>
                    <div class="text-4xl lg:text-5xl font-extrabold text-orange-600 mb-2">1.2k+</div>
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Kelas Online</div>
                </div>
                <div>
                    <div class="text-4xl lg:text-5xl font-extrabold text-gray-900 mb-2">98%</div>
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Kepuasan</div>
                </div>
                <div>
                    <div class="text-4xl lg:text-5xl font-extrabold text-gray-900 mb-2">24/7</div>
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Support</div>
                </div>
            </div>
        </div>
    </div>

    {{-- FEATURES SECTION --}}
    <div id="features" class="py-20 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Section Header --}}
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-orange-600 font-bold tracking-wide uppercase mb-3 text-sm">Fitur Unggulan</h2>
                <h3 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-gray-900 mb-4">
                    Semua yang Anda Butuhkan
                </h3>
                <p class="text-lg text-gray-600">
                    Kami menggabungkan teknologi dan pedagogi untuk menciptakan pengalaman belajar yang efektif.
                </p>
            </div>

            {{-- Feature Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                {{-- Feature 1: Multi-Platform --}}
                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 group">
                    <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center text-orange-600 mb-5 group-hover:bg-orange-600 group-hover:text-white transition-colors duration-300">
                        {{-- Heroicon: Device Phone Mobile --}}
                        <x-heroicon-o-device-phone-mobile class="h-8 w-8" />
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-orange-600 transition-colors">
                        Akses Multi-Platform
                    </h3>
                    <p class="text-gray-600 leading-relaxed text-sm">
                        Belajar dari laptop, tablet, atau smartphone Anda. Progres tersinkronisasi otomatis di semua perangkat.
                    </p>
                </div>

                {{-- Feature 2: Forum --}}
                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 group">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 mb-5 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                        {{-- Heroicon: Chat Bubble --}}
                        <x-heroicon-o-chat-bubble-left-right class="h-8 w-8" />
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors">
                        Forum Diskusi
                    </h3>
                    <p class="text-gray-600 leading-relaxed text-sm">
                        Tanya jawab langsung dengan instruktur dan berkolaborasi dengan sesama siswa dalam komunitas aktif.
                    </p>
                </div>

                {{-- Feature 3: Certificate --}}
                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 group md:col-span-2 lg:col-span-1">
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center text-green-600 mb-5 group-hover:bg-green-600 group-hover:text-white transition-colors duration-300">
                        {{-- Heroicon: Check Badge / Academic Cap --}}
                        <x-heroicon-o-check-badge class="h-8 w-8" />
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-green-600 transition-colors">
                        Sertifikat Terverifikasi
                    </h3>
                    <p class="text-gray-600 leading-relaxed text-sm">
                        Dapatkan sertifikat kelulusan resmi yang dapat Anda lampirkan di CV atau LinkedIn untuk validasi keahlian.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- ABOUT US SECTION --}}
    <div id="about" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Section Header --}}
            <div class="text-center mb-12">
                <h2 class="text-orange-600 font-bold tracking-wide uppercase mb-3 text-sm">Tentang Kami</h2>
                <h3 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-gray-900 mb-4">
                    Membangun Masa Depan
                </h3>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-16">

                {{-- Text Content --}}
                <div class="order-2 lg:order-1">
                    <div class="space-y-4 text-gray-600 text-lg leading-relaxed">
                        <p>
                            Ascend LMS bukan sekadar platform kursus online. Kami adalah ekosistem pembelajaran yang didedikasikan untuk membantu individu berkembang di era digital.
                        </p>
                        <p>
                            Dengan kurikulum yang terus diperbarui dan instruktur praktisi industri, kami menjembatani kesenjangan antara teori akademis dan kebutuhan dunia kerja nyata.
                        </p>
                    </div>
                </div>

                {{-- Image & Quote --}}
                <div class="order-1 lg:order-2">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl group">
                        <img class="w-full h-72 lg:h-80 object-cover transform group-hover:scale-105 transition duration-700 ease-in-out" src="https://images.unsplash.com/photo-1531482615713-2afd69097998?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Team meeting">

                        {{-- Quote Overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex items-end p-8">
                            <div class="transform translate-y-2 group-hover:translate-y-0 transition duration-300">
                                {{-- Heroicon: Quote --}}
                                <x-heroicon-s-chat-bubble-oval-left-ellipsis class="h-8 w-8 text-orange-500 mb-3" />

                                <p class="text-white font-medium text-base italic leading-relaxed">
                                    "Pendidikan adalah senjata paling ampuh untuk mengubah dunia."
                                </p>
                                <p class="text-orange-400 text-sm mt-2 font-bold">- Nelson Mandela</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Mission & Vision Cards (Versi Rapih & Jelas) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-16">

                {{-- Mission Card --}}
                <div class="relative bg-white rounded-3xl p-8 shadow-lg border border-orange-100 group transform hover:-translate-y-2 hover:shadow-2xl transition-all duration-300 ease-out">
                    <div class="flex flex-col h-full">
                        {{-- Icon Box --}}
                        <div class="w-16 h-16 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center mb-6 transform group-hover:scale-110 group-hover:bg-orange-600 group-hover:text-white transition-all duration-300">
                            {{-- Heroicon: Globe --}}
                            <x-heroicon-o-globe-alt class="h-8 w-8" />
                        </div>

                        <h4 class="text-2xl font-extrabold text-gray-900 mb-3">Misi Kami</h4>
                        <p class="text-gray-600 leading-relaxed">
                            Mewujudkan akses pendidikan berkualitas tinggi bagi semua orang, tanpa batasan geografis atau ekonomi.
                        </p>
                    </div>
                </div>

                {{-- Vision Card --}}
                <div class="relative bg-white rounded-3xl p-8 shadow-lg border border-gray-200 group transform hover:-translate-y-2 hover:shadow-2xl transition-all duration-300 ease-out">
                    <div class="flex flex-col h-full">
                        {{-- Icon Box --}}
                        <div class="w-16 h-16 bg-gray-100 text-gray-800 rounded-2xl flex items-center justify-center mb-6 transform group-hover:scale-110 group-hover:bg-gray-900 group-hover:text-white transition-all duration-300">
                            {{-- Heroicon: Trophy --}}
                            <x-heroicon-o-trophy class="h-8 w-8" />
                        </div>

                        <h4 class="text-2xl font-extrabold text-gray-900 mb-3">Visi Kami</h4>
                        <p class="text-gray-600 leading-relaxed">
                            Menjadi platform pengembangan skill nomor #1 di Asia Tenggara yang memberdayakan jutaan pembelajar.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- BLOG SECTION --}}
    <div id="blog" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Section Header --}}
            <div class="text-center mb-16 max-w-3xl mx-auto">
                <h2 class="text-orange-600 font-bold tracking-wide uppercase mb-3 text-sm">Blog & Artikel</h2>
                <h3 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-gray-900 mb-4">Artikel Terbaru</h3>
                <p class="text-lg text-gray-600">Tips belajar, tren teknologi, dan wawasan karir.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                {{-- Blog Card 1 --}}
                <article class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 group cursor-pointer flex flex-col h-full">
                    <div class="h-48 overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Productivity" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <span class="absolute top-4 left-4 px-3 py-1 text-xs font-bold text-white bg-orange-600 rounded-full uppercase shadow-md">Productivity</span>
                    </div>
                    <div class="p-6 flex flex-col flex-1">
                        <div class="flex items-center text-xs text-gray-500 mb-3 space-x-4">
                            <span class="flex items-center"><x-heroicon-m-clock class="w-4 h-4 mr-1"/> 5 min read</span>
                            <span class="flex items-center"><x-heroicon-m-calendar class="w-4 h-4 mr-1"/> 2 days ago</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-3 group-hover:text-orange-600 transition line-clamp-2">
                            Teknik Pomodoro untuk Belajar Lebih Efektif
                        </h3>
                        <p class="text-gray-600 text-sm leading-relaxed line-clamp-3 mb-4 flex-1">
                            Pelajari cara membagi waktu belajar agar tetap fokus dengan teknik manajemen waktu yang efektif.
                        </p>
                    </div>
                </article>

                {{-- Blog Card 2 --}}
                <article class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 group cursor-pointer flex flex-col h-full">
                    <div class="h-48 overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1555949963-ff9fe0c870eb?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Technology" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <span class="absolute top-4 left-4 px-3 py-1 text-xs font-bold text-white bg-blue-600 rounded-full uppercase shadow-md">Technology</span>
                    </div>
                    <div class="p-6 flex flex-col flex-1">
                        <div class="flex items-center text-xs text-gray-500 mb-3 space-x-4">
                            <span class="flex items-center"><x-heroicon-m-clock class="w-4 h-4 mr-1"/> 8 min read</span>
                            <span class="flex items-center"><x-heroicon-m-calendar class="w-4 h-4 mr-1"/> 5 days ago</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition line-clamp-2">
                            Masa Depan AI dalam Pendidikan
                        </h3>
                        <p class="text-gray-600 text-sm leading-relaxed line-clamp-3 mb-4 flex-1">
                            Bagaimana kecerdasan buatan mengubah cara kita belajar dan mengajar di masa depan?
                        </p>
                    </div>
                </article>

                {{-- Blog Card 3 --}}
                <article class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 group cursor-pointer flex flex-col h-full md:col-span-2 lg:col-span-1">
                    <div class="h-48 overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Career" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <span class="absolute top-4 left-4 px-3 py-1 text-xs font-bold text-white bg-green-600 rounded-full uppercase shadow-md">Career</span>
                    </div>
                    <div class="p-6 flex flex-col flex-1">
                        <div class="flex items-center text-xs text-gray-500 mb-3 space-x-4">
                            <span class="flex items-center"><x-heroicon-m-clock class="w-4 h-4 mr-1"/> 6 min read</span>
                            <span class="flex items-center"><x-heroicon-m-calendar class="w-4 h-4 mr-1"/> 1 week ago</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-3 group-hover:text-green-600 transition line-clamp-2">
                            Skill Paling Dicari Tahun 2025
                        </h3>
                        <p class="text-gray-600 text-sm leading-relaxed line-clamp-3 mb-4 flex-1">
                            Daftar soft skill dan hard skill yang wajib Anda kuasai untuk bersaing di pasar kerja global.
                        </p>
                    </div>
                </article>
            </div>
        </div>
    </div>

    {{-- FINAL CTA SECTION --}}
    <div class="relative bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 py-24 overflow-hidden">
        {{-- Background Decoration --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-orange-600 rounded-full blur-[120px] opacity-20"></div>
        </div>

        <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-white mb-6 leading-tight">
                Siap Mengubah Hidup Anda?
            </h2>
            <p class="text-lg md:text-xl text-gray-300 mb-10 max-w-2xl mx-auto leading-relaxed">
                Bergabunglah dengan ribuan pembelajar lainnya. Dapatkan akses tanpa batas ke pengetahuan dunia hari ini.
            </p>

            <div class="flex flex-col sm:flex-row justify-center gap-4 mb-10">
                <a href="{{ route('register') }}" class="group inline-flex items-center justify-center px-8 py-4 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-full transition-all duration-300 shadow-lg shadow-orange-900/50 transform hover:-translate-y-1">
                    <span>Daftar Gratis Sekarang</span>
                    <x-heroicon-m-arrow-right class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" />
                </a>
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 bg-transparent border-2 border-gray-600 hover:border-white text-gray-300 hover:text-white font-bold rounded-full transition-all duration-300">
                    Masuk Akun
                </a>
            </div>

            {{-- Benefits --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6 text-sm text-gray-400">
                <div class="flex items-center gap-2">
                    <x-heroicon-s-check-circle class="h-5 w-5 text-green-500" />
                    <span>Tidak perlu kartu kredit</span>
                </div>
                <div class="flex items-center gap-2">
                    <x-heroicon-s-check-circle class="h-5 w-5 text-green-500" />
                    <span>Gratis selamanya</span>
                </div>
            </div>
        </div>
    </div>
@endsection
