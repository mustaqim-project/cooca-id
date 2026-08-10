<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\Invoice;
use Illuminate\Support\Str;

$subscription = Subscription::with('subscriptionPlan.product', 'customer')->where('status', 'trial')
    ->orWhere('status', 'active')
    ->latest()
    ->first();

if (!$subscription) {
    echo "No suitable subscription found.\n";
    exit(0);
}

$customer = $subscription->customer;
$plan = $subscription->subscriptionPlan;
$price = (float) ($plan->price ?? 0);

if ($price <= 0) {
    echo "Plan price is zero — skipping simulated checkout.\n";
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

Invoice::create([
    'transaction_id' => $transaction->id,
    'invoice_number' => $invoiceNumber,
    'customer_id' => $customer->getKey(),
    'amount' => $price,
    'status' => 'issued',
    'issued_at' => now(),
    'due_at' => now()->addDays(3),
]);

// Provide simulated snap details (no external call)
$snap = [
    'snap_token' => 'SIM-' . strtoupper(Str::random(12)),
    'snap_url' => null,
];

// Optionally update transaction with simulated midtrans_order_id
Transaction::where('id', $transaction->id)->update([
    'midtrans_order_id' => $invoiceNumber,
    'midtrans_transaction_id' => 'SIM-' . strtoupper(Str::random(8)),
]);

echo "Simulated checkout created:\n";
echo "Subscription: {$subscription->id}\n";
echo "Transaction: {$transaction->id}\n";
echo "Invoice: {$invoiceNumber}\n";
echo json_encode($snap, JSON_PRETTY_PRINT) . "\n";
