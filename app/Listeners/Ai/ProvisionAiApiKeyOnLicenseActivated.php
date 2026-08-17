<?php

declare(strict_types=1);

namespace App\Listeners\Ai;

use App\Events\License\LicenseGenerated;
use App\Models\Product;
use App\Services\Ai\AiApiKeyService;
use App\Services\Ai\AiQuotaService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class ProvisionAiApiKeyOnLicenseActivated implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(
        private readonly AiApiKeyService $keyService,
        private readonly AiQuotaService $quotaService,
    ) {}

    public function handle(LicenseGenerated $event): void
    {
        $license = $event->license;

        $aiProduct = Product::where('slug', 'ai-assistant')->first();
        
        if (!$aiProduct || $license->product_id !== $aiProduct->id) {
            return; // Only process AI Module licenses
        }

        // 1. Issue an API Key
        $this->keyService->issueForLicense($license, 'Default Key', $license->domain_id);

        // 2. Start initial Quota Cycle
        $this->quotaService->startNewCycle($license);
    }
}
