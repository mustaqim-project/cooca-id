<?php
declare(strict_types=1);

namespace App\Jobs\Notification;

use App\Models\Customer;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class SendSubscriptionExpiryReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        private readonly Customer $customer,
        private readonly Subscription $subscription,
        private readonly int $daysUntilExpiry,
    ) {}

    public function handle(): void
    {
        try {
            $productName = $this->subscription->license?->product?->name ?? 'Produk COOCA.ID';
            
            // Dispatch email notification
            $this->customer->notify(
                new \App\Notifications\Customer\SubscriptionExpiryReminderNotification(
                    $productName,
                    $this->subscription->expires_at,
                    $this->daysUntilExpiry
                )
            );

            // Dispatch WhatsApp notification via service
            $whatsappService = app(\App\Services\Notification\WhatsAppService::class);
            $message = sprintf(
                "Peringatan Subscription akan Berakhir!\n\n" .
                "Halo %s,\n" .
                "Subscription Anda untuk %s akan berakhir dalam %d hari.\n\n" .
                "Tanggal berakhir: %s\n\n" .
                "Silakan perpanjang subscription Anda untuk terus menggunakan layanan.",
                $this->customer->name,
                $productName,
                $this->daysUntilExpiry,
                $this->subscription->expires_at->format('d F Y')
            );
            $whatsappService->send($this->customer->email, $message);
        } catch (\Throwable $e) {
            report($e);
            throw $e;
        }
    }
}
