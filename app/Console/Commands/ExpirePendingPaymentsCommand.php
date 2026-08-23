<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Domain;
use App\Models\Invoice;
use App\Models\License;
use App\Models\Subscription;
use App\Models\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class ExpirePendingPaymentsCommand extends Command
{
    protected $signature = 'payments:expire-pending';
    protected $description = 'Automatically expire pending payment transactions older than 1 hour and release reserved domains';

    public function handle(): int
    {
        $expirationThreshold = now()->subHours(1);

        $pendingTransactions = Transaction::where('status', 'pending')
            ->where('created_at', '<', $expirationThreshold)
            ->whereNull('payment_proof') // Don't auto-expire if customer uploaded manual transfer proof awaiting admin approval
            ->with(['subscription.license.domainRecord', 'invoice'])
            ->get();

        if ($pendingTransactions->isEmpty()) {
            $this->info('No pending transactions to expire.');
            return self::SUCCESS;
        }

        $count = 0;

        foreach ($pendingTransactions as $transaction) {
            DB::beginTransaction();
            try {
                // 1. Mark transaction as failed/expired
                $transaction->update([
                    'status' => 'failed',
                    'failed_at' => now(),
                    'rejection_reason' => 'Batas waktu pembayaran 1 jam telah berakhir (Expired).',
                ]);

                // 2. Mark invoice as cancelled
                if ($transaction->invoice) {
                    $transaction->invoice->update([
                        'status' => 'cancelled',
                    ]);
                }

                // 3. Clean up pending unpaid subscription & release domain
                $subscription = $transaction->subscription;
                if ($subscription) {
                    $license = $subscription->license;

                    // If the license is inactive (never paid/active), delete or cancel to free up the domain
                    if ($license && $license->status === License::STATUS_INACTIVE) {
                        $domainRecord = $license->domainRecord;
                        if ($domainRecord && $domainRecord->status === Domain::STATUS_PENDING) {
                            $domainRecord->delete();
                        }
                        $license->delete();
                    }

                    // If subscription was never activated (status trial/unpaid)
                    if (in_array($subscription->status, ['trial', 'unpaid', 'pending', 'inactive'], true)) {
                        $subscription->delete();
                    }
                }

                DB::commit();
                $count++;

                Log::info("[ExpirePendingPayments] Transaction #{$transaction->id} (Code: {$transaction->code}) expired and domain released.", [
                    'transaction_id' => $transaction->id,
                    'customer_id'    => $transaction->customer_id,
                ]);
            } catch (\Throwable $e) {
                DB::rollBack();
                Log::error("[ExpirePendingPayments] Failed to expire transaction #{$transaction->id}: " . $e->getMessage());
            }
        }

        $this->info("Successfully expired {$count} pending transaction(s) and released reserved domains.");
        return self::SUCCESS;
    }
}
