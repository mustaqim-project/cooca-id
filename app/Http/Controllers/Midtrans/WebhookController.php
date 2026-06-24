<?php

namespace App\Http\Controllers\Midtrans;

use App\Http\Controllers\Controller;
use App\Models\MidtransTransaction;
use App\Models\Setting;
use App\Models\Transaction;
use App\Services\Payment\PaymentService;
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

        // Check for duplicate callback protection using idempotency
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
            // Find the transaction by midtrans_order_id
            $transaction = Transaction::where('midtrans_order_id', $orderId)->first();

            if (!$transaction) {
                Log::channel('payment')->error('Midtrans webhook: Transaction not found', [
                    'order_id' => $orderId
                ]);
                return response()->json(['message' => 'Transaction not found'], 404);
            }

            // Determine the appropriate status and call PaymentService
            $status = match (true) {
                $transactionStatus === 'capture' && $fraudStatus === 'accept' => 'paid',
                $transactionStatus === 'settlement' => 'paid',
                $transactionStatus === 'pending' => 'pending',
                $transactionStatus === 'deny' => 'failed',
                $transactionStatus === 'expire' => 'failed',
                $transactionStatus === 'cancel' => 'failed',
                $transactionStatus === 'refund' => 'refunded',
                default => 'failed',
            };

            // Create MidtransTransaction log first for idempotency
            $midtransTransaction = MidtransTransaction::create([
                'transaction_id' => $transaction->id,
                'order_id' => $orderId,
                'gross_amount' => $payload['gross_amount'] ?? $transaction->net_amount,
                'currency' => $payload['currency'] ?? 'IDR',
                'payment_type' => $payload['payment_type'] ?? null,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'status_code' => $payload['status_code'] ?? null,
                'raw_response' => $payload,
                'transaction_time' => isset($payload['transaction_time']) ? now()->parse($payload['transaction_time']) : null,
                'settlement_time' => isset($payload['settlement_time']) ? now()->parse($payload['settlement_time']) : null,
            ]);

            // Use PaymentService to handle status updates
            match ($status) {
                'paid' => $this->paymentService->markAsPaid(
                    $transaction,
                    $payload['transaction_id'] ?? null,
                    $transactionStatus,
                    $payload
                ),
                'pending' => $this->paymentService->markAsPending(
                    $transaction,
                    $payload['transaction_id'] ?? null,
                    $transactionStatus,
                    $payload
                ),
                'refunded' => $this->paymentService->markAsRefunded(
                    $transaction,
                    $payload['transaction_id'] ?? null,
                    $transactionStatus,
                    $payload
                ),
                default => $this->paymentService->markAsFailed(
                    $transaction,
                    $payload['transaction_id'] ?? null,
                    $transactionStatus,
                    $payload,
                    $payload['status_message'] ?? 'Payment failed'
                ),
            };

            DB::commit();

            Log::channel('payment')->info('Midtrans webhook processed successfully', [
                'order_id'           => $orderId,
                'transaction_status' => $transactionStatus,
                'new_status'         => $status,
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
     * Verify Midtrans signature key with enhanced security
     */
    private function verifySignature(Request $request): bool
    {
        $payload = $request->all();
        $providedSignature = $payload['signature_key'] ?? $request->header('X-Signature-Key');

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

        $serverKey = Setting::get('payment.midtrans_server_key', config('services.midtrans.server_key'));
        
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
}
