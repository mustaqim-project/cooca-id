<?php

declare(strict_types=1);

namespace App\Http\Resources\Affiliator;

use App\Models\Affiliator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class AffiliatorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'referral_code' => $this->referral_code,
            'balance' => $this->balance,
            'bank_account' => $this->when($this->bank_account),
            'bank_name' => $this->when($this->bank_name),
            'parent_referred_by_id' => $this->when($this->parent_referred_by_id),
            'total_referrals' => $this->whenLoaded('referrals', fn () => $this->referrals->count()),
            'total_downlines' => $this->whenLoaded('downlines', fn () => $this->downlines->count()),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
