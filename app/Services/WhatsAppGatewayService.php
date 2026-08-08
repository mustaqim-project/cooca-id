<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;

class WhatsAppGatewayService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.whatsapp_gateway.url', env('WA_SERVER_URL', 'http://127.0.0.1:3000')), '/');
    }


    /**
     * Inisialisasi/Start Sesi WA di wa-server.
     */
    public function startSession(string $sessionId, ?string $webhookUrl = null): array
    {
        try {
            $response = Http::timeout(10)->post("{$this->baseUrl}/api/sessions/start", [
                'sessionId' => $sessionId,
                'webhookUrl' => $webhookUrl,
            ]);

            return $response->json() ?? ['success' => false, 'error' => 'No response from WA server'];
        } catch (\Throwable $e) {
            Log::error("[WhatsAppGatewayService] Start session failed: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Mengambil status sesi WA di wa-server.
     */
    public function getStatus(string $sessionId): array
    {
        try {
            $response = Http::timeout(5)->get("{$this->baseUrl}/api/sessions/{$sessionId}/status");
            return $response->json() ?? ['success' => false, 'status' => 'disconnected'];
        } catch (\Throwable $e) {
            return ['success' => false, 'status' => 'disconnected', 'error' => $e->getMessage()];
        }
    }

    /**
     * Mengambil URL QR Code HTML interaktif.
     */
    public function getQrCodeHtmlUrl(string $sessionId): string
    {
        return "{$this->baseUrl}/api/sessions/{$sessionId}/qr";
    }

    /**
     * Mengambil data QR Code (Base64).
     */
    public function getQrCodeData(string $sessionId): array
    {
        try {
            $response = Http::timeout(5)->get("{$this->baseUrl}/api/sessions/{$sessionId}/qr");
            return $response->json() ?? ['success' => false];
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Mengirim pesan, file (PDF, DOCX, XLSX, Image, Audio, Video) via wa-server.
     */
    public function sendMessage(string $sessionId, string $target, ?string $message = null, ?string $mediaUrl = null, array $options = []): array
    {
        // Check global setting toggle
        if (! (bool) Setting::get('whatsapp.notifications_active', true)) {
            Log::info("[WhatsAppGatewayService] Send message skipped: WhatsApp notifications are globally disabled.");
            return ['success' => false, 'error' => 'WhatsApp notifications are globally disabled'];
        }

        try {
            $payload = array_merge([
                'session' => $sessionId,
                'target' => $target,
                'message' => $message,
                'url' => $mediaUrl,
            ], $options);

            $response = Http::timeout(60)->post("{$this->baseUrl}/send-message", $payload);


            return $response->json() ?? ['success' => false, 'error' => 'No response from WA server'];
        } catch (\Throwable $e) {
            Log::error("[WhatsAppGatewayService] Send message failed: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }


    /**
     * Menghapus/Stop sesi di wa-server.
     */
    public function deleteSession(string $sessionId): array
    {
        try {
            $response = Http::timeout(5)->delete("{$this->baseUrl}/api/sessions/{$sessionId}");
            return $response->json() ?? ['success' => true];
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
