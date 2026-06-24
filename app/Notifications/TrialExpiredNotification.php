<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Traits\HasQueueConfiguration;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TrialExpiredNotification extends Notification implements ShouldQueue
{
    use Queueable, HasQueueConfiguration;

    public function __construct(
        private readonly Subscription $subscription
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
            ->subject('Trial Period Expired')
            ->greeting('Hello!')
            ->line("Your trial period for {$this->subscription->subscriptionPlan?->name ?? 'our service'} has expired.")
            ->line("Your access to the service has been suspended.")
            ->action('Upgrade to Continue', config('app.url') . '/subscription/plans')
            ->line('Upgrade to a paid plan to restore your access and continue using all features.')
            ->salutation('We hope to see you back soon!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'trial_expired',
            'subscription_id' => $this->subscription->id,
            'message' => "Your trial has expired. Upgrade to a paid plan to restore access.",
            'action_url' => '/subscription/plans',
            'priority' => 'urgent',
        ];
    }
}
