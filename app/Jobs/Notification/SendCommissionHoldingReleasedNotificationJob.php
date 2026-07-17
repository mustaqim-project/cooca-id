<?php

declare(strict_types=1);

namespace App\Jobs\Notification;

use App\Models\AffiliateCommission;
use App\Services\Notification\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Job untuk mengirim notifikasi commission holding released ke affiliator
 */
class SendCommissionHoldingReleasedNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public AffiliateCommission $commission
    ) {}

    public function handle(NotificationService $notificationService): void
    {
        $affiliator = $this->commission->affiliator;
        
        $data = [
            'commission_amount' => $this->commission->amount,
            'available_balance' => $affiliator->wallet?->balance ?? 0,
        ];

        $notificationService->sendToAffiliator(
            $affiliator,
            NotificationService::COMMISSION_HOLDING_RELEASED,
            $data
        );
    }
}
