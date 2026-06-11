<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface LicenseRepositoryInterface extends RepositoryInterface
{
    /**
     * Find license by code and domain.
     */
    public function findByCodeAndDomain(string $licenseCode, string $domain): ?\App\Models\License;

    /**
     * Find license by code.
     */
    public function findByCode(string $licenseCode): ?\App\Models\License;

    /**
     * Find license by token.
     */
    public function findByToken(string $tokenCode): ?\App\Models\License;

    /**
     * Validate license (code + token + domain).
     *
     * @return array{valid: bool, status?: string, message?: string}
     */
    public function validateLicense(string $licenseCode, string $tokenCode, string $domain): array;

    /**
     * Get licenses by customer.
     */
    public function getByCustomer(string $customerId): Collection;

    /**
     * Get licenses by product.
     */
    public function getByProduct(string $productId): Collection;

    /**
     * Get active licenses.
     */
    public function getActiveLicenses(): Collection;

    /**
     * Get expired licenses.
     */
    public function getExpiredLicenses(): Collection;

    /**
     * Get licenses expiring soon.
     */
    public function getExpiringSoon(int $days = 7): Collection;

    /**
     * Check if domain is already bound.
     */
    public function isDomainBound(string $domain, ?string $excludeId = null): bool;

    /**
     * Generate unique license code.
     */
    public function generateLicenseCode(): string;

    /**
     * Generate unique token code.
     */
    public function generateTokenCode(): string;
}
