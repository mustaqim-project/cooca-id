<?php declare(strict_types=1);

namespace App\Services\License;

use App\Models\License;
use App\Models\Customer;
use App\DTOs\License\LicenseData;
use App\Repositories\Contracts\LicenseRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

final class LicenseService
{
    private const int CACHE_TTL = 900;

    public function __construct(
        private readonly LicenseRepositoryInterface $licenseRepository,
    ) {}

    /**
     * Generate a new license with 16-digit code and token
     */
    public function generateLicense(LicenseData $data): License
    {
        $licenseCode = $this->generateUniqueCode();
        $tokenCode = $this->generateUniqueCode();

        $licenseData = $data->toArray();
        $licenseData['license_code'] = $licenseCode;
        $licenseData['token_code'] = $tokenCode;

        return $this->licenseRepository->create($licenseData);
    }

    /**
     * Validate license with triple-check: domain + code + token
     * Uses Redis caching for performance (15 minutes TTL)
     */
    public function validateLicense(string $domain, string $licenseCode, ?string $tokenCode): array
    {
        $cacheKey = $this->buildCacheKey($domain, $licenseCode, $tokenCode ?? '');

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($domain, $licenseCode, $tokenCode) {
            $license = $this->licenseRepository->findByDomainAndCode($domain, $licenseCode);

            if (!$license) {
                return [
                    'valid' => false,
                    'status' => 'invalid',
                    'message' => 'License not found',
                ];
            }

            if ($tokenCode !== null && $license->token_code !== $tokenCode) {
                return [
                    'valid' => false,
                    'status' => 'invalid',
                    'message' => 'Invalid token code',
                ];
            }

            if ($license->domain !== $domain) {
                return [
                    'valid' => false,
                    'status' => 'invalid',
                    'message' => 'Domain mismatch',
                ];
            }

            if ($license->status !== 'active') {
                return [
                    'valid' => false,
                    'status' => $license->status,
                    'message' => "License is {$license->status}",
                ];
            }

            if ($license->expires_at && $license->expires_at->isPast()) {
                return [
                    'valid' => false,
                    'status' => 'expired',
                    'message' => 'License has expired',
                ];
            }

            return [
                'valid' => true,
                'status' => 'active',
                'message' => 'License is valid',
                'license' => [
                    'id' => $license->id,
                    'customer_id' => $license->customer_id->toString(),
                    'product_id' => $license->product_id->toString(),
                    'domain' => $license->domain,
                    'expires_at' => $license->expires_at?->toIso8601String(),
                ],
            ];
        });
    }

    /**
     * Activate a license
     */
    public function activateLicense(License $license, \DateTimeInterface $activatedAt, \DateTimeInterface $expiresAt): License
    {
        return $this->licenseRepository->update($license->id, [
            'status' => 'active',
            'activated_at' => $activatedAt,
            'expires_at' => $expiresAt,
        ]);
    }

    /**
     * Revoke a license
     */
    public function revokeLicense(License $license, string $reason, ?\Ramsey\Uuid\UuidInterface $adminId): License
    {
        $license = $this->licenseRepository->update($license->id, [
            'status' => 'revoked',
            'revoked_at' => now(),
            'revoked_by' => $adminId,
            'revocation_reason' => $reason,
        ]);

        $this->clearLicenseCache($license);

        return $license;
    }

    /**
     * Expire a license
     */
    public function expireLicense(License $license): License
    {
        $license = $this->licenseRepository->update($license->id, [
            'status' => 'expired',
        ]);

        $this->clearLicenseCache($license);

        return $license;
    }

    /**
     * Check if domain already has an active license
     */
    public function hasActiveLicenseForDomain(string $domain): bool
    {
        return $this->licenseRepository->existsByDomainAndStatus($domain, 'active');
    }

    /**
     * Find license by code
     */
    public function findByCode(string $code): ?License
    {
        return $this->licenseRepository->findByCode($code);
    }

    /**
     * Generate unique 16-character code
     */
    private function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(Str::random(16));
        } while ($this->licenseRepository->existsByCode($code));

        return $code;
    }

    /**
     * Clear cache for a specific license
     */
    private function clearLicenseCache(License $license): void
    {
        $cacheKey = $this->buildCacheKey(
            $license->domain,
            $license->license_code,
            $license->token_code
        );
        Cache::forget($cacheKey);
        
        // Also clear wildcard pattern if Redis available
        if (config('cache.default') === 'redis') {
            try {
                $pattern = "license:validate:{$license->domain}:{$license->license_code}:*";
                $keys = Redis::keys($pattern);
                if (!empty($keys)) {
                    Redis::del($keys);
                }
            } catch (\Exception $e) {
                // Redis not available or error occurred
            }
        }
    }

    /**
     * Build cache key from parts
     */
    private function buildCacheKey(string ...$parts): string
    {
        return 'license:validate:' . implode(':', $parts);
    }
}
