<?php

namespace App\Mail;

use App\Models\{Notification, User};
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GeneralNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Notification $notification, public User $recipient) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->notification->title . ' - School ERP');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.notification');
    }
}
