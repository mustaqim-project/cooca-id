<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Actions\Payment\ProcessPayment\ProcessPaymentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\ProcessPaymentRequest;
use App\Models\Invoice;
use App\Services\Payment\PaymentService;
use Illuminate\Support\Facades\Auth;


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

        return view('customer.payments.show', [
            'payment' => $transaction
        ]);
    }

    /**
     * Process payment for subscription.
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

        $paymentData = $this->processPaymentAction->execute($transaction);
        $paymentUrl = $paymentData['snap_url'] ?? null;

        if (!$paymentUrl) {
            return back()->with('error', 'Gagal memproses pembayaran. Silakan coba lagi.');
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
