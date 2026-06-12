<?php
declare(strict_types=1);

namespace App\Services\Notification;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

final class WhatsAppService
{
    private readonly string $token;
    private const API_URL = 'https://api.fonnte.com/send';

    public function __construct()
    {
        $this->token = config('services.fonnte.token', '');
    }

    /**
     * Send WhatsApp message to single phone number
     */
    public function send(string $phone, string $message): bool
    {
        if (empty($this->token)) {
            report(new \RuntimeException('FONNTE_TOKEN not configured'));
            return false;
        }

        $normalizedPhone = $this->normalizePhone($phone);
        
        if (empty($normalizedPhone)) {
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
                'Content-Type' => 'application/json',
            ])->post(self::API_URL, [
                'target' => $normalizedPhone,
                'message' => $message,
                'countryCode' => '62',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return isset($data['success']) && $data['success'] === true;
            }

            return false;
        } catch (\Throwable $e) {
            report($e);
            return false;
        }
    }

    /**
     * Send WhatsApp message to multiple phone numbers
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
     * Normalize phone number to international format (628xxx)
     */
    private function normalizePhone(string $phone): ?string
    {
        // Remove all non-numeric characters except +
        $cleaned = preg_replace('/[^\d+]/', '', $phone);
        
        // Remove leading +
        $cleaned = ltrim($cleaned, '+');

        // Handle Indonesian phone numbers
        if (str_starts_with($cleaned, '0')) {
            // Replace leading 0 with 62
            $cleaned = '62' . substr($cleaned, 1);
        } elseif (str_starts_with($cleaned, '62')) {
            // Already in correct format
            $cleaned = $cleaned;
        } elseif (strlen($cleaned) >= 9 && strlen($cleaned) <= 13) {
            // Assume it's a local number without country code
            $cleaned = '62' . $cleaned;
        }

        // Validate: should be 62 followed by 9-12 digits
        if (!preg_match('/^62\d{9,12}$/', $cleaned)) {
            return null;
        }

        return $cleaned;
    }
}
