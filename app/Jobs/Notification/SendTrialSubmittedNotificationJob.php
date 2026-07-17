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
 * Job untuk mengirim notifikasi trial submitted ke admin
 */
class SendTrialSubmittedNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Trial $trial
    ) {}

    public function handle(NotificationService $notificationService): void
    {
        // Notifikasi ke customer bahwa trial telah disubmit
        $customer = $this->trial->customer;
        
        $data = [
            'product_name' => $this->trial->subscriptionPlan->name ?? 'Produk',
            'trial_id' => $this->trial->id,
            'submitted_at' => $this->trial->submitted_at?->format('d/m/Y H:i'),
        ];

        $notificationService->sendToCustomer(
            $customer,
            NotificationService::TRIAL_SUBMITTED,
            $data
        );
    }
}
