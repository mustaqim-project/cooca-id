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
    public function index(\Illuminate\Http\Request $request)
    {
        $transactions = \App\Models\Transaction::with(['customer', 'subscription', 'invoice', 'verifier'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($sub) use ($search) {
                    $sub->where('invoice_number', 'like', "%{$search}%")
                        ->orWhere('sender_name', 'like', "%{$search}%")
                        ->orWhereHas('customer', fn ($c) => $c->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
                });
            })
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('gateway'), function ($q) use ($request) {
                if ($request->gateway === 'manual') {
                    $q->where(fn ($sub) => $sub->where('payment_gateway', 'manual')->orWhere('payment_method', 'bank_transfer_manual'));
                } elseif ($request->gateway === 'midtrans') {
                    $q->where(fn ($sub) => $sub->where('payment_gateway', 'midtrans')->orWhere('payment_method', 'midtrans'));
                }
            })
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('admin.transactions.index', [
            'transactions' => $transactions,
        ]);
    }

    /**
     * Display the specified transaction.
     */
    public function show(string $id)
    {
        $transaction = \App\Models\Transaction::with(['customer', 'subscription.subscriptionPlan.product', 'invoice', 'verifier'])->find($id);

        if (!$transaction) {
            abort(404, 'Transaction not found');
        }

        return view('admin.transactions.show', [
            'transaction' => $transaction,
        ]);
    }

    /**
     * Verify and approve manual bank transfer payment.
     */
    public function verify(string $id)
    {
        $transaction = \App\Models\Transaction::find($id);

        if (!$transaction) {
            abort(404, 'Transaction not found');
        }

        if ($transaction->status === 'paid') {
            return back()->with('error', 'Transaksi ini sudah berstatus PAID.');
        }

        $adminId = auth('admin')->id() ?? auth()->id();
        $this->paymentService->verifyManualPayment($transaction, $adminId);

        return back()->with('success', "Transaksi #{$transaction->invoice_number} berhasil diverifikasi dan disetujui. Layanan pelanggan telah diaktifkan.");
    }

    /**
     * Reject manual bank transfer payment proof.
     */
    public function reject(\Illuminate\Http\Request $request, string $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ], [
            'rejection_reason.required' => 'Wajib memberikan alasan penolakan bukti bayar.',
        ]);

        $transaction = \App\Models\Transaction::find($id);

        if (!$transaction) {
            abort(404, 'Transaction not found');
        }

        $adminId = auth('admin')->id() ?? auth()->id();
        $this->paymentService->rejectManualPayment($transaction, $request->input('rejection_reason'), $adminId);

        return back()->with('success', "Bukti pembayaran untuk transaksi #{$transaction->invoice_number} telah ditolak.");
    }

    /**
     * Mark transaction as paid (manual override for api/legacy).
     */
    public function markAsPaid(string $id)
    {
        $transaction = $this->paymentService->findTransactionById($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $adminId = auth('admin')->id() ?? auth()->id();
        $this->paymentService->verifyManualPayment($transaction, $adminId);

        return response()->json([
            'message' => 'Transaction marked as paid',
            'data' => new TransactionResource($transaction->fresh()),
        ]);
    }

    /**
     * Refund the specified transaction.
     */
    public function refund(string $id, \Illuminate\Http\Request $request)
    {
        $transaction = $this->paymentService->findTransactionById($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $this->paymentService->refundTransaction($id, $request->input('reason'));

        return response()->json([
            'message' => 'Transaction refunded successfully',
            'data' => new TransactionResource($transaction->fresh()),
        ]);
    }
}
