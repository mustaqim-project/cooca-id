<?php

declare(strict_types=1);

namespace App\DTOs\Subscription;

use Ramsey\Uuid\UuidInterface;

final readonly class SubscriptionData
{
    public function __construct(
        public UuidInterface $customerId,
        public ?UuidInterface $licenseId = null,
        public UuidInterface $subscriptionPlanId,
        public string $status = 'trial',
        public ?\DateTimeInterface $startedAt = null,
        public ?\DateTimeInterface $expiresAt = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            customerId: $data['customer_id'],
            licenseId: $data['license_id'] ?? null,
            subscriptionPlanId: $data['subscription_plan_id'],
            status: $data['status'] ?? 'trial',
            startedAt: isset($data['started_at']) ? new \DateTimeImmutable($data['started_at']) : null,
            expiresAt: isset($data['expires_at']) ? new \DateTimeImmutable($data['expires_at']) : null,
        );
    }

    public function toArray(): array
    {
        return [
            'customer_id' => $this->customerId->toString(),
            'license_id' => $this->licenseId?->toString(),
            'subscription_plan_id' => $this->subscriptionPlanId->toString(),
            'status' => $this->status,
            'started_at' => $this->startedAt?->format('Y-m-d H:i:s'),
            'expires_at' => $this->expiresAt?->format('Y-m-d H:i:s'),
        ];
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isTrial(): bool
    {
        return $this->status === 'trial';
    }

    public function isExpired(): bool
    {
        if ($this->expiresAt === null) {
            return false;
        }

        return $this->expiresAt < new \DateTimeImmutable();
    }
}
