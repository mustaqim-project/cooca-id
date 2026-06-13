<?php declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Invoice;
use Ramsey\Uuid\UuidInterface;
use Illuminate\Support\Collection;

interface InvoiceRepositoryInterface
{
    public function findById(UuidInterface|string $id): ?Invoice;
    public function findByTransactionId(UuidInterface|string $transactionId): ?Invoice;
    public function findByCustomerId(UuidInterface|string $customerId): Collection;
    public function create(array $data): Invoice;
    public function updateStatus(UuidInterface|string $id, string $status): Invoice;
    public function generateInvoiceNumber(): string;
}
