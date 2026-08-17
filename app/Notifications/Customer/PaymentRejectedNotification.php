<?php

declare(strict_types=1);

namespace App\Notifications\Customer;

use App\Models\Transaction;
use App\Traits\HasQueueConfiguration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class PaymentRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable, HasQueueConfiguration;

    public function __construct(
        private readonly Transaction $transaction,
        private readonly string $reason
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Bukti Pembayaran Ditolak - Invoice #' . ($this->transaction->invoice_number ?? $this->transaction->id))
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Bukti pembayaran transfer manual yang Anda unggah untuk invoice #' . $this->transaction->invoice_number . ' belum dapat kami verifikasi.')
            ->line('Alasan Penolakan: ' . $this->reason)
            ->line('Silakan login ke dashboard Anda untuk memeriksa rincian tagihan dan mengunggah kembali bukti transfer yang valid.')
            ->action('Unggah Ulang Bukti Bayar', route('customer.invoices.show', $this->transaction->invoice?->id ?? $this->transaction->id))
            ->line('Jika Anda membutuhkan bantuan, silakan hubungi tim customer support kami.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'payment_rejected',
            'invoice_number' => $this->transaction->invoice_number,
            'amount' => (float) $this->transaction->net_amount,
            'reason' => $this->reason,
            'message' => 'Bukti pembayaran untuk invoice #' . $this->transaction->invoice_number . ' ditolak. Alasan: ' . $this->reason,
        ];
    }
}
