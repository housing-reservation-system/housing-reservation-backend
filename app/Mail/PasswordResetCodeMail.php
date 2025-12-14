<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use Illuminate\Contracts\Queue\ShouldQueue;

class PasswordResetCodeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $resetCode;

    public function __construct(string $resetCode)
    {
        $this->resetCode = $resetCode;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Password Reset Code',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.password-reset-code',
        );
    }
}
