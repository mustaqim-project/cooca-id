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

final class LicenseReadyMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        private readonly Customer $customer,
        private readonly string $licenseCode,
        private readonly string $tokenCode,
        private readonly string $domain,
        private readonly string $productName,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'License Siap Digunakan - ' . $this->productName,
            tags: ['license', 'ready'],
            metadata: [
                'customer_id' => $this->customer->id,
                'license_code' => $this->licenseCode,
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.customer.license-ready',
            with: [
                'customerName' => $this->customer->name,
                'productName' => $this->productName,
                'licenseCode' => $this->licenseCode,
                'tokenCode' => $this->tokenCode,
                'domain' => $this->domain,
                'dashboardUrl' => route('customer.dashboard'),
            ],
        );
    }
}
