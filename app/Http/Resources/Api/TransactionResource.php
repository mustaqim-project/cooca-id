<?php

declare(strict_types=1);

namespace App\Http\Resources\Api;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,
            'gross_amount' => $this->gross_amount,
            'voucher_discount' => $this->voucher_discount,
            'net_amount' => $this->net_amount,
            'payment_method' => $this->payment_method,
            'payment_gateway' => $this->payment_gateway,
            'status' => $this->status,
            'paid_at' => $this->paid_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'subscription' => new SubscriptionResource($this->whenLoaded('subscription')),
            'voucher' => new VoucherResource($this->whenLoaded('voucher')),
        ];
    }
}
