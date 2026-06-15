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
            'order_id' => $this->transaction->order_id,
        ]);

        DB::transaction(function () use ($subscriptionService) {
            // Cek ulang status transaksi untuk mencegah race condition
            $this->transaction->refresh();
            
            if ($this->transaction->status !== 'paid') {
                Log::channel('payment')->warning('Skipping activation, transaction not paid', [
                    'transaction_id' => $this->transaction->id,
                    'current_status' => $this->transaction->status,
                ]);
                return;
            }

            // Cek apakah sudah ada subscription aktif untuk order ini
            $existingSubscription = Subscription::where('transaction_id', $this->transaction->id)
                ->first();

            if ($existingSubscription) {
                if ($existingSubscription->status === 'active') {
                    Log::channel('payment')->info('Subscription already active', [
                        'subscription_id' => $existingSubscription->id,
                    ]);
                    return;
                }
                
                // Update status jika sebelumnya failed/pending
                $existingSubscription->update([
                    'status' => 'active',
                    'activated_at' => now(),
                ]);
            } else {
                // Buat subscription baru
                $subscriptionService->createSubscriptionFromTransaction($this->transaction);
            }

            // Update ERP Request status jika terkait
            if ($this->transaction->erpRequest) {
                $this->transaction->erpRequest->update([
                    'status' => 'active', // atau status akhir yang sesuai
                    'approved_at' => now(),
                ]);
            }
        });

        Log::channel('payment')->info('Subscription activated successfully', [
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
