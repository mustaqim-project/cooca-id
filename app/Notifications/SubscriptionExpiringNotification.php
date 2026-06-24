<?php

namespace App\Notifications;

use App\Traits\HasQueueConfiguration;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionExpiringNotification extends Notification implements ShouldQueue
{
    use Queueable, HasQueueConfiguration;

    public function __construct(
        private Subscription $subscription,
        private int $daysRemaining
    ) {}

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $daysText = $this->daysRemaining === 0 
            ? 'hari ini' 
            : ($this->daysRemaining === 1 ? 'besok' : "dalam {$this->daysRemaining} hari");

        return (new MailMessage)
            ->subject('Langganan Anda Segera Berakhir')
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line("Langganan COOCA.ID Anda akan berakhir {$daysText}.")
            ->line('Detail Langganan:')
            ->line('- Plan: ' . $this->subscription->plan)
            ->line('- Berakhir: ' . $this->subscription->ends_at->format('d F Y'))
            ->line('Perpanjang sekarang untuk melanjutkan akses ke semua fitur.')
            ->action('Perpanjang Sekarang', url('/customer/subscription'))
            ->line('Jika ada pertanyaan, silakan hubungi tim support kami.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Langganan Segera Berakhir',
            'message' => "Langganan Anda akan berakhir dalam {$this->daysRemaining} hari",
            'type' => 'subscription_expiring',
            'subscription_id' => $this->subscription->id,
            'days_remaining' => $this->daysRemaining,
            'ends_at' => $this->subscription->ends_at?->toIso8601String(),
            'url' => '/customer/subscription',
        ];
    }
}
