<?php
declare(strict_types=1);

namespace App\Mail\Customer;

use App\Traits\HasQueueConfiguration;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class PaymentConfirmedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, HasQueueConfiguration;


    public function __construct(
        private readonly Customer $customer,
        private readonly string $invoiceNumber,
        private readonly float $amount,
        private readonly ?string $productName = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pembayaran Berhasil - Invoice ' . $this->invoiceNumber,
            tags: ['payment', 'confirmation'],
            metadata: [
                'customer_id' => $this->customer->id,
                'invoice_number' => $this->invoiceNumber,
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.customer.payment-confirmed',
            with: [
                'customerName' => $this->customer->name,
                'invoiceNumber' => $this->invoiceNumber,
                'amount' => number_format($this->amount, 0, ',', '.'),
                'productName' => $this->productName ?? 'Layanan COOCA.ID',
            ],
        );
    }
}
