<?php

declare(strict_types=1);

namespace App\Jobs\Finance;

use App\Models\Transaction;
use App\Services\Finance\AccountingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AutoJournalPaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [10, 30, 60]; // Retry after 10s, 30s, 60s

    public function __construct(
        public readonly Transaction $transaction
    ) {}

    public function handle(AccountingService $accountingService): void
    {
        try {
            $accountingService->autoJournalMidtransPayment($this->transaction);
        } catch (\Exception $e) {
            Log::error('AutoJournalPaymentJob failed', [
                'transaction_id' => $this->transaction->id,
                'error' => $e->getMessage()
            ]);
            throw $e; // Trigger retry
        }
    }
}
