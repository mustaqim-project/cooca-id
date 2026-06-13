<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\License;
use App\Repositories\Contracts\LicenseRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Str;

final class LicenseRepository extends BaseRepository implements LicenseRepositoryInterface
{
    public function __construct(License $model)
    {
        parent::__construct($model);
    }

    public function findByCodeAndDomain(string $licenseCode, string $domain): ?License
    {
        return $this->model
            ->where('license_code', $licenseCode)
            ->where('domain', $domain)
            ->with(['customer', 'product', 'subscriptionPlan'])
            ->first();
    }

    public function findByCode(string $licenseCode): ?License
    {
        return $this->model
            ->where('license_code', $licenseCode)
            ->with(['customer', 'product', 'subscriptionPlan'])
            ->first();
    }

    public function findByToken(string $tokenCode): ?License
    {
        return $this->model
            ->where('token_code', $tokenCode)
            ->with(['customer', 'product', 'subscriptionPlan'])
            ->first();
    }

    public function validateLicense(string $licenseCode, string $tokenCode, string $domain): array
    {
        $license = $this->model
            ->where('license_code', $licenseCode)
            ->where('token_code', $tokenCode)
            ->where('domain', $domain)
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

    public function getByCustomer(string $customerId): Collection
    {
        return $this->model
            ->where('customer_id', $customerId)
            ->with(['product', 'subscriptionPlan'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getByProduct(string $productId): Collection
    {
        return $this->model
            ->where('product_id', $productId)
            ->with(['customer', 'subscriptionPlan'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getActiveLicenses(): Collection
    {
        return $this->model
            ->where('status', 'active')
            ->with(['customer', 'product', 'subscriptionPlan'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getExpiredLicenses(): Collection
    {
        return $this->model
            ->where('status', 'active')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', Carbon::now())
            ->with(['customer', 'product', 'subscriptionPlan'])
            ->orderBy('expires_at')
            ->get();
    }

    public function getExpiringSoon(int $days = 7): Collection
    {
        $expiryDate = Carbon::now()->addDays($days);

        return $this->model
            ->where('status', 'active')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', $expiryDate)
            ->where('expires_at', '>=', Carbon::now())
            ->with(['customer', 'product', 'subscriptionPlan'])
            ->orderBy('expires_at')
            ->get();
    }

    public function isDomainBound(string $domain, ?string $excludeId = null): bool
    {
        $query = $this->model->where('domain', $domain);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    public function generateLicenseCode(): string
    {
        do {
            $code = strtoupper(Str::random(16));
        } while ($this->model->where('license_code', $code)->exists());

        return $code;
    }

    public function generateTokenCode(): string
    {
        do {
            $token = strtoupper(Str::random(32));
        } while ($this->model->where('token_code', $token)->exists());

        return $token;
    }
}
