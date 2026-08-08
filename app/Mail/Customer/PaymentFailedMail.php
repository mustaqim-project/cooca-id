<?php

declare(strict_types=1);

namespace App\Mail\Customer;

use App\Traits\HasQueueConfiguration;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class PaymentFailedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, HasQueueConfiguration;

    public function __construct(
        private readonly Customer $customer,
        private readonly Transaction $transaction,
        private readonly string $errorMessage,
        private readonly string $retryUrl
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⚠️ Gagal Melakukan Pembayaran Invoice: ' . $this->transaction->invoice_number,
            tags: ['payment', 'failed', 'invoice'],
            metadata: [
                'customer_id' => $this->customer->id,
                'transaction_id' => $this->transaction->id,
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.customer.payment-failed',
            with: [
                'customerName' => $this->customer->name,
                'invoiceNumber' => $this->transaction->invoice_number,
                'amount' => $this->transaction->net_amount,
                'errorMessage' => $this->errorMessage,
                'retryUrl' => $this->retryUrl,
            ],
        );
    }
}
