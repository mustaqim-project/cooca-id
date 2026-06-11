<?php

declare(strict_types=1);

namespace App\DTOs\License;

use Ramsey\Uuid\UuidInterface;

final readonly class LicenseData
{
    public function __construct(
        public ?UuidInterface $customerId = null,
        public ?UuidInterface $productId = null,
        public ?UuidInterface $subscriptionPlanId = null,
        public ?string $domain = null,
        public ?string $licenseCode = null,
        public ?string $tokenCode = null,
        public string $status = 'inactive',
        public ?\DateTimeInterface $activatedAt = null,
        public ?\DateTimeInterface $expiresAt = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            customerId: isset($data['customer_id']) ? $data['customer_id'] : null,
            productId: isset($data['product_id']) ? $data['product_id'] : null,
            subscriptionPlanId: isset($data['subscription_plan_id']) ? $data['subscription_plan_id'] : null,
            domain: $data['domain'] ?? null,
            licenseCode: $data['license_code'] ?? null,
            tokenCode: $data['token_code'] ?? null,
            status: $data['status'] ?? 'inactive',
            activatedAt: isset($data['activated_at']) ? new \DateTimeImmutable($data['activated_at']) : null,
            expiresAt: isset($data['expires_at']) ? new \DateTimeImmutable($data['expires_at']) : null,
        );
    }

    public function toArray(): array
    {
        return [
            'customer_id' => $this->customerId?->toString(),
            'product_id' => $this->productId?->toString(),
            'subscription_plan_id' => $this->subscriptionPlanId?->toString(),
            'domain' => $this->domain,
            'license_code' => $this->licenseCode,
            'token_code' => $this->tokenCode,
            'status' => $this->status,
            'activated_at' => $this->activatedAt?->format('Y-m-d H:i:s'),
            'expires_at' => $this->expiresAt?->format('Y-m-d H:i:s'),
        ];
    }
}
