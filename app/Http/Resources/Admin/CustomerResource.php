<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        /** @var Customer $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'business_name' => $this->business_name,
            'domain' => $this->domain,
            'affiliator_id' => $this->affiliator_id,
            'google_id' => $this->whenNotNull($this->google_id),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
