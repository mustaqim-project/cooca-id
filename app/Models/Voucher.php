<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $code
 * @property string $name
 * @property string|null $description
 * @property string $type
 * @property float $value
 * @property float $min_purchase
 * @property float|null $max_discount
 * @property int|null $max_usage
 * @property int $used_count
 * @property int|null $per_user_limit
 * @property \Carbon\Carbon $valid_from
 * @property \Carbon\Carbon $valid_until
 * @property bool $is_active
 * @property array|null $applicable_products
 * @property string $created_by
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 */
final class Voucher extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'vouchers';

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'min_purchase',
        'max_discount',
        'max_usage',
        'used_count',
        'per_user_limit',
        'valid_from',
        'valid_until',
        'is_active',
        'applicable_products',
        'created_by',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'max_usage' => 'integer',
        'used_count' => 'integer',
        'per_user_limit' => 'integer',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
        'applicable_products' => 'array',
    ];

    public const TYPE_PERCENT = 'percent';
    public const TYPE_NOMINAL = 'nominal';

    public static function getTypes(): array
    {
        return [self::TYPE_PERCENT, self::TYPE_NOMINAL];
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function usages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(VoucherUsage::class, 'voucher_id');
    }

    public function transactions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Transaction::class, 'voucher_id');
    }

    public function scopeActive($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>=', now());
    }

    public function scopeApplicableToProduct($query, string $productId): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where(function ($q) use ($productId) {
            $q->whereNull('applicable_products')
                ->orWhereJsonContains('applicable_products', $productId);
        });
    }

    public function isAvailable(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if (now()->lt($this->valid_from) || now()->gt($this->valid_until)) {
            return false;
        }

        if ($this->max_usage !== null && $this->used_count >= $this->max_usage) {
            return false;
        }

        return true;
    }

    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }
}
