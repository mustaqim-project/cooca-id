<?php

declare(strict_types=1);

namespace App\Events\Provisioning;

use App\Models\ProvisioningJob;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProvisioningFailed
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly ProvisioningJob $provisioningJob,
        public readonly string $errorMessage,
    ) {}
}
