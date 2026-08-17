<?php
declare(strict_types=1);

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

final class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \App\Events\Payment\PaymentPaid::class => [
            \App\Listeners\Payment\ProcessCommissionOnPayment::class,
            \App\Listeners\Payment\ActivateSubscriptionOnPayment::class,
            \App\Listeners\Payment\SendPaymentConfirmation::class,
        ],
        \App\Events\Payment\PaymentFailed::class => [],
        \App\Events\Subscription\SubscriptionActivated::class => [],
        \App\Events\Subscription\SubscriptionExpired::class => [
            \App\Listeners\Subscription\SendSubscriptionExpiryWarning::class,
        ],
        \App\Events\Subscription\SubscriptionCancelled::class => [],
        \App\Events\License\LicenseGenerated::class => [
            \App\Listeners\License\SendLicenseReadyNotification::class,
            \App\Listeners\Ai\ProvisionAiApiKeyOnLicenseActivated::class,
        ],
        \App\Events\License\LicenseRevoked::class => [],
        \App\Events\Affiliate\CommissionCalculated::class => [],
        \App\Events\Affiliate\WithdrawalRequested::class => [],
        \App\Events\Affiliate\WithdrawalApproved::class => [],
        \App\Events\Customer\CustomerRegistered::class => [
            \App\Listeners\Customer\SendWelcomeNotification::class,
        ],
        \App\Events\Customer\AffiliatorRegistered::class => [],
    ];

    public function boot(): void
    {
        //
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
