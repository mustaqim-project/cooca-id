<?php
declare(strict_types=1);

namespace App\Jobs\Notification;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class SendPaymentConfirmationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        private readonly Transaction $transaction,
    ) {}

    public function handle(): void
    {
        try {
            $customer = $this->transaction->customer;
            
            if (!$customer) {
                return;
            }

            // Dispatch email notification
            $customer->notify(
                new \App\Notifications\Customer\PaymentConfirmedNotification($this->transaction)
            );

            // Dispatch WhatsApp notification via service
            $whatsappService = app(\App\Services\Notification\WhatsAppService::class);
            $message = sprintf(
                "Pembayaran Berhasil!\n\n" .
                "Terima kasih %s,\n" .
                "Pembayaran Anda dengan invoice %s sebesar Rp %s telah berhasil.\n\n" .
                "Terima kasih telah menggunakan COOCA.ID",
                $customer->name,
                $this->transaction->invoice_number,
                number_format((float) $this->transaction->net_amount, 0, ',', '.')
            );
            $whatsappService->send($customer->phone ?? $customer->email, $message);
        } catch (\Throwable $e) {
            report($e);
            throw $e;
        }
    }
}
