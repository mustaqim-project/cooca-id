<?php

namespace Tests\Feature\Payment;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MidtransVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('services.midtrans.server_key', 'SB-Mid-server-TESTKEY12345');
        Config::set('services.midtrans.client_key', 'SB-Mid-client-TESTKEY12345');
        Config::set('services.midtrans.sandbox', true);
    }

    public function test_midtrans_dummy_verifier_account_can_login_and_access_checkout()
    {
        $customer = Customer::create([
            'name'              => 'Midtrans Verifikator',
            'email'             => 'midtrans.verification@cooca.id',
            'phone'             => '6288899992222',
            'password'          => Hash::make('Midtrans2026!'),
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
            'business_name'     => 'Midtrans Verifikator',
        ]);

        $response = $this->post('/customer/login', [
            'email'    => 'midtrans.verification@cooca.id',
            'password' => 'Midtrans2026!',
        ]);

        $this->assertAuthenticatedAs($customer, 'customer');
    }

    public function test_midtrans_webhook_v1_endpoint_handles_settlement_notifications()
    {
        $customer = Customer::create([
            'name'              => 'Midtrans Verifikator',
            'email'             => 'midtrans.verification@cooca.id',
            'phone'             => '6288899992222',
            'password'          => Hash::make('Midtrans2026!'),
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
        ]);

        $orderId = 'INV-MIDTRANS-TEST1';
        $transaction = Transaction::create([
            'customer_id'       => $customer->id,
            'type'              => 'subscription',
            'invoice_number'    => $orderId,
            'gross_amount'      => 150000,
            'net_amount'        => 150000,
            'payment_gateway'   => 'midtrans',
            'midtrans_order_id' => $orderId,
            'status'            => 'pending',
        ]);

        Invoice::create([
            'transaction_id' => $transaction->id,
            'invoice_number' => $orderId,
            'customer_id'    => $customer->id,
            'amount'         => 150000,
            'status'         => Invoice::STATUS_ISSUED,
            'issued_at'      => now(),
        ]);

        $serverKey = config('services.midtrans.server_key');
        $statusCode = '200';
        $grossAmount = '150000.00';
        $signatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        $payload = [
            'transaction_time'   => now()->toDateTimeString(),
            'transaction_status' => 'settlement',
            'status_message'     => 'midtrans response is good',
            'status_code'        => $statusCode,
            'signature_key'      => $signatureKey,
            'payment_type'       => 'bank_transfer',
            'order_id'           => $orderId,
            'gross_amount'       => $grossAmount,
            'fraud_status'       => 'accept',
            'transaction_id'     => 'midtrans-tx-999',
        ];

        $response = $this->postJson('/api/v1/midtrans/webhook', $payload);
        $response->assertStatus(200);

        $this->assertDatabaseHas('transactions', [
            'id'     => $transaction->id,
            'status' => 'paid',
        ]);
    }

    public function test_midtrans_redirect_urls_respond_successfully()
    {
        $customer = Customer::create([
            'name'              => 'Midtrans Verifikator',
            'email'             => 'midtrans.verification@cooca.id',
            'phone'             => '6288899992222',
            'password'          => Hash::make('Midtrans2026!'),
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
        ]);

        $res1 = $this->actingAs($customer, 'customer')->get('/customer/payments/success');
        $this->assertTrue(in_array($res1->getStatusCode(), [200, 302]));

        $res2 = $this->actingAs($customer, 'customer')->get('/customer/payments/pending');
        $this->assertTrue(in_array($res2->getStatusCode(), [200, 302]));

        $res3 = $this->actingAs($customer, 'customer')->get('/customer/payments/failed');
        $this->assertTrue(in_array($res3->getStatusCode(), [200, 302]));
    }
}
