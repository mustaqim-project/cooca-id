<?php

declare(strict_types=1);

namespace App\Actions\Affiliate\CalculateCommission;

use App\Models\Transaction;
use App\Models\AffiliateCommission;
use App\Services\Affiliate\AffiliateService;

final readonly class CalculateCommissionAction
{
    public function __construct(
        private AffiliateService $affiliateService,
    ) {}

    /**
     * Execute commission calculation for a transaction
     * Returns array of created commissions and total amount
     */
    public function execute(Transaction $transaction): array
    {
        return $this->affiliateService->processCommissions($transaction);
    }
}
