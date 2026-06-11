<?php

declare(strict_types=1);

namespace App\Http\Resources\Api;

use App\Models\AffiliateCommission;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class CommissionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'level' => $this->level,
            'gross_amount' => $this->gross_amount,
            'commission_percent' => $this->commission_percent,
            'commission_amount' => $this->commission_amount,
            'status' => $this->status,
            'cleared_at' => $this->cleared_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'transaction' => new TransactionResource($this->whenLoaded('transaction')),
            'customer' => new CustomerResource($this->whenLoaded('customer')),
        ];
    }
}
