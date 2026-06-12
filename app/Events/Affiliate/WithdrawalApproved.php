<?php
declare(strict_types=1);

namespace App\Events\Affiliate;

use App\Models\AffiliateWithdrawal;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class WithdrawalApproved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly AffiliateWithdrawal $withdrawal,
    ) {}
}
