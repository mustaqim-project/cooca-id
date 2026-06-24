<?php

declare(strict_types=1);

namespace App\Notifications\Affiliator;

use App\Traits\HasQueueConfiguration;

use App\Models\AffiliateWithdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class WithdrawalApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable, HasQueueConfiguration;

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
            ->subject('Withdrawal Approved - COOCA.ID Affiliate Program')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your withdrawal request has been approved and is being processed.')
            ->line('Withdrawal Details:')
            ->line('- Amount: Rp ' . number_format($this->withdrawal->amount, 0, ',', '.'))
            ->line('- Bank: ' . $this->withdrawal->bank_name)
            ->line('- Account Number: ' . $this->withdrawal->account_number)
            ->line('- Requested On: ' . $this->withdrawal->created_at->format('d F Y'))
            ->line('The funds will be transferred to your account within 1-3 business days.')
            ->action('View Your Wallet', route('affiliator.wallet.index'))
            ->salutation('Thank you for being part of our affiliate program');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'withdrawal_approved',
            'withdrawal_id' => $this->withdrawal->id,
            'amount' => $this->withdrawal->amount,
            'message' => 'Your withdrawal of Rp ' . number_format($this->withdrawal->amount, 0, ',', '.') . ' has been approved.',
        ];
    }
}
