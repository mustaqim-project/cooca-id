<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CompanyBankAccount extends Model
{
    use HasFactory;

    protected $table = 'company_bank_accounts';

    protected $fillable = [
        'bank_name',
        'bank_code',
        'account_number',
        'account_holder',
        'branch',
        'logo',
        'qr_code_image',
        'instructions',
        'is_active',
        'is_primary',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_primary' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Scope a query to only include active accounts.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order accounts properly (primary first, then sort_order, then id).
     */
    public function scopeOrdered($query)
    {
        return $query->orderByDesc('is_primary')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc');
    }

    /**
     * Get the full URL for the bank logo.
     */
    public function getLogoUrlAttribute(): ?string
    {
        if (!$this->logo) {
            return null;
        }

        if (str_starts_with($this->logo, 'http://') || str_starts_with($this->logo, 'https://')) {
            return $this->logo;
        }

        if (str_starts_with($this->logo, '/assets/')) {
            return asset($this->logo);
        }

        return asset('storage/' . $this->logo);
    }

    /**
     * Get the full URL for the QR code image.
     */
    public function getQrCodeUrlAttribute(): ?string
    {
        if (!$this->qr_code_image) {
            return null;
        }

        if (str_starts_with($this->qr_code_image, 'http://') || str_starts_with($this->qr_code_image, 'https://')) {
            return $this->qr_code_image;
        }

        return asset('storage/' . $this->qr_code_image);
    }

    /**
     * Get CSS badge color class or hex based on common bank names.
     */
    public function getBadgeColorAttribute(): string
    {
        $name = strtoupper($this->bank_name);

        if (str_contains($name, 'BCA')) {
            return '#005baa';
        } elseif (str_contains($name, 'MANDIRI')) {
            return '#003366';
        } elseif (str_contains($name, 'BNI')) {
            return '#f15a24';
        } elseif (str_contains($name, 'BRI')) {
            return '#00529c';
        } elseif (str_contains($name, 'BSI')) {
            return '#00a39d';
        } elseif (str_contains($name, 'CIMB')) {
            return '#ed1b24';
        } elseif (str_contains($name, 'PERMATA')) {
            return '#008542';
        } elseif (str_contains($name, 'QRIS')) {
            return '#e01a22';
        }

        return '#4361ee';
    }
}
