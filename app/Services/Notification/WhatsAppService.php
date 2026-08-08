<?php

declare(strict_types=1);

namespace App\Services\Notification;

use App\Models\ApiIntegration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

/**
 * WhatsApp Service
 *
 * Mengirim pesan WhatsApp melalui microservice whatsapp-web.js lokal.
 * Konfigurasi diambil dari tabel api_integrations (provider: whatsapp).
 */
final class WhatsAppService
{
    private ?string $serverUrl = null;
    private ?string $apiToken = null;
    private bool $configured = false;

    public function __construct()
    {
        $this->loadConfig();
    }

    /**
     * Load konfigurasi dari api_integrations.
     */
    private function loadConfig(): void
    {
        try {
            $integration = Cache::remember('api_integration.whatsapp', 3600, function () {
                return ApiIntegration::where('provider', 'whatsapp')
                    ->where('is_active', true)
                    ->first();
            });

            if ($integration && !empty($integration->config)) {
                $this->serverUrl = rtrim($integration->config['server_url'] ?? 'http://localhost:3000', '/');
                $this->apiToken = $integration->config['api_token'] ?? null;
                $this->configured = true;
            }
        } catch (\Exception $e) {
            Log::warning('WhatsAppService: Failed to load config', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Check apakah WhatsApp service sudah dikonfigurasi.
     */
    public function isConfigured(): bool
    {
        return $this->configured;
    }

    /**
     * Get status WhatsApp client (ready/pending/loading).
     */
    public function getStatus(): array
    {
        if (!$this->configured) {
            return ['status' => 'unconfigured', 'message' => 'WhatsApp belum dikonfigurasi.'];
        }

        try {
            $response = Http::timeout(5)->get("{$this->serverUrl}/qr");
            return $response->json() ?? ['status' => 'error', 'message' => 'Empty response'];
        } catch (\Exception $e) {
            Log::error('WhatsAppService: Failed to get status', ['error' => $e->getMessage()]);
            return ['status' => 'error', 'message' => 'Server tidak dapat dihubungi: ' . $e->getMessage()];
        }
    }

    /**
     * Send WhatsApp message to single phone number via whatsapp-web.js.
     *
     * @param string $phone Nomor tujuan (format: 08xx atau 62xx)
     * @param string $message Isi pesan
     */
    public function send(string $phone, string $message): bool
    {
        $result = $this->sendMessage($phone, $message);
        return $result['success'];
    }

    /**
     * Kirim pesan WhatsApp (detailed response).
     *
     * @param string $number Nomor tujuan (format: 08xx atau 62xx)
     * @param string $message Isi pesan
     * @return array{success: bool, message: string}
     */
    public function sendMessage(string $number, string $message): array
    {
        $normalizedPhone = $this->normalizePhone($number);
        if (empty($normalizedPhone)) {
            return ['success' => false, 'message' => 'Nomor telepon tidak valid.'];
        }

        try {
            \App\Models\WhatsAppQueue::create([
                'phone'   => $normalizedPhone,
                'message' => $message,
                'status'  => 'pending',
            ]);

            return ['success' => true, 'message' => 'Pesan berhasil dimasukkan ke antrean.'];
        } catch (\Exception $e) {
            Log::error('WhatsAppService: Exception', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    /**
     * Send WhatsApp message to multiple phone numbers.
     *
     * @param array<string> $phones
     * @return array{success: int, failed: int}
     */
    public function sendBulk(array $phones, string $message): array
    {
        $success = 0;
        $failed = 0;

        foreach ($phones as $phone) {
            if ($this->send($phone, $message)) {
                $success++;
            } else {
                $failed++;
            }
        }

        return [
            'success' => $success,
            'failed' => $failed,
        ];
    }

    /**
     * Kirim pesan notifikasi transaksional.
     */
    public function sendTransactionalMessage(string $number, string $template, array $data = []): array
    {
        $message = $this->renderTemplate($template, $data);
        return $this->sendMessage($number, $message);
    }

    /**
     * Render template pesan sederhana (placeholder replacement).
     */
    private function renderTemplate(string $template, array $data): string
    {
        foreach ($data as $key => $value) {
            $template = str_replace("{{$key}}", (string) $value, $template);
        }
        return $template;
    }

    /**
     * Normalize phone number to international format (628xxx).
     */
    private function normalizePhone(string $phone): ?string
    {
        // Remove all non-numeric characters except +
        $cleaned = preg_replace('/[^\d+]/', '', $phone);

        // Remove leading +
        $cleaned = ltrim($cleaned, '+');

        // Handle Indonesian phone numbers
        if (str_starts_with($cleaned, '0')) {
            $cleaned = '62' . substr($cleaned, 1);
        } elseif (!str_starts_with($cleaned, '62') && strlen($cleaned) >= 9 && strlen($cleaned) <= 13) {
            $cleaned = '62' . $cleaned;
        }

        // Validate: should be 62 followed by 9-12 digits
        if (!preg_match('/^62\d{9,12}$/', $cleaned)) {
            return null;
        }

        return $cleaned;
    }
}
