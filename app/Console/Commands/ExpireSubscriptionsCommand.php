<?php declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Services\License\LicenseService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

final class ExpireSubscriptionsCommand extends Command
{
    protected $signature = 'subscriptions:expire';
    protected $description = 'Mark all expired subscriptions as expired and suspend associated licenses';

    public function __construct(
        private readonly LicenseService $licenseService,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $now = now();
        
        $expiredSubscriptions = Subscription::where('status', 'active')
            ->where('ends_at', '<', $now)
            ->get();

        if ($expiredSubscriptions->isEmpty()) {
            $this->info('No subscriptions to expire.');
            return self::SUCCESS;
        }

        $count = 0;

        foreach ($expiredSubscriptions as $subscription) {
            try {
                $subscription->update(['status' => 'expired']);

                // Suspend associated license
                $license = $subscription->license;
                if ($license && $license->isActive()) {
                    $this->licenseService->expireLicense($license);
                }

                $count++;

                Log::info('Subscription expired', [
                    'subscription_id' => $subscription->id,
                    'customer_id' => $subscription->customer_id->toString(),
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to expire subscription', [
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Expired {$count} subscription(s).");

        return self::SUCCESS;
    }
}
