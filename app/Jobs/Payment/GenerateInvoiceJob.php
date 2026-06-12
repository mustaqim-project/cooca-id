<?php
declare(strict_types=1);

namespace App\Jobs\Payment;

use App\Models\Transaction;
use App\Services\Invoice\InvoiceService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class GenerateInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        private readonly Transaction $transaction,
    ) {}

    public function handle(InvoiceService $invoiceService): void
    {
        try {
            $invoiceService->generateFromTransaction($this->transaction);
        } catch (\Throwable $e) {
            report($e);
            throw $e;
        }
    }
}
