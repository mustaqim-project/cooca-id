<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Actions\Payment\ProcessPayment\ProcessPaymentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\ProcessPaymentRequest;
use App\Models\Invoice;
use App\Services\Payment\PaymentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

final class PaymentController extends Controller
{
    public function __construct(
        private readonly PaymentService $paymentService,
        private readonly ProcessPaymentAction $processPaymentAction
    ) {}

    public function index()
    {
        $payments = \App\Models\Transaction::where('customer_id', Auth::id())
            ->paginate(15);

        return view('customer.payments.index', [
            'payments' => $payments
        ]);
    }

    public function show(string $payment)
    {
        $transaction = \App\Models\Transaction::where('id', $payment)
            ->where('customer_id', Auth::id())
            ->first();

        if (!$transaction) {
            abort(404);
        }

        Gate::authorize('view', $transaction);

        return view('customer.payments.show', [
            'payment' => $transaction
        ]);
    }

    /**
     * Process payment for invoice/subscription/project.
     * Supports 2 payment types: Midtrans and Manual Bank Transfer with proof upload.
     */
    public function store(ProcessPaymentRequest $request)
    {
        $customer = Auth::user();
        $data = $request->validated();

        if (empty($data['invoice_id'])) {
            abort(400, 'Invalid payment request: invoice_id is required.');
        }

        $invoice = Invoice::with('transaction')
            ->where('id', $data['invoice_id'])
            ->where('customer_id', $customer->getKey())
            ->firstOrFail();

        $transaction = $invoice->transaction;
        if (!$transaction) {
            abort(400, 'Invoice tidak terkait dengan transaksi.');
        }

        if ($transaction->status === 'paid') {
            return back()->with('error', 'Invoice sudah dibayar.');
        }

        $paymentType = $data['payment_type'] ?? ($request->hasFile('payment_proof') ? 'manual_transfer' : 'midtrans');

        // TIPE 2: TRANSFER BANK MANUAL
        if (in_array($paymentType, ['manual_transfer', 'bank_transfer_manual', 'manual']) || $request->hasFile('payment_proof')) {
            if (!$request->hasFile('payment_proof')) {
                return back()->with('error', 'Wajib mengunggah bukti pembayaran untuk metode Transfer Bank Manual.');
            }

            $proofFile = $request->file('payment_proof');
            $proofPath = $proofFile->store('payment_proofs', 'public');

            $transaction->update([
                'payment_method' => 'bank_transfer_manual',
                'payment_gateway' => 'manual',
                'payment_proof' => $proofPath,
                'payment_proof_uploaded_at' => now(),
                'sender_name' => $data['sender_name'] ?? null,
                'payment_notes' => $data['payment_notes'] ?? null,
                'status' => 'pending',
                'rejection_reason' => null, // reset in case of re-upload
            ]);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Bukti pembayaran berhasil diunggah dan sedang dalam proses verifikasi tim kami.',
                    'redirect_url' => route('customer.invoices.show', $invoice->id),
                ]);
            }

            return redirect()->route('customer.invoices.show', $invoice->id)
                ->with('success', 'Bukti pembayaran berhasil diunggah! Pembayaran Anda akan segera diverifikasi oleh tim kami dalam 1x24 jam.');
        }

        // TIPE 1: PAYMENT GATEWAY MIDTRANS
        $transaction->update([
            'payment_method' => 'midtrans',
            'payment_gateway' => 'midtrans',
        ]);

        $paymentData = $this->processPaymentAction->execute($transaction);
        $paymentUrl = $paymentData['snap_url'] ?? null;

        if (!$paymentUrl) {
            return back()->with('error', 'Gagal memproses pembayaran ke Midtrans. Silakan coba lagi atau gunakan Transfer Bank Manual.');
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Payment initiated successfully',
                'payment_url' => $paymentUrl,
            ]);
        }

        return redirect()->to($paymentUrl);
    }

    /**
     * Handle Midtrans redirect/callback.
     */
    public function callback(string $orderId)
    {
        $transaction = $this->paymentService->findTransactionByOrderId($orderId);

        if (!$transaction) {
            abort(404, 'Transaction not found');
        }

        return redirect()->route('customer.invoices.index');
    }

    /**
     * Display payment success page.
     */
    public function success()
    {
        return view('customer.payments.success');
    }

    /**
     * Display payment pending page.
     */
    public function pending()
    {
        return view('customer.payments.pending');
    }

    /**
     * Display payment failed page.
     */
    public function failed()
    {
        return view('customer.payments.failed');
    }
}
