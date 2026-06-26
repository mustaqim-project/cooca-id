<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Actions\Payment\ProcessPayment\ProcessPaymentAction;
use App\DTOs\TransactionData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\ProcessPaymentRequest;
use App\Services\Payment\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;


final class PaymentController extends Controller
{
    public function __construct(
        private readonly PaymentService $paymentService,
        private readonly ProcessPaymentAction $processPaymentAction
    ) {}

    public function index(): Illuminate\View\View
    {
        $payments = \App\Models\Transaction::where('customer_id', Auth::guard('customer')->id())
            ->paginate(15);
            
        return view('customer.payments.index', [
            'payments' => $payments
        ]);
    }

    public function show(string $payment): Illuminate\View\View
    {
        $transaction = \App\Models\Transaction::where('id', $payment)
            ->where('customer_id', Auth::guard('customer')->id())
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
    public function store(ProcessPaymentRequest $request): JsonResponse
    {
        $customer = Auth::guard('customer')->user();
        $data = $request->validated();

        $transactionData = TransactionData::from([
            'customer_id' => $customer->getKey(),
            'subscription_id' => $data['subscription_id'],
            'gross_amount' => $data['gross_amount'],
            'voucher_discount' => $data['voucher_discount'] ?? 0,
            'voucher_id' => $data['voucher_id'] ?? null,
            'payment_method' => $data['payment_method'] ?? 'bank_transfer',
        ]);

        $paymentUrl = ($this->processPaymentAction)($transactionData);

        return response()->json([
            'message' => 'Payment initiated successfully',
            'payment_url' => $paymentUrl,
        ]);
    }

    /**
     * Handle Midtrans redirect/callback.
     */
    public function callback(string $orderId): RedirectResponse
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
    public function success(): Illuminate\View\View
    {
        return view('customer.payments.success');
    }

    /**
     * Display payment pending page.
     */
    public function pending(): Illuminate\View\View
    {
        return view('customer.payments.pending');
    }

    /**
     * Display payment failed page.
     */
    public function failed(): Illuminate\View\View
    {
        return view('customer.payments.failed');
    }
}
