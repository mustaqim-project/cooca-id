<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

final class MidtransWebhookController extends Controller
{
    public function __construct(
        private readonly PaymentService $paymentService
    ) {}

    /**
     * Handle Midtrans payment webhook notifications.
     * Verifies SHA512 signature before processing.
     */
    public function midtrans(Request $request): JsonResponse
    {
        $payload = $request->all();
        
        // Verify Midtrans signature
        if (!$this->paymentService->verifyWebhookSignature($payload)) {
            Log::warning('Invalid Midtrans webhook signature', ['payload' => $payload]);
            
            return response()->json([
                'message' => 'Invalid signature',
            ], 401);
        }

        try {
            $orderId = $payload['order_id'] ?? null;
            $transactionStatus = $payload['transaction_status'] ?? null;
            $fraudStatus = $payload['fraud_status'] ?? null;

            // Process based on transaction status
            match ($transactionStatus) {
                'capture' => $this->processCapture($orderId, $fraudStatus),
                'settlement' => $this->processSettlement($orderId),
                'pending' => $this->processPending($orderId),
                'deny', 'expire', 'cancel' => $this->processFailed($orderId),
                'refund' => $this->processRefund($orderId),
                default => Log::info('Unhandled Midtrans status', [
                    'order_id' => $orderId,
                    'status' => $transactionStatus,
                ]),
            };

            return response()->json([
                'message' => 'Webhook processed successfully',
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans webhook processing failed', [
                'order_id' => $payload['order_id'] ?? null,
                'error' => $e->getMessage(),
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
