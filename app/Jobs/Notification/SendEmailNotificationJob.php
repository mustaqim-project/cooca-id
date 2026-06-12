<?php
declare(strict_types=1);

namespace App\Jobs\Notification;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class SendEmailNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        private readonly mixed $notifiable,
        private readonly string $notificationType,
        private readonly array $data,
    ) {}

    public function handle(): void
    {
        try {
            $notificationClass = match ($this->notificationType) {
                'payment_confirmed' => \App\Notifications\Customer\PaymentConfirmedNotification::class,
                'license_ready' => \App\Notifications\Customer\LicenseReadyNotification::class,
                'subscription_expiry_warning' => \App\Notifications\Customer\SubscriptionExpiryReminderNotification::class,
                'welcome' => \App\Notifications\Customer\WelcomeNotification::class,
                default => null,
            };

            if ($notificationClass && method_exists($this->notifiable, 'notify')) {
                $this->notifiable->notify(new $notificationClass(...array_values($this->data)));
            }
        } catch (\Throwable $e) {
            report($e);
            throw $e;
        }
    }
}
