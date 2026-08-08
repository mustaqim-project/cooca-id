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

final class ResourceLimitWarningMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, HasQueueConfiguration;

    public function __construct(
        private readonly Customer $customer,
        private readonly string $resourceType,
        private readonly float $usagePercentage,
        private readonly string $upgradeUrl
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⚠️ Peringatan: Batas Pemakaian ' . ucfirst($this->resourceType) . ' Hampir Terpenuhi',
            tags: ['resource', 'limit', 'warning'],
            metadata: [
                'customer_id' => $this->customer->id,
                'resource_type' => $this->resourceType,
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.customer.resource-limit-warning',
            with: [
                'customerName' => $this->customer->name,
                'resourceType' => $this->resourceType,
                'usagePercentage' => $this->usagePercentage,
                'upgradeUrl' => $this->upgradeUrl,
            ],
        );
    }
}
