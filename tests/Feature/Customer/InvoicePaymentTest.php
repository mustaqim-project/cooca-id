<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

final class InvoicePaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_initiate_invoice_payment(): void
    {
        Config::set('services.midtrans.server_key', 'SB-MIDTEST123');
        Config::set('services.midtrans.sandbox', true);

        Http::fake([
            'https://app.sandbox.midtrans.com/snap/v1/transactions' => Http::response([
                'token' => 'snap-token-123',
                'redirect_url' => 'https://app.sandbox.midtrans.com/snap/pay/snap-token-123',
                'order_id' => 'INV-TEST-001',
            ], 200),
        ]);

        $customer = Customer::factory()->create([
            'email' => 'customer@cooca.id',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
            'status' => 'active',
        ]);

        $transaction = Transaction::create([
            'customer_id' => $customer->id,
            'invoice_number' => 'INV-TEST-001',
            'type' => 'subscription_new',
            'status' => 'pending',
            'gross_amount' => 100000,
            'net_amount' => 100000,
            'payment_method' => 'midtrans',
            'payment_gateway' => 'midtrans',
        ]);

        $invoice = Invoice::create([
            'transaction_id' => $transaction->id,
            'customer_id' => $customer->id,
            'invoice_number' => 'INV-TEST-001',
            'amount' => 100000,
            'status' => 'issued',
            'issued_at' => now(),
            'due_at' => now()->addDays(7),
        ]);

        $response = $this->actingAs($customer, 'customer')
            ->post(route('customer.payments.store'), [
                'invoice_id' => $invoice->id,
            ]);

        $response->assertRedirect('https://app.sandbox.midtrans.com/snap/pay/snap-token-123');
    }
}
