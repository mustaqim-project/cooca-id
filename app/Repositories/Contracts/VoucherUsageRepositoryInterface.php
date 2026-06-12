<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\VoucherUsage;
use Ramsey\Uuid\UuidInterface;
use Illuminate\Database\Eloquent\Collection;

interface VoucherUsageRepositoryInterface extends RepositoryInterface
{
    /**
     * Find voucher usage by customer and voucher.
     */
    public function findByCustomerAndVoucher(UuidInterface $customerId, UuidInterface $voucherId): ?VoucherUsage;

    /**
     * Count voucher usage by customer and voucher.
     */
    public function countByCustomerAndVoucher(UuidInterface $customerId, UuidInterface $voucherId): int;

    /**
     * Check if customer has used voucher.
     */
    public function existsByCustomerAndVoucher(UuidInterface $customerId, UuidInterface $voucherId): bool;

    /**
     * Get all voucher usage by customer.
     */
    public function findByCustomerId(UuidInterface $customerId): Collection;

    /**
     * Get voucher usage by transaction.
     */
    public function findByTransactionId(UuidInterface $transactionId): ?VoucherUsage;
}
