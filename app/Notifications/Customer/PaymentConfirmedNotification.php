<?php
declare(strict_types=1);

namespace App\Notifications\Customer;

use App\Traits\HasQueueConfiguration;

use App\Mail\Customer\PaymentConfirmedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\WhatsAppMessage;
use Illuminate\Notifications\Notification;

final class PaymentConfirmedNotification extends Notification implements ShouldQueue
{
    use Queueable, HasQueueConfiguration;


    public function __construct(
        private readonly \App\Models\Transaction $transaction
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $this->transaction->loadMissing(['customer', 'invoice', 'subscription.subscriptionPlan.product', 'aiTokenPurchase.package', 'project']);
        
        $invoice = $this->transaction->invoice ?? \App\Models\Invoice::where('transaction_id', $this->transaction->id)->first();
        if (!$invoice) {
            $invoice = new \App\Models\Invoice([
                'invoice_number' => $this->transaction->invoice_number,
                'customer_id' => $this->transaction->customer_id,
                'amount' => $this->transaction->net_amount ?? $this->transaction->amount,
                'status' => 'paid',
                'paid_at' => $this->transaction->paid_at ?? now(),
                'issued_at' => $this->transaction->created_at ?? now(),
            ]);
            $invoice->setRelation('transaction', $this->transaction);
            $invoice->setRelation('customer', $notifiable);
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('emails.invoice-pdf', [
            'invoice' => $invoice,
            'transaction' => $this->transaction,
            'customer' => $notifiable,
        ]);

        $itemDesc = $this->transaction->type === 'ai_token_topup'
            ? 'Top-Up Kuota Token AI'
            : ($this->transaction->subscription?->subscriptionPlan?->name ?? ($this->transaction->description ?? 'Layanan SaaS COOCA.ID'));

        return (new MailMessage())
            ->subject('Pembayaran Berhasil & Terverifikasi - Invoice #' . $this->transaction->invoice_number)
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Kabar baik! Pembayaran Anda telah berhasil diproses dan dikonfirmasi.')
            ->line('• Nomor Invoice: ' . $this->transaction->invoice_number)
            ->line('• Layanan: ' . $itemDesc)
            ->line('• Total Pembayaran: Rp ' . number_format((float) ($this->transaction->net_amount ?? $this->transaction->amount), 0, ',', '.'))
            ->line('• Status: LUNAS (PAID)')
            ->action('Buka Dashboard Customer', route('customer.dashboard'))
            ->line('Terlampir file PDF bukti invoice resmi untuk transaksi ini.')
            ->line('Terima kasih telah mempercayai layanan COOCA.ID.')
            ->attachData($pdf->output(), 'Invoice_' . $this->transaction->invoice_number . '.pdf', [
                'mime' => 'application/pdf',
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'payment_confirmed',
            'invoice_number' => $this->transaction->invoice_number,
            'amount' => (float) $this->transaction->net_amount,
            'message' => 'Pembayaran Anda dengan invoice ' . $this->transaction->invoice_number . ' sebesar Rp ' . number_format((float) $this->transaction->net_amount, 0, ',', '.') . ' telah berhasil.',
        ];
    }
}
