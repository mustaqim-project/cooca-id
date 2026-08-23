<?php

declare(strict_types=1);

namespace App\Mail\Admin;

use App\Models\LiveChat;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewLiveChatAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public LiveChat $chat,
        public string $initialMessage
    ) {}

    public function envelope(): Envelope
    {
        $siteName = setting('site.name', 'COOCA.ID');

        return new Envelope(
            subject: "[Live Chat Masuk] Percakapan Baru dari {$this->chat->customer_name} — {$siteName}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-new-live-chat-alert',
            with: [
                'chat'           => $this->chat,
                'initialMessage' => $this->initialMessage,
                'siteName'       => setting('site.name', 'COOCA.ID'),
            ],
        );
    }
}
