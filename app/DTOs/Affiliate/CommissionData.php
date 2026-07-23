<?php

declare(strict_types=1);

namespace App\DTOs\Affiliate;

use App\Models\Setting;
use Ramsey\Uuid\UuidInterface;

final readonly class CommissionData
{
    public function __construct(
        public UuidInterface $affiliatorId,
        public UuidInterface $transactionId,
        public UuidInterface $customerId,
        public int $level,
        public float $grossAmount,
        public float $commissionPercent,
        public float $commissionAmount,
        public string $status = 'pending',
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            affiliatorId: $data['referred_by_id'],
            transactionId: $data['transaction_id'],
            customerId: $data['customer_id'],
            level: (int) ($data['level'] ?? 1),
            grossAmount: (float) ($data['gross_amount'] ?? 0),
            commissionPercent: (float) ($data['commission_percent'] ?? 0),
            commissionAmount: (float) ($data['commission_amount'] ?? 0),
            status: $data['status'] ?? 'pending',
        );
    }

    public function toArray(): array
    {
        return [
            'referred_by_id' => $this->affiliatorId->toString(),
            'transaction_id' => $this->transactionId->toString(),
            'customer_id' => $this->customerId->toString(),
            'level' => $this->level,
            'gross_amount' => $this->grossAmount,
            'commission_percent' => $this->commissionPercent,
            'commission_amount' => $this->commissionAmount,
            'status' => $this->status,
        ];
    }

    /**
     * Calculate commission amount based on gross amount and level
     * L1 and L2 percentages are configured in database settings.
     */
    public static function calculateCommission(float $grossAmount, int $level): float
    {
        $percent = match ($level) {
            1 => (float) Setting::get('affiliate.commission_rate_level_1', config('affiliate.commission_rate_level_1', 25)),
            2 => (float) Setting::get('affiliate.commission_rate_level_2', config('affiliate.commission_rate_level_2', 5)),
            default => 0.0,
        };

        return round($grossAmount * ($percent / 100), 2);
    }
}
