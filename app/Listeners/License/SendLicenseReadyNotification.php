<?php
declare(strict_types=1);

namespace App\Listeners\License;

use App\Events\License\LicenseGenerated;
use App\Jobs\Notification\SendLicenseReadyJob;

final class SendLicenseReadyNotification
{
    public function handle(LicenseGenerated $event): void
    {
        if ($event->license->is_trial) {
            return;
        }

        SendLicenseReadyJob::dispatch(
            $event->license->customer,
            $event->license->license_code,
            $event->license->token_code,
            $event->license->domain,
            $event->license->product?->name ?? 'COOCA ERP',
        );
    }
}
