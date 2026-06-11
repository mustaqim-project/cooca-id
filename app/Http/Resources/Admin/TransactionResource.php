<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        /** @var Transaction $this */
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'subscription_id' => $this->subscription_id,
            'invoice_number' => $this->invoice_number,
            'gross_amount' => $this->gross_amount,
            'voucher_discount' => $this->voucher_discount,
            'net_amount' => $this->net_amount,
            'payment_method' => $this->payment_method,
            'payment_gateway' => $this->payment_gateway,
            'midtrans_order_id' => $this->whenNotNull($this->midtrans_order_id),
            'midtrans_transaction_id' => $this->whenNotNull($this->midtrans_transaction_id),
            'midtrans_status' => $this->whenNotNull($this->midtrans_status),
            'status' => $this->status,
            'paid_at' => $this->paid_at?->toIso8601String(),
            'failed_at' => $this->whenNotNull($this->failed_at),
            'refunded_at' => $this->whenNotNull($this->refunded_at),
            'customer' => $this->whenLoaded('customer', fn() => new CustomerResource($this->customer)),
            'subscription' => $this->whenLoaded('subscription', fn() => new SubscriptionResource($this->subscription)),
            'voucher' => $this->whenLoaded('voucher', fn() => new VoucherResource($this->voucher)),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
