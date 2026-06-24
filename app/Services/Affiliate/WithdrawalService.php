<?php

namespace App\Services\Affiliate;

use App\Models\User;
use App\Models\AffiliateWallet;
use App\Models\AffiliateWithdrawal;
use App\Models\AffiliateCommission;
use Illuminate\Support\Facades\DB;

class WithdrawalService
{
    /**
     * Minimum withdrawal amount
     */
    const MINIMUM_WITHDRAWAL = 100000; // IDR

    /**
     * Create a withdrawal request
     */
    public function createWithdrawal(User $affiliate, array $data): AffiliateWithdrawal
    {
        DB::beginTransaction();
        try {
            $wallet = AffiliateWallet::where('affiliator_id', $affiliate->id)->firstOrFail();
            
            $amount = (float) ($data['amount'] ?? 0);

            // Validate minimum amount
            if ($amount < self::MINIMUM_WITHDRAWAL) {
                throw new \InvalidArgumentException("Minimum withdrawal is Rp " . number_format(self::MINIMUM_WITHDRAWAL, 0, ',', '.'));
            }

            // Validate sufficient balance
            if ($wallet->balance < $amount) {
                throw new \InvalidArgumentException("Insufficient wallet balance");
            }

            // Deduct from wallet
            $wallet->decrement('balance', $amount);

            // Create withdrawal request
            $withdrawal = AffiliateWithdrawal::create([
                'affiliate_id' => $affiliate->id,
                'amount' => $amount,
                'bank_name' => $data['bank_name'],
                'account_number' => $data['account_number'],
                'account_holder' => $data['account_holder'],
                'notes' => $data['notes'] ?? null,
                'status' => 'pending',
                'requested_at' => now(),
            ]);

            DB::commit();

            // Create activity log
            \App\Models\ActivityLog::create([
                'user_id' => $affiliate->id,
                'user_type' => 'affiliator',
                'action' => 'withdrawal_requested',
                'module' => 'Affiliate',
                'description' => "Withdrawal requested: Rp " . number_format($amount, 0, ',', '.'),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'metadata' => [
                    'withdrawal_id' => $withdrawal->id,
                    'amount' => $amount,
                ],
            ]);

            return $withdrawal;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Approve a withdrawal request
     */
    public function approveWithdrawal(AffiliateWithdrawal $withdrawal, string $processedBy): void
    {
        DB::beginTransaction();
        try {
            $withdrawal->update([
                'status' => 'approved',
                'approved_by' => $processedBy,
                'approved_at' => now(),
            ]);

            // Create activity log
            \App\Models\ActivityLog::create([
                'user_id' => $processedBy,
                'user_type' => 'admin',
                'action' => 'withdrawal_approved',
                'module' => 'Affiliate',
                'description' => "Withdrawal approved for user {$withdrawal->affiliate_id}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'metadata' => [
                    'withdrawal_id' => $withdrawal->id,
                    'amount' => $withdrawal->amount,
                ],
            ]);

            // Send notification
            $withdrawal->affiliate->notify(
                new \App\Notifications\WithdrawalApprovedNotification($withdrawal)
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Reject a withdrawal request
     */
    public function rejectWithdrawal(AffiliateWithdrawal $withdrawal, string $processedBy, string $reason): void
    {
        DB::beginTransaction();
        try {
            // Refund to wallet
            $wallet = AffiliateWallet::where('affiliator_id', $withdrawal->affiliate_id)->firstOrFail();
            $wallet->increment('balance', $withdrawal->amount);

            $withdrawal->update([
                'status' => 'rejected',
                'rejected_by' => $processedBy,
                'rejected_at' => now(),
                'rejection_reason' => $reason,
            ]);

            // Create activity log
            \App\Models\ActivityLog::create([
                'user_id' => $processedBy,
                'user_type' => 'admin',
                'action' => 'withdrawal_rejected',
                'module' => 'Affiliate',
                'description' => "Withdrawal rejected for user {$withdrawal->affiliate_id}: {$reason}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'metadata' => [
                    'withdrawal_id' => $withdrawal->id,
                    'reason' => $reason,
                ],
            ]);

            // Send notification
            $withdrawal->affiliate->notify(
                new \App\Notifications\WithdrawalRejectedNotification($withdrawal, $reason)
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get withdrawal statistics for an affiliate
     */
    public function getStatistics(User $affiliate): array
    {
        $totalWithdrawn = AffiliateWithdrawal::where('affiliate_id', $affiliate->id)
            ->where('status', 'approved')
            ->sum('amount');

        $pendingWithdrawals = AffiliateWithdrawal::where('affiliate_id', $affiliate->id)
            ->where('status', 'pending')
            ->sum('amount');

        return [
            'total_withdrawn' => $totalWithdrawn,
            'pending_amount' => $pendingWithdrawals,
            'available_balance' => $affiliate->wallet?->balance ?? 0,
            'pending_balance' => $affiliate->wallet?->pending_balance ?? 0,
        ];
    }
}
