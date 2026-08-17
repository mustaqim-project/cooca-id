<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class AiPlanConfig extends Model
{
    use HasUuids;

    protected $table = 'ai_plan_configs';

    protected $fillable = [
        'subscription_plan_id',
        'monthly_token_quota',
        'requests_per_minute',
        'allowed_models',
        'overage_policy',
    ];

    protected $casts = [
        'monthly_token_quota' => 'integer',
        'requests_per_minute' => 'integer',
        'allowed_models' => 'array',
    ];

    public function subscriptionPlan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }
}
