<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface VoucherRepositoryInterface extends RepositoryInterface
{
    /**
     * Find voucher by code.
     */
    public function findByCode(string $code): ?\App\Models\Voucher;

    /**
     * Get active vouchers.
     */
    public function getActiveVouchers(): Collection;

    /**
     * Get vouchers applicable to product.
     */
    public function getApplicableToProduct(string $productId): Collection;

    /**
     * Validate voucher for customer and amount.
     *
     * @return array{valid: bool, discount?: float, message?: string}
     */
    public function validateVoucher(string $code, string $customerId, float $amount, ?string $productId = null): array;

    /**
     * Calculate discount amount.
     */
    public function calculateDiscount(\App\Models\Voucher $voucher, float $amount): float;

    /**
     * Increment usage count.
     */
    public function incrementUsage(string $voucherId): bool;

    /**
     * Get vouchers by type.
     */
    public function getByType(string $type): Collection;

    /**
     * Check if customer has used voucher.
     */
    public function customerHasUsedVoucher(string $voucherId, string $customerId): bool;

    /**
     * Get usage count by customer.
     */
    public function getUsageCountByCustomer(string $voucherId, string $customerId): int;
}
