@extends('layouts.landing')

@section('content')
    {{-- HERO SECTION --}}
    <div id="home" class="relative bg-gradient-to-b from-white to-gray-50 overflow-hidden py-20 lg:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

                {{-- Left Content --}}
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center px-4 py-2 rounded-full bg-orange-50 border border-orange-200 text-orange-600 text-xs font-bold uppercase tracking-wide mb-6">
                        <span class="flex h-2 w-2 rounded-full bg-orange-600 mr-2 animate-pulse"></span>
                        Platform LMS #1 Indonesia
                    </div>

                    <h1 class="text-4xl md:text-5xl xl:text-6xl font-extrabold text-gray-900 leading-tight mb-6">
                        Revolusi Cara <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-red-600">Belajar Anda</span>
                    </h1>

                    <p class="text-lg text-gray-600 leading-relaxed mb-8 max-w-xl mx-auto lg:mx-0">
                        Akses ribuan materi pembelajaran berkualitas tinggi, dipandu oleh instruktur ahli. Tingkatkan karir dan keahlian Anda dengan fleksibilitas penuh.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-full transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            Mulai Sekarang
                        </a>
                        <a href="#features" class="inline-flex items-center justify-center px-8 py-4 bg-white hover:bg-gray-50 text-gray-900 font-bold rounded-full border-2 border-gray-300 hover:border-orange-600 hover:text-orange-600 transition-all duration-300">
                            Lihat Fitur
                        </a>
                    </div>
                </div>

                {{-- Right Image --}}
                <div class="relative">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                        <img class="w-full h-auto object-cover" src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80" alt="Student learning">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- STATS SECTION --}}
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-4xl lg:text-5xl font-extrabold text-gray-900 mb-2">25k+</div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wider">Pelajar Aktif</div>
                </div>
                <div>
                    <div class="text-4xl lg:text-5xl font-extrabold text-orange-600 mb-2">1.2k+</div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wider">Kelas Online</div>
                </div>
                <div>
                    <div class="text-4xl lg:text-5xl font-extrabold text-gray-900 mb-2">98%</div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wider">Kepuasan</div>
                </div>
                <div>
                    <div class="text-4xl lg:text-5xl font-extrabold text-gray-900 mb-2">24/7</div>
                    <div class="text-sm font-medium text-gray-600 uppercase tracking-wider">Support</div>
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
                    Semua yang Anda Butuhkan untuk Sukses
                </h3>
                <p class="text-lg text-gray-600">
                    Kami menggabungkan teknologi dan pedagogi untuk menciptakan pengalaman belajar yang efektif dan menyenangkan.
                </p>
            </div>

            {{-- Feature Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                {{-- Feature 1 --}}
                <div class="bg-white p-8 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 group">
                    <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl flex items-center justify-center text-white mb-5 group-hover:scale-110 transition-transform duration-300">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-orange-600 transition-colors">
                        Akses Multi-Platform
                    </h3>
                    <p class="text-gray-600 leading-relaxed">
                        Belajar dari laptop, tablet, atau smartphone Anda. Progres tersinkronisasi otomatis di semua perangkat.
                    </p>
                </div>

                {{-- Feature 2 --}}
                <div class="bg-white p-8 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 group">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center text-white mb-5 group-hover:scale-110 transition-transform duration-300">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors">
                        Forum Diskusi
                    </h3>
                    <p class="text-gray-600 leading-relaxed">
                        Tanya jawab langsung dengan instruktur dan berkolaborasi dengan sesama siswa dalam komunitas belajar yang aktif.
                    </p>
                </div>

                {{-- Feature 3 --}}
                <div class="bg-white p-8 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 group md:col-span-2 lg:col-span-1">
                    <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-teal-500 rounded-xl flex items-center justify-center text-white mb-5 group-hover:scale-110 transition-transform duration-300">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-green-600 transition-colors">
                        Sertifikat Terverifikasi
                    </h3>
                    <p class="text-gray-600 leading-relaxed">
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
                    Membangun Masa Depan Melalui Pendidikan
                </h3>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-12">

                {{-- Left Content --}}
                <div class="order-2 lg:order-1">
                    <div class="space-y-4 text-gray-600 text-base leading-relaxed">
                        <p>
                            Ascend LMS bukan sekadar platform kursus online. Kami adalah ekosistem pembelajaran yang didedikasikan untuk membantu individu berkembang di era digital.
                        </p>
                        <p>
                            Dengan kurikulum yang terus diperbarui dan instruktur praktisi industri, kami menjembatani kesenjangan antara teori akademis dan kebutuhan dunia kerja nyata.
                        </p>
                    </div>
                </div>

                {{-- Right Image --}}
                <div class="order-1 lg:order-2">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl group">
                        <img class="w-full h-auto object-cover transform group-hover:scale-105 transition duration-500" src="https://images.unsplash.com/photo-1531482615713-2afd69097998?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Team meeting">

                        {{-- Quote Overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent flex items-end p-8">
                            <div class="transform translate-y-2 group-hover:translate-y-0 transition duration-300">
                                <svg class="h-8 w-8 text-orange-500 mb-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                                </svg>
                                <p class="text-white font-medium text-base italic leading-relaxed">
                                    "Pendidikan adalah senjata paling ampuh untuk mengubah dunia."
                                </p>
                                <p class="text-orange-400 text-sm mt-2">- Nelson Mandela</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Mission & Vision Cards - REDESIGNED --}}
            {{-- <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-12"> --}}

                {{-- Mission Card --}}
                {{-- <div class="relative bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-8 text-white shadow-xl overflow-hidden group hover:shadow-2xl transition-all duration-300">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-4">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h4 class="text-2xl font-bold mb-3">Misi Kami</h4>
                        <p class="text-orange-50 leading-relaxed">
                            Mewujudkan akses pendidikan berkualitas tinggi bagi semua orang, tanpa batasan geografis atau ekonomi.
                        </p>
                    </div>
                </div> --}}

                {{-- Vision Card --}}
                {{-- <div class="relative bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-8 text-white shadow-xl overflow-hidden group hover:shadow-2xl transition-all duration-300">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-4">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                        <h4 class="text-2xl font-bold mb-3">Visi Kami</h4>
                        <p class="text-gray-300 leading-relaxed">
                            Menjadi platform pengembangan skill #1 di Asia yang memberdayakan jutaan pembelajar.
                        </p>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>

    {{-- BLOG / ARTICLE PREVIEW --}}
    <div id="blog" class="py-20 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Section Header --}}
            <div class="text-center mb-16 max-w-3xl mx-auto">
                <h2 class="text-orange-600 font-bold tracking-wide uppercase mb-3 text-sm">Blog & Artikel</h2>
                <h3 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-gray-900 mb-4">Artikel Terbaru</h3>
                <p class="text-lg text-gray-600">Tips belajar, tren teknologi, dan wawasan karir.</p>
            </div>

            {{-- Blog Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                {{-- Blog Card 1 --}}
                <article class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 group cursor-pointer">
                    <div class="h-48 overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Productivity" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <span class="absolute top-4 left-4 px-3 py-1 text-xs font-bold text-white bg-orange-600 rounded-full uppercase">Productivity</span>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-orange-600 transition line-clamp-2">
                            Teknik Pomodoro untuk Belajar Lebih Efektif
                        </h3>
                        <p class="text-gray-600 text-sm leading-relaxed line-clamp-3 mb-4">
                            Pelajari cara membagi waktu belajar agar tetap fokus dan tidak mudah lelah dengan teknik manajemen waktu yang terbukti efektif.
                        </p>
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100 text-xs text-gray-500">
                            <span>5 min read</span>
                            <span>2 days ago</span>
                        </div>
                    </div>
                </article>

                {{-- Blog Card 2 --}}
                <article class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 group cursor-pointer">
                    <div class="h-48 overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1555949963-ff9fe0c870eb?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Technology" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <span class="absolute top-4 left-4 px-3 py-1 text-xs font-bold text-white bg-blue-600 rounded-full uppercase">Technology</span>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition line-clamp-2">
                            Masa Depan AI dalam Pendidikan
                        </h3>
                        <p class="text-gray-600 text-sm leading-relaxed line-clamp-3 mb-4">
                            Bagaimana kecerdasan buatan mengubah cara kita belajar dan mengajar di masa depan? Simak ulasan lengkapnya di sini.
                        </p>
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100 text-xs text-gray-500">
                            <span>8 min read</span>
                            <span>5 days ago</span>
                        </div>
                    </div>
                </article>

                {{-- Blog Card 3 --}}
                <article class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 group cursor-pointer md:col-span-2 lg:col-span-1">
                    <div class="h-48 overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Career" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <span class="absolute top-4 left-4 px-3 py-1 text-xs font-bold text-white bg-green-600 rounded-full uppercase">Career</span>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-green-600 transition line-clamp-2">
                            Skill Paling Dicari Tahun 2025
                        </h3>
                        <p class="text-gray-600 text-sm leading-relaxed line-clamp-3 mb-4">
                            Daftar soft skill dan hard skill yang wajib Anda kuasai untuk bersaing di pasar kerja global yang kompetitif dan dinamis.
                        </p>
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100 text-xs text-gray-500">
                            <span>6 min read</span>
                            <span>1 week ago</span>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>

    {{-- FINAL CTA SECTION --}}
    <div class="relative bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 py-20 overflow-hidden">

        {{-- Background Decoration --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none opacity-20">
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-orange-600 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-white mb-6 leading-tight">
                Siap Mengubah Hidup Anda?
            </h2>
            <p class="text-lg md:text-xl text-gray-300 mb-10 max-w-2xl mx-auto leading-relaxed">
                Bergabunglah dengan ribuan pembelajar lainnya. Dapatkan akses tanpa batas ke pengetahuan dunia hari ini.
            </p>

            {{-- CTA Buttons --}}
            <div class="flex flex-col sm:flex-row justify-center gap-4 mb-10">
                <a href="{{ route('register') }}" class="group inline-flex items-center justify-center px-8 py-4 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-full transition-all duration-300 shadow-lg transform hover:-translate-y-1">
                    Daftar Gratis Sekarang
                    <svg class="ml-2 h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 bg-transparent border-2 border-white hover:bg-white text-white hover:text-gray-900 font-bold rounded-full transition-all duration-300">
                    Masuk Akun
                </a>
            </div>
        </div>
    </div>
@endsection
