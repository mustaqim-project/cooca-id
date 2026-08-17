<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class AiQuotaWarning extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly mixed $customer,
        public readonly mixed $cycle,
        public readonly int $percentage
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "AI Module Quota Warning: {$this->percentage}% Used",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ai-quota-warning',
        );
    }
}
