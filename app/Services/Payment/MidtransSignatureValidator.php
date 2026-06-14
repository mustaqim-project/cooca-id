<?php declare(strict_types=1);

namespace App\Services\Payment;

use Illuminate\Support\Facades\Log;

/**
 * Service for validating Midtrans webhook signatures.
 * Implements SHA512 signature verification for payment callbacks.
 */
final class MidtransSignatureValidator
{
    private readonly string $serverKey;
    private readonly bool $sandbox;

    public function __construct()
    {
        $this->serverKey = config('services.midtrans.server_key', '');
        $this->sandbox = config('services.midtrans.sandbox', true);
    }

    /**
     * Validate Midtrans webhook signature using SHA512.
     * 
     * @param array $payload The webhook payload data
     * @param string $providedSignature The signature from X-Signature-Key header or signature_key field
     * @return bool True if signature is valid, false otherwise
     */
    public function validate(array $payload, string $providedSignature): bool
    {
        if (empty($providedSignature)) {
            Log::warning('MidtransSignatureValidator: Empty signature provided', [
                'order_id' => $payload['order_id'] ?? 'unknown',
            ]);
            return false;
        }

        if (empty($this->serverKey)) {
            Log::critical('MidtransSignatureValidator: Server key not configured');
            return false;
        }

        // Required fields for signature calculation
        $requiredFields = ['order_id', 'status_code', 'gross_amount'];
        foreach ($requiredFields as $field) {
            if (!isset($payload[$field])) {
                Log::warning('MidtransSignatureValidator: Missing required field for signature validation', [
                    'order_id' => $payload['order_id'] ?? 'unknown',
                    'missing_field' => $field,
                ]);
                return false;
            }
        }

        // Construct the string to hash according to Midtrans documentation
        $inputString = $payload['order_id']
            . $payload['status_code']
            . $payload['gross_amount']
            . $this->serverKey;

        $calculatedSignature = hash('sha512', $inputString);

        $isValid = hash_equals($calculatedSignature, $providedSignature);

        if (!$isValid) {
            Log::critical('MidtransSignatureValidator: Invalid signature detected - POTENTIAL FRAUD ATTEMPT', [
                'order_id' => $payload['order_id'],
                'provided_signature_prefix' => substr($providedSignature, 0, 16),
                'expected_signature_prefix' => substr($calculatedSignature, 0, 16),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'timestamp' => now()->toIso8601String(),
            ]);
        } else {
            Log::info('MidtransSignatureValidator: Signature validated successfully', [
                'order_id' => $payload['order_id'],
            ]);
        }

        return $isValid;
    }

    /**
     * Validate signature from raw request content (for header-based signature).
     * 
     * @param string $rawPayload The raw JSON payload content
     * @param string $providedSignature The signature from X-Signature-Key header
     * @return bool True if signature is valid, false otherwise
     */
    public function validateFromRaw(string $rawPayload, string $providedSignature): bool
    {
        if (empty($providedSignature)) {
            Log::warning('MidtransSignatureValidator: Empty signature in header');
            return false;
        }

        if (empty($this->serverKey)) {
            Log::critical('MidtransSignatureValidator: Server key not configured');
            return false;
        }

        $payload = json_decode($rawPayload, true);
        
        if (!is_array($payload)) {
            Log::error('MidtransSignatureValidator: Invalid JSON payload');
            return false;
        }

        return $this->validate($payload, $providedSignature);
    }

    /**
     * Get the server key (for testing purposes only).
     * 
     * @internal Do not use in production code
     */
    public function getServerKey(): string
    {
        return $this->serverKey;
    }

    /**
     * Check if sandbox mode is enabled.
     */
    public function isSandbox(): bool
    {
        return $this->sandbox;
    }
}
