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
    public function activateLicense(License $license, \DateTimeInterface $activatedAt, ?\DateTimeInterface $expiresAt): License
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
     * Activate license from ERP client
     */
    public function activateErpLicense(array $data): array
    {
        $license = $this->licenseRepository->findByCode($data['license_code']);

        if (!$license) {
            return ['valid' => false, 'message' => 'License code not found'];
        }

        if ($license->status !== 'active' && $license->status !== 'pending') {
            return ['valid' => false, 'message' => 'License is ' . $license->status];
        }

        if ($license->expires_at && $license->expires_at->isPast()) {
            return ['valid' => false, 'message' => 'License has expired'];
        }

        // Validate customer registered URL if exists
        $customerDomain = $license->customer?->domain;
        if (!empty($customerDomain)) {
            $normalizedCustomerDomain = str_replace(['http://', 'https://'], '', $customerDomain);
            $normalizedCustomerDomain = rtrim($normalizedCustomerDomain, '/');
            
            $normalizedRequestDomain = str_replace(['http://', 'https://'], '', $data['domain']);
            $normalizedRequestDomain = rtrim($normalizedRequestDomain, '/');
            
            if ($normalizedCustomerDomain !== $normalizedRequestDomain) {
                return ['valid' => false, 'message' => 'Customer URL mismatch'];
            }
        }

        // If domain is not set, bind it. If set, it must match.
        if (empty($license->domain)) {
            $this->licenseRepository->update($license->id, [
                'domain' => $data['domain'],
                'status' => 'active'
            ]);
            $license->domain = $data['domain'];
            $license->status = 'active';
            $this->clearLicenseCache($license);
        } elseif ($license->domain !== $data['domain']) {
            return ['valid' => false, 'message' => 'License is already bound to another domain'];
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

        if ($license->domain !== $data['domain']) {
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
            'license_key' => $license->license_key ?? null,
            'subscription_status' => strtolower($license->status),
            'subscription_plan' => $license->product->name ?? 'Standard',
            'subscription_started_at' => $startedAt ? $startedAt->toIso8601String() : null,
            'subscription_expired_at' => $expiredAt ? $expiredAt->toIso8601String() : null,
            'license_status' => strtolower($license->status),
            'token' => $license->token_code,
            'last_validation' => now()->toIso8601String(),
            'next_validation' => now()->addDays(7)->toIso8601String(),
            'payload' => $payload,
        ];

        $secret = config('services.cooca.secret', config('app.key'));
        // The signature signs the payload part
        $data['signature'] = hash_hmac('sha256', json_encode($payload), $secret);

        return [
            'valid' => true,
            'data' => $data,
        ];
    }
}
