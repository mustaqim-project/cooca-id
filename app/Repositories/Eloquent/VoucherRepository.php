<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Voucher;
use App\Repositories\Contracts\VoucherRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

final class VoucherRepository extends BaseRepository implements VoucherRepositoryInterface
{
    public function __construct(Voucher $model)
    {
        parent::__construct($model);
    }

    public function findByCode(string $code): ?Voucher
    {
        return $this->model
            ->where('code', $code)
            ->where('is_active', true)
            ->first();
    }

    public function getActiveVouchers(): Collection
    {
        return $this->model
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('valid_until')
                      ->orWhere('valid_until', '>=', Carbon::now());
            })
            ->where(function ($query) {
                $query->whereNull('max_usage')
                      ->orWhereRaw('used_count < max_usage');
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function validateVoucher(string $code, float $purchaseAmount, string $customerId): array
    {
        $voucher = $this->findByCode($code);

        if (!$voucher) {
            return [
                'valid' => false,
                'message' => 'Voucher code not found or inactive',
            ];
        }

        // Check validity period
        if ($voucher->valid_from && $voucher->valid_from->isFuture()) {
            return [
                'valid' => false,
                'message' => 'Voucher is not yet valid',
            ];
        }

        if ($voucher->valid_until && $voucher->valid_until->isPast()) {
            return [
                'valid' => false,
                'message' => 'Voucher has expired',
            ];
        }

        // Check usage limits
        if ($voucher->max_usage !== null && $voucher->used_count >= $voucher->max_usage) {
            return [
                'valid' => false,
                'message' => 'Voucher usage limit reached',
            ];
        }

        // Check minimum purchase
        if ($purchaseAmount < $voucher->min_purchase) {
            return [
                'valid' => false,
                'message' => 'Minimum purchase amount not met (minimum: ' . number_format($voucher->min_purchase, 2) . ')',
            ];
        }

        // Check per-user limit
        if ($voucher->per_user_limit !== null) {
            $usageCount = $voucher->usages()->where('customer_id', $customerId)->count();
            if ($usageCount >= $voucher->per_user_limit) {
                return [
                    'valid' => false,
                    'message' => 'You have reached the usage limit for this voucher',
                ];
            }
        }

        // Check applicable products
        if ($voucher->applicable_products !== null) {
            // This should be validated against the cart/products being purchased
            // For now, we assume it's valid if the array exists
        }

        // Calculate discount
        $discount = $this->calculateDiscount($voucher, $purchaseAmount);

        return [
            'valid' => true,
            'message' => 'Voucher is valid',
            'voucher_id' => $voucher->id,
            'type' => $voucher->type,
            'value' => $voucher->value,
            'discount_amount' => $discount,
        ];
    }

    public function calculateDiscount(Voucher $voucher, float $purchaseAmount): float
    {
        if ($voucher->type === 'percent') {
            $discount = ($purchaseAmount * $voucher->value) / 100;
            
            // Apply max discount cap if set
            if ($voucher->max_discount !== null && $discount > $voucher->max_discount) {
                $discount = $voucher->max_discount;
            }
            
            return $discount;
        }

        // Nominal discount
        $discount = $voucher->value;
        
        // Cannot exceed purchase amount
        if ($discount > $purchaseAmount) {
            $discount = $purchaseAmount;
        }
        
        return $discount;
    }

    public function incrementUsage(string $voucherId): bool
    {
        $voucher = $this->model->find($voucherId);
        
        if (!$voucher) {
            return false;
        }

        $voucher->increment('used_count');

        return true;
    }

    public function decrementUsage(string $voucherId): bool
    {
        $voucher = $this->model->find($voucherId);
        
        if (!$voucher || $voucher->used_count <= 0) {
            return false;
        }

        $voucher->decrement('used_count');

        return true;
    }
}
