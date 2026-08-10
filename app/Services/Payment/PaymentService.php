<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\Models\Transaction;
use App\Models\ApiIntegration;
use App\Models\MidtransTransaction;
use App\Models\Invoice;
use App\Models\Subscription;
use App\Jobs\Notification\SendPaymentConfirmationJob;
use App\Jobs\Payment\ProcessCommissionJob;
use App\Jobs\Finance\AutoJournalPaymentJob;
use App\Jobs\ActivateSubscriptionJob;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

final class PaymentService
{
    private readonly string $midtransServerKey;
    private readonly bool $midtransSandbox;

    public function __construct()
    {
        // Load Midtrans config from ApiIntegration first, fallback to config/services.php
        $integration = ApiIntegration::where('provider', 'midtrans')
            ->where('is_active', true)
            ->first();

        if ($integration && !empty($integration->config)) {
            $this->midtransServerKey = (string) ($integration->config['server_key'] ?? config('services.midtrans.server_key', ''));
            $sandbox = $integration->config['sandbox'] ?? false;
            $this->midtransSandbox = filter_var($sandbox, FILTER_VALIDATE_BOOLEAN);
        } else {
            $this->midtransServerKey = (string) config('services.midtrans.server_key', '');
            $this->midtransSandbox = (bool) config('services.midtrans.sandbox', true);
        }
    }

    /**
     * Create Midtrans Snap transaction
     */
    public function createSnapTransaction(Transaction $transaction): array
    {
        // Ensure order_id only contains characters allowed by Midtrans
        $rawOrderId = (string) $transaction->invoice_number;
        // Midtrans allows alphanumeric and - _ ~ . ; replace other chars with '-'
        $sanitizedOrderId = preg_replace('/[^A-Za-z0-9\-_.~]/', '-', $rawOrderId);

        $payload = [
            'transaction_details' => [
                'order_id' => $sanitizedOrderId,
                'gross_amount' => (int) round((float) $transaction->net_amount),
            ],
            'customer_details' => [
                'email' => $transaction->customer->email,
                'first_name' => $transaction->customer->name,
            ],
            'callbacks' => [
                'finish' => route('customer.payments.success'),
                'error' => route('customer.payments.failed'),
            ],
        ];

        $response = Http::withBasicAuth($this->midtransServerKey, '')
            ->post($this->getMidtransSnapUrl() . '/transactions', $payload);

        if ($response->failed()) {
            Log::error('Midtrans Snap creation failed', [
                'transaction_id' => (string) $transaction->id,
                'response' => $response->body(),
            ]);

            throw new \RuntimeException('Failed to create payment transaction');
        }

        $data = $response->json();

        // If Midtrans did not return an order_id, don't fail — use our sanitized order id
        if (!isset($data['order_id'])) {
            Log::warning('Midtrans Snap creation returned no order_id; using local order id', [
                'transaction_id' => (string) $transaction->id,
                'provided_order_id' => $sanitizedOrderId,
                'response' => $response->body(),
            ]);

            $data['order_id'] = $sanitizedOrderId;
        }

        // Update transaction with Midtrans info
        DB::table('transactions')
            ->where('id', (string) $transaction->id)
            ->update([
                'midtrans_order_id' => $data['order_id'],
                'midtrans_transaction_id' => $data['transaction_id'] ?? null,
            ]);

        return [
            'snap_token' => $data['token'] ?? null,
            'snap_url' => $data['redirect_url'] ?? null,
        ];
    }

