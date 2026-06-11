<?php

declare(strict_types=1);

namespace App\Http\Resources\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'base_price' => $this->base_price,
            'features' => $this->features ?? [],
            'specifications' => $this->specifications ?? [],
            'demo_url' => $this->when($this->demo_url),
            'thumbnail' => $this->when($this->thumbnail),
            'screenshots' => $this->screenshots ?? [],
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'category' => new ProductCategoryResource($this->whenLoaded('category')),
            'subscription_plans' => SubscriptionPlanResource::collection($this->whenLoaded('subscriptionPlans')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
