@component('mail::message')
# Kode Verifikasi Login (OTP)

Halo,

Anda telah meminta kode verifikasi satu kali (OTP) untuk login ke akun Anda.

Kode OTP Anda adalah:

@component('mail::panel')
**{{ $otpCode }}**
@endcomponent

Kode ini akan **kedaluwarsa dalam {{ $expiryMinutes }} menit** (90 detik). Segera masukkan kode ini di halaman login untuk melanjutkan.

Jika Anda tidak mencoba login, abaikan email ini.

Terima kasih,
Tim Ascend LMS
@endcomponent
