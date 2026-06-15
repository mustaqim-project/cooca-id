<?php

declare(strict_types=1);

namespace App\Notifications\Affiliator;

use App\Models\AffiliateCommission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class CommissionEarnedNotification extends Notification implements ShouldQueue
{
    use Queueable, HasQueueConfiguration;

    public function __construct(
        private readonly AffiliateCommission $commission,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Commission Earned - COOCA.ID Affiliate Program')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Congratulations! You have earned a new commission.')
            ->line('Commission Details:')
            ->line('- Amount: Rp ' . number_format($this->commission->amount, 0, ',', '.'))
            ->line('- Level: ' . $this->commission->level)
            ->line('- From Transaction: ' . $this->commission->transaction->invoice->invoice_number ?? 'N/A')
            ->line('- Status: ' . ucfirst($this->commission->status))
            ->action('View Your Wallet', route('affiliator.wallet.index'))
            ->line('Keep promoting COOCA.ID to earn more commissions!')
            ->salutation('Thank you for being part of our affiliate program');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'commission_earned',
            'commission_id' => $this->commission->id->toString(),
            'amount' => $this->commission->amount,
            'level' => $this->commission->level,
            'message' => 'You have earned a new commission of Rp ' . number_format($this->commission->amount, 0, ',', '.'),
        ];
    }
}
