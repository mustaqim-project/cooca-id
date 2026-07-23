<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class AffiliateWithdrawalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'referred_by_id' => $this->referred_by_id,
            'affiliator' => $this->whenLoaded('affiliator', fn () => [
                'id' => $this->affiliator->id,
                'name' => $this->affiliator->name,
                'email' => $this->affiliator->email,
                'referral_code' => $this->affiliator->referral_code,
            ]),
            'amount' => (float) $this->amount,
            'fee' => (float) $this->fee,
            'net_amount' => (float) $this->net_amount,
            'withdrawal_method' => $this->withdrawal_method,
            'payment_method' => $this->withdrawal_method,
            'method' => $this->withdrawal_method,
            'bank_name' => $this->withdrawal_method,
            'account_number' => $this->account_number,
            'account_name' => $this->account_name,
            'account_holder' => $this->account_name,
            'status' => $this->status,
            'approved_by' => $this->approved_by,
            'approved_at' => $this->approved_at?->toISOString(),
            'rejected_at' => $this->rejected_at?->toISOString(),
            'rejection_reason' => $this->rejection_reason,
            'paid_at' => $this->paid_at?->toISOString(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
