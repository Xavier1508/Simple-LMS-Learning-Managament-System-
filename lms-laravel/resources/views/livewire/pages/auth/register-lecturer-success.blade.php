<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public function mount()
    {
        if (!session()->has('lecturer_registered')) {
            $this->redirect(route('register.lecturer'), navigate: true);
        }
    }
}; ?>

<div class="w-full max-w-lg text-center p-8 md:p-12 bg-white rounded-2xl shadow-2xl space-y-8 relative overflow-hidden">
    {{-- Decorative Background --}}
    <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 to-cyan-500"></div>

    <div class="flex flex-col items-center justify-center">
        <div class="w-24 h-24 bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-6 animate-bounce shadow-lg shadow-green-200">
            <x-heroicon-s-check-circle class="w-12 h-12" />
        </div>

        <h3 class="text-3xl font-extrabold text-gray-900 mb-2">Pendaftaran Berhasil!</h3>
        <p class="text-gray-500">Selamat bergabung di Ascend LMS.</p>
    </div>

    <div class="space-y-4 text-left bg-gray-50 p-6 rounded-xl border border-gray-100">
        <p class="text-gray-700">
            Akun Dosen Anda telah aktif. Kami telah mengirimkan detail kredensial ke:
        </p>
        <div class="flex items-center gap-3 p-3 bg-white rounded-lg border border-gray-200 shadow-sm">
            <x-heroicon-o-envelope class="w-6 h-6 text-blue-500" />
            <span class="font-mono font-bold text-gray-800">{{ session('lecturer_registered.email') }}</span>
        </div>

        <p class="text-sm text-gray-500">
            Silakan cek Inbox atau Spam folder Anda untuk melihat <strong>Kode Dosen</strong> Anda.
        </p>
    </div>

    <div class="p-5 bg-red-50 border-l-4 border-red-500 text-red-900 rounded-r-lg text-left shadow-sm">
        <div class="flex items-start gap-3">
            <x-heroicon-s-exclamation-triangle class="w-6 h-6 shrink-0 text-red-600 mt-0.5" />
            <div>
                <p class="font-bold text-lg">PENTING: Private Number</p>
                <p class="text-sm pt-2 leading-relaxed">
                    Demi keamanan, <strong>Private Number</strong> akan dikirim terpisah oleh Admin setelah verifikasi manual (maks. 7x24 jam).
                    Anda membutuhkan Kode Dosen + Private Number untuk login.

                    Jika belum dihubungi silahkan balas kembali ke email ini.
                </p>
            </div>
        </div>
    </div>

    <a href="{{ route('login.lecturer') }}" wire:navigate class="w-full flex items-center justify-center gap-2 bg-blue-600 text-white py-4 rounded-xl font-bold text-lg hover:bg-blue-700 transition duration-150 shadow-lg shadow-blue-300/50 transform hover:-translate-y-1">
        <span>Lanjut ke Halaman Login</span>
        <x-heroicon-m-arrow-right class="w-5 h-5" />
    </a>
</div>
