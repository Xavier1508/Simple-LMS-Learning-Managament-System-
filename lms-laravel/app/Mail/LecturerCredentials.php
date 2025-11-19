<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LecturerCredentials extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public array $data, // Akan berisi 'lecturer_code' DAN 'otp_code'
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Konfirmasi Pendaftaran Dosen & Kode OTP',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.lecturer-credentials',
            with: [
                'lecturerCode' => $this->data['lecturer_code'] ?? 'N/A',
                'otpCode' => $this->data['otp_code'] ?? 'N/A', // Kirim OTP ke View
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
