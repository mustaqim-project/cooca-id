<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Invoice;

$invoice = Invoice::latest()->first();
if (!$invoice) {
    echo "No invoices found in database.\n";
    exit(0);
}

/** @var \App\Services\Invoice\InvoiceService $service */
$service = app()->make(\App\Services\Invoice\InvoiceService::class);

try {
    $path = $service->generateInvoicePdf($invoice);
    echo "Generated PDF at: {$path}\n";
} catch (\Exception $e) {
    echo "Failed to generate PDF: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
