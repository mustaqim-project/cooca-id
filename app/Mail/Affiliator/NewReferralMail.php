<?php

declare(strict_types=1);

namespace App\Mail\Affiliator;

use App\Traits\HasQueueConfiguration;
use App\Models\Affiliator;
use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class NewReferralMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, HasQueueConfiguration;

    public function __construct(
        private readonly Affiliator $affiliator,
        private readonly Customer $referredCustomer,
        private readonly string $affiliateDashboardUrl
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎉 Referral Baru Terdaftar: ' . $this->referredCustomer->name,
            tags: ['affiliator', 'referral', 'registered'],
            metadata: [
                'affiliator_id' => $this->affiliator->id,
                'referred_customer_id' => $this->referredCustomer->id,
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.affiliator.new-referral',
            with: [
                'affiliatorName' => $this->affiliator->name,
                'referredCustomerName' => $this->referredCustomer->name,
                'affiliateDashboardUrl' => $this->affiliateDashboardUrl,
            ],
        );
    }
}
