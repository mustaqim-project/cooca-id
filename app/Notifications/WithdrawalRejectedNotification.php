<?php

namespace App\Notifications;

use App\Traits\HasQueueConfiguration;

use App\Models\AffiliateWithdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WithdrawalRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable, HasQueueConfiguration;

    public function __construct(
        private AffiliateWithdrawal $withdrawal,
        private string $reason
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
            ->subject('Penarikan Ditolak')
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Maaf, penarikan dana Anda ditolak.')
            ->line('Detail Penarikan:')
            ->line('- Jumlah: Rp ' . number_format($this->withdrawal->amount, 0, ',', '.'))
            ->line('- Alasan: ' . $this->reason)
            ->line('Dana telah dikembalikan ke saldo wallet Anda.')
            ->action('Lihat Detail', url('/affiliator/withdrawals'));
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Penarikan Ditolak',
            'message' => 'Penarikan Rp ' . number_format($this->withdrawal->amount, 0, ',', '.') . ' ditolak: ' . $this->reason,
            'type' => 'withdrawal_rejected',
            'withdrawal_id' => $this->withdrawal->id,
            'amount' => $this->withdrawal->amount,
            'reason' => $this->reason,
            'url' => '/affiliator/withdrawals',
        ];
    }
}
