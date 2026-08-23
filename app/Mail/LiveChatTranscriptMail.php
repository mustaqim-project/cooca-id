<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\LiveChat;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LiveChatTranscriptMail extends Mailable
{
    use Queueable, SerializesModels;

    public LiveChat $chat;
    public string $recipientType;

    /**
     * Create a new message instance.
     *
     * @param LiveChat $chat
     * @param string $recipientType 'customer' or 'admin'
     */
    public function __construct(LiveChat $chat, string $recipientType = 'customer')
    {
        $this->chat = $chat;
        $this->recipientType = $recipientType;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $siteName = setting('site.name', 'COOCA.ID');
        $subject = $this->recipientType === 'admin'
            ? "[Live Chat Log] Transkrip Sesi Chat #{$this->chat->id} — {$this->chat->customer_name}"
            : "Ringkasan & Transkrip Percakapan Live Chat — {$siteName}";

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.live-chat-transcript',
            with: [
                'chat' => $this->chat,
                'messages' => $this->chat->messages()->get(),
                'recipientType' => $this->recipientType,
                'siteName' => setting('site.name', 'COOCA.ID'),
            ],
        );
    }
}
