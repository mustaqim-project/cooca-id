<?php
declare(strict_types=1);

namespace App\Services\Notification;

use App\Models\Affiliator;
use App\Models\Customer;
use App\Models\Notification as NotificationModel;
use App\Models\NotificationTemplate;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class NotificationService
{
    public const PAYMENT_CONFIRMED = 'payment_confirmed';
    public const LICENSE_READY = 'license_ready';
    public const SUBSCRIPTION_EXPIRY_WARNING = 'subscription_expiry_warning';
    public const SUBSCRIPTION_EXPIRED = 'subscription_expired';
    public const SUBSCRIPTION_ACTIVATED = 'subscription_activated';
    public const WELCOME = 'welcome';
    public const COMMISSION_RECEIVED = 'commission_received';
    public const WITHDRAWAL_APPROVED = 'withdrawal_approved';
    public const WITHDRAWAL_REJECTED = 'withdrawal_rejected';
    public const WITHDRAWAL_PAID = 'withdrawal_paid';
    public const TRIAL_SUBMITTED = 'trial_submitted';
    public const TRIAL_APPROVED = 'trial_approved';
    public const TRIAL_REJECTED = 'trial_rejected';
    public const TRIAL_STARTED = 'trial_started';
    public const TRIAL_EXPIRING_SOON = 'trial_expiring_soon';
    public const TRIAL_EXPIRED = 'trial_expired';
    public const TRIAL_CONVERTED = 'trial_converted';
    public const COMMISSION_HOLDING_RELEASED = 'commission_holding_released';

    /**
     * Render notification template with variables
     */
    public function renderTemplate(string $templateName, array $variables): ?string
    {
        $template = NotificationTemplate::where('name', $templateName)
            ->where('is_active', true)
            ->first();

        if (!$template) {
            return null;
        }

        $body = $template->body;

        foreach ($variables as $key => $value) {
            $body = str_replace('{{' . $key . '}}', (string) $value, $body);
        }

        return $body;
    }

    /**
     * Send notification to customer via all channels
     */
    public function sendToCustomer(
        Customer $customer,
        string $type,
        array $data = []
    ): void {
        $this->sendViaChannel($customer, $type, 'email', $data);
        $this->sendViaChannel($customer, $type, 'whatsapp', $data);
        $this->sendViaChannel($customer, $type, 'database', $data);
    }

    /**
     * Send notification to affiliator via all channels
     */
    public function sendToAffiliator(
        Affiliator $affiliator,
        string $type,
        array $data = []
    ): void {
        $this->sendViaChannel($affiliator, $type, 'email', $data);
        $this->sendViaChannel($affiliator, $type, 'whatsapp', $data);
        $this->sendViaChannel($affiliator, $type, 'database', $data);
    }

    /**
     * Send notification via specific channel
     */
    private function sendViaChannel(
        mixed $notifiable,
        string $type,
        string $channel,
        array $data
    ): void {
        try {
            $message = $this->getMessageForType($type, $channel, $data);

            if (empty($message)) {
                return;
            }

            $this->logNotification($notifiable, $type, $channel, $message, $data);

            // Dispatch appropriate job based on channel
            match ($channel) {
                'email' => $this->dispatchEmailJob($notifiable, $type, $data),
                'whatsapp' => $this->dispatchWhatsAppJob($notifiable, $type, $data),
                'database' => $this->saveDatabaseNotification($notifiable, $type, $message, $data),
                default => null,
            };
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /**
     * Get message content based on type and channel
     */
    private function getMessageForType(string $type, string $channel, array $data): ?string
    {
        return match ($type) {
            self::PAYMENT_CONFIRMED => $this->getPaymentConfirmedMessage($channel, $data),
            self::LICENSE_READY => $this->getLicenseReadyMessage($channel, $data),
            self::SUBSCRIPTION_EXPIRY_WARNING => $this->getSubscriptionExpiryMessage($channel, $data),
            self::WELCOME => $this->getWelcomeMessage($channel, $data),
            self::COMMISSION_RECEIVED => $this->getCommissionMessage($channel, $data),
            self::WITHDRAWAL_APPROVED => $this->getWithdrawalApprovedMessage($channel, $data),
            self::WITHDRAWAL_REJECTED => $this->getWithdrawalRejectedMessage($channel, $data),
            self::TRIAL_SUBMITTED => $this->getTrialSubmittedMessage($channel, $data),
            self::TRIAL_APPROVED => $this->getTrialApprovedMessage($channel, $data),
            self::TRIAL_REJECTED => $this->getTrialRejectedMessage($channel, $data),
            self::TRIAL_STARTED => $this->getTrialStartedMessage($channel, $data),
            self::TRIAL_EXPIRING_SOON => $this->getTrialExpiringSoonMessage($channel, $data),
            self::TRIAL_EXPIRED => $this->getTrialExpiredMessage($channel, $data),
            self::TRIAL_CONVERTED => $this->getTrialConvertedMessage($channel, $data),
            self::COMMISSION_HOLDING_RELEASED => $this->getCommissionHoldingReleasedMessage($channel, $data),
            default => null,
        };
    }

    /**
     * Log notification to database
     */
    private function logNotification(
        mixed $notifiable,
        string $type,
        string $channel,
        string $message,
        array $data
    ): void {
        NotificationModel::create([
            'notifiable_type' => get_class($notifiable),
            'notifiable_id' => $notifiable->id,
            'type' => $type,
            'channel' => $channel,
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * Dispatch email notification job
     */
    private function dispatchEmailJob(mixed $notifiable, string $type, array $data): void
    {
        // Implementation depends on specific notification class
        // This is a placeholder for actual job dispatching
    }

    /**
     * Dispatch WhatsApp notification job
     */
    private function dispatchWhatsAppJob(mixed $notifiable, string $type, array $data): void
    {
        // Implementation depends on specific job class
        // This is a placeholder for actual job dispatching
    }

    /**
     * Save database notification
     */
    private function saveDatabaseNotification(
        mixed $notifiable,
        string $type,
        string $message,
        array $data
    ): void {
        $notifiable->notifications()->create([
            'type' => $type,
            'data' => [
                'message' => $message,
                ...$data,
            ],
        ]);
    }

    // Message builders for each notification type

    private function getPaymentConfirmedMessage(string $channel, array $data): ?string
    {
        return match ($channel) {
            'whatsapp' => sprintf(
                "Pembayaran Berhasil!\n\n" .
                "Invoice: %s\n" .
                "Jumlah: Rp %s\n\n" .
                "Terima kasih telah menggunakan COOCA.ID",
                $data['invoice_number'] ?? '-',
                number_format($data['amount'] ?? 0, 0, ',', '.')
            ),
            default => null,
        };
    }

    private function getLicenseReadyMessage(string $channel, array $data): ?string
    {
        return match ($channel) {
            'whatsapp' => sprintf(
                "License Siap Digunakan!\n\n" .
                "Produk: %s\n" .
                "License Code: %s\n" .
                "Token Code: %s\n" .
                "Domain: %s",
                $data['product_name'] ?? '-',
                $data['license_code'] ?? '-',
                $data['token_code'] ?? '-',
                $data['domain'] ?? '-'
            ),
            default => null,
        };
    }

    private function getSubscriptionExpiryMessage(string $channel, array $data): ?string
    {
        return match ($channel) {
            'whatsapp' => sprintf(
                "Peringatan Subscription akan Berakhir!\n\n" .
                "Produk: %s\n" .
                "Berakhir dalam: %d hari\n" .
                "Tanggal: %s",
                $data['product_name'] ?? '-',
                $data['days_until_expiry'] ?? 0,
                $data['expires_at'] ?? '-'
            ),
            default => null,
        };
    }

    private function getWelcomeMessage(string $channel, array $data): ?string
    {
        return match ($channel) {
            'whatsapp' => sprintf(
                "Selamat Bergabung di COOCA.ID!\n\n" .
                "Halo %s,\n" .
                "Terima kasih telah bergabung dengan COOCA.ID.",
                $data['name'] ?? 'Customer'
            ),
            default => null,
        };
    }

    private function getCommissionMessage(string $channel, array $data): ?string
    {
        return match ($channel) {
            'whatsapp' => sprintf(
                "Komisi Diterima!\n\n" .
                "Jumlah: Rp %s\n" .
                "Level: %d\n" .
                "Saldo Anda: Rp %s",
                number_format($data['commission_amount'] ?? 0, 0, ',', '.'),
                $data['level'] ?? 1,
                number_format($data['balance'] ?? 0, 0, ',', '.')
            ),
            default => null,
        };
    }

    private function getWithdrawalApprovedMessage(string $channel, array $data): ?string
    {
        return match ($channel) {
            'whatsapp' => sprintf(
                "Withdrawal Disetujui!\n\n" .
                "Jumlah: Rp %s\n" .
                "Fee: Rp %s\n" .
                "Diterima: Rp %s\n\n" .
                "Estimasi transfer: 1-3 hari kerja",
                number_format($data['amount'] ?? 0, 0, ',', '.'),
                number_format($data['fee'] ?? 0, 0, ',', '.'),
                number_format($data['net_amount'] ?? 0, 0, ',', '.')
            ),
            default => null,
        };
    }

    private function getWithdrawalRejectedMessage(string $channel, array $data): ?string
    {
        return match ($channel) {
            'whatsapp' => sprintf(
                "Withdrawal Ditolak\n\n" .
                "Alasan: %s\n\n" .
                "Silakan hubungi support untuk informasi lebih lanjut.",
                $data['rejection_reason'] ?? 'Tidak diketahui'
            ),
            default => null,
        };
    }

    private function getTrialSubmittedMessage(string $channel, array $data): ?string
    {
        return match ($channel) {
            'whatsapp' => sprintf(
                "Permintaan Trial Disubmit\n\n" .
                "Produk: %s\n" .
                "ID Trial: %s\n" .
                "Status: Menunggu persetujuan admin",
                $data['product_name'] ?? '-',
                $data['trial_id'] ?? '-'
            ),
            default => null,
        };
    }

    private function getTrialApprovedMessage(string $channel, array $data): ?string
    {
        return match ($channel) {
            'whatsapp' => sprintf(
                "Trial Disetujui!\n\n" .
                "Produk: %s\n" .
                "Domain: %s\n" .
                "Periode trial: %d hari\n\n" .
                "Silakan akses panel customer Anda untuk memulai testing.",
                $data['product_name'] ?? '-',
                $data['domain'] ?? '-',
                $data['trial_days'] ?? 14
            ),
            default => null,
        };
    }

    private function getTrialRejectedMessage(string $channel, array $data): ?string
    {
        return match ($channel) {
            'whatsapp' => sprintf(
                "Trial Ditolak\n\n" .
                "Produk: %s\n" .
                "Alasan: %s\n\n" .
                "Silakan hubungi support untuk informasi lebih lanjut.",
                $data['product_name'] ?? '-',
                $data['rejection_reason'] ?? 'Tidak diketahui'
            ),
            default => null,
        };
    }

    private function getTrialStartedMessage(string $channel, array $data): ?string
    {
        return match ($channel) {
            'whatsapp' => sprintf(
                "Trial Dimulai!\n\n" .
                "Produk: %s\n" .
                "Domain: %s\n" .
                "Berakhir pada: %s\n\n" .
                "Selamat melakukan testing!",
                $data['product_name'] ?? '-',
                $data['domain'] ?? '-',
                $data['expires_at'] ?? '-'
            ),
            default => null,
        };
    }

    private function getTrialExpiringSoonMessage(string $channel, array $data): ?string
    {
        return match ($channel) {
            'whatsapp' => sprintf(
                "Trial Segera Berakhir!\n\n" .
                "Produk: %s\n" .
                "Sisa waktu: %d hari\n" .
                "Tanggal berakhir: %s\n\n" .
                "Konversi ke subscription sekarang untuk melanjutkan penggunaan.",
                $data['product_name'] ?? '-',
                $data['days_until_expiry'] ?? 0,
                $data['expires_at'] ?? '-'
            ),
            default => null,
        };
    }

    private function getTrialExpiredMessage(string $channel, array $data): ?string
    {
        return match ($channel) {
            'whatsapp' => sprintf(
                "Trial Telah Berakhir\n\n" .
                "Produk: %s\n" .
                "Tanggal berakhir: %s\n\n" .
                "Subscribe sekarang untuk terus menikmati layanan kami.",
                $data['product_name'] ?? '-',
                $data['expired_at'] ?? '-'
            ),
            default => null,
        };
    }

    private function getTrialConvertedMessage(string $channel, array $data): ?string
    {
        return match ($channel) {
            'whatsapp' => sprintf(
                "Trial Berhasil Dikonversi!\n\n" .
                "Produk: %s\n" .
                "Subscription ID: %s\n" .
                "Invoice: %s\n\n" .
                "Terima kasih telah berlangganan!",
                $data['product_name'] ?? '-',
                $data['subscription_id'] ?? '-',
                $data['invoice_number'] ?? '-'
            ),
            default => null,
        };
    }

    private function getCommissionHoldingReleasedMessage(string $channel, array $data): ?string
    {
        return match ($channel) {
            'whatsapp' => sprintf(
                "Komisi Tersedia untuk Withdrawal!\n\n" .
                "Jumlah: Rp %s\n" .
                "Saldo tersedia: Rp %s\n\n" .
                "Silakan ajukan withdrawal dari panel affiliator Anda.",
                number_format($data['commission_amount'] ?? 0, 0, ',', '.'),
                number_format($data['available_balance'] ?? 0, 0, ',', '.')
            ),
            default => null,
        };
    }
}
