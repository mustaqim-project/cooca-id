<?php
declare(strict_types=1);

namespace App\Listeners\User;

use App\Events\User\CustomerRegistered;
use App\Jobs\Notification\SendWelcomeMailJob;

final class SendWelcomeNotification
{
    public function handle(CustomerRegistered $event): void
    {
        SendWelcomeMailJob::dispatch($event->customer);
    }
}
