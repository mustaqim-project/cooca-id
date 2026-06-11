<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\Models\Transaction;
use App\DTOs\Payment\TransactionData;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class PaymentService
{
    private readonly string $midtransServerKey;
    private readonly bool $midtransSandbox;

    public function __construct()
    {
        $this->midtransServerKey = config('services.midtrans.server_key', '');
        $this->midtransSandbox = config('services.midtrans.sandbox', true);
    }

    /**
     * Create Midtrans Snap transaction
     */
    public function createSnapTransaction(Transaction $transaction): array
    {
        $payload = [
            'transaction_details' => [
                'order_id' => $transaction->invoice_number,
                'gross_amount' => (int) round($transaction->net_amount),
            ],
            'customer_details' => [
                'email' => $transaction->customer->email,
                'first_name' => $transaction->customer->name,
            ],
            'callbacks' => [
                'finish' => route('customer.payment.success'),
                'error' => route('customer.payment.error'),
            ],
        ];

        $response = Http::withBasicAuth($this->midtransServerKey, '')
            ->post($this->getMidtransSnapUrl() . '/transactions', $payload);

        if ($response->failed()) {
            Log::error('Midtrans Snap creation failed', [
                'transaction_id' => $transaction->id->toString(),
                'response' => $response->body(),
            ]);

            throw new \RuntimeException('Failed to create payment transaction');
        }

        $data = $response->json();

        // Update transaction with Midtrans info
        DB::table('transactions')
            ->where('id', $transaction->id->toString())
            ->update([
                'midtrans_order_id' => $data['order_id'],
                'midtrans_transaction_id' => $data['transaction_id'] ?? null,
            ]);

        return [
            'snap_token' => $data['token'],
            'snap_url' => $data['redirect_url'] ?? null,
        ];
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
            ->where('id', $transaction->id->toString())
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
                'transaction_id' => $transaction->id->toString(),
                'response' => $response->body(),
            ]);

            throw new \RuntimeException('Failed to process refund');
        }

        DB::table('transactions')
            ->where('id', $transaction->id->toString())
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
}
