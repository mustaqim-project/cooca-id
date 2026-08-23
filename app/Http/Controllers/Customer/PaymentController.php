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
        $payments = \App\Models\Transaction::with(['invoice', 'subscription.subscriptionPlan.product', 'subscription.license'])
            ->where('customer_id', Auth::id())
            ->latest('created_at')
            ->paginate(15);

        return view('customer.payments.index', [
            'payments' => $payments
        ]);
    }

    public function show(string $payment)
    {
        $transaction = \App\Models\Transaction::with(['invoice', 'subscription.subscriptionPlan.product', 'subscription.license.domainRecord'])
            ->where('id', $payment)
            ->where('customer_id', Auth::id())
            ->firstOrFail();

        Gate::authorize('view', $transaction);

        // Check if transaction has expired (> 1 hour) and unpaid without proof
        if ($transaction->status === 'pending' && $transaction->created_at->lt(now()->subHours(1)) && empty($transaction->payment_proof)) {
            $this->expireTransactionRecord($transaction);
        }

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

        // Cek apakah transaksi sudah kedaluwarsa (> 1 jam)
        if ($transaction->status === 'pending' && $transaction->created_at->lt(now()->subHours(1)) && empty($transaction->payment_proof)) {
            $this->expireTransactionRecord($transaction);
            return redirect()->route('customer.products.index')
                ->with('error', 'Batas waktu pembayaran (1 jam) telah berakhir. Transaksi ini telah kedaluwarsa dan domain Anda telah dibebaskan. Silakan lakukan pemesanan ulang.');
        }

        if (in_array($transaction->status, ['failed', 'expire', 'cancel'], true)) {
            return redirect()->route('customer.products.index')
                ->with('error', 'Transaksi ini telah dibatalkan / kedaluwarsa. Silakan lakukan pemesanan ulang.');
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

            // Kirim notifikasi email ke agungmustaqim15@gmail.com dan cooca.idn@gmail.com
            try {
                \Illuminate\Support\Facades\Mail::to(['agungmustaqim15@gmail.com', 'cooca.idn@gmail.com'])
                    ->send(new \App\Mail\Admin\SubscriptionPaymentReceivedMail($transaction, 'Bukti Pembayaran Manual Diunggah (Menunggu Verifikasi)'));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('[PaymentController] Failed to send admin payment proof email: ' . $e->getMessage());
            }

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

    /**
     * Expire transaction record and release domain.
     */
    private function expireTransactionRecord(\App\Models\Transaction $transaction): void
    {
        \Illuminate\Support\Facades\DB::transaction(function () use ($transaction) {
            $transaction->update([
                'status' => 'failed',
                'failed_at' => now(),
                'rejection_reason' => 'Batas waktu pembayaran 1 jam telah berakhir (Expired).',
            ]);

            if ($transaction->invoice) {
                $transaction->invoice->update([
                    'status' => 'cancelled',
                ]);
            }

            $subscription = $transaction->subscription;
            if ($subscription) {
                $license = $subscription->license;
                if ($license && $license->status === \App\Models\License::STATUS_INACTIVE) {
                    $domainRecord = $license->domainRecord;
                    if ($domainRecord && $domainRecord->status === \App\Models\Domain::STATUS_PENDING) {
                        $domainRecord->delete();
                    }
                    $license->delete();
                }

                if (in_array($subscription->status, ['trial', 'unpaid', 'pending', 'inactive'], true)) {
                    $subscription->delete();
                }
            }
        });
    }
}

