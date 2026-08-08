<?php

declare(strict_types=1);

namespace App\Mail\Admin;

use App\Traits\HasQueueConfiguration;
use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class SlaBreachMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, HasQueueConfiguration;

    public function __construct(
        private readonly Ticket $ticket,
        private readonly int $hoursElapsed,
        private readonly string $adminUrl
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⚠️ SLA BREACH: Tiket #' . $this->ticket->ticket_number . ' Belum Direspon',
            tags: ['admin', 'support', 'sla', 'breach'],
            metadata: [
                'ticket_id' => $this->ticket->id,
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.admin.sla-breach',
            with: [
                'ticketNumber' => $this->ticket->ticket_number,
                'subjectLine' => $this->ticket->subject,
                'priority' => $this->ticket->priority,
                'hoursElapsed' => $this->hoursElapsed,
                'adminUrl' => $this->adminUrl,
            ],
        );
    }
}
