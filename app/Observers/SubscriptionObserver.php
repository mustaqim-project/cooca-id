<?php
declare(strict_types=1);

namespace App\Observers;

use App\Events\Subscription\SubscriptionExpired;
use App\Models\Subscription;

final class SubscriptionObserver
{
    public function updated(Subscription $subscription): void
    {
        if ($subscription->isDirty('status')) {
            if ($subscription->status === 'expired' && $subscription->wasChanged('status')) {
                event(new SubscriptionExpired($subscription));
            }
        }
    }
}
