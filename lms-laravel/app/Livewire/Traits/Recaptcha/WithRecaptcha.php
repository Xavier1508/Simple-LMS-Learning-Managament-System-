<?php

namespace App\Livewire\Traits\Recaptcha;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

trait WithRecaptcha
{
    /**
     * Properti ini otomatis tersedia di Component yang menggunakan Trait ini.
     * Tidak perlu deklarasi ulang di component.
     */
    public string $recaptchaToken = '';

    /**
     * Fungsi utama untuk memverifikasi token ke Google.
     * Panggil fungsi ini di baris pertama method submit/register/login.
     *
     * @param  string  $action  Nama aksi untuk report di dashboard Google (opsional)
     * @param  float|null  $minScore  Skor minimal (0.0 - 1.0). Null = otomatis detect env.
     *
     * @throws ValidationException Jika verifikasi gagal
     */
    public function verifyRecaptcha(string $action = 'submit', ?float $minScore = null): void
    {
        // 1. Validasi Keberadaan Token (Dari Frontend)
        if (empty($this->recaptchaToken)) {
            // Jika token kosong, biasanya JS belum selesai load atau diblokir adblock
            throw ValidationException::withMessages([
                'recaptchaToken' => 'Verifikasi keamanan belum selesai. Silakan tunggu sebentar dan klik lagi.',
            ]);
        }

        // 2. Cek Konfigurasi (Agar tidak error 500 jika lupa set .env)
        $secret = config('services.recaptcha.secret');
        if (empty($secret)) {
            if (app()->isLocal()) {
                // Di local, kita biarkan lewat (bypass) jika config belum ada, biar ga ribet develop
                Log::warning('Recaptcha Secret Key belum di-set di .env, verifikasi di-bypass.');

                return;
            }
            throw ValidationException::withMessages([
                'recaptchaToken' => 'Konfigurasi Server Error: Recaptcha Secret Missing.',
            ]);
        }

        // 3. Kirim Request ke Google
        try {
            $response = Http::asForm()->timeout(5)->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secret,
                'response' => $this->recaptchaToken,
                'remoteip' => request()->ip(),
            ]);
        } catch (\Exception $e) {
            // Handle jika server Google down atau koneksi server kita bermasalah
            Log::error('Recaptcha Connection Error: '.$e->getMessage());
            throw ValidationException::withMessages([
                'recaptchaToken' => 'Gagal menghubungi server keamanan Google. Cek koneksi internet.',
            ]);
        }

        $body = $response->json();

        // 4. Analisis Response Google

        // Cek Success Flag
        if (! ($body['success'] ?? false)) {
            Log::error('Recaptcha Verification Failed', ['response' => $body, 'ip' => request()->ip()]);

            // Khusus Localhost: Kadang Google nolak karena domain 'localhost' belum didaftarkan di admin console
            // Kita kasih pesan spesifik biar developer tau.
            if (in_array('invalid-input-secret', $body['error-codes'] ?? [])) {
                throw ValidationException::withMessages(['recaptchaToken' => 'Error: Secret Key Recaptcha Salah.']);
            }

            throw ValidationException::withMessages([
                'recaptchaToken' => 'Verifikasi keamanan gagal. Silakan refresh halaman.',
            ]);
        }

        // 5. Cek Skor (Bot Detection)
        // Skor 1.0 = Sangat Manusia, 0.0 = Sangat Bot
        $score = $body['score'] ?? 0.0;

        // Jika parameter minScore tidak diisi, gunakan default:
        // Localhost agak longgar (0.3), Production lebih ketat (0.5)
        $threshold = $minScore ?? (app()->isLocal() ? 0.3 : 0.5);

        if ($score < $threshold) {
            Log::warning('Recaptcha Low Score Detected', [
                'email' => $this->email ?? 'unknown', // Mencoba log email jika ada property email
                'score' => $score,
                'ip' => request()->ip(),
            ]);

            throw ValidationException::withMessages([
                'recaptchaToken' => 'Sistem mendeteksi aktivitas mencurigakan. Silakan coba lagi nanti.',
            ]);
        }

        // 6. Cek Action (Opsional tapi disarankan)
        // Memastikan token yang digenerate untuk action 'login' tidak dipakai buat 'register'
        if (isset($body['action']) && $body['action'] !== $action) {
            Log::warning('Recaptcha Action Mismatch', ['expected' => $action, 'received' => $body['action']]);
            throw ValidationException::withMessages([
                'recaptchaToken' => 'Invalid Security Action.',
            ]);
        }

        // Jika sampai sini, berarti LULUS.
        // Reset token biar tidak dipakai ulang (Replay Attack Prevention)
        $this->recaptchaToken = '';
    }
}
