<?php
declare(strict_types=1);

namespace App\Mail\Customer;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class WelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, HasQueueConfiguration;


    public function __construct(
        private readonly Customer $customer,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Selamat Bergabung di COOCA.ID!',
            tags: ['welcome', 'registration'],
            metadata: [
                'customer_id' => $this->customer->id,
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.customer.welcome',
            with: [
                'customerName' => $this->customer->name,
                'businessName' => $this->customer->business_name ?? 'Bisnis Anda',
                'loginUrl' => route('customer.login'),
                'dashboardUrl' => route('customer.dashboard'),
            ],
        );
    }
}
