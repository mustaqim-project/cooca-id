<?php

declare(strict_types=1);

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
    public function activateLicense(License $license, \DateTimeInterface $activatedAt, ?\DateTimeInterface $expiresAt): License
    {
        $this->licenseRepository->update($license->id, [
            'status' => 'active',
            'activated_at' => $activatedAt,
            'expires_at' => $expiresAt,
        ]);

        return $this->licenseRepository->find($license->id);
    }

    /**
     * Revoke a license
     */
    public function revokeLicense(License $license, string $reason, ?\Ramsey\Uuid\UuidInterface $adminId, ?string $category = null): License
    {
        $license = $this->licenseRepository->update($license->id, [
            'status' => 'revoked',
            'revoked_at' => now(),
            'revoked_by' => $adminId,
            'revocation_reason' => $reason,
            'revocation_category' => $category,
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

    /**
     * Paginate licenses
     */
    public function paginate(int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->licenseRepository->paginate($perPage);
    }

    /**
     * Find license by ID
     */
    public function findById(string $id): ?License
    {
        return $this->licenseRepository->find($id);
    }

    /**
     * Revoke license wrapper
     */
    public function revoke(string $id, ?string $revokedBy, ?string $reason, ?string $category = null): void
    {
        $license = $this->findById($id);
        if ($license) {
            $adminId = $revokedBy ? \Ramsey\Uuid\Uuid::fromString($revokedBy) : null;
            $this->revokeLicense($license, $reason ?? 'No reason provided', $adminId, $category);
        }
    }

    /**
     * Activate license wrapper
     */
    public function activate(string $id): void
    {
        $license = $this->findById($id);
        if ($license) {
            $this->activateLicense($license, now(), now()->addYear());
        }
    }

    /**
     * Normalize domain string by removing protocol, port, www, and trailing path.
     */
    private function normalizeDomain(?string $domain): string
    {
        if (empty($domain)) {
            return '';
        }
        $domain = preg_replace('#^https?://#i', '', trim($domain));
        $domain = explode('/', $domain)[0];
        $domain = explode(':', $domain)[0];
        $domain = preg_replace('#^www\.#i', '', $domain);

        return strtolower(trim($domain));
    }

    /**
     * Activate license from ERP client
     */
    public function activateErpLicense(array $data): array
    {
        $license = $this->licenseRepository->findByCode($data['license_code']);

        if (!$license) {
            return ['valid' => false, 'message' => 'License code not found'];
        }

        // Validate License Key / Token Code if provided
        if (!empty($data['license_key']) && $license->token_code !== trim($data['license_key'])) {
            return ['valid' => false, 'message' => 'Invalid License Key (Token Code)'];
        }

        // Validate customer email if provided and license has customer
        if (!empty($data['email']) && $license->customer && !empty($license->customer->email)) {
            if (strtolower(trim($license->customer->email)) !== strtolower(trim($data['email']))) {
                return ['valid' => false, 'message' => 'Email does not match license owner'];
            }
        }

        // Check revoked
        if ($license->status === 'revoked') {
            return ['valid' => false, 'message' => 'License has been revoked: ' . ($license->revocation_reason ?? 'Administrative action')];
        }

        // Check expired
        if ($license->status === 'expired' || ($license->expires_at && $license->expires_at->isPast())) {
            return ['valid' => false, 'message' => 'License has expired'];
        }

        $requestDomain = $this->normalizeDomain($data['domain'] ?? '');
        if (empty($requestDomain)) {
            return ['valid' => false, 'message' => 'Domain is required'];
        }

        // Validate customer registered domain if exists
        $customerDomain = $this->normalizeDomain($license->customer?->domain);
        if (!empty($customerDomain) && $customerDomain !== 'localhost' && $requestDomain !== 'localhost') {
            if ($customerDomain !== $requestDomain) {
                return ['valid' => false, 'message' => 'Customer domain mismatch'];
            }
        }

        $licenseDomain = $this->normalizeDomain($license->domain);

        // If domain is not set or placeholder, bind the incoming domain and activate
        if (empty($licenseDomain) || empty($license->domain)) {
            $updateData = [
                'domain' => $data['domain'],
                'status' => 'active',
            ];
            if (!$license->activated_at) {
                $updateData['activated_at'] = now();
            }
            if (!$license->starts_at) {
                $updateData['starts_at'] = now();
            }
            if (!$license->expires_at) {
                $updateData['expires_at'] = $license->subscription?->expires_at ?? now()->addYear();
            }

            $this->licenseRepository->update($license->id, $updateData);
            $license = $this->licenseRepository->find($license->id);
            $this->clearLicenseCache($license);
        } elseif ($licenseDomain !== $requestDomain) {
            return ['valid' => false, 'message' => 'License is already bound to another domain (' . $license->domain . ')'];
        } else {
            // Domain matches — ensure status is active if it was inactive or pending
            if ($license->status === 'inactive' || $license->status === 'pending') {
                $this->licenseRepository->update($license->id, [
                    'status' => 'active',
                    'activated_at' => $license->activated_at ?? now(),
                    'starts_at' => $license->starts_at ?? now(),
                ]);
                $license = $this->licenseRepository->find($license->id);
                $this->clearLicenseCache($license);
            }
        }

        // Generate response payload
        return $this->generateErpResponse($license);
    }

    /**
     * Sync license from ERP client
     */
    public function syncErpLicense(array $data): array
    {
        $license = $this->licenseRepository->findByCode($data['license_code']);

        if (!$license) {
            return ['valid' => false, 'message' => 'License code not found'];
        }

        $licenseDomain = $this->normalizeDomain($license->domain);
        $requestDomain = $this->normalizeDomain($data['domain'] ?? '');

        if ($licenseDomain !== $requestDomain && !empty($licenseDomain)) {
            return ['valid' => false, 'message' => 'Domain mismatch'];
        }

        return $this->generateErpResponse($license);
    }

    /**
     * Generate ERP response payload with HMAC signature
     */
    private function generateErpResponse(License $license): array
    {
        $payload = [
            'customer' => [
                'id' => $license->customer->id ?? null,
                'business_name' => $license->customer->business_name ?? null,
                'email' => $license->customer->email ?? null,
            ],
            'product' => [
                'id' => $license->product->id ?? null,
                'name' => $license->product->name ?? null,
            ]
        ];

        $startedAt = $license->activated_at ?? $license->created_at;
        $expiredAt = $license->expires_at;

        $data = [
            'license_code' => $license->license_code,
            'license_key' => $license->token_code,
            'subscription_status' => strtolower($license->status),
            'subscription_plan' => $license->subscriptionPlan->name ?? $license->product->name ?? 'Standard',
            'subscription_started_at' => $startedAt ? $startedAt->toIso8601String() : null,
            'subscription_expired_at' => $expiredAt ? $expiredAt->toIso8601String() : null,
            'license_status' => strtolower($license->status),
            'token' => $license->token_code,
            'last_validation' => now()->toIso8601String(),
            'next_validation' => now()->addDays(7)->toIso8601String(),
            'payload' => $payload,
        ];

        $secret = config('services.cooca.secret', 'cooca-license-shared-secret-key-2026');
        // The signature signs the payload part
        $data['signature'] = hash_hmac('sha256', json_encode($payload), $secret);

        return [
            'valid' => true,
            'data' => $data,
        ];
    }
}
