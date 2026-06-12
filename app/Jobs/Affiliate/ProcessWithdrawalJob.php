<?php
declare(strict_types=1);

namespace App\Jobs\Affiliate;

use App\Models\AffiliateWithdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class ProcessWithdrawalJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 120;

    public function __construct(
        private readonly AffiliateWithdrawal $withdrawal,
    ) {}

    public function handle(): void
    {
        try {
            if ($this->withdrawal->status !== 'approved') {
                return;
            }

            // Simulate payment processing via bank transfer or e-wallet
            // In production, integrate with Xendit/Midtrans Disbursement API
            
            $this->withdrawal->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            // Send notification to affiliator
            $this->withdrawal->affiliator->notify(
                new \App\Notifications\Affiliator\WithdrawalPaidNotification($this->withdrawal)
            );
        } catch (\Throwable $e) {
            report($e);
            
            $this->withdrawal->update([
                'status' => 'rejected',
                'rejection_reason' => 'Payment processing failed: ' . $e->getMessage(),
                'rejected_at' => now(),
            ]);
            
            throw $e;
        }
    }
}
