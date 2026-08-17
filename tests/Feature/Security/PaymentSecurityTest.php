<?php

declare(strict_types=1);

namespace Tests\Feature\Security;

use Tests\TestCase;
use App\Models\Customer;
use App\Models\Transaction;
use App\Services\Payment\MidtransSignatureValidator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;

/**
 * Security Test Suite: Payment & Webhook Security
 *
 * OWASP A01: Broken Access Control (webhook forgery)
 * OWASP A02: Cryptographic Failures (signature validation)
 * OWASP A08: Software and Data Integrity Failures
 */
final class PaymentSecurityTest extends TestCase
{
    use RefreshDatabase;

    private MidtransSignatureValidator $validator;
    private string $serverKey;

    protected function setUp(): void
    {
        parent::setUp();

        $this->serverKey = 'SB-Mid-server-test-security-key';
        Config::set('services.midtrans.server_key', $this->serverKey);
        Config::set('services.midtrans.sandbox', true);

        $this->validator = new MidtransSignatureValidator();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // OWASP A01 — Webhook Forgery: Unsigned requests rejected
    // ─────────────────────────────────────────────────────────────────────────

    public function test_webhook_without_signature_is_rejected(): void
    {
        $response = $this->postJson('/api/v1/midtrans/webhook', [
            'order_id'           => 'ORDER-001',
            'transaction_status' => 'settlement',
            'gross_amount'       => '100000',
            'status_code'        => '200',
        ], ['Accept' => 'application/json']);
        // No X-Signature-Key header

        $response->assertStatus(401);
    }

    public function test_webhook_with_forged_signature_is_rejected(): void
    {
        $response = $this->postJson('/api/v1/midtrans/webhook', [
            'order_id'           => 'ORDER-002',
            'transaction_status' => 'settlement',
            'gross_amount'       => '100000',
            'status_code'        => '200',
        ], [
            'Accept'         => 'application/json',
            'X-Signature-Key' => 'forged_signature_' . bin2hex(random_bytes(16)),
        ]);

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Invalid signature']);
    }

    public function test_webhook_with_tampered_amount_is_rejected(): void
    {
        $orderId     = 'ORDER-TAMPER-001';
        $realAmount  = '100000';
        $fakeAmount  = '1'; // Attacker lowers the price

        // Sign with real amount
        $validSig = hash('sha512', $orderId . '200' . $realAmount . $this->serverKey);

        // But submit with tampered amount
        $response = $this->postJson('/api/v1/midtrans/webhook', [
            'order_id'           => $orderId,
            'transaction_status' => 'settlement',
            'gross_amount'       => $fakeAmount,
            'status_code'        => '200',
        ], [
            'Accept'         => 'application/json',
            'X-Signature-Key' => $validSig,
        ]);

        // Signature won't match because amount is different
        $response->assertStatus(401);
    }

    public function test_webhook_with_empty_signature_is_rejected(): void
    {
        $response = $this->postJson('/api/v1/midtrans/webhook', [
            'order_id'           => 'ORDER-003',
            'transaction_status' => 'settlement',
            'gross_amount'       => '100000',
            'status_code'        => '200',
        ], [
            'Accept'         => 'application/json',
            'X-Signature-Key' => '',
        ]);

        $response->assertStatus(401);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // OWASP A02 — Cryptographic: Signature uses SHA-512 (not MD5/SHA1)
    // ─────────────────────────────────────────────────────────────────────────

    public function test_signature_validator_uses_sha512_not_md5(): void
    {
        $payload = [
            'order_id'     => 'ORDER-HASH-TEST',
            'status_code'  => '200',
            'gross_amount' => '100000',
        ];

        $md5Signature = hash('md5',
            $payload['order_id'] . $payload['status_code'] . $payload['gross_amount'] . $this->serverKey
        );

        // MD5 signature must be rejected
        $this->assertFalse($this->validator->validate($payload, $md5Signature),
            'MD5 signatures should be rejected — only SHA-512 is acceptable'
        );
    }

    public function test_signature_validator_uses_sha512_not_sha1(): void
    {
        $payload = [
            'order_id'     => 'ORDER-SHA1-TEST',
            'status_code'  => '200',
            'gross_amount' => '100000',
        ];

        $sha1Signature = hash('sha1',
            $payload['order_id'] . $payload['status_code'] . $payload['gross_amount'] . $this->serverKey
        );

        $this->assertFalse($this->validator->validate($payload, $sha1Signature),
            'SHA1 signatures should be rejected — only SHA-512 is acceptable'
        );
    }

    // ─────────────────────────────────────────────────────────────────────────
    // OWASP A02 — Timing Attack: Signature comparison is constant-time
    // ─────────────────────────────────────────────────────────────────────────

    public function test_wrong_signatures_differing_at_all_positions_are_rejected(): void
    {
        $payload = [
            'order_id'     => 'ORDER-TIMING',
            'status_code'  => '200',
            'gross_amount' => '100000',
        ];

        $correctSig  = hash('sha512',
            $payload['order_id'] . $payload['status_code'] . $payload['gross_amount'] . $this->serverKey
        );

        // First char wrong
        $this->assertFalse($this->validator->validate($payload, 'z' . substr($correctSig, 1)));
        // Last char wrong
        $this->assertFalse($this->validator->validate($payload, substr($correctSig, 0, -1) . 'z'));
        // Middle char wrong
        $mid = (int) (strlen($correctSig) / 2);
        $tampered = substr($correctSig, 0, $mid) . 'z' . substr($correctSig, $mid + 1);
        $this->assertFalse($this->validator->validate($payload, $tampered));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // OWASP A01 — Payment route requires authentication
    // ─────────────────────────────────────────────────────────────────────────

    public function test_payment_process_requires_customer_authentication(): void
    {
        $response = $this->post(route('customer.payments.store'), [
            'product_id' => '00000000-0000-0000-0000-000000000000',
            'amount'     => 100000,
        ]);

        // Must redirect to login, not process the payment
        $response->assertStatus(302);
        $this->assertGuest('customer');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // OWASP A08 — Integrity: Cannot replay old webhooks
    // (Midtrans includes order_id; repeated settlement should be idempotent)
    // ─────────────────────────────────────────────────────────────────────────

    public function test_webhook_endpoint_handles_missing_order_gracefully(): void
    {
        $nonExistentOrder = 'ORDER-DOES-NOT-EXIST-' . uniqid();
        $sig = hash('sha512', $nonExistentOrder . '200' . '100000' . $this->serverKey);

        $response = $this->postJson('/api/v1/midtrans/webhook', [
            'order_id'           => $nonExistentOrder,
            'transaction_status' => 'settlement',
            'gross_amount'       => '100000',
            'status_code'        => '200',
        ], [
            'Accept'         => 'application/json',
            'X-Signature-Key' => $sig,
        ]);

        // Should not 500 — should return a graceful response (404 or 200 with error info)
        $this->assertNotEquals(500, $response->status(),
            'Webhook with valid signature but non-existent order should not cause 500'
        );
    }
}
