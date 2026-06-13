<?php

namespace App\Notifications;

use App\Models\AffiliateCommission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class CommissionEarnedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private AffiliateCommission $commission
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
            ->subject('Komisi Baru Diterima!')
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Anda telah menerima komisi baru dari program afiliasi.')
            ->line('Detail Komisi:')
            ->line('- Jumlah: Rp ' . number_format($this->commission->commission_amount, 0, ',', '.'))
            ->line('- Level: ' . ($this->commission->level === 1 ? 'Level 1 (Direct)' : 'Level 2 (Upline)'))
            ->line('- Dari Order: ' . $this->commission->order->id)
            ->line('- Status: Pending (akan cair dalam 7 hari)')
            ->action('Lihat Detail', url('/affiliator/commissions'))
            ->line('Terima kasih telah menjadi bagian dari program afiliasi COOCA.ID!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Komisi Baru Diterima',
            'message' => 'Anda menerima komisi Rp ' . number_format($this->commission->commission_amount, 0, ',', '.') . ' dari order #' . $this->commission->order->id,
            'type' => 'commission_earned',
            'commission_id' => $this->commission->id,
            'order_id' => $this->commission->order_id,
            'amount' => $this->commission->commission_amount,
            'url' => '/affiliator/commissions',
        ];
    }

    /**
     * Get the broadcast representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => 'Komisi Baru Diterima',
            'message' => 'Anda menerima komisi Rp ' . number_format($this->commission->commission_amount, 0, ',', '.'),
            'type' => 'commission_earned',
        ]);
    }
}
