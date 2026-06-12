<?php
declare(strict_types=1);

namespace App\Observers;

use App\Events\Payment\PaymentFailed;
use App\Events\Payment\PaymentPaid;
use App\Models\Transaction;

final class TransactionObserver
{
    public function updated(Transaction $transaction): void
    {
        if ($transaction->isDirty('status')) {
            if ($transaction->status === 'paid' && $transaction->wasChanged('status')) {
                event(new PaymentPaid($transaction));
            }

            if ($transaction->status === 'failed' && $transaction->wasChanged('status')) {
                event(new PaymentFailed($transaction));
            }
        }
    }
}
