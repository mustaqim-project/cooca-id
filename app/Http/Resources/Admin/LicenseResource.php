<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin;

use App\Models\License;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class LicenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        /** @var License $this */
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'product_id' => $this->product_id,
            'subscription_plan_id' => $this->subscription_plan_id,
            'license_code' => $this->license_code,
            'token_code' => $this->token_code,
            'domain' => $this->domain,
            'status' => $this->status,
            'activated_at' => $this->activated_at?->toIso8601String(),
            'expires_at' => $this->expires_at?->toIso8601String(),
            'revoked_at' => $this->whenNotNull($this->revoked_at),
            'revoked_by' => $this->whenLoaded('revokedBy', fn() => new AdminResource($this->revokedBy)),
            'revocation_reason' => $this->whenNotNull($this->revocation_reason),
            'customer' => $this->whenLoaded('customer', fn() => new CustomerResource($this->customer)),
            'product' => $this->whenLoaded('product', fn() => new ProductResource($this->product)),
            'subscription_plan' => $this->whenLoaded('subscriptionPlan', fn() => new SubscriptionPlanResource($this->subscriptionPlan)),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
