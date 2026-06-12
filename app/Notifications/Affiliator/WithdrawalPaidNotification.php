<?php
declare(strict_types=1);

namespace App\Notifications\Affiliator;

use App\Models\AffiliateWithdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class WithdrawalPaidNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        private readonly AffiliateWithdrawal $withdrawal,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Withdrawal Telah Ditransfer')
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Withdrawal Anda telah berhasil ditransfer.')
            ->line('Jumlah: Rp ' . number_format($this->withdrawal->amount, 0, ',', '.'))
            ->line('Fee: Rp ' . number_format($this->withdrawal->fee, 0, ',', '.'))
            ->line('Diterima: Rp ' . number_format($this->withdrawal->net_amount, 0, ',', '.'))
            ->line('Metode: ' . ucfirst($this->withdrawal->withdrawal_method))
            ->line('Silakan cek rekening Anda dalam 1-3 hari kerja.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'withdrawal_paid',
            'withdrawal_id' => $this->withdrawal->id,
            'amount' => $this->withdrawal->amount,
            'net_amount' => $this->withdrawal->net_amount,
            'message' => 'Withdrawal sebesar Rp ' . number_format($this->withdrawal->net_amount, 0, ',', '.') . ' telah ditransfer.',
        ];
    }
}
