<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\TransactionResource;
use App\Services\Payment\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

final class TransactionController extends Controller
{
    public function __construct(
        private readonly PaymentService $paymentService
    ) {}

    /**
     * Display listing of transactions.
     */
    public function index(): View
    {
        $transactions = $this->paymentService->getTransactionsPaginated(15);

        return view('admin.transactions.index', [
            'transactions' => TransactionResource::collection($transactions),
        ]);
    }

    /**
     * Display the specified transaction.
     */
    public function show(string $id): View
    {
        $transaction = $this->paymentService->findTransactionById($id);

        if (!$transaction) {
            abort(404, 'Transaction not found');
        }

        return view('admin.transactions.show', [
            'transaction' => new TransactionResource($transaction),
        ]);
    }

    /**
     * Mark transaction as paid (manual override).
     */
    public function markAsPaid(string $id): JsonResponse
    {
        $transaction = $this->paymentService->findTransactionById($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $this->paymentService->markAsPaid($id);

        return response()->json([
            'message' => 'Transaction marked as paid',
            'data' => new TransactionResource($transaction->fresh()),
        ]);
    }

    /**
     * Refund the specified transaction.
     */
    public function refund(string $id, array $data): JsonResponse
    {
        $transaction = $this->paymentService->findTransactionById($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $this->paymentService->refundTransaction($id, $data['reason'] ?? null);

        return response()->json([
            'message' => 'Transaction refunded successfully',
            'data' => new TransactionResource($transaction->fresh()),
        ]);
    }
}
