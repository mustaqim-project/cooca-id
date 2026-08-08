<?php

declare(strict_types=1);

namespace App\Mail\Customer;

use App\Traits\HasQueueConfiguration;
use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class SuspiciousLoginMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, HasQueueConfiguration;

    public function __construct(
        private readonly Customer $customer,
        private readonly string $ipAddress,
        private readonly string $timestamp,
        private readonly string $userAgent,
        private readonly string $securityUrl
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔒 Keamanan Akun: Aktivitas Login Mencurigakan Terdeteksi',
            tags: ['security', 'login', 'suspicious'],
            metadata: [
                'customer_id' => $this->customer->id,
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.customer.suspicious-login',
            with: [
                'customerName' => $this->customer->name,
                'ipAddress' => $this->ipAddress,
                'timestamp' => $this->timestamp,
                'userAgent' => $this->userAgent,
                'securityUrl' => $this->securityUrl,
            ],
        );
    }
}
