<?php

declare(strict_types=1);

namespace App\DTOs\Voucher;

use Ramsey\Uuid\UuidInterface;

final readonly class VoucherData
{
    public function __construct(
        public string $code,
        public string $name,
        public ?string $description = null,
        public string $type = 'percent', // percent or nominal
        public float $value = 0.0,
        public float $minPurchase = 0.0,
        public ?float $maxDiscount = null,
        public ?int $maxUsage = null,
        public int $usedCount = 0,
        public ?int $perUserLimit = null,
        public \DateTimeInterface $validFrom,
        public \DateTimeInterface $validUntil,
        public bool $isActive = true,
        public ?array $applicableProducts = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            code: $data['code'],
            name: $data['name'],
            description: $data['description'] ?? null,
            type: $data['type'] ?? 'percent',
            value: (float) ($data['value'] ?? 0),
            minPurchase: (float) ($data['min_purchase'] ?? 0),
            maxDiscount: isset($data['max_discount']) ? (float) $data['max_discount'] : null,
            maxUsage: isset($data['max_usage']) ? (int) $data['max_usage'] : null,
            usedCount: (int) ($data['used_count'] ?? 0),
            perUserLimit: isset($data['per_user_limit']) ? (int) $data['per_user_limit'] : null,
            validFrom: new \DateTimeImmutable($data['valid_from']),
            validUntil: new \DateTimeImmutable($data['valid_until']),
            isActive: (bool) ($data['is_active'] ?? true),
            applicableProducts: $data['applicable_products'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'value' => $this->value,
            'min_purchase' => $this->minPurchase,
            'max_discount' => $this->maxDiscount,
            'max_usage' => $this->maxUsage,
            'used_count' => $this->usedCount,
            'per_user_limit' => $this->perUserLimit,
            'valid_from' => $this->validFrom->format('Y-m-d H:i:s'),
            'valid_until' => $this->validUntil->format('Y-m-d H:i:s'),
            'is_active' => $this->isActive,
            'applicable_products' => $this->applicableProducts,
        ];
    }

    /**
     * Calculate discount amount for a given purchase amount
     * CRITICAL: This discount does NOT affect affiliate commission calculation
     */
    public function calculateDiscount(float $purchaseAmount): float
    {
        if (!$this->isActive) {
            return 0.0;
        }

        if ($purchaseAmount < $this->minPurchase) {
            return 0.0;
        }

        $now = new \DateTimeImmutable();
        if ($now < $this->validFrom || $now > $this->validUntil) {
            return 0.0;
        }

        if ($this->maxUsage !== null && $this->usedCount >= $this->maxUsage) {
            return 0.0;
        }

        $discount = match ($this->type) {
            'percent' => $purchaseAmount * ($this->value / 100),
            'nominal' => $this->value,
            default => 0.0,
        };

        // Apply max discount cap if set
        if ($this->maxDiscount !== null && $discount > $this->maxDiscount) {
            $discount = $this->maxDiscount;
        }

        // Discount cannot exceed purchase amount
        return min($discount, $purchaseAmount);
    }
}
