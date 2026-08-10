<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Transaction;

$txn = Transaction::where('payment_gateway', 'midtrans')
    ->where('status', 'pending')
    ->latest()
    ->first();

if (!$txn) {
    echo "No pending Midtrans transactions found.\n";
    exit(0);
}

/** @var \App\Services\Payment\PaymentService $svc */
$svc = app()->make(\App\Services\Payment\PaymentService::class);

$payload = [
    'order_id' => $txn->midtrans_order_id ?? $txn->invoice_number,
    'transaction_id' => 'SIMULATED-' . strtoupper(uniqid()),
    'gross_amount' => $txn->net_amount,
    'payment_type' => 'credit_card',
    'transaction_status' => 'settlement',
    'status_code' => '200',
    'fraud_status' => 'accept',
    'transaction_time' => now()->toIso8601String(),
];

try {
    $svc->markAsPaid($txn, $payload['transaction_id'], 'settlement', $payload);
    echo "Transaction {$txn->id} marked as paid (simulated).\n";
    $inv = \App\Models\Invoice::where('transaction_id', $txn->id)->first();
    if ($inv) {
        echo "Invoice {$inv->invoice_number} status: {$inv->status}\n";
    }
} catch (\Exception $e) {
    echo "Failed to mark transaction as paid: " . $e->getMessage() . "\n";
}
