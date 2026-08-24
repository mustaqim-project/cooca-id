<?php

declare(strict_types=1);

namespace App\Mail\Admin;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionPaymentReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Transaction $transaction,
        public string $eventTitle = 'Pembayaran Langganan Diterima'
    ) {}

    public function envelope(): Envelope
    {
        $siteName = setting('site.name', 'COOCA.ID');
        $invoiceNum = $this->transaction->invoice_number ?? $this->transaction->code ?? ('#' . $this->transaction->id);
        $amountFmt = 'Rp ' . number_format((float) ($this->transaction->net_amount ?? $this->transaction->gross_amount), 0, ',', '.');
        $customerName = $this->transaction->customer?->name ?? 'Customer';

        return new Envelope(
            subject: "[Notifikasi Pembayaran] {$this->eventTitle} — {$invoiceNum} ({$amountFmt}) — {$customerName}",
        );
    }

    public function content(): Content
    {
        $this->transaction->loadMissing(['customer', 'subscription.subscriptionPlan.product', 'subscription.license', 'invoice', 'aiTokenPurchase.package', 'project']);

        return new Content(
            view: 'emails.admin-subscription-payment-received',
            with: [
                'transaction' => $this->transaction,
                'customer'    => $this->transaction->customer,
                'subscription'=> $this->transaction->subscription,
                'plan'        => $this->transaction->subscription?->subscriptionPlan,
                'product'     => $this->transaction->subscription?->subscriptionPlan?->product,
                'license'     => $this->transaction->subscription?->license,
                'eventTitle'  => $this->eventTitle,
                'siteName'    => setting('site.name', 'COOCA.ID'),
            ],
        );
    }
}
