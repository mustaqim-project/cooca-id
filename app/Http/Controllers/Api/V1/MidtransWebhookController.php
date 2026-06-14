<?php declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Payment\PaymentService;
use App\Services\Payment\MidtransSignatureValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

final class MidtransWebhookController extends Controller
{
    public function __construct(
        private readonly PaymentService $paymentService,
        private readonly MidtransSignatureValidator $signatureValidator
    ) {}

    /**
     * Handle Midtrans payment webhook notifications.
     * Verifies SHA512 signature before processing.
     */
    public function midtrans(Request $request): JsonResponse
    {
        $rawPayload = $request->getContent();
        $payload = json_decode($rawPayload, true);
        $signature = $request->header('X-Signature-Key', '') ?: ($payload['signature_key'] ?? '');
        
        // Verify Midtrans signature using dedicated validator service
        if (!$this->signatureValidator->validate($payload, $signature)) {
            Log::warning('MidtransWebhookController: Invalid signature rejected', [
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

            if (!$orderId) {
                Log::error('MidtransWebhookController: Missing order_id in payload', [
                    'payload' => $payload,
                ]);
                return response()->json(['message' => 'Missing order_id'], 400);
            }

            // Process based on transaction status
            match ($transactionStatus) {
                'capture' => $this->processCapture($orderId, $fraudStatus),
                'settlement' => $this->processSettlement($orderId),
                'pending' => $this->processPending($orderId),
                'deny', 'expire', 'cancel' => $this->processFailed($orderId),
                'refund' => $this->processRefund($orderId),
                default => Log::info('MidtransWebhookController: Unhandled status', [
                    'order_id' => $orderId,
                    'status' => $transactionStatus,
                ]),
            };

            Log::info('MidtransWebhookController: Webhook processed successfully', [
                'order_id' => $orderId,
                'status' => $transactionStatus,
            ]);

            return response()->json([
                'message' => 'Webhook processed successfully',
            ]);
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

    private function processCapture(string $orderId, ?string $fraudStatus): void
    {
        if ($fraudStatus === 'accept') {
            $this->paymentService->markAsPaid($orderId);
        } else {
            $this->paymentService->markAsFailed($orderId);
        }
    }

    private function processSettlement(string $orderId): void
    {
        $this->paymentService->markAsPaid($orderId);
    }

    private function processPending(string $orderId): void
    {
        $this->paymentService->markAsPending($orderId);
    }

    private function processFailed(string $orderId): void
    {
        $this->paymentService->markAsFailed($orderId);
    }

    private function processRefund(string $orderId): void
    {
        $this->paymentService->markAsRefunded($orderId);
    }
}
