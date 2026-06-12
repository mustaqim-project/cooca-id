<?php
declare(strict_types=1);

namespace App\Notifications\Customer;

use App\Mail\Customer\PaymentConfirmedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\WhatsAppMessage;
use Illuminate\Notifications\Notification;

final class PaymentConfirmedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        private readonly string $invoiceNumber,
        private readonly float $amount,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Pembayaran Berhasil - Invoice ' . $this->invoiceNumber)
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Pembayaran Anda telah berhasil diproses.')
            ->line('Invoice Number: ' . $this->invoiceNumber)
            ->line('Jumlah Dibayar: Rp ' . number_format($this->amount, 0, ',', '.'))
            ->action('Lihat Dashboard', route('customer.dashboard'))
            ->line('Terima kasih telah menggunakan COOCA.ID.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'payment_confirmed',
            'invoice_number' => $this->invoiceNumber,
            'amount' => $this->amount,
            'message' => 'Pembayaran Anda dengan invoice ' . $this->invoiceNumber . ' sebesar Rp ' . number_format($this->amount, 0, ',', '.') . ' telah berhasil.',
        ];
    }
}
