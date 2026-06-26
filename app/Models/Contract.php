<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Contract extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'contracts';

    protected $fillable = [
        'license_id',
        'customer_id',
        'contract_number',
        'status',
        'pdf_path',
        'customer_signature_data',
        'cooca_signature_path',
        'signed_at',
    ];

    protected function casts(): array
    {
        return [
            ...parent::casts(),
            'signed_at' => 'datetime',
        ];
    }

    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class, 'license_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Generate a unique contract number.
     */
    public static function generateContractNumber(): string
    {
        $year  = now()->format('Y');
        $month = now()->format('m');
        $count = static::whereYear('created_at', $year)->whereMonth('created_at', $month)->count() + 1;

        return sprintf('COOCA-%s%s-%04d', $year, $month, $count);
    }
}
