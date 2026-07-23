<?php
declare(strict_types=1);

namespace App\Jobs\Notification;


use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class SendLicenseReadyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        private readonly Customer $customer,
        private readonly string $licenseCode,
        private readonly string $tokenCode,
        private readonly string $domain,
        private readonly string $productName,
    ) {}

    public function handle(): void
    {
        try {
            // Dispatch email notification
            $this->customer->notify(
                new \App\Notifications\Customer\LicenseReadyNotification(
                    $this->licenseCode,
                    $this->tokenCode,
                    $this->domain,
                    $this->productName
                )
            );

            // Dispatch WhatsApp notification via service
            $whatsappService = app(\App\Services\Notification\WhatsAppService::class);
            $message = sprintf(
                "License Siap Digunakan!\n\n" .
                "Terima kasih %s,\n" .
                "License untuk produk %s telah siap.\n\n" .
                "License Code: %s\n" .
                "Token Code: %s\n" .
                "Domain: %s\n\n" .
                "Silakan login ke dashboard untuk menggunakan layanan.",
                $this->customer->name,
                $this->productName,
                $this->licenseCode,
                $this->tokenCode,
                $this->domain
            );
            $whatsappService->send($this->customer->email, $message);
        } catch (\Throwable $e) {
            report($e);
            throw $e;
        }
    }
}
