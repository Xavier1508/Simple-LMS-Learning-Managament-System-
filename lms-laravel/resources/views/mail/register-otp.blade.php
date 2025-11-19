@component('mail::message')
# Verifikasi Pendaftaran Akun

Halo,

Terima kasih telah mendaftar di Ascend LMS. Untuk mengaktifkan akun Anda, silakan gunakan kode verifikasi (OTP) di bawah ini:

@component('mail::panel')
**{{ $otpCode }}**
@endcomponent

Kode ini akan **kedaluwarsa dalam {{ $expiryMinutes }} menit** (90 detik).

Jika Anda tidak merasa melakukan pendaftaran, silakan abaikan email ini.

Terima kasih,
Tim Ascend LMS
@endcomponent
