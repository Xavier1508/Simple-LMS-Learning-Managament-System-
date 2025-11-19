@component('mail::message')
# Pendaftaran Dosen

Terima kasih telah mendaftar. Berikut adalah **Kode Dosen** Anda:

@component('mail::panel')
Kode Dosen: **{{ $lecturerCode }}**
@endcomponent

Untuk menyelesaikan pendaftaran saat ini, silakan masukkan **Kode OTP** berikut di halaman registrasi:

@component('mail::panel')
Kode OTP: **{{ $otpCode }}**
@endcomponent

<div style="padding: 15px; margin-top: 20px; border-left: 5px solid #ef4444; background-color: #fee2e2;">
PENTING: Private Number Anda

Private Number (Nomor Rahasia) akan dikirimkan oleh Admin Sistem setelah akun Anda diverifikasi sepenuhnya (maksimal 7x24 jam). Anda membutuhkannya untuk Login nanti.
</div>

Salam hormat,
Tim Ascend LMS
@endcomponent
