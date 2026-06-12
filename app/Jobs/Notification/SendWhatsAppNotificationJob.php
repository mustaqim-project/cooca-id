<?php
declare(strict_types=1);

namespace App\Jobs\Notification;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class SendWhatsAppNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        private readonly string $phone,
        private readonly string $message,
    ) {}

    public function handle(): void
    {
        try {
            $whatsappService = app(\App\Services\Notification\WhatsAppService::class);
            $whatsappService->send($this->phone, $this->message);
        } catch (\Throwable $e) {
            report($e);
            throw $e;
        }
    }
}
