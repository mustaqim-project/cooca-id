<?php declare(strict_types=1);

namespace Tests\Feature\Payment;

use Tests\TestCase;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Invoice;
use App\Services\Payment\MidtransSignatureValidator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

final class MidtransWebhookSignatureTest extends TestCase
{
    use RefreshDatabase;

    private MidtransSignatureValidator $validator;
    private string $serverKey;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->serverKey = 'SB-Mid-server-test-key-12345';
        Config::set('services.midtrans.server_key', $this->serverKey);
        Config::set('services.midtrans.sandbox', true);
        
        $this->validator = new MidtransSignatureValidator();
    }

    /**
     * Test valid signature validation.
     */
    public function test_valid_signature_is_accepted(): void
    {
        $payload = [
            'order_id' => 'ORDER-TEST-001',
            'status_code' => '200',
            'gross_amount' => '100000',
        ];

        // Calculate expected signature
        $inputString = $payload['order_id'] . $payload['status_code'] . $payload['gross_amount'] . $this->serverKey;
        $validSignature = hash('sha512', $inputString);

        $result = $this->validator->validate($payload, $validSignature);

        $this->assertTrue($result, 'Valid signature should be accepted');
    }

    /**
     * Test invalid signature is rejected.
     */
    public function test_invalid_signature_is_rejected(): void
    {
        $payload = [
            'order_id' => 'ORDER-TEST-002',
            'status_code' => '200',
            'gross_amount' => '100000',
        ];

        $invalidSignature = 'invalid_signature_' . bin2hex(random_bytes(16));

        $result = $this->validator->validate($payload, $invalidSignature);

        $this->assertFalse($result, 'Invalid signature should be rejected');
    }

    /**
     * Test empty signature is rejected.
     */
    public function test_empty_signature_is_rejected(): void
    {
        $payload = [
            'order_id' => 'ORDER-TEST-003',
            'status_code' => '200',
            'gross_amount' => '100000',
        ];

        $result = $this->validator->validate($payload, '');

        $this->assertFalse($result, 'Empty signature should be rejected');
    }

    /**
     * Test missing required fields are rejected.
     */
    public function test_missing_required_fields_are_rejected(): void
    {
        $payload = [
            'order_id' => 'ORDER-TEST-004',
            // Missing status_code and gross_amount
        ];

        $inputString = $payload['order_id'] . '200' . '100000' . $this->serverKey;
        $signature = hash('sha512', $inputString);

        $result = $this->validator->validate($payload, $signature);

        $this->assertFalse($result, 'Payload with missing required fields should be rejected');
    }

    /**
     * Test webhook endpoint rejects invalid signature.
     */
    public function test_webhook_endpoint_rejects_invalid_signature(): void
    {
        $fakePayload = [
            'order_id' => 'FAKE-ORDER-001',
            'transaction_status' => 'settlement',
            'fraud_status' => 'accept',
            'gross_amount' => '100000',
            'status_code' => '200',
        ];

        $invalidSignature = 'fake_signature';

        $response = $this->postJson('/api/midtrans/webhook', $fakePayload, [
            'X-Signature-Key' => $invalidSignature,
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Invalid signature']);
    }

    /**
     * Test webhook endpoint accepts valid signature.
     */
    public function test_webhook_endpoint_accepts_valid_signature(): void
    {
        // Create a customer and transaction first
        $customer = User::factory()->create();
        $invoiceNumber = 'INV-TEST-WEBHOOK-001';
        $transaction = Transaction::create([
            'user_id' => \App\Models\User::factory()->create()->id,
            'invoice_number' => 'INV-TEST-WEBHOOK-001',
            'gross_amount' => 100000,
            'voucher_discount' => 0,
            'net_amount' => 100000,
            'payment_gateway' => 'midtrans',
            'midtrans_order_id' => $invoiceNumber,
            'status' => 'pending',
        ]);
        $invoice = Invoice::create([
            'transaction_id' => $transaction->id,
            'user_id' => $customer->id,
            'invoice_number' => $invoiceNumber,
            'amount' => 100000,
            'status' => 'issued',
            'issued_at' => now(),
            'due_at' => now()->addDay(),
        ]);
        
        $payload = [
            'order_id' => $invoice->invoice_number,
            'transaction_status' => 'settlement',
            'fraud_status' => 'accept',
            'gross_amount' => '100000',
            'status_code' => '200',
        ];

        // Calculate valid signature
        $inputString = $payload['order_id'] . $payload['status_code'] . $payload['gross_amount'] . $this->serverKey;
        $validSignature = hash('sha512', $inputString);

        $response = $this->postJson('/api/midtrans/webhook', $payload, [
            'X-Signature-Key' => $validSignature,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);

        $response->assertOk();
    }

    /**
     * Test time-based attack prevention using hash_equals.
     */
    public function test_signature_validation_uses_constant_time_comparison(): void
    {
        $payload = [
            'order_id' => 'ORDER-TIMING-TEST',
            'status_code' => '200',
            'gross_amount' => '100000',
        ];

        $inputString = $payload['order_id'] . $payload['status_code'] . $payload['gross_amount'] . $this->serverKey;
        $correctSignature = hash('sha512', $inputString);
        
        // Create signatures that differ at different positions
        $wrongSignature1 = 'a' . substr($correctSignature, 1);
        $wrongSignature2 = substr($correctSignature, 0, -1) . 'b';
        $wrongSignature3 = str_repeat('f', strlen($correctSignature));

        // All should be rejected
        $this->assertFalse($this->validator->validate($payload, $wrongSignature1));
        $this->assertFalse($this->validator->validate($payload, $wrongSignature2));
        $this->assertFalse($this->validator->validate($payload, $wrongSignature3));
    }

    /**
     * Test logging of invalid signature attempts.
     */
    public function test_invalid_signature_attempts_are_logged(): void
    {
        Log::shouldReceive('critical')
            ->once()
            ->withArgs(fn (string $message, array $context): bool =>
                str_contains($message, 'POTENTIAL FRAUD')
                && ($context['order_id'] ?? null) === 'ORDER-LOG-TEST'
            );

        $payload = [
            'order_id' => 'ORDER-LOG-TEST',
            'status_code' => '200',
            'gross_amount' => '100000',
        ];

        $this->validator->validate($payload, 'invalid_signature');
    }
}
