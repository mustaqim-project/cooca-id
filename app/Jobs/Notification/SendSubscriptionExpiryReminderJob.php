<?php
declare(strict_types=1);

namespace App\Jobs\Notification;


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
            
            // Calculate amount
            $plan = $this->subscription->subscriptionPlan;
            $price = (float) ($plan?->price ?? 0);
            $discountPercent = (float) ($plan?->discount_percent ?? 0);
            $discountAmount = round($price * ($discountPercent / 100), 2);
            $netAmount = round($price - $discountAmount, 2);

            // Generate signed URL valid until H+1 of expiry date
            // H+1 of expiry date means expires_at + 1 day
            $expirationDate = $this->subscription->expires_at->copy()->addDay();
            $paymentUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
                'payment.direct',
                $expirationDate,
                ['subscription' => $this->subscription->id]
            );

            // Dispatch email notification
            $this->customer->notify(
                new \App\Notifications\Customer\SubscriptionExpiryReminderNotification(
                    $productName,
                    $this->subscription->expires_at,
                    $this->daysUntilExpiry,
                    $paymentUrl,
                    $netAmount
                )
            );

            // Dispatch WhatsApp notification via service
            $whatsappService = app(\App\Services\Notification\WhatsAppService::class);
            $message = sprintf(
                "Peringatan Subscription akan Berakhir!\n\n" .
                "Halo %s,\n" .
                "Subscription Anda untuk %s akan berakhir dalam %d hari.\n\n" .
                "Tanggal berakhir: %s\n" .
                "Total Tagihan: Rp %s\n\n" .
                "Silakan klik link berikut untuk langsung membayar (tanpa login):\n%s\n\n" .
                "Link ini berlaku hingga %s.",
                $this->customer->name,
                $productName,
                $this->daysUntilExpiry,
                $this->subscription->expires_at->format('d F Y'),
                number_format($netAmount, 0, ',', '.'),
                $paymentUrl,
                $expirationDate->format('d F Y H:i')
            );
            $whatsappService->send($this->customer->email, $message);
        } catch (\Throwable $e) {
            report($e);
            throw $e;
        }
    }
}
