<?php
declare(strict_types=1);

namespace App\Jobs\Payment;

use App\Events\Affiliate\CommissionCalculated;
use App\Models\Transaction;
use App\Services\Affiliate\AffiliateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class ProcessCommissionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        private readonly Transaction $transaction,
    ) {}

    public function handle(AffiliateService $affiliateService): void
    {
        try {
            $affiliateService->processCommissions($this->transaction);
            
            CommissionCalculated::dispatch($this->transaction);
        } catch (\Throwable $e) {
            report($e);
            throw $e;
        }
    }
}
