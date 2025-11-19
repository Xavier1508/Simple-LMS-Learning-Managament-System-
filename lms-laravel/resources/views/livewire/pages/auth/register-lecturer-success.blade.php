<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    /**
     * Pastikan pengguna tidak bisa mengakses halaman ini tanpa data sukses
     * (yaitu, tanpa redirect dari form register).
     */
    public function mount()
    {
        if (!session()->has('lecturer_registered')) {
            $this->redirect(route('register.lecturer'), navigate: true);
        }
    }
}; ?>

<div class="w-full max-w-lg text-center p-8 md:p-12 bg-white rounded-2xl shadow-2xl space-y-6">
    <div class="text-green-600 mx-auto w-16 h-16 flex items-center justify-center rounded-full bg-green-100">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
    </div>

    <h3 class="text-2xl font-bold text-gray-900">Pendaftaran Berhasil!</h3>

    <p class="text-gray-600">
        Akun Dosen Anda telah berhasil didaftarkan.
        <br><br>
        Kami telah mengirimkan <strong>Kode Dosen</strong> Anda melalui email ke <strong>{{ session('lecturer_registered.email') }}</strong>.
    </p>

    <div class="p-4 bg-red-100 border-l-4 border-red-500 text-red-800 rounded-lg text-left">
        <p class="font-bold">⚠️ Private Number (SANGAT PENTING!)</p>
        <p class="text-sm pt-1">
            Untuk alasan keamanan, <strong>Private Number</strong> (Nomor Rahasia) akan dikirimkan oleh Admin Sistem ke email ini setelah validasi akun selesai (maksimal <strong>7x24 jam</strong>). Anda memerlukan Private Number, Kode Dosen, dan Password untuk Login.
        </p>
    </div>

    <a href="{{ route('login.lecturer') }}" wire:navigate class="w-full inline-block bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 transition duration-150 shadow-md">
        Lanjut ke Halaman Login Dosen
    </a>
</div>
