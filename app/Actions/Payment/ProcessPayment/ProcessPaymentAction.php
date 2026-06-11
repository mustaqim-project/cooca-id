<?php

declare(strict_types=1);

namespace App\Actions\Payment\ProcessPayment;

use App\Models\Transaction;
use App\Services\Payment\PaymentService;

final readonly class ProcessPaymentAction
{
    public function __construct(
        private PaymentService $paymentService,
    ) {}

    /**
     * Create Midtrans Snap transaction for payment
     */
    public function execute(Transaction $transaction): array
    {
        return $this->paymentService->createSnapTransaction($transaction);
    }
}
