<?php

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionExpiredNotification extends Notification implements ShouldQueue
{
    use Queueable, HasQueueConfiguration;

    public function __construct(
        private Subscription $subscription
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
        return (new MailMessage)
            ->subject('Langganan Anda Telah Berakhir')
            ->greeting('Halo ' . $notifiable->name . '!')
            ->error()
            ->line('Langganan COOCA.ID Anda telah berakhir pada ' . $this->subscription->ends_at->format('d F Y') . '.')
            ->line('Akses Anda ke fitur-fitur premium telah ditangguhkan.')
            ->line('Silakan perpanjang langganan untuk melanjutkan akses.')
            ->action('Perpanjang Sekarang', url('/customer/subscription'))
            ->line('Jika ada pertanyaan, silakan hubungi tim support kami.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Langganan Telah Berakhir',
            'message' => 'Langganan Anda telah berakhir. Silakan perpanjang untuk melanjutkan akses.',
            'type' => 'subscription_expired',
            'subscription_id' => $this->subscription->id,
            'ended_at' => $this->subscription->ends_at?->toIso8601String(),
            'url' => '/customer/subscription',
        ];
    }
}
