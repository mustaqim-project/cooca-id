<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface TransactionRepositoryInterface extends RepositoryInterface
{
    /**
     * Find transaction by invoice number.
     */
    public function findByInvoiceNumber(string $invoiceNumber): ?\App\Models\Transaction;

    /**
     * Find transaction by Midtrans order ID.
     */
    public function findByMidtransOrderId(string $orderId): ?\App\Models\Transaction;

    /**
     * Get transactions by customer.
     */
    public function getByCustomer(string $customerId): Collection;

    /**
     * Get paid transactions.
     */
    public function getPaidTransactions(): Collection;

    /**
     * Get pending transactions.
     */
    public function getPendingTransactions(): Collection;

    /**
     * Get transactions with pagination and filters.
     *
     * @param array<string, mixed> $filters
     */
    public function paginateWithFilters(int $perPage = 15, array $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator;

    /**
     * Calculate total gross amount in date range.
     */
    public function getTotalGrossAmount(\DateTime $startDate, \DateTime $endDate): float;

    /**
     * Count transactions by status.
     */
    public function countByStatus(string $status): int;

    /**
     * Get recent transactions.
     */
    public function getRecent(int $limit = 10): Collection;
}
