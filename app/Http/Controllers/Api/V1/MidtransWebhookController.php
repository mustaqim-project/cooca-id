<?php declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Payment\PaymentService;
use App\Services\Payment\MidtransSignatureValidator;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

final class MidtransWebhookController extends Controller
{
    public function __construct(
        private readonly PaymentService $paymentService,
        private readonly MidtransSignatureValidator $signatureValidator
    ) {}

    /**
     * Handle Midtrans payment webhook notifications.
     * Verifies SHA512 signature before processing.
     * Implements idempotency to prevent duplicate processing.
     */
    public function midtrans(Request $request): JsonResponse
    {
        $rawPayload = $request->getContent();
        $payload = json_decode($rawPayload, true);
        $signature = $request->header('X-Signature-Key', '') ?: ($payload['signature_key'] ?? '');
        
        // Verify Midtrans signature using dedicated validator service
        if (!$this->signatureValidator->validate($payload, $signature)) {
            Log::channel('security')->warning('MidtransWebhookController: Invalid signature rejected', [
                'ip' => $request->ip(),
                'order_id' => $payload['order_id'] ?? 'unknown',
                'user_agent' => $request->userAgent(),
            ]);
            
            return response()->json([
                'message' => 'Invalid signature',
            ], 401);
        }

        try {
            $orderId = $payload['order_id'] ?? null;
            $transactionStatus = $payload['transaction_status'] ?? null;
            $fraudStatus = $payload['fraud_status'] ?? null;
            $transactionId = $payload['transaction_id'] ?? null;

            if (!$orderId) {
                Log::error('MidtransWebhookController: Missing order_id in payload', [
                    'payload' => $payload,
                ]);
                return response()->json(['message' => 'Missing order_id'], 400);
            }

            // Idempotency check: Prevent duplicate processing of same transaction status
            $existingTransaction = DB::table('midtrans_transactions')
                ->where('order_id', $orderId)
                ->where('transaction_status', $transactionStatus)
                ->first();

            if ($existingTransaction) {
                Log::info('MidtransWebhookController: Duplicate callback ignored (idempotency)', [
                    'order_id' => $orderId,
                    'transaction_status' => $transactionStatus,
                    'transaction_id' => $transactionId,
                ]);
                return response()->json([
                    'message' => 'Callback already processed',
                    'status' => 'ignored_duplicate'
                ], 200);
            }

            // Find the transaction in our system
            $transaction = Transaction::where('midtrans_order_id', $orderId)->first();
            
            if (!$transaction) {
                Log::error('MidtransWebhookController: Transaction not found', [
                    'order_id' => $orderId,
                ]);
                return response()->json(['message' => 'Transaction not found'], 404);
            }

            // Check if transaction already has final status (prevent re-processing)
            if (in_array($transaction->status, ['paid', 'refunded'])) {
                Log::info('MidtransWebhookController: Transaction already in final status', [
                    'order_id' => $orderId,
                    'current_status' => $transaction->status,
                ]);
                return response()->json([
                    'message' => 'Transaction already processed',
                    'status' => 'already_finalized'
                ], 200);
            }

            DB::beginTransaction();
            try {
                // Process based on transaction status
                match ($transactionStatus) {
                    'capture' => $this->processCapture($transaction, $fraudStatus, $transactionId, $payload),
                    'settlement' => $this->processSettlement($transaction, $transactionId, $payload),
                    'pending' => $this->processPending($transaction, $transactionId, $payload),
                    'deny', 'expire', 'cancel' => $this->processFailed($transaction, $transactionId, $payload),
                    'refund' => $this->processRefund($transaction, $transactionId, $payload),
                    default => Log::info('MidtransWebhookController: Unhandled status', [
                        'order_id' => $orderId,
                        'status' => $transactionStatus,
                    ]),
                };

                DB::commit();

                Log::info('MidtransWebhookController: Webhook processed successfully', [
                    'order_id' => $orderId,
                    'transaction_id' => $transaction->id->toString(),
                    'status' => $transactionStatus,
                ]);

                return response()->json([
                    'message' => 'Webhook processed successfully',
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('MidtransWebhookController: Processing failed', [
                'order_id' => $payload['order_id'] ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Webhook processing failed',
            ], 500);
        }
    }

    private function processCapture(Transaction $transaction, ?string $fraudStatus, ?string $midtransTransactionId, array $payload): void
    {
        if ($fraudStatus === 'accept') {
            $this->paymentService->markAsPaid($transaction, $midtransTransactionId, 'capture', $payload);
        } else {
            $this->paymentService->markAsFailed($transaction, $midtransTransactionId, 'capture', $payload, 'Fraud detection failed');
        }
    }

    private function processSettlement(Transaction $transaction, ?string $midtransTransactionId, array $payload): void
    {
        $this->paymentService->markAsPaid($transaction, $midtransTransactionId, 'settlement', $payload);
    }

    private function processPending(Transaction $transaction, ?string $midtransTransactionId, array $payload): void
    {
        $this->paymentService->markAsPending($transaction, $midtransTransactionId, 'pending', $payload);
    }

    private function processFailed(Transaction $transaction, ?string $midtransTransactionId, array $payload): void
    {
        $reason = match ($payload['transaction_status'] ?? null) {
            'deny' => 'Payment denied by bank or fraud detection',
            'expire' => 'Payment expired without completion',
            'cancel' => 'Payment cancelled by user or merchant',
            default => 'Payment failed',
        };
        
        $this->paymentService->markAsFailed($transaction, $midtransTransactionId, $payload['transaction_status'] ?? 'failed', $payload, $reason);
    }

    private function processRefund(Transaction $transaction, ?string $midtransTransactionId, array $payload): void
    {
        $this->paymentService->markAsRefunded($transaction, $midtransTransactionId, 'refund', $payload);
    }
}
