<?php

namespace App\Jobs;

use App\Models\Transaction;
use App\Models\Subscription;
use App\Services\Subscription\SubscriptionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ActivateSubscriptionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Transaction $transaction;
    protected int $tries = 3;
    protected int $backoff = 60; // seconds

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function handle(SubscriptionService $subscriptionService): void
    {
        Log::channel('payment')->info('Activating subscription', [
            'transaction_id' => $this->transaction->id,
            'order_id'       => $this->transaction->order_id ?? null,
        ]);

        DB::transaction(function () use ($subscriptionService) {
            // Re-check transaction status to prevent race conditions
            $this->transaction->refresh();

            if ($this->transaction->status !== 'paid') {
                Log::channel('payment')->warning('Skipping activation, transaction not paid', [
                    'transaction_id' => $this->transaction->id,
                    'current_status' => $this->transaction->status,
                ]);
                return;
            }

            $subscription = $this->transaction->subscription;

            if (!$subscription) {
                Log::channel('payment')->error('No subscription found for transaction', [
                    'transaction_id' => $this->transaction->id,
                ]);
                return;
            }

            $transactionType  = $this->transaction->type ?? 'new';
            $durationMonths   = $subscription->subscriptionPlan?->duration_months ?? 1;

            if ($transactionType === 'renewal') {
                // Renewal: extend expiry date from current expires_at
                if ($subscription->status === 'active' || $subscription->status === 'expired') {
                    $subscriptionService->renewSubscription($subscription, $durationMonths);

                    Log::channel('payment')->info('Subscription renewed via payment', [
                        'subscription_id' => $subscription->id,
                        'months'          => $durationMonths,
                    ]);
                } else {
                    Log::channel('payment')->warning('Cannot renew subscription with status: ' . $subscription->status, [
                        'subscription_id' => $subscription->id,
                    ]);
                }
            } else {
                // New activation
                if ($subscription->status === 'active') {
                    Log::channel('payment')->info('Subscription already active', [
                        'subscription_id' => $subscription->id,
                    ]);
                    return;
                }

                $subscriptionService->activateSubscription($subscription, $durationMonths);

                // Update ERP Request status if related via License
                if ($subscription->license && $subscription->license->erpRequest) {
                    $subscription->license->erpRequest->update([
                        'status'      => 'active',
                        'approved_at' => now(),
                    ]);
                }

                Log::channel('payment')->info('Subscription activated via payment', [
                    'subscription_id' => $subscription->id,
                ]);
            }
        });

        Log::channel('payment')->info('ActivateSubscriptionJob completed', [
            'transaction_id' => $this->transaction->id,
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        Log::channel('payment')->error('Failed to activate subscription', [
            'transaction_id' => $this->transaction->id,
            'error' => $exception->getMessage(),
        ]);

        // Opsional: Kirim alert ke admin via Slack/Email
        // Notification::send(new AdminUser(), new SubscriptionActivationFailed($this->transaction, $exception));
    }
}
