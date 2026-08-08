<?php

declare(strict_types=1);

namespace App\Mail\Admin;

use App\Traits\HasQueueConfiguration;
use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class HealthCheckFailedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, HasQueueConfiguration;

    public function __construct(
        private readonly Customer $tenantCustomer,
        private readonly string $subdomain,
        private readonly string $errorDetails,
        private readonly string $timestamp,
        private readonly string $adminUrl
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🚨 ALERT: Health Check Failed - Tenant ' . $this->subdomain,
            tags: ['admin', 'health', 'failed', 'alert'],
            metadata: [
                'tenant_customer_id' => $this->tenantCustomer->id,
                'subdomain' => $this->subdomain,
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.admin.health-check-failed',
            with: [
                'tenantName' => $this->tenantCustomer->name,
                'subdomain' => $this->subdomain,
                'errorDetails' => $this->errorDetails,
                'timestamp' => $this->timestamp,
                'adminUrl' => $this->adminUrl,
            ],
        );
    }
}
