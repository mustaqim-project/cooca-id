<?php

namespace App\Services\Affiliate;

use App\Models\Affiliator;
use App\Models\AffiliateWallet;
use App\Models\AffiliateWithdrawal;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class WithdrawalService
{
    /**
     * Create a withdrawal request
     */
    public function createWithdrawal(Affiliator $affiliate, array $data): AffiliateWithdrawal
    {
        DB::beginTransaction();
        try {
            $wallet = AffiliateWallet::firstOrCreate(
                ['affiliator_id' => $affiliate->id],
                ['balance' => 0, 'pending_balance' => 0]
            );
            
            $amount = (float) ($data['amount'] ?? 0);
            $minimumWithdrawal = (float) Setting::get('affiliate.minimum_withdrawal', config('affiliate.minimum_withdrawal', 50000));

            // Validate minimum amount
            if ($amount < $minimumWithdrawal) {
                throw new \InvalidArgumentException("Minimum withdrawal is Rp " . number_format($minimumWithdrawal, 0, ',', '.'));
            }

            // Validate sufficient balance
            if ($wallet->balance < $amount) {
                throw new \InvalidArgumentException("Insufficient wallet balance");
            }

            // Deduct from wallet
            $wallet->decrement('balance', $amount);
            $affiliate->decrement('balance', $amount);

            $withdrawalMethod = $data['withdrawal_method'] ?? $data['method'] ?? 'bank';
            $fee = $withdrawalMethod === 'bank'
                ? (float) Setting::get('affiliate.withdrawal_fee_bank', config('affiliate.withdrawal_fee_bank', 2500))
                : (float) Setting::get('affiliate.withdrawal_fee_ewallet', config('affiliate.withdrawal_fee_ewallet', 1000));

            // Create withdrawal request
            $withdrawal = AffiliateWithdrawal::create([
                'affiliator_id' => $affiliate->id,
                'amount' => $amount,
                'fee' => $fee,
                'net_amount' => max(0, $amount - $fee),
                'withdrawal_method' => $withdrawalMethod,
                'account_number' => $data['account_number'],
                'account_name' => $data['account_name'] ?? $data['account_holder'] ?? $affiliate->name,
                'status' => 'pending',
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
                'description' => "Withdrawal approved for user {$withdrawal->affiliator_id}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'metadata' => [
                    'withdrawal_id' => $withdrawal->id,
                    'amount' => $withdrawal->amount,
                ],
            ]);

            // Send notification
            $withdrawal->affiliator->notify(
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
            $wallet = AffiliateWallet::where('affiliator_id', $withdrawal->affiliator_id)->firstOrFail();
            $wallet->increment('balance', $withdrawal->amount);
            $withdrawal->affiliator?->increment('balance', $withdrawal->amount);

            $withdrawal->update([
                'status' => 'rejected',
                'approved_by' => $processedBy,
                'rejected_at' => now(),
                'rejection_reason' => $reason,
            ]);

            // Create activity log
            \App\Models\ActivityLog::create([
                'user_id' => $processedBy,
                'user_type' => 'admin',
                'action' => 'withdrawal_rejected',
                'module' => 'Affiliate',
                'description' => "Withdrawal rejected for user {$withdrawal->affiliator_id}: {$reason}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'metadata' => [
                    'withdrawal_id' => $withdrawal->id,
                    'reason' => $reason,
                ],
            ]);

            // Send notification
            $withdrawal->affiliator->notify(
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
    public function getStatistics(Affiliator $affiliate): array
    {
        $totalWithdrawn = AffiliateWithdrawal::where('affiliator_id', $affiliate->id)
            ->where('status', 'approved')
            ->sum('amount');

        $pendingWithdrawals = AffiliateWithdrawal::where('affiliator_id', $affiliate->id)
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
