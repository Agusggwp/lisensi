<?php

namespace App\Mail;

use App\Models\License;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LicenseExpiringReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public License $license)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reminder: License akan segera expired',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.licenses.expiring-reminder',
            with: [
                'license' => $this->license,
                'client' => $this->license->client,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
