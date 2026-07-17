<?php

declare(strict_types=1);

namespace App\Jobs\Subscription;

use App\Models\Subscription;
use App\Services\Subscription\SubscriptionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Job untuk memproses auto-renewal subscription
 * Dipanggil daily oleh scheduler untuk cek subscription yang expire hari ini
 * dan proses renewal jika ada invoice payment yang berhasil
 */
class ProcessAutoRenewalJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly SubscriptionService $subscriptionService
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $processedCount = 0;
        $renewedCount = 0;
        $expiredCount = 0;
        $now = now();

        // Cari subscription active yang expire hari ini atau sudah expired
        $subscriptionsExpiringToday = Subscription::where('status', 'active')
            ->whereNotNull('expires_at')
            ->whereDate('expires_at', '<=', $now->copy()->addDay())
            ->with(['license', 'customer', 'subscriptionPlan'])
            ->get();

        DB::transaction(function () use ($subscriptionsExpiringToday, &$processedCount, &$renewedCount, &$expiredCount) {
            foreach ($subscriptionsExpiringToday as $subscription) {
                try {
                    $processedCount++;

                    // Cek apakah ada invoice payment yang successful untuk renewal
                    $hasPaidInvoice = $this->hasPaidRenewalInvoice($subscription);

                    if ($hasPaidInvoice) {
                        // Auto-renew dengan invoice payment
                        $this->processRenewalWithPayment($subscription);
                        $renewedCount++;
                    } else {
                        // Tidak ada payment, suspend atau expire
                        $this->processExpiration($subscription);
                        $expiredCount++;
                    }
                } catch (\Exception $e) {
                    Log::error("Failed to process subscription {$subscription->id}: " . $e->getMessage());
                    // Continue processing other subscriptions
                }
            }
        });

        // Log summary
        Log::info("Auto-renewal job completed", [
            'processed_count' => $processedCount,
            'renewed_count' => $renewedCount,
            'expired_count' => $expiredCount,
            'processed_at' => $now->toIso8601String(),
        ]);

        // Create activity log
        \App\Models\ActivityLog::create([
            'causer_id' => null,
            'causer_type' => 'system',
            'action' => 'auto_renewal_processed',
            'module' => 'subscription',
            'description' => "Processed {$processedCount} subscriptions: {$renewedCount} renewed, {$expiredCount} expired",
            'ip_address' => 'system',
            'user_agent' => 'Scheduler',
            'metadata' => [
                'processed_count' => $processedCount,
                'renewed_count' => $renewedCount,
                'expired_count' => $expiredCount,
                'processed_at' => $now->toIso8601String(),
            ],
        ]);
    }

    /**
     * Check if subscription has paid renewal invoice
     */
    private function hasPaidRenewalInvoice(Subscription $subscription): bool
    {
        // Cek invoice yang terkait dengan subscription ini
        // Invoice status = paid dan belum digunakan untuk renewal
        return \App\Models\Invoice::where('subscription_id', $subscription->id)
            ->where('status', 'paid')
            ->where('invoice_type', 'renewal')
            ->whereNull('applied_at') // Belum diaplikasikan ke renewal
            ->exists();
    }

    /**
     * Process renewal with successful payment
     */
    private function processRenewalWithPayment(Subscription $subscription): void
    {
        // Ambil invoice yang paid
        $invoice = \App\Models\Invoice::where('subscription_id', $subscription->id)
            ->where('status', 'paid')
            ->where('invoice_type', 'renewal')
            ->whereNull('applied_at')
            ->first();

        if (!$invoice) {
            throw new \RuntimeException("No paid invoice found for subscription {$subscription->id}");
        }

        // Mark invoice as applied
        $invoice->update(['applied_at' => now()]);

        // Renew subscription via service
        $this->subscriptionService->autoRenewSubscription($subscription, $invoice);

        Log::info("Subscription auto-renewed with payment", [
            'subscription_id' => $subscription->id,
            'invoice_id' => $invoice->id,
        ]);
    }

    /**
     * Process subscription expiration (no payment received)
     */
    private function processExpiration(Subscription $subscription): void
    {
        // Grace period 7 hari sebelum suspend/expire
        $graceDays = 7;
        
        if ($subscription->expires_at && 
            now()->diffInDays($subscription->expires_at) <= $graceDays) {
            // Masih dalam grace period, suspend dulu
            $subscription->update(['status' => 'suspended']);
            
            Log::info("Subscription suspended (grace period)", [
                'subscription_id' => $subscription->id,
                'days_in_grace' => now()->diffInDays($subscription->expires_at),
            ]);
        } else {
            // Sudah melewati grace period, expire
            $subscription->update(['status' => 'expired']);
            
            Log::info("Subscription expired (no payment)", [
                'subscription_id' => $subscription->id,
            ]);
        }

        // Suspend associated license
        if ($subscription->license) {
            $subscription->license->update(['status' => 'suspended']);
        }
    }
}
