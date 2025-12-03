<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Carbon\Carbon;

new #[Layout('layouts.guest')] class extends Component
{
    public bool $isVerified = false;

    public function mount(): void
    {
        // Cek jika user sudah verified, langsung lempar
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
            return;
        }

        if (!Auth::check()) {
            $this->redirect(route('login'), navigate: true);
            return;
        }

        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
            return;
        }
        // CATATAN: Auto-send dihapus di sini untuk mencegah spam saat refresh.
        // Pengiriman sudah ditangani oleh LoginForm saat login, atau tombol Resend di bawah.
    }

    public function checkStatus(Logout $logout): void
    {
        $user = Auth::user()->fresh();

        if (!$user) {
            $logout();
            $this->js("alert('Sesi tidak valid.'); window.location.href = '/';");
            return;
        }

        if ($user->hasVerifiedEmail()) {
            $this->isVerified = true;
            return;
        }

        $lifespan = $user->created_at->diffInSeconds(Carbon::now());

        if ($lifespan > 300) { // 300 detik = 5 menit
            $user->delete(); // Hapus User dari DB
            $logout();       // Logout Session

            $this->js("
                alert('KEAMANAN: Waktu verifikasi habis (Batas 5 Menit).\\n\\nAkun Anda telah dihapus otomatis dari sistem demi keamanan.\\nSilakan lakukan registrasi ulang.');
                window.location.href = '/';
            ");
        }
    }

    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
            return;
        }

        $key = 'send-verification:' . Auth::id();

        if (RateLimiter::tooManyAttempts($key, 1)) {
            // Rate Limit: Max 1x per menit
            return;
        }

        Auth::user()->sendEmailVerificationNotification();
        RateLimiter::hit($key, 60);
        Session::flash('status', 'verification-link-sent');
    }

    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
}; ?>

{{-- UI COMPONENT --}}
<div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden relative"
     x-data="{
         timeLeft: 0,
         endTime: null,
         timer: null,
         isVerified: @entangle('isVerified'),

         init() {
            // LOGIKA TIMER 2 MENIT (Sisi Client UX)
            // Ini timer 'kapan harus logout', bukan timer 'hapus akun'.
            // Timer hapus akun dihandle di backend (checkStatus).

            const storedTime = localStorage.getItem('verify_end_time');
            const now = new Date().getTime();

            if (storedTime && storedTime > now) {
                this.endTime = parseInt(storedTime);
            } else {
                // Set 2 menit dari sekarang
                this.endTime = now + (120 * 1000);
                localStorage.setItem('verify_end_time', this.endTime);
            }

            this.startTimer();

            this.$watch('isVerified', value => {
                if (value) {
                    clearInterval(this.timer);
                    localStorage.removeItem('verify_end_time');
                }
            });
         },

         formatTime(ms) {
             if (ms < 0) ms = 0;
             const totalSeconds = Math.floor(ms / 1000);
             const m = Math.floor(totalSeconds / 60);
             const s = totalSeconds % 60;
             return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
         },

         startTimer() {
             this.timer = setInterval(() => {
                 if (this.isVerified) return;

                 const now = new Date().getTime();
                 const diff = this.endTime - now;

                 if (diff > 0) {
                     this.timeLeft = diff;
                 } else {
                     this.timeLeft = 0;
                     clearInterval(this.timer);
                     localStorage.removeItem('verify_end_time');

                     // Client Side Timeout (Logout Saja)
                     $wire.logout();
                 }
             }, 1000);
         }
     }"
     wire:poll.2s="checkStatus"
>
    {{-- MODAL SUKSES --}}
    <div x-show="isVerified"
         x-transition.opacity.duration.500ms
         class="absolute inset-0 z-50 bg-white/95 backdrop-blur-sm flex flex-col items-center justify-center p-8 text-center"
         style="display: none;">

        <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-6 animate-bounce">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <h2 class="text-3xl font-bold text-gray-900 mb-2">Email Terverifikasi!</h2>
        <p class="text-gray-600 mb-8">
            Akun Anda kini aktif sepenuhnya.
        </p>

        <a href="{{ route('dashboard') }}" wire:navigate class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-blue-700 transition duration-150 shadow-lg shadow-blue-300/50">
            Lanjut ke Dashboard
        </a>
    </div>

    {{-- KONTEN NORMAL --}}
    <div x-show="!isVerified">
        <div class="bg-orange-600 p-6 text-center">
            <div class="mx-auto bg-white/20 w-16 h-16 rounded-full flex items-center justify-center backdrop-blur-sm mb-4">
                 <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-white">Verifikasi Email</h2>
            <p class="text-orange-100 text-sm mt-2">Selesaikan dalam 5 Menit Total</p>
        </div>

        <div class="p-8 md:p-10 space-y-6">
            <div class="text-center">
                <p class="text-gray-600 leading-relaxed">
                    Link verifikasi telah dikirim ke email <strong>{{ Auth::user()?->email }}</strong>.
                </p>

                {{-- TIMER ALERT --}}
                <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                     <p class="text-sm text-red-700 font-semibold mb-1">Sesi Halaman Ini</p>
                     <p class="text-3xl font-mono font-bold text-red-600" x-text="formatTime(timeLeft)">00:00</p>
                     <p class="text-xs text-red-500 mt-2">
                         Akun akan <strong>DIHAPUS PERMANEN</strong> jika tidak diverifikasi dalam 5 menit sejak pendaftaran.
                     </p>
                </div>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3 animate-pulse">
                    <svg class="h-5 w-5 text-green-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-sm text-green-700 font-medium">
                        Link baru berhasil dikirim. Cek inbox/spam.
                    </div>
                </div>
            @endif

            <div class="flex flex-col gap-3 pt-2">
                <button wire:click="sendVerification" class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-blue-700 transition duration-150 shadow-md shadow-blue-300/50 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Kirim Ulang Email
                </button>

                <button wire:click="logout" type="button" class="w-full bg-white text-gray-700 px-6 py-3 rounded-lg font-semibold border border-gray-300 hover:bg-gray-50 hover:text-red-600 transition duration-150">
                    Keluar (Logout)
                </button>
            </div>
        </div>
    </div>
</div>
