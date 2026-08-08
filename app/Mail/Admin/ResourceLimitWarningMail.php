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

final class ResourceLimitWarningMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, HasQueueConfiguration;

    public function __construct(
        private readonly Customer $tenantCustomer,
        private readonly string $subdomain,
        private readonly string $resourceType,
        private readonly float $usagePercentage,
        private readonly string $adminUrl
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⚠️ Tenant Resource Limit Alert: ' . $this->subdomain . ' (' . ucfirst($this->resourceType) . ')',
            tags: ['admin', 'resource', 'limit', 'warning'],
            metadata: [
                'tenant_customer_id' => $this->tenantCustomer->id,
                'subdomain' => $this->subdomain,
                'resource_type' => $this->resourceType,
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.admin.resource-limit-warning',
            with: [
                'tenantName' => $this->tenantCustomer->name,
                'subdomain' => $this->subdomain,
                'resourceType' => $this->resourceType,
                'usagePercentage' => $this->usagePercentage,
                'adminUrl' => $this->adminUrl,
            ],
        );
    }
}
