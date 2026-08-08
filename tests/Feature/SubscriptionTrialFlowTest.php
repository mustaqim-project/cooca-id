<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Product;
use App\Models\SubscriptionPlan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SubscriptionTrialFlowTest extends TestCase
{
    // We won't use RefreshDatabase so we test against the real dev DB
    use RefreshDatabase;

    public function test_subscribe_flow_and_trial_flow()
    {
        $customer = Customer::firstOrCreate(
            ['email' => 'realtest_test_flow@cooca.id'],
            [
                'name' => 'Test Flow',
                'password' => Hash::make('password'),
                'phone' => '08999999999',
                'phone_verified_at' => now(),
            ]
        );
        $customer->markEmailAsVerified();
        $customer->update(['phone_verified_at' => now()]);

        // Ensure company profile exists
        $profile = $customer->companyProfile ?? $customer->companyProfile()->create([
            'company_name' => 'Flow Test Company',
            'phone' => '08123456789',
            'address' => 'Test Address',
        ]);

        $this->actingAs($customer, 'customer');

        // Get a product
        $product = Product::first();
        if (!$product) {
            $this->markTestSkipped('No product available to test.');
        }

        // Test Trial Form Request
        $response = $this->get('/customer/trials/create?product_slug=' . $product->slug);
        if ($response->status() === 302) {
            file_put_contents('debug.txt', "REDIRECTED TO: " . $response->headers->get('Location') . "\n");
        }
        $response->assertStatus(200);
        $this->assertStringContainsString('Request 14-Day Free Trial', $response->getContent());

        // Test Trial Submit
        $postTrial = $this->post('/customer/trials', [
            'product_id' => $product->id,
            'business_name' => 'Flow Test Business',
            'requested_subdomain' => 'test-subdomain-123',
            'notes' => 'Testing trial flow from script',
        ]);
        $postTrial->assertRedirect();
        
        $this->assertDatabaseHas('erp_requests', [
            'customer_id' => $customer->id,
            'product_id' => $product->id,
        ]);
        echo "Trial Flow: OK\n";

        // Test Subscribe Form Request
        $plan = $product->subscriptionPlans()->first();
        if (!$plan) {
            $this->markTestSkipped('No subscription plan available for product.');
        }

        $subGet = $this->get('/customer/subscriptions/create?product_slug=' . $product->slug);
        $subGet->assertStatus(200);
        $this->assertStringContainsString('Konfigurasi Langganan', $subGet->getContent());

        // Test Subscribe Submit
        $postSub = $this->post('/customer/subscriptions', [
            'product_slug' => $product->slug,
            'subscription_plan_id' => $plan->id,
            'domain' => 'test-domain-' . rand(1000, 9999),
        ]);
        
        // This should redirect to the invoice/payment page
        $postSub->assertRedirect();
        
        $this->assertDatabaseHas('subscriptions', [
            'customer_id' => $customer->id,
            'subscription_plan_id' => $plan->id,
        ]);
        
        $subscription = \App\Models\Subscription::where('customer_id', $customer->id)->latest()->first();
        
        echo "Subscribe Flow: OK\n";
    }
}
