<?php
declare(strict_types=1);

namespace App\Notifications\Customer;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class LicenseReadyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        private readonly string $licenseCode,
        private readonly string $tokenCode,
        private readonly string $domain,
        private readonly string $productName,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('License Siap Digunakan - ' . $this->productName)
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('License untuk produk ' . $this->productName . ' telah siap digunakan.')
            ->line('License Code: ' . $this->licenseCode)
            ->line('Token Code: ' . $this->tokenCode)
            ->line('Domain: ' . $this->domain)
            ->action('Buka Dashboard', route('customer.dashboard'))
            ->line('Simpan credential ini dengan baik.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'license_ready',
            'product_name' => $this->productName,
            'license_code' => $this->licenseCode,
            'token_code' => $this->tokenCode,
            'domain' => $this->domain,
            'message' => 'License untuk ' . $this->productName . ' telah siap digunakan.',
        ];
    }
}
