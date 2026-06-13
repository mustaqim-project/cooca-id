<?php

declare(strict_types=1);

namespace App\Notifications\Customer;

use App\Models\License;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class TrialExpiredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly License $license,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Trial Expired - Upgrade Your COOCA.ID Subscription')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your trial period for COOCA.ID ERP has expired.')
            ->line('Trial Details:')
            ->line('- Domain: ' . $this->license->domain)
            ->line('- Expired On: ' . $this->license->expires_at->format('d F Y'))
            ->line('To continue using our services, please upgrade to a paid subscription plan.')
            ->action('View Subscription Plans', route('customer.subscriptions.index'))
            ->line('If you have any questions or need assistance, please contact our support team.')
            ->salutation('Thank you for using COOCA.ID');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'trial_expired',
            'license_id' => $this->license->id->toString(),
            'license_code' => $this->license->license_code,
            'expired_at' => $this->license->expires_at->toIso8601String(),
            'message' => 'Your trial period has expired. Please upgrade to continue using our services.',
        ];
    }
}
