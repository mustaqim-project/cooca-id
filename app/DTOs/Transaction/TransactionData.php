<?php

declare(strict_types=1);

namespace App\DTOs\Transaction;

use Ramsey\Uuid\UuidInterface;

final readonly class TransactionData
{
    public function __construct(
        public UuidInterface $customerId,
        public ?UuidInterface $subscriptionId = null,
        public ?UuidInterface $voucherId = null,
        public string $invoiceNumber,
        public float $grossAmount,
        public float $voucherDiscount = 0.0,
        public float $netAmount,
        public ?string $paymentMethod = null,
        public ?string $paymentGateway = null,
        public ?string $midtransOrderId = null,
        public ?string $midtransTransactionId = null,
        public ?string $midtransStatus = null,
        public string $status = 'pending',
        public ?\DateTimeInterface $paidAt = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            customerId: $data['customer_id'],
            subscriptionId: $data['subscription_id'] ?? null,
            voucherId: $data['voucher_id'] ?? null,
            invoiceNumber: $data['invoice_number'],
            grossAmount: (float) ($data['gross_amount'] ?? 0),
            voucherDiscount: (float) ($data['voucher_discount'] ?? 0),
            netAmount: (float) ($data['net_amount'] ?? 0),
            paymentMethod: $data['payment_method'] ?? null,
            paymentGateway: $data['payment_gateway'] ?? null,
            midtransOrderId: $data['midtrans_order_id'] ?? null,
            midtransTransactionId: $data['midtrans_transaction_id'] ?? null,
            midtransStatus: $data['midtrans_status'] ?? null,
            status: $data['status'] ?? 'pending',
            paidAt: isset($data['paid_at']) ? new \DateTimeImmutable($data['paid_at']) : null,
        );
    }

    public function toArray(): array
    {
        return [
            'customer_id' => $this->customerId->toString(),
            'subscription_id' => $this->subscriptionId?->toString(),
            'voucher_id' => $this->voucherId?->toString(),
            'invoice_number' => $this->invoiceNumber,
            'gross_amount' => $this->grossAmount,
            'voucher_discount' => $this->voucherDiscount,
            'net_amount' => $this->netAmount,
            'payment_method' => $this->paymentMethod,
            'payment_gateway' => $this->paymentGateway,
            'midtrans_order_id' => $this->midtransOrderId,
            'midtrans_transaction_id' => $this->midtransTransactionId,
            'midtrans_status' => $this->midtransStatus,
            'status' => $this->status,
            'paid_at' => $this->paidAt?->format('Y-m-d H:i:s'),
        ];
    }

    public function getCommissionBaseAmount(): float
    {
        // CRITICAL: Commission is calculated from gross_amount, NOT net_amount
        return $this->grossAmount;
    }
}
