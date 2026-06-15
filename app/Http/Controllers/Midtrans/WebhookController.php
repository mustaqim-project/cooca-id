<?php

namespace App\Http\Controllers\Midtrans;

use App\Http\Controllers\Controller;
use App\Models\MidtransTransaction;
use App\Models\Payment;
use App\Models\Invoice;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Handle Midtrans webhook notifications
     */
    public function handle(Request $request)
    {
        // Verify signature key
        if (!$this->verifySignature($request)) {
            Log::channel('security')->warning('Midtrans webhook: Invalid signature', [
                'ip'      => $request->ip(),
                'payload' => $request->only(['order_id', 'transaction_status']),
            ]);
            return response()->json(['message' => 'Invalid signature'], 401);
        }

        $payload = $request->all();
        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        if (!$orderId) {
            Log::channel('payment')->error('Midtrans webhook: Missing order_id', ['payload' => $payload]);
            return response()->json(['message' => 'Missing order_id'], 400);
        }

        // Check for duplicate callback protection
        $existingTransaction = MidtransTransaction::where('order_id', $orderId)
            ->where('transaction_status', $transactionStatus)
            ->latest()
            ->first();

        if ($existingTransaction) {
            Log::info('Midtrans webhook: Duplicate callback ignored', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus
            ]);
            return response()->json(['message' => 'Duplicate callback ignored'], 200);
        }

        DB::beginTransaction();
        try {
            // Log transaction
            $transaction = MidtransTransaction::create([
                'order_id' => $orderId,
                'transaction_id' => $payload['transaction_id'] ?? null,
                'gross_amount' => $payload['gross_amount'] ?? 0,
                'currency' => $payload['currency'] ?? 'IDR',
                'payment_type' => $payload['payment_type'] ?? null,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'status_code' => $payload['status_code'] ?? null,
                'status_message' => $payload['status_message'] ?? null,
                'signature_key' => $payload['signature_key'] ?? null,
                'transaction_time' => $payload['transaction_time'] ?? null,
                'settlement_time' => $payload['settlement_time'] ?? null,
                'expiry_time' => $payload['expiry_time'] ?? null,
                'channel_response_code' => $payload['channel_response_code'] ?? null,
                'channel_response_message' => $payload['channel_response_message'] ?? null,
                'bank' => $payload['bank'] ?? null,
                'va_numbers' => json_encode($payload['va_numbers'] ?? []),
                'bill_key' => $payload['bill_key'] ?? null,
                'biller_code' => $payload['biller_code'] ?? null,
                'pdf_url' => $payload['pdf_url'] ?? null,
                'finish_redirect_url' => $payload['finish_redirect_url'] ?? null,
                'metadata' => json_encode($payload),
            ]);

            // Find related payment
            $payment = Payment::where('reference_id', $orderId)->first();

            if ($payment) {
                $this->processPaymentStatus($payment, $transactionStatus, $fraudStatus, $transaction);
            } else {
                Log::channel('payment')->warning('Midtrans webhook: Payment not found', [
                    'order_id' => $orderId
                ]);
            }

            DB::commit();

            Log::channel('payment')->info('Midtrans webhook processed successfully', [
                'order_id'           => $orderId,
                'transaction_status' => $transactionStatus,
            ]);

            return response()->json(['message' => 'Webhook processed successfully'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('payment')->error('Midtrans webhook processing failed', [
                'order_id' => $orderId,
                'error'    => $e->getMessage(),
                'trace'    => $e->getTraceAsString(),
            ]);

            return response()->json(['message' => 'Processing failed'], 500);
        }
    }

    /**
    /**
     * Verify Midtrans signature key with enhanced security
     */
    private function verifySignature(Request $request): bool
    {
        $payload = $request->all();
        $providedSignature = $payload['signature_key'] ?? null;

        if (!$providedSignature) {
            Log::warning('Midtrans webhook: Missing signature_key', [
                'order_id' => $payload['order_id'] ?? 'unknown',
                'ip' => $request->ip()
            ]);
            return false;
        }

        // Required fields for signature calculation
        $requiredFields = ['order_id', 'status_code', 'gross_amount'];
        foreach ($requiredFields as $field) {
            if (!isset($payload[$field])) {
                Log::warning('Midtrans webhook: Missing required field for signature', [
                    'order_id' => $payload['order_id'] ?? 'unknown',
                    'missing_field' => $field
                ]);
                return false;
            }
        }

        $serverKey = config('services.midtrans.server_key');
        
        if (empty($serverKey)) {
            Log::error('Midtrans webhook: Server key not configured');
            return false;
        }

        $inputString = $payload['order_id'] .
                      $payload['status_code'] .
                      $payload['gross_amount'] .
                      $serverKey;

        $calculatedSignature = hash('sha512', $inputString);

        $isValid = hash_equals($calculatedSignature, $providedSignature);

        if (!$isValid) {
            Log::channel('security')->critical('Midtrans webhook: Invalid signature detected - POTENTIAL FRAUD', [
                'order_id'            => $payload['order_id'],
                'provided_signature'  => substr($providedSignature, 0, 16) . '...',
                'expected_signature'  => substr($calculatedSignature, 0, 16) . '...',
                'ip_address'          => $request->ip(),
                'user_agent'          => $request->userAgent(),
            ]);
        }

        return $isValid;
    }

    /**
     * Process payment status based on Midtrans response
     */
    private function processPaymentStatus(Payment $payment, string $transactionStatus, ?string $fraudStatus, MidtransTransaction $transaction)
    {
        $newStatus = match ($transactionStatus) {
            'capture' => ($fraudStatus === 'accept') ? 'Success' : 'Failed',
            'settlement' => 'Success',
            'pending' => 'Pending',
            'deny' => 'Failed',
            'expire' => 'Failed',
            'cancel' => 'Failed',
            'refund' => 'Refunded',
            default => 'Failed',
        };

        // Update payment status
        $payment->update([
            'status' => $newStatus,
            'midtrans_transaction_id' => $transaction->id,
            'paid_at' => in_array($newStatus, ['Success', 'Refunded']) ? now() : null,
        ]);

        // Update invoice status if payment is successful
        if ($newStatus === 'Success' && $payment->invoice) {
            $payment->invoice->update([
                'status' => 'Paid',
                'paid_at' => now(),
            ]);

            // Trigger subscription activation if this is a subscription payment
            if ($payment->subscription) {
                $payment->subscription->update([
                    'status' => 'Active',
                    'starts_at' => now(),
                ]);
            }

            // Queue notification
            $payment->invoice->user->notify(
                new \App\Notifications\PaymentSuccessNotification($payment)
            );
        } elseif ($newStatus === 'Failed' && $payment->invoice) {
            $payment->invoice->update(['status' => 'Failed']);
            
            // Queue notification
            $payment->invoice->user->notify(
                new \App\Notifications\PaymentFailedNotification($payment)
            );
        }

        // Create activity log
        \App\Models\ActivityLog::create([
            'user_id' => $payment->invoice?->customer_id ?? $payment->subscription?->customer_id,
            'user_type' => 'customer',
            'action' => 'payment_webhook_received',
            'module' => 'Payment',
            'description' => "Payment webhook received: {$transactionStatus}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'metadata' => [
                'payment_id' => $payment->id,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'new_status' => $newStatus,
            ],
        ]);
    }
}
