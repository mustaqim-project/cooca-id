<?php

declare(strict_types=1);

namespace App\Actions\License\GenerateLicense;

use App\Models\License;
use App\DTOs\License\LicenseData;
use App\Services\License\LicenseService;

final readonly class GenerateLicenseAction
{
    public function __construct(
        private LicenseService $licenseService,
    ) {}

    public function execute(LicenseData $data): License
    {
        return $this->licenseService->generateLicense($data);
    }
}
