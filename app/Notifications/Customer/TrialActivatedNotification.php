<?php

declare(strict_types=1);

namespace App\Notifications\Customer;

use App\Models\License;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class TrialActivatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly License $license,
        private readonly \DateTimeInterface $trialEndsAt,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('🎉 Trial Activated - Your COOCA.ID ERP is Ready!')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Great news! Your ERP trial has been activated and is ready to use.')
            ->line('Trial Details:')
            ->line('- Domain: ' . $this->license->domain)
            ->line('- License Code: ' . $this->license->license_code)
            ->line('- Token Code: ' . $this->license->token_code)
            ->line('- Trial Ends: ' . $this->trialEndsAt->format('d F Y'))
            ->action('Login to Your ERP', route('customer.login'))
            ->line('If you have any questions during your trial, feel free to contact our support team.')
            ->salutation('Welcome to COOCA.ID!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'trial_activated',
            'license_id' => $this->license->id->toString(),
            'license_code' => $this->license->license_code,
            'domain' => $this->license->domain,
            'trial_ends_at' => $this->trialEndsAt->toIso8601String(),
            'message' => 'Your trial has been activated! Your ERP system is ready to use.',
        ];
    }
}
