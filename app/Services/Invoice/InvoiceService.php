<?php declare(strict_types=1);

namespace App\Services\Invoice;

use App\Models\Transaction;
use App\Models\Invoice;
use App\Repositories\Contracts\InvoiceRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

final class InvoiceService
{
    public function __construct(
        private readonly InvoiceRepositoryInterface $invoiceRepository,
    ) {}

    public function generateFromTransaction(Transaction $transaction): Invoice
    {
        return \DB::transaction(function () use ($transaction) {
            $invoiceNumber = $this->invoiceRepository->generateInvoiceNumber();

            $invoice = $this->invoiceRepository->create([
                'invoice_number' => $invoiceNumber,
                'transaction_id' => $transaction->id,
                'customer_id' => $transaction->customer_id,
                'gross_amount' => $transaction->gross_amount,
                'net_amount' => $transaction->net_amount,
                'status' => 'pending',
                'due_date' => now()->addDays(7),
            ]);

            $pdfPath = $this->generateInvoicePdf($invoice);

            $invoice->update(['pdf_path' => $pdfPath]);

            return $invoice->fresh();
        });
    }

    public function generateInvoicePdf(Invoice $invoice): string
    {
        $yearMonth = $invoice->created_at->format('Y/m');
        $filename = "{$invoice->invoice_number}.pdf";
        $relativePath = "invoices/{$yearMonth}/{$filename}";
        $fullPath = storage_path("app/public/{$relativePath}");

        $pdf = Pdf::loadView('emails.invoice-pdf', [
            'invoice' => $invoice,
            'transaction' => $invoice->transaction,
            'customer' => $invoice->customer,
        ]);

        $directory = dirname($fullPath);

        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $pdf->save($fullPath);

        return "public/{$relativePath}";
    }

    public function getInvoiceForCustomer(string $customerId, string $invoiceId): Invoice
    {
        $invoice = $this->invoiceRepository->findById($invoiceId);

        if (!$invoice) {
            throw new \RuntimeException('Invoice not found');
        }

        if ($invoice->customer_id->toString() !== $customerId) {
            throw new \RuntimeException('Unauthorized access to invoice');
        }

        return $invoice;
    }
}
