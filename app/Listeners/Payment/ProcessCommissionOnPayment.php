<?php
declare(strict_types=1);

namespace App\Listeners\Payment;

use App\Events\Payment\PaymentPaid;
use App\Services\Affiliate\AffiliateService;

final class ProcessCommissionOnPayment
{
    public function __construct(
        private readonly AffiliateService $affiliateService,
    ) {}

    public function handle(PaymentPaid $event): void
    {
        $this->affiliateService->processCommissions($event->transaction);
    }
}
