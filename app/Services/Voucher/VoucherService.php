<?php

declare(strict_types=1);

namespace App\Services\Voucher;

use App\Models\Voucher;
use App\Models\Customer;
use App\DTOs\Voucher\VoucherData;
use App\Repositories\Contracts\VoucherRepositoryInterface;
use App\Repositories\Contracts\VoucherUsageRepositoryInterface;

final class VoucherService
{
    public function __construct(
        private readonly VoucherRepositoryInterface $voucherRepository,
        private readonly VoucherUsageRepositoryInterface $usageRepository,
    ) {}

    /**
     * Validate and apply voucher to a purchase
     * CRITICAL: Voucher discount does NOT affect affiliate commission calculation
     */
    public function applyVoucher(string $code, float $purchaseAmount, Customer $customer, ?array $productIds = null): ?VoucherData
    {
        $voucher = $this->voucherRepository->findByCode($code);

        if (!$voucher || !$voucher->is_active) {
            return null;
        }

        $voucherData = VoucherData::fromArray([
            'code' => $voucher->code,
            'name' => $voucher->name,
            'description' => $voucher->description,
            'type' => $voucher->type,
            'value' => $voucher->value,
            'min_purchase' => $voucher->min_purchase,
            'max_discount' => $voucher->max_discount,
            'max_usage' => $voucher->max_usage,
            'used_count' => $voucher->used_count,
            'per_user_limit' => $voucher->per_user_limit,
            'valid_from' => $voucher->valid_from,
            'valid_until' => $voucher->valid_until,
            'is_active' => $voucher->is_active,
            'applicable_products' => $voucher->applicable_products,
        ]);

        // Check validity period
        $now = now();
        if ($now < $voucher->valid_from || $now > $voucher->valid_until) {
            return null;
        }

        // Check usage limit
        if ($voucher->max_usage !== null && $voucher->used_count >= $voucher->max_usage) {
            return null;
        }

        // Check minimum purchase
        if ($purchaseAmount < $voucher->min_purchase) {
            return null;
        }

        // Check product applicability
        if ($productIds !== null && $voucher->applicable_products !== null) {
            $applicableProducts = json_decode($voucher->applicable_products, true);
            if (!empty($applicableProducts)) {
                $hasApplicableProduct = false;
                foreach ($productIds as $productId) {
                    if (in_array($productId, $applicableProducts)) {
                        $hasApplicableProduct = true;
                        break;
                    }
                }
                if (!$hasApplicableProduct) {
                    return null;
                }
            }
        }

        // Check per-user limit
        if ($voucher->per_user_limit !== null) {
            $userUsageCount = $this->usageRepository->countByCustomerAndVoucher($customer->id, $voucher->id);
            if ($userUsageCount >= $voucher->per_user_limit) {
                return null;
            }
        }

        // Check if customer already used this voucher
        if ($this->usageRepository->existsByCustomerAndVoucher($customer->id, $voucher->id)) {
            return null;
        }

        return $voucherData;
    }

    /**
     * Calculate discount amount for a voucher
     */
    public function calculateDiscount(VoucherData $voucherData, float $purchaseAmount): float
    {
        return $voucherData->calculateDiscount($purchaseAmount);
    }

    /**
     * Record voucher usage
     */
    public function recordUsage(
        Voucher $voucher,
        Customer $customer,
        \Ramsey\Uuid\UuidInterface $transactionId,
        float $discountAmount,
    ): void {
        $this->usageRepository->create([
            'voucher_id' => $voucher->id,
            'customer_id' => $customer->id,
            'transaction_id' => $transactionId,
            'discount_amount' => $discountAmount,
            'used_at' => now(),
        ]);

        // Increment voucher usage count
        $this->voucherRepository->incrementUsage($voucher->id);
    }

    /**
     * Get customer's voucher usage history
     */
    public function getCustomerUsageHistory(Customer $customer): array
    {
        return $this->usageRepository->findByCustomerId($customer->id);
    }

    /**
     * Check if customer can use a specific voucher
     */
    public function canCustomerUseVoucher(Customer $customer, Voucher $voucher): bool
    {
        // Check if already used
        if ($this->usageRepository->existsByCustomerAndVoucher($customer->id, $voucher->id)) {
            return false;
        }

        // Check per-user limit
        if ($voucher->per_user_limit !== null) {
            $usageCount = $this->usageRepository->countByCustomerAndVoucher($customer->id, $voucher->id);
            if ($usageCount >= $voucher->per_user_limit) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get available vouchers for a customer
     */
    public function getAvailableVouchers(Customer $customer, float $purchaseAmount, ?array $productIds = null): array
    {
        $vouchers = $this->voucherRepository->getActiveVouchers();
        $availableVouchers = [];

        foreach ($vouchers as $voucher) {
            $voucherData = $this->applyVoucher(
                $voucher->code,
                $purchaseAmount,
                $customer,
                $productIds
            );

            if ($voucherData) {
                $availableVouchers[] = [
                    'voucher' => $voucherData,
                    'discount_amount' => $voucherData->calculateDiscount($purchaseAmount),
                ];
            }
        }

        // Sort by discount amount (highest first)
        usort($availableVouchers, fn($a, $b) => 
            $b['discount_amount'] <=> $a['discount_amount']
        );

        return $availableVouchers;
    }

    /**
     * Paginate vouchers
     */
    public function paginate(int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->voucherRepository->paginate($perPage);
    }

    /**
     * Create a new voucher
     */
    public function create(array $data): Voucher
    {
        return $this->voucherRepository->create($data);
    }

    /**
     * Find voucher by ID
     */
    public function findById(string $id): ?Voucher
    {
        return $this->voucherRepository->find($id);
    }

    /**
     * Update an existing voucher
     */
    public function update(string $id, array $data): bool
    {
        return $this->voucherRepository->update($id, $data);
    }

    /**
     * Activate a voucher
     */
    public function activate(string $id): bool
    {
        return $this->voucherRepository->update($id, ['is_active' => true]);
    }

    /**
     * Deactivate a voucher
     */
    public function deactivate(string $id): bool
    {
        return $this->voucherRepository->update($id, ['is_active' => false]);
    }
}
