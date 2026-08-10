<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\Invoice;

$subscription = Subscription::with('subscriptionPlan.product', 'customer', 'license')
    ->where('status', 'trial')
    ->orWhere('status', 'active')
    ->latest()
    ->first();

if (!$subscription) {
    echo "No suitable subscription found for automation.\n";
    exit(0);
}

$customer = $subscription->customer;
$plan = $subscription->subscriptionPlan;
$price = (float) ($plan->price ?? 0);

if ($price <= 0) {
    echo "Plan price is zero — cannot automate paid flow.\n";
    exit(0);
}

$yearMonth = now()->format('Ym');
$lastTxn = Transaction::where('invoice_number', 'like', "INV/{$yearMonth}%")->orderBy('invoice_number', 'desc')->first();
$lastNum = $lastTxn ? (int) substr($lastTxn->invoice_number, -5) : 0;
$invoiceNumber = "INV/{$yearMonth}/" . str_pad((string)($lastNum + 1), 5, '0', STR_PAD_LEFT);

$transaction = Transaction::create([
    'customer_id' => $customer->getKey(),
    'subscription_id' => $subscription->id,
    'type' => 'renewal',
    'invoice_number' => $invoiceNumber,
    'gross_amount' => $price,
    'voucher_discount' => 0,
    'net_amount' => $price,
    'payment_method' => 'midtrans',
    'payment_gateway' => 'midtrans',
    'status' => 'pending',
]);

$invoice = Invoice::create([
    'transaction_id' => $transaction->id,
    'invoice_number' => $invoiceNumber,
    'customer_id' => $customer->getKey(),
    'amount' => $price,
    'status' => 'issued',
    'issued_at' => now(),
    'due_at' => now()->addDays(3),
]);

echo "Created transaction {$transaction->id} and invoice {$invoice->invoice_number}\n";

// Generate PDF
$invoiceService = app()->make(\App\Services\Invoice\InvoiceService::class);
$pdfPath = $invoiceService->generateInvoicePdf($invoice);
$invoice->update(['pdf_path' => $pdfPath]);

echo "Generated PDF: {$pdfPath}\n";

// Simulate webhook markAsPaid
$paymentService = app()->make(\App\Services\Payment\PaymentService::class);
$payload = [
    'order_id' => $invoiceNumber,
    'transaction_id' => 'SIM-' . strtoupper(uniqid()),
    'gross_amount' => $transaction->net_amount,
    'payment_type' => 'credit_card',
    'transaction_status' => 'settlement',
    'status_code' => '200',
    'fraud_status' => 'accept',
    'transaction_time' => now()->toIso8601String(),
];

try {
    $paymentService->markAsPaid($transaction, $payload['transaction_id'], 'settlement', $payload);
    echo "Payment simulated: transaction marked as paid.\n";
} catch (\Exception $e) {
    echo "Failed to simulate payment: " . $e->getMessage() . "\n";
    exit(1);
}

$invoice->refresh();
$transaction->refresh();
$subscription->refresh();

echo "Invoice status: {$invoice->status}\n";
echo "Transaction status: {$transaction->status}\n";
if ($subscription->license) {
    echo "License status: {$subscription->license->status}\n";
}

echo "Subscription status: {$subscription->status}\n";
