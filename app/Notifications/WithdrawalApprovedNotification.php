<?php

namespace App\Notifications;

use App\Models\AffiliateWithdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WithdrawalApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable, HasQueueConfiguration;

    public function __construct(
        private AffiliateWithdrawal $withdrawal
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
            ->subject('Penarikan Disetujui')
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Penarikan dana Anda telah disetujui.')
            ->line('Detail Penarikan:')
            ->line('- Jumlah: Rp ' . number_format($this->withdrawal->amount, 0, ',', '.'))
            ->line('- Bank: ' . $this->withdrawal->bank_name)
            ->line('- No. Rekening: ' . $this->withdrawal->account_number)
            ->line('- Atas Nama: ' . $this->withdrawal->account_holder)
            ->line('Dana akan ditransfer dalam 1-3 hari kerja.')
            ->action('Lihat Detail', url('/affiliator/withdrawals'));
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Penarikan Disetujui',
            'message' => 'Penarikan Rp ' . number_format($this->withdrawal->amount, 0, ',', '.') . ' telah disetujui',
            'type' => 'withdrawal_approved',
            'withdrawal_id' => $this->withdrawal->id,
            'amount' => $this->withdrawal->amount,
            'url' => '/affiliator/withdrawals',
        ];
    }
}
