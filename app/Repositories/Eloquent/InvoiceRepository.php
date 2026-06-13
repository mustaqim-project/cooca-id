<?php declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Invoice;
use App\Repositories\Contracts\InvoiceRepositoryInterface;
use Ramsey\Uuid\UuidInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class InvoiceRepository implements InvoiceRepositoryInterface
{
    public function findById(UuidInterface|string $id): ?Invoice
    {
        return Invoice::find($id instanceof UuidInterface ? $id->toString() : $id);
    }

    public function findByTransactionId(UuidInterface|string $transactionId): ?Invoice
    {
        return Invoice::where('transaction_id', $transactionId instanceof UuidInterface ? $transactionId->toString() : $transactionId)
            ->first();
    }

    public function findByCustomerId(UuidInterface|string $customerId): Collection
    {
        return Invoice::where('customer_id', $customerId instanceof UuidInterface ? $customerId->toString() : $customerId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function create(array $data): Invoice
    {
        return Invoice::create($data);
    }

    public function updateStatus(UuidInterface|string $id, string $status): Invoice
    {
        $invoice = $this->findById($id);

        if (!$invoice) {
            throw new \RuntimeException("Invoice not found: {$id}");
        }

        $invoice->update(['status' => $status]);

        return $invoice->fresh();
    }

    public function generateInvoiceNumber(): string
    {
        $yearMonth = now()->format('Ym');
        $prefix = "INV/{$yearMonth}/";

        $lastInvoice = Invoice::where('invoice_number', 'like', "INV/{$yearMonth}%")
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_number, -5);
            $nextNumber = str_pad((string) ($lastNumber + 1), 5, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '00001';
        }

        return $prefix . $nextNumber;
    }
}
