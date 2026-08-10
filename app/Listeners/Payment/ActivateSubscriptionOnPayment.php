<?php

declare(strict_types=1);

namespace App\Listeners\Payment;

use App\Events\Payment\PaymentPaid;
use App\Jobs\Notification\SendPaymentConfirmationJob;
use App\Services\Subscription\SubscriptionService;

final class ActivateSubscriptionOnPayment
{
    public function __construct(
        private readonly SubscriptionService $subscriptionService,
    ) {}

    public function handle(PaymentPaid $event): void
    {
        if ($event->transaction->subscription) {
            $subscription = $event->transaction->subscription;
            $duration = $subscription->subscriptionPlan->duration_months ?? 1;
            $this->subscriptionService->activateSubscription($subscription, (int) $duration);
        }
    }
}
