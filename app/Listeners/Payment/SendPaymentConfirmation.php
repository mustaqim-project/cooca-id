<?php
declare(strict_types=1);

namespace App\Listeners\Payment;

use App\Events\Payment\PaymentPaid;
use App\Jobs\Notification\SendPaymentConfirmationJob;

final class SendPaymentConfirmation
{
    public function handle(PaymentPaid $event): void
    {
        SendPaymentConfirmationJob::dispatch($event->transaction);
    }
}
