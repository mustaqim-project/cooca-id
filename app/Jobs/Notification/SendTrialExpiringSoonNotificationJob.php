<?php

declare(strict_types=1);

namespace App\Jobs\Notification;

use App\Models\Trial;
use App\Services\Notification\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Job untuk mengirim notifikasi trial expiring soon ke customer
 */
class SendTrialExpiringSoonNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Trial $trial,
        public int $daysUntilExpiry = 3
    ) {}

    public function handle(NotificationService $notificationService): void
    {
        $customer = $this->trial->customer;
        
        $data = [
            'product_name' => $this->trial->subscriptionPlan->name ?? 'Produk',
            'days_until_expiry' => $this->daysUntilExpiry,
            'expires_at' => $this->trial->trial_ends_at?->format('d/m/Y H:i') ?? '-',
        ];

        $notificationService->sendToCustomer(
            $customer,
            NotificationService::TRIAL_EXPIRING_SOON,
            $data
        );
    }
}
