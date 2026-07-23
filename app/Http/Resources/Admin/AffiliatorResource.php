<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin;

use App\Models\Affiliator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class AffiliatorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        /** @var Affiliator $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'referral_code' => $this->referral_code,
            'balance' => $this->balance,
            'bank_account' => $this->whenNotNull($this->bank_account),
            'bank_name' => $this->whenNotNull($this->bank_name),
            'parent_referred_by_id' => $this->whenNotNull($this->parent_referred_by_id),
            'google_id' => $this->whenNotNull($this->google_id),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
