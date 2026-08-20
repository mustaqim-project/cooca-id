<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class DirectPaymentController extends Controller
{
    public function __construct(
        private readonly PaymentService $paymentService
    ) {}

    /**
     * Show direct checkout page for a subscription renewal via signed URL.
     */
    public function checkout(Request $request, string $subscription)
    {
        if (!$request->hasValidSignature()) {
            abort(401, 'Link pembayaran tidak valid atau sudah kedaluwarsa.');
        }

        $subscriptionModel = Subscription::with(['subscriptionPlan.product', 'customer'])->findOrFail($subscription);
        $customer = $subscriptionModel->customer;
        $plan = $subscriptionModel->subscriptionPlan;
        
        $price = (float) ($plan?->price ?? 0);

        if ($price <= 0) {
            abort(400, 'Tidak ada harga yang tersedia untuk plan ini.');
        }

        $discountPercent = (float) ($plan?->discount_percent ?? 0);
        $discountAmount = round($price * ($discountPercent / 100), 2);
        $subtotal = round($price - $discountAmount, 2);
        $taxAmount = round($subtotal * 0.11, 2);
        $netAmount = round($subtotal + $taxAmount, 2);

        // Check if there is already a pending transaction
        $pendingTransaction = Transaction::where('subscription_id', $subscriptionModel->id)
            ->where('type', 'renewal')
            ->where('status', 'pending')
            ->latest()
            ->first();

        $snapData = null;

        // If there's no pending transaction, generate one automatically
        if (!$pendingTransaction) {
            try {
                $snapData = DB::transaction(function () use ($customer, $subscriptionModel, $price, $discountAmount, $subtotal, $taxAmount, $netAmount) {
                    $yearMonth = now()->format('Ym');
                    $lastTxn = Transaction::where('invoice_number', 'like', "INV/{$yearMonth}%")
                        ->orderBy('invoice_number', 'desc')
                        ->lockForUpdate()
                        ->first();
                    $lastNum = $lastTxn ? (int) substr($lastTxn->invoice_number, -5) : 0;
                    $invoiceNumber = "INV/{$yearMonth}/" . str_pad((string) ($lastNum + 1), 5, '0', STR_PAD_LEFT);

                    $transaction = Transaction::create([
                        'customer_id'      => $customer->id,
                        'subscription_id'  => $subscriptionModel->id,
                        'type'             => 'renewal',
                        'invoice_number'   => $invoiceNumber,
                        'gross_amount'     => $price,
                        'voucher_discount' => $discountAmount, // Only plan discount applied here automatically
                        'subtotal_amount'  => $subtotal,
                        'tax_amount'       => $taxAmount,
                        'voucher_id'       => null,
                        'net_amount'       => $netAmount,
                        'payment_method'   => 'midtrans',
                        'payment_gateway'  => 'midtrans',
                        'status'           => 'pending',
                    ]);

                    Invoice::create([
                        'transaction_id'  => $transaction->id,
                        'invoice_number'  => $invoiceNumber,
                        'customer_id'     => $customer->id,
                        'subtotal_amount' => $subtotal,
                        'tax_amount'      => $taxAmount,
                        'amount'          => $netAmount,
                        'status'          => 'issued',
                        'issued_at'       => now(),
                        'due_at'          => now()->addDays(3),
                    ]);

                    return $this->paymentService->createSnapTransaction($transaction);
                });
            } catch (\Exception $e) {
                // Return gracefully with error if we can't create transaction
                abort(500, 'Gagal membuat transaksi: ' . $e->getMessage());
            }
        }

        return view('web.direct-checkout', [
            'subscription' => $subscriptionModel,
            'plan' => $plan,
            'customer' => $customer,
            'price' => $price,
            'discountAmount' => $discountAmount,
            'netAmount' => $netAmount,
            'pendingTransaction' => $pendingTransaction,
            'snapToken' => $snapData['snap_token'] ?? null,
            'snapUrl' => $snapData['snap_url'] ?? null,
        ]);
    }
}
