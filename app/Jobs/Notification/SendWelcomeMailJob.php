<?php
declare(strict_types=1);

namespace App\Jobs\Notification;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class SendWelcomeMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        private readonly \App\Models\Customer $customer,
    ) {}

    public function handle(): void
    {
        try {
            $this->customer->notify(
                new \App\Notifications\Customer\WelcomeNotification()
            );

            $whatsappService = app(\App\Services\Notification\WhatsAppService::class);
            $message = sprintf(
                "Selamat Bergabung di COOCA.ID!\n\n" .
                "Halo %s,\n" .
                "Terima kasih telah bergabung dengan COOCA.ID.\n\n" .
                "Anda sekarang dapat mengakses berbagai sistem ERP untuk bisnis Anda.\n\n" .
                "Login ke dashboard: https://cooca.id/customer/login",
                $this->customer->name
            );
            $whatsappService->send($this->customer->email, $message);
        } catch (\Throwable $e) {
            report($e);
            throw $e;
        }
    }
}
