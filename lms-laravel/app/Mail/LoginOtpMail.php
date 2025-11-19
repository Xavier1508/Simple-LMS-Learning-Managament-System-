<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoginOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Membuat instance pesan baru.
     */
    public function __construct(
        public string $otpCode,
        public float $expiryMinutes,
    ) {}

    /**
     * Mendapatkan envelope pesan.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode Verifikasi Login (OTP) Anda',
        );
    }

    /**
     * Mendapatkan definisi konten pesan.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.login-otp',
            with: [
                'otpCode' => $this->otpCode,
                'expiryMinutes' => $this->expiryMinutes,
            ],
        );
    }

    /**
     * Mendapatkan attachment untuk pesan.
     */
    public function attachments(): array
    {
        return [];
    }
}