    /**
     * Mark transaction as paid - called from webhook controller
     * Handles: subscription activation, invoice update, commission calculation, notifications
     */
    public function markAsPaid(
        Transaction $transaction,
        ?string $midtransTransactionId,
        string $midtransStatus,
        array $payload
    ): void {
        DB::beginTransaction();
        try {
            // Update transaction status
            $transaction->update([
                'status' => 'paid',
                'midtrans_transaction_id' => $midtransTransactionId,
                'midtrans_status' => $midtransStatus,
                'paid_at' => Carbon::now(),
            ]);

            // Log to midtrans_transactions table for idempotency tracking
            MidtransTransaction::create([
                'transaction_id' => (string) $transaction->id,
                'order_id' => $payload['order_id'] ?? $transaction->midtrans_order_id,
                'gross_amount' => $payload['gross_amount'] ?? $transaction->gross_amount,
                'currency' => $payload['currency'] ?? 'IDR',
                'payment_type' => $payload['payment_type'] ?? null,
                'transaction_status' => $midtransStatus,
                'fraud_status' => $payload['fraud_status'] ?? null,
                'status_code' => $payload['status_code'] ?? null,
                'raw_response' => $payload,
                'transaction_time' => isset($payload['transaction_time']) ? Carbon::parse($payload['transaction_time']) : null,
                'settlement_time' => isset($payload['settlement_time']) ? Carbon::parse($payload['settlement_time']) : null,
            ]);

            // Update related invoice
            $invoice = Invoice::where('transaction_id', (string) $transaction->id)->first();
            if ($invoice) {
                $invoice->update([
                    'status' => 'paid',
                    'paid_at' => Carbon::now(),
                ]);
            }

            // Activate subscription via queued job for reliability
            if ($transaction->subscription) {
                ActivateSubscriptionJob::dispatch($transaction);
            }

            // Record voucher usage if applicable
            if ($transaction->voucher_id) {
                $voucher = \App\Models\Voucher::find($transaction->voucher_id);
                $customer = \App\Models\Customer::find($transaction->customer_id);
                if ($voucher && $customer) {
                    $uuid = \Ramsey\Uuid\Uuid::fromString((string) $transaction->id);
                    app(\App\Services\Voucher\VoucherService::class)->recordUsage(
                        $voucher,
                        $customer,
                        $uuid,
                        (float) $transaction->voucher_discount
                    );
                }
            }

            DB::commit();

            // Queue notifications (async to avoid blocking webhook response)
            SendPaymentConfirmationJob::dispatch($transaction);

            // Queue commission calculation for affiliate payouts
            ProcessCommissionJob::dispatch($transaction);

            // Queue auto-journaling to Finance/ERP
            AutoJournalPaymentJob::dispatch($transaction);

            Log::info('PaymentService: Transaction marked as paid', [
                'transaction_id' => (string) $transaction->id,
                'invoice_number' => $transaction->invoice_number,
                'amount' => $transaction->net_amount,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PaymentService: Failed to mark transaction as paid', [
                'transaction_id' => (string) $transaction->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Mark transaction as pending
     */
    public function markAsPending(
        Transaction $transaction,
        ?string $midtransTransactionId,
        string $midtransStatus,
        array $payload
    ): void {
        DB::beginTransaction();
        try {
            $transaction->update([
                'status' => 'pending',
                'midtrans_transaction_id' => $midtransTransactionId,
                'midtrans_status' => $midtransStatus,
            ]);

            // Log to midtrans_transactions table
            MidtransTransaction::create([
                'transaction_id' => (string) $transaction->id,
                'order_id' => $payload['order_id'] ?? $transaction->midtrans_order_id,
                'gross_amount' => $payload['gross_amount'] ?? $transaction->gross_amount,
                'currency' => $payload['currency'] ?? 'IDR',
                'payment_type' => $payload['payment_type'] ?? null,
                'transaction_status' => $midtransStatus,
                'fraud_status' => $payload['fraud_status'] ?? null,
                'status_code' => $payload['status_code'] ?? null,
                'raw_response' => $payload,
            ]);

            DB::commit();

            Log::info('PaymentService: Transaction marked as pending', [
                'transaction_id' => (string) $transaction->id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Mark transaction as failed
     */
    public function markAsFailed(
        Transaction $transaction,
        ?string $midtransTransactionId,
        string $midtransStatus,
        array $payload,
        string $failureReason = 'Payment failed'
    ): void {
        DB::beginTransaction();
        try {
            $transaction->update([
                'status' => 'failed',
                'midtrans_transaction_id' => $midtransTransactionId,
                'midtrans_status' => $midtransStatus,
                'failed_at' => Carbon::now(),
            ]);

            // Log to midtrans_transactions table
            MidtransTransaction::create([
                'transaction_id' => (string) $transaction->id,
                'order_id' => $payload['order_id'] ?? $transaction->midtrans_order_id,
                'gross_amount' => $payload['gross_amount'] ?? $transaction->gross_amount,
                'currency' => $payload['currency'] ?? 'IDR',
                'payment_type' => $payload['payment_type'] ?? null,
                'transaction_status' => $midtransStatus,
                'fraud_status' => $payload['fraud_status'] ?? null,
                'status_code' => $payload['status_code'] ?? null,
                'raw_response' => $payload,
            ]);

            // Update related invoice
            $invoice = Invoice::where('transaction_id', (string) $transaction->id)->first();
            if ($invoice) {
                $invoice->update(['status' => 'failed']);
            }

            DB::commit();

            // Queue failure notification
            SendPaymentConfirmationJob::dispatch($transaction);

            Log::warning('PaymentService: Transaction marked as failed', [
                'transaction_id' => (string) $transaction->id,
                'reason' => $failureReason,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Mark transaction as refunded
     */
    public function markAsRefunded(
        Transaction $transaction,
        ?string $midtransTransactionId,
        string $midtransStatus,
        array $payload
    ): void {
        DB::beginTransaction();
        try {
            $transaction->update([
                'status' => 'refunded',
                'midtrans_transaction_id' => $midtransTransactionId,
                'midtrans_status' => $midtransStatus,
                'refunded_at' => Carbon::now(),
            ]);

            // Log to midtrans_transactions table
            MidtransTransaction::create([
                'transaction_id' => (string) $transaction->id,
                'order_id' => $payload['order_id'] ?? $transaction->midtrans_order_id,
                'gross_amount' => $payload['gross_amount'] ?? $transaction->gross_amount,
                'currency' => $payload['currency'] ?? 'IDR',
                'payment_type' => $payload['payment_type'] ?? null,
                'transaction_status' => $midtransStatus,
                'fraud_status' => $payload['fraud_status'] ?? null,
                'status_code' => $payload['status_code'] ?? null,
                'raw_response' => $payload,
            ]);

            // Update related invoice
            $invoice = Invoice::where('transaction_id', (string) $transaction->id)->first();
            if ($invoice) {
                $invoice->update(['status' => 'refunded']);
            }

            // Deactivate subscription if exists
            if ($transaction->subscription) {
                $transaction->subscription->update([
                    'status' => 'cancelled',
                    'ends_at' => Carbon::now(),
                ]);
            }

            DB::commit();

            // Queue refund notification
            SendPaymentConfirmationJob::dispatch($transaction);

            Log::info('PaymentService: Transaction marked as refunded', [
                'transaction_id' => (string) $transaction->id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Verify Midtrans webhook signature
     */
    public function verifyWebhookSignature(string $rawPayload, string $signature): bool
    {
        $sha512Hash = hash('sha512', $rawPayload . $this->midtransServerKey);

        return hash_equals($sha512Hash, $signature);
    }

    /**
     * Process Midtrans webhook notification
     * @deprecated Use markAsPaid/markAsFailed methods instead
     */
    public function processWebhook(array $notification): Transaction
    {
        $transaction = Transaction::where('midtrans_order_id', $notification['order_id'])->firstOrFail();

        $midtransStatus = $notification['transaction_status'];
        $fraudStatus = $notification['fraud_status'] ?? null;

        // Determine transaction status based on Midtrans status
        $status = match (true) {
            $midtransStatus === 'capture' && $fraudStatus === 'accept' => 'paid',
            $midtransStatus === 'settlement' => 'paid',
            $midtransStatus === 'pending' => 'pending',
            $midtransStatus === 'deny' => 'failed',
            $midtransStatus === 'expire' => 'failed',
            $midtransStatus === 'cancel' => 'failed',
            $midtransStatus === 'refund' => 'refunded',
            default => 'pending',
        };

        $updateData = [
            'midtrans_status' => $midtransStatus,
            'status' => $status,
        ];

        if ($status === 'paid') {
            $updateData['paid_at'] = now();
        } elseif ($status === 'failed') {
            $updateData['failed_at'] = now();
        }

        DB::table('transactions')
            ->where('id', (string) $transaction->id)
            ->update($updateData);

        return $transaction->fresh();
    }

    /**
     * Get transaction status from Midtrans API
     */
    public function getTransactionStatus(string $orderId): array
    {
        $response = Http::withBasicAuth($this->midtransServerKey, '')
            ->get($this->getMidtransApiUrl() . "/{$orderId}/status");

        if ($response->failed()) {
            throw new \RuntimeException('Failed to get transaction status');
        }

        return $response->json();
    }

    /**
     * Refund a transaction
     */
    public function refund(Transaction $transaction, float $amount): array
    {
        $payload = [
            'amount' => (int) round($amount),
            'reason' => 'Refund request',
        ];

        $response = Http::withBasicAuth($this->midtransServerKey, '')
            ->post($this->getMidtransApiUrl() . "/{$transaction->midtrans_order_id}/refund", $payload);

        if ($response->failed()) {
            Log::error('Midtrans refund failed', [
                'transaction_id' => (string) $transaction->id,
                'response' => $response->body(),
            ]);

            throw new \RuntimeException('Failed to process refund');
        }

        DB::table('transactions')
            ->where('id', (string) $transaction->id)
            ->update([
                'status' => 'refunded',
                'refunded_at' => now(),
            ]);

        return $response->json();
    }

    /**
     * Get Midtrans Snap API URL
     */
    private function getMidtransSnapUrl(): string
    {
        return $this->midtransSandbox
            ? 'https://app.sandbox.midtrans.com/snap/v1'
            : 'https://app.midtrans.com/snap/v1';
    }

    /**
     * Get Midtrans API URL
     */
    private function getMidtransApiUrl(): string
    {
        return $this->midtransSandbox
            ? 'https://api.sandbox.midtrans.com/v2'
            : 'https://api.midtrans.com/v2';
    }

    /**
     * Paginate transactions
     */
    public function getTransactionsPaginated(int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Transaction::with(['customer', 'subscription.subscriptionPlan.product'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Find transaction by ID
     */
    public function findTransactionById(string $id): ?Transaction
    {
        return Transaction::with(['customer', 'subscription.subscriptionPlan.product', 'invoice'])->find($id);
    }

    /**
     * Find transaction by Midtrans order ID
     */
    public function findTransactionByOrderId(string $orderId): ?Transaction
    {
        return Transaction::with(['customer', 'subscription.subscriptionPlan.product', 'invoice'])
            ->where('midtrans_order_id', $orderId)
            ->first();
    }

    /**
     * Refund a transaction wrapper
     */
    public function refundTransaction(string $id, ?string $reason): array
    {
        $transaction = $this->findTransactionById($id);
        if (!$transaction) {
            throw new \RuntimeException('Transaction not found');
        }

        // This simulates a full refund based on net amount
        return $this->refund($transaction, (float) $transaction->net_amount);
    }
}
