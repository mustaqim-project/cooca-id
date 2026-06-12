<?php
declare(strict_types=1);

namespace App\Listeners\Subscription;

use App\Events\Subscription\SubscriptionExpired;
use App\Jobs\Notification\SendSubscriptionExpiryReminderJob;

final class SendSubscriptionExpiryWarning
{
    public function handle(SubscriptionExpired $event): void
    {
        SendSubscriptionExpiryReminderJob::dispatch(
            $event->subscription->customer,
            $event->subscription,
        );
    }
}
