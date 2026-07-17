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
 * Job untuk mengirim notifikasi trial approved ke customer
 */
class SendTrialApprovedNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Trial $trial
    ) {}

    public function handle(NotificationService $notificationService): void
    {
        $customer = $this->trial->customer;
        
        $data = [
            'product_name' => $this->trial->subscriptionPlan->name ?? 'Produk',
            'domain' => $this->trial->domain ?? '-',
            'trial_days' => $this->trial->trial_days ?? 14,
        ];

        $notificationService->sendToCustomer(
            $customer,
            NotificationService::TRIAL_APPROVED,
            $data
        );
    }
}
