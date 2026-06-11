<?php

declare(strict_types=1);

namespace App\Http\Resources\Api;

use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class SubscriptionPlanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'duration_months' => $this->duration_months,
            'price' => $this->price,
            'discount_percent' => $this->discount_percent,
            'final_price' => $this->price * (1 - ($this->discount_percent / 100)),
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
        ];
    }
}
