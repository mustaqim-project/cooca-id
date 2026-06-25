<?php

declare(strict_types=1);

namespace App\Services\Affiliate;

use App\Models\AffiliateWallet;
use App\Models\Affiliator;
use App\Models\Transaction;
use App\Models\AffiliateCommission;
use App\Models\AffiliateWithdrawal;
use App\Models\Setting;
use App\DTOs\Affiliate\CommissionData;
use App\Repositories\Contracts\AffiliateCommissionRepositoryInterface;
use App\Repositories\Contracts\AffiliatorRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class AffiliateService
{
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
                $l1CommissionPercent = $this->commissionPercent(1);
                $l1CommissionAmount = CommissionData::calculateCommission(
                    (float) $transaction->gross_amount,
                    1
                );

                $l1Commission = $this->createCommission(
                    affiliatorId: $l1Affiliator->id,
                    transactionId: $transaction->id,
                    customerId: $customer->id,
                    level: 1,
                    grossAmount: (float) $transaction->gross_amount,
                    commissionPercent: $l1CommissionPercent,
                    commissionAmount: $l1CommissionAmount,
                );

                $commissions[] = $l1Commission;
                $totalCommission += $l1CommissionAmount;

                $this->incrementPendingBalance($l1Affiliator->id, $l1CommissionAmount);

                // Level 2: Parent affiliator (5% of gross_amount)
                if ($l1Affiliator->parent_affiliator_id) {
                    $l2Affiliator = $l1Affiliator->parent;
                    if ($l2Affiliator) {
                        $l2CommissionPercent = $this->commissionPercent(2);
                        $l2CommissionAmount = CommissionData::calculateCommission(
                            (float) $transaction->gross_amount,
                            2
                        );

                        $l2Commission = $this->createCommission(
                            affiliatorId: $l2Affiliator->id,
                            transactionId: $transaction->id,
                            customerId: $customer->id,
                            level: 2,
                            grossAmount: (float) $transaction->gross_amount,
                            commissionPercent: $l2CommissionPercent,
                            commissionAmount: $l2CommissionAmount,
                        );

                        $commissions[] = $l2Commission;
                        $totalCommission += $l2CommissionAmount;

                        $this->incrementPendingBalance($l2Affiliator->id, $l2CommissionAmount);
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
        string $affiliatorId,
        string $transactionId,
        string $customerId,
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

    private function commissionPercent(int $level): float
    {
        return match ($level) {
            1 => (float) Setting::get('affiliate.commission_rate_level_1', config('affiliate.commission_rate_level_1', 25)),
            2 => (float) Setting::get('affiliate.commission_rate_level_2', config('affiliate.commission_rate_level_2', 5)),
            default => 0.0,
        };
    }

    private function wallet(string $affiliatorId): AffiliateWallet
    {
        return AffiliateWallet::firstOrCreate(
            ['affiliator_id' => $affiliatorId],
            ['balance' => 0, 'pending_balance' => 0]
        );
    }

    private function incrementPendingBalance(string $affiliatorId, float $amount): void
    {
        $this->wallet($affiliatorId)->increment('pending_balance', $amount);
    }

    private function incrementAvailableBalance(string $affiliatorId, float $amount): void
    {
        $this->wallet($affiliatorId)->increment('balance', $amount);

        DB::table('affiliators')
            ->where('id', $affiliatorId)
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

            if ($commission->status === AffiliateCommission::STATUS_CLEARED) {
                $this->incrementAvailableBalance($commission->affiliator_id, -(float) $commission->commission_amount);
            } else {
                $this->incrementPendingBalance($commission->affiliator_id, -(float) $commission->commission_amount);
            }
        }
    }

    /**
     * Clear pending commissions to cleared status
     */
    public function clearCommissions(\DateTimeInterface $beforeDate): int
    {
        $clearedCount = 0;

        $commissions = AffiliateCommission::query()
            ->where('status', AffiliateCommission::STATUS_PENDING)
            ->where('created_at', '<=', $beforeDate)
            ->get();

        foreach ($commissions as $commission) {
            $this->commissionRepository->update($commission->id, [
                'status' => AffiliateCommission::STATUS_CLEARED,
                'cleared_at' => now(),
            ]);

            $this->incrementPendingBalance($commission->affiliator_id, -(float) $commission->commission_amount);
            $this->incrementAvailableBalance($commission->affiliator_id, (float) $commission->commission_amount);

            $clearedCount++;
        }

        return $clearedCount;
    }

    /**
     * Get total commission for an affiliator
     */
    public function getTotalCommission(Affiliator $affiliator, ?string $status = null): float
    {
        $query = AffiliateCommission::query()->where('affiliator_id', $affiliator->id);

        if ($status !== null) {
            $query->where('status', $status);
        }

        return (float) $query->sum('commission_amount');
    }

    /**
     * Get commission breakdown by level
     */
    public function getCommissionBreakdown(Affiliator $affiliator): array
    {
        return [
            'level_1' => (float) AffiliateCommission::query()
                ->where('affiliator_id', $affiliator->id)
                ->where('level', 1)
                ->sum('commission_amount'),
            'level_2' => (float) AffiliateCommission::query()
                ->where('affiliator_id', $affiliator->id)
                ->where('level', 2)
                ->sum('commission_amount'),
        ];
    }

    public function getAvailableBalance(string $affiliatorId): float
    {
        return (float) $this->wallet($affiliatorId)->balance;
    }

    public function requestWithdrawal(
        string $affiliatorId,
        float $amount,
        string $withdrawalMethod,
        string $accountNumber,
        string $accountName
    ): AffiliateWithdrawal {
        return DB::transaction(function () use ($affiliatorId, $amount, $withdrawalMethod, $accountNumber, $accountName) {
            $minimumWithdrawal = (float) Setting::get('affiliate.minimum_withdrawal', config('affiliate.minimum_withdrawal', 50000));
            if ($amount < $minimumWithdrawal) {
                throw new \InvalidArgumentException('Minimum withdrawal is Rp ' . number_format($minimumWithdrawal, 0, ',', '.'));
            }

            $this->wallet($affiliatorId);

            $wallet = AffiliateWallet::query()
                ->where('affiliator_id', $affiliatorId)
                ->lockForUpdate()
                ->first();
            if (!$wallet || (float) $wallet->balance < $amount) {
                throw new \InvalidArgumentException('Insufficient wallet balance');
            }

            $fee = $this->withdrawalFee($withdrawalMethod);
            $netAmount = max(0, $amount - $fee);

            $wallet->decrement('balance', $amount);
            DB::table('affiliators')->where('id', $affiliatorId)->decrement('balance', $amount);

            return AffiliateWithdrawal::create([
                'affiliator_id' => $affiliatorId,
                'amount' => $amount,
                'fee' => $fee,
                'net_amount' => $netAmount,
                'withdrawal_method' => $withdrawalMethod,
                'account_number' => $accountNumber,
                'account_name' => $accountName,
                'status' => AffiliateWithdrawal::STATUS_PENDING,
            ]);
        });
    }

    public function getWithdrawals(string $affiliatorId, int $perPage = 15): LengthAwarePaginator
    {
        return AffiliateWithdrawal::query()
            ->where('affiliator_id', $affiliatorId)
            ->latest()
            ->paginate($perPage);
    }

    public function getWithdrawalHistory(string $affiliatorId)
    {
        return AffiliateWithdrawal::query()
            ->where('affiliator_id', $affiliatorId)
            ->latest()
            ->get();
    }

    public function getWithdrawalById(string $id, string $affiliatorId): ?AffiliateWithdrawal
    {
        return AffiliateWithdrawal::query()
            ->where('id', $id)
            ->where('affiliator_id', $affiliatorId)
            ->first();
    }

    public function getWithdrawalsPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return AffiliateWithdrawal::query()
            ->with('affiliator')
            ->latest()
            ->paginate($perPage);
    }

    public function findWithdrawalById(string $id): ?AffiliateWithdrawal
    {
        return AffiliateWithdrawal::query()
            ->with('affiliator')
            ->find($id);
    }

    public function approveWithdrawal(string $id, string $adminId): void
    {
        AffiliateWithdrawal::query()
            ->where('id', $id)
            ->where('status', AffiliateWithdrawal::STATUS_PENDING)
            ->update([
                'status' => AffiliateWithdrawal::STATUS_APPROVED,
                'approved_by' => $adminId,
                'approved_at' => now(),
            ]);
    }

    public function rejectWithdrawal(string $id, string $adminId, string $reason): void
    {
        DB::transaction(function () use ($id, $adminId, $reason): void {
            $withdrawal = AffiliateWithdrawal::query()
                ->where('id', $id)
                ->where('status', AffiliateWithdrawal::STATUS_PENDING)
                ->lockForUpdate()
                ->firstOrFail();

            $this->incrementAvailableBalance($withdrawal->affiliator_id, (float) $withdrawal->amount);

            $withdrawal->update([
                'status' => AffiliateWithdrawal::STATUS_REJECTED,
                'approved_by' => $adminId,
                'rejected_at' => now(),
                'rejection_reason' => $reason,
            ]);
        });
    }

    public function markWithdrawalAsPaid(string $id): void
    {
        AffiliateWithdrawal::query()
            ->where('id', $id)
            ->where('status', AffiliateWithdrawal::STATUS_APPROVED)
            ->update([
                'status' => AffiliateWithdrawal::STATUS_PAID,
                'paid_at' => now(),
            ]);
    }

    private function withdrawalFee(string $withdrawalMethod): float
    {
        $settingKey = $withdrawalMethod === 'bank'
            ? 'affiliate.withdrawal_fee_bank'
            : 'affiliate.withdrawal_fee_ewallet';

        $configKey = $withdrawalMethod === 'bank'
            ? 'affiliate.withdrawal_fee_bank'
            : 'affiliate.withdrawal_fee_ewallet';

        return (float) Setting::get($settingKey, config($configKey, 0));
    }
}
