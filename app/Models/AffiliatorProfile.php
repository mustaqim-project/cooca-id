<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class AffiliatorProfile extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id',
        'balance',
        'bank_account',
        'bank_name',
        'parent_referred_by_id',
        'referral_code',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public function affiliator(): BelongsTo
    {
        return $this->belongsTo(Affiliator::class);
    }

    public function parentAffiliator(): BelongsTo
    {
        return $this->belongsTo(Affiliator::class, 'parent_referred_by_id');
    }
}


