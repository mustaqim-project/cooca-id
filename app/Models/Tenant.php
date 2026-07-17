<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Tenant extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'erp_request_id',
        'product_id',
        'subdomain',
        'custom_domain',
        'db_name',
        'db_host',
        'status',
        'server_ip',
        'config',
        'provisioned_at',
        'suspended_at',
    ];

    protected function casts(): array
    {
        return [
            'config' => 'array',
            'provisioned_at' => 'datetime',
            'suspended_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function erpRequest(): BelongsTo
    {
        return $this->belongsTo(ErpRequest::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
