<x-mail::message>
# Halo!

Kami menerima permintaan untuk mereset password akun **Ascend LMS** Anda yang terkait dengan email {{ $email }}.

Klik tombol di bawah ini untuk membuat password baru:

<x-mail::button :url="$url">
Reset Password Saya
</x-mail::button>

Jika Anda tidak merasa meminta reset password, mohon abaikan email ini. Akun Anda tetap aman.

Terima kasih,<br>
Tim {{ config('app.name') }}
</x-mail::message>
