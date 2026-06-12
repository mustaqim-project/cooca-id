<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\VoucherUsage;
use App\Repositories\Contracts\VoucherUsageRepositoryInterface;
use Ramsey\Uuid\UuidInterface;
use Illuminate\Database\Eloquent\Collection;

final class VoucherUsageRepository extends Repository implements VoucherUsageRepositoryInterface
{
    public function getModel(): string
    {
        return VoucherUsage::class;
    }

    /**
     * @inheritDoc
     */
    public function findByCustomerAndVoucher(UuidInterface $customerId, UuidInterface $voucherId): ?VoucherUsage
    {
        return $this->model->newQuery()
            ->where('customer_id', $customerId->toString())
            ->where('voucher_id', $voucherId->toString())
            ->first();
    }

    /**
     * @inheritDoc
     */
    public function countByCustomerAndVoucher(UuidInterface $customerId, UuidInterface $voucherId): int
    {
        return $this->model->newQuery()
            ->where('customer_id', $customerId->toString())
            ->where('voucher_id', $voucherId->toString())
            ->count();
    }

    /**
     * @inheritDoc
     */
    public function existsByCustomerAndVoucher(UuidInterface $customerId, UuidInterface $voucherId): bool
    {
        return $this->model->newQuery()
            ->where('customer_id', $customerId->toString())
            ->where('voucher_id', $voucherId->toString())
            ->exists();
    }

    /**
     * @inheritDoc
     */
    public function findByCustomerId(UuidInterface $customerId): Collection
    {
        return $this->model->newQuery()
            ->where('customer_id', $customerId->toString())
            ->with(['voucher', 'transaction'])
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function findByTransactionId(UuidInterface $transactionId): ?VoucherUsage
    {
        return $this->model->newQuery()
            ->where('transaction_id', $transactionId->toString())
            ->first();
    }
}
