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

final class SubscriptionExpiryReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, HasQueueConfiguration;


    public function __construct(
        private readonly Customer $customer,
        private readonly string $productName,
        private readonly \DateTimeInterface $expiresAt,
        private readonly int $daysUntilExpiry,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Peringatan: Subscription akan Berakhir dalam ' . $this->daysUntilExpiry . ' Hari',
            tags: ['subscription', 'expiry-warning'],
            metadata: [
                'customer_id' => $this->customer->id,
                'days_until_expiry' => $this->daysUntilExpiry,
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.customer.subscription-expiry-reminder',
            with: [
                'customerName' => $this->customer->name,
                'productName' => $this->productName,
                'expiresAt' => $this->expiresAt->format('d F Y'),
                'daysUntilExpiry' => $this->daysUntilExpiry,
                'renewalUrl' => route('customer.subscriptions.index'),
            ],
        );
    }
}
