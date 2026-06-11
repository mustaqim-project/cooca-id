<?php

declare(strict_types=1);

namespace App\Actions\Voucher\ApplyVoucher;

use App\Models\Customer;
use App\DTOs\Voucher\VoucherData;
use App\Services\Voucher\VoucherService;

final readonly class ApplyVoucherAction
{
    public function __construct(
        private VoucherService $voucherService,
    ) {}

    /**
     * Apply voucher code to a purchase
     * Returns VoucherData if valid, null otherwise
     */
    public function execute(
        string $code,
        float $purchaseAmount,
        Customer $customer,
        ?array $productIds = null,
    ): ?VoucherData {
        return $this->voucherService->applyVoucher($code, $purchaseAmount, $customer, $productIds);
    }
}
