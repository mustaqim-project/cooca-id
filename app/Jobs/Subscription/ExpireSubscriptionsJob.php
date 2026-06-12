<?php
declare(strict_types=1);

namespace App\Jobs\Subscription;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class ExpireSubscriptionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    public function handle(): void
    {
        $expiredSubscriptions = Subscription::where('status', 'active')
            ->where('expires_at', '<=', now())
            ->get();

        foreach ($expiredSubscriptions as $subscription) {
            try {
                $subscription->update(['status' => 'expired']);

                // Dispatch event for observers/listeners
                \App\Events\Subscription\SubscriptionExpired::dispatch($subscription);
            } catch (\Throwable $e) {
                report($e);
            }
        }
    }
}
