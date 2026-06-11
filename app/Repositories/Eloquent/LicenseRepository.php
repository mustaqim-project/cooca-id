<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\License;
use App\Repositories\Contracts\LicenseRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

final class LicenseRepository extends BaseRepository implements LicenseRepositoryInterface
{
    public function __construct(License $model)
    {
        parent::__construct($model);
    }

    public function findByCode(string $licenseCode): ?License
    {
        return $this->model
            ->where('license_code', $licenseCode)
            ->with(['customer', 'product', 'subscriptionPlan'])
            ->first();
    }

    public function findByDomain(string $domain): ?License
    {
        return $this->model
            ->where('domain', $domain)
            ->where('status', 'active')
            ->with(['customer', 'product', 'subscriptionPlan'])
            ->first();
    }

    public function findByCodeAndToken(string $licenseCode, string $tokenCode): ?License
    {
        return $this->model
            ->where('license_code', $licenseCode)
            ->where('token_code', $tokenCode)
            ->with(['customer', 'product', 'subscriptionPlan'])
            ->first();
    }

    public function validateLicense(string $domain, string $licenseCode, string $tokenCode): array
    {
        $license = $this->model
            ->where('domain', $domain)
            ->where('license_code', $licenseCode)
            ->where('token_code', $tokenCode)
            ->first();

        if (!$license) {
            return [
                'valid' => false,
                'message' => 'License not found',
            ];
        }

        if ($license->status !== 'active') {
            return [
                'valid' => false,
                'message' => 'License is not active (current status: ' . $license->status . ')',
                'status' => $license->status,
            ];
        }

        if ($license->expires_at && $license->expires_at->isPast()) {
            return [
                'valid' => false,
                'message' => 'License has expired',
                'expired_at' => $license->expires_at->toIso8601String(),
            ];
        }

        return [
            'valid' => true,
            'message' => 'License is valid',
            'customer_id' => $license->customer_id,
            'product_id' => $license->product_id,
            'expires_at' => $license->expires_at?->toIso8601String(),
        ];
    }

    public function getActiveLicensesByCustomer(string $customerId): Collection
    {
        return $this->model
            ->where('customer_id', $customerId)
            ->where('status', 'active')
            ->with(['product', 'subscriptionPlan'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getExpiringLicenses(int $daysUntilExpiry = 7): Collection
    {
        $expiryDate = Carbon::now()->addDays($daysUntilExpiry);

        return $this->model
            ->where('status', 'active')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', $expiryDate)
            ->with(['customer', 'product'])
            ->orderBy('expires_at')
            ->get();
    }

    public function clearCacheByDomain(string $domain): void
    {
        Cache::forget('license_validation:' . $domain);
    }
}
