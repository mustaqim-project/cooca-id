<?php

declare(strict_types=1);

namespace App\Services\Affiliate;

use App\Models\Affiliator;
use App\Models\Transaction;
use App\Models\AffiliateCommission;
use App\DTOs\Affiliate\CommissionData;
use App\Repositories\Contracts\AffiliateCommissionRepositoryInterface;
use App\Repositories\Contracts\AffiliatorRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class AffiliateService
{
    // Commission percentages (from GROSS_AMOUNT, not net_amount)
    private const float L1_COMMISSION_PERCENT = 25.0;
    private const float L2_COMMISSION_PERCENT = 5.0;

    public function __construct(
        private readonly AffiliateCommissionRepositoryInterface $commissionRepository,
        private readonly AffiliatorRepositoryInterface $affiliatorRepository,
    ) {}

    /**
     * Calculate and create commissions for a transaction
     * CRITICAL: Commission is calculated from gross_amount, NOT net_amount
     * Wrapped in DB transaction for data integrity
     */
    public function processCommissions(Transaction $transaction): array
    {
        return DB::transaction(function () use ($transaction) {
            $customer = $transaction->customer;
            
            if (!$customer->affiliator_id) {
                return ['commissions' => [], 'total' => 0];
            }

            $commissions = [];
            $totalCommission = 0;

            // Level 1: Direct referrer (25% of gross_amount)
            $l1Affiliator = $customer->affiliator;
            if ($l1Affiliator) {
                $l1CommissionAmount = CommissionData::calculateCommission(
                    $transaction->gross_amount,
                    1
                );

                $l1Commission = $this->createCommission(
                    affiliatorId: $l1Affiliator->id,
                    transactionId: $transaction->id,
                    customerId: $customer->id,
                    level: 1,
                    grossAmount: $transaction->gross_amount,
                    commissionPercent: self::L1_COMMISSION_PERCENT,
                    commissionAmount: $l1CommissionAmount,
                );

                $commissions[] = $l1Commission;
                $totalCommission += $l1CommissionAmount;

                // Update L1 affiliator balance
                $this->updateAffiliatorBalance($l1Affiliator->id, $l1CommissionAmount);

                // Level 2: Parent affiliator (5% of gross_amount)
                if ($l1Affiliator->parent_affiliator_id) {
                    $l2Affiliator = $l1Affiliator->parent;
                    if ($l2Affiliator) {
                        $l2CommissionAmount = CommissionData::calculateCommission(
                            $transaction->gross_amount,
                            2
                        );

                        $l2Commission = $this->createCommission(
                            affiliatorId: $l2Affiliator->id,
                            transactionId: $transaction->id,
                            customerId: $customer->id,
                            level: 2,
                            grossAmount: $transaction->gross_amount,
                            commissionPercent: self::L2_COMMISSION_PERCENT,
                            commissionAmount: $l2CommissionAmount,
                        );

                        $commissions[] = $l2Commission;
                        $totalCommission += $l2CommissionAmount;

                        // Update L2 affiliator balance
                        $this->updateAffiliatorBalance($l2Affiliator->id, $l2CommissionAmount);
                    }
                }
            }

            return [
                'commissions' => $commissions,
                'total' => $totalCommission,
            ];
        });
    }

    /**
     * Create a commission record
     */
    private function createCommission(
        \Ramsey\Uuid\UuidInterface $affiliatorId,
        \Ramsey\Uuid\UuidInterface $transactionId,
        \Ramsey\Uuid\UuidInterface $customerId,
        int $level,
        float $grossAmount,
        float $commissionPercent,
        float $commissionAmount,
    ): AffiliateCommission {
        return $this->commissionRepository->create([
            'affiliator_id' => $affiliatorId,
            'transaction_id' => $transactionId,
            'customer_id' => $customerId,
            'level' => $level,
            'gross_amount' => $grossAmount,
            'commission_percent' => $commissionPercent,
            'commission_amount' => $commissionAmount,
            'status' => 'pending',
        ]);
    }

    /**
     * Update affiliator balance
     */
    private function updateAffiliatorBalance(\Ramsey\Uuid\UuidInterface $affiliatorId, float $amount): void
    {
        DB::table('affiliators')
            ->where('id', $affiliatorId->toString())
            ->increment('balance', $amount);
    }

    /**
     * Clear commissions for a cancelled transaction
     */
    public function cancelCommissions(Transaction $transaction): void
    {
        $commissions = $this->commissionRepository->findByTransactionId($transaction->id);

        foreach ($commissions as $commission) {
            $this->commissionRepository->update($commission->id, [
                'status' => 'cancelled',
            ]);

            // Deduct from affiliator balance
            $this->updateAffiliatorBalance($commission->affiliator_id, -$commission->commission_amount);
        }
    }

    /**
     * Clear pending commissions to cleared status
     */
    public function clearCommissions(\DateTimeInterface $beforeDate): int
    {
        $clearedCount = 0;

        $commissions = $this->commissionRepository->getPendingCommissionsBefore($beforeDate);

        foreach ($commissions as $commission) {
            $this->commissionRepository->update($commission->id, [
                'status' => 'cleared',
                'cleared_at' => now(),
            ]);
            $clearedCount++;
        }

        return $clearedCount;
    }

    /**
     * Get total commission for an affiliator
     */
    public function getTotalCommission(Affiliator $affiliator, ?string $status = null): float
    {
        return $this->commissionRepository->getTotalByAffiliator($affiliator->id, $status);
    }

    /**
     * Get commission breakdown by level
     */
    public function getCommissionBreakdown(Affiliator $affiliator): array
    {
        return [
            'level_1' => $this->commissionRepository->getTotalByAffiliatorAndLevel($affiliator->id, 1),
            'level_2' => $this->commissionRepository->getTotalByAffiliatorAndLevel($affiliator->id, 2),
        ];
    }
}
