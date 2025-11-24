<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $url;

    public $email;

    public function __construct($token, $email)
    {
        $this->email = $email;
        $this->url = route('password.reset', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset Password - Ascend LMS',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.auth.reset-password',
            with: [
                'url' => $this->url,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
