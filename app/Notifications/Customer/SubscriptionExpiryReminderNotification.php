<?php
declare(strict_types=1);

namespace App\Notifications\Customer;

use App\Traits\HasQueueConfiguration;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class SubscriptionExpiryReminderNotification extends Notification implements ShouldQueue
{
    use Queueable, HasQueueConfiguration;


    public function __construct(
        private readonly string $productName,
        private readonly \DateTimeInterface $expiresAt,
        private readonly int $daysUntilExpiry,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Peringatan: Subscription akan Berakhir dalam ' . $this->daysUntilExpiry . ' Hari')
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Subscription Anda untuk ' . $this->productName . ' akan segera berakhir.')
            ->line('Tanggal berakhir: ' . $this->expiresAt->format('d F Y'))
            ->line('Sisa waktu: ' . $this->daysUntilExpiry . ' hari')
            ->action('Perpanjang Sekarang', route('customer.subscriptions.index'))
            ->line('Perpanjang sekarang untuk menghindari gangguan layanan.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'subscription_expiry_warning',
            'product_name' => $this->productName,
            'expires_at' => $this->expiresAt->format('Y-m-d H:i:s'),
            'days_until_expiry' => $this->daysUntilExpiry,
            'message' => 'Subscription Anda untuk ' . $this->productName . ' akan berakhir dalam ' . $this->daysUntilExpiry . ' hari.',
        ];
    }
}
