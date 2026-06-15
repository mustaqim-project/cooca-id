<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\WhatsAppMessage;

class TrialExpiringSoonNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly Subscription $subscription,
        private readonly int $daysUntilExpiry
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
        $days = $this->daysUntilExpiry;
        
        return (new MailMessage)
            ->subject("Trial Ending Soon - {$days} Days Remaining")
            ->greeting('Hello!')
            ->line("Your trial period for {$this->subscription->subscriptionPlan?->name ?? 'our service'} will expire in {$days} day(s).")
            ->line("Don't lose access to all the features you've been enjoying!")
            ->action('Upgrade Now', config('app.url') . '/subscription/plans')
            ->line('Upgrade to a paid plan to continue using our services without interruption.')
            ->salutation('Thank you for using our service!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'trial_expiring_soon',
            'subscription_id' => $this->subscription->id,
            'days_until_expiry' => $this->daysUntilExpiry,
            'message' => "Your trial will expire in {$this->daysUntilExpiry} day(s). Upgrade now to continue using our services.",
            'action_url' => '/subscription/plans',
            'priority' => 'high',
        ];
    }
}
