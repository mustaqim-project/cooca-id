<?php

namespace Tests\Feature\Affiliate;

use App\Models\Affiliator;
use App\Models\Customer;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\AffiliateCommission;
use App\Models\AffiliateWallet;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\License;
use App\Models\Setting;
use App\Services\Affiliate\CommissionCalculationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommissionCalculationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed settings
        Setting::updateOrCreate(
            ['key' => 'affiliate.commission_rate_level_1'],
            ['value' => '25', 'type' => 'float', 'group' => 'affiliate']
        );

        Setting::updateOrCreate(
            ['key' => 'affiliate.commission_rate_level_2'],
            ['value' => '5', 'type' => 'float', 'group' => 'affiliate']
        );
    }

    public function test_level_1_commission_calculation(): void
    {
        // Create affiliate hierarchy
        $topAffiliate = Affiliator::factory()->create([
            'name' => 'Top Affiliate',
            'email' => 'top@test.com',
            'status' => 'active',
        ]);

        $level1Affiliate = Affiliator::factory()->create([
            'name' => 'Level 1 Affiliate',
            'email' => 'level1@test.com',
            'status' => 'active',
            'parent_affiliator_id' => $topAffiliate->id,
        ]);

        // Create customer referred by level 1 affiliate
        $customer = Customer::factory()->create([
            'name' => 'Test Customer',
            'email' => 'customer@test.com',
            'affiliator_id' => $level1Affiliate->id,
        ]);

        // Create plan and subscription
        $category = ProductCategory::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $plan = SubscriptionPlan::factory()->create([
            'product_id' => $product->id,
            'name' => 'Test Plan',
            'price' => 100000,
            'duration_months' => 1,
            'is_active' => true,
        ]);
        $license = License::factory()->create([
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'subscription_plan_id' => $plan->id,
            'status' => 'active',
        ]);

        $subscription = Subscription::create([
            'customer_id' => $customer->id,
            'license_id' => $license->id,
            'subscription_plan_id' => $plan->id,
            'status' => 'active',
            'started_at' => now(),
            'expires_at' => now()->addYear(),
        ]);

        // Create transaction
        $transaction = Transaction::create([
            'customer_id' => $customer->id,
            'subscription_id' => $subscription->id,
            'invoice_number' => 'INV-AFF-001',
            'type' => 'subscription_new',
            'status' => 'paid',
            'gross_amount' => 100000,
            'net_amount' => 100000,
            'payment_method' => 'bank_transfer',
            'paid_at' => now(),
        ]);

        // Create wallets
        AffiliateWallet::create([
            'referred_by_id' => $level1Affiliate->id,
            'balance' => 0,
            'pending_balance' => 0,
        ]);
        AffiliateWallet::create([
            'referred_by_id' => $topAffiliate->id,
            'balance' => 0,
            'pending_balance' => 0,
        ]);

        // Calculate commission
        $service = app(CommissionCalculationService::class);
        $service->calculateForTransaction($transaction);

        // Assert commissions were created
        $this->assertDatabaseCount('affiliate_commissions', 2);

        // Level 1 commission (25% of 100000)
        $this->assertDatabaseHas('affiliate_commissions', [
            'referred_by_id' => $level1Affiliate->id,
            'level' => 1,
            'commission_percent' => 25.0,
            'commission_amount' => 25000,
            'status' => 'pending',
        ]);

        // Level 2 commission (5% of 100000) - goes to upline (topAffiliate)
        $this->assertDatabaseHas('affiliate_commissions', [
            'referred_by_id' => $topAffiliate->id,
            'level' => 2,
            'commission_percent' => 5.0,
            'commission_amount' => 5000,
            'status' => 'pending',
        ]);

        // Check wallet pending balance
        $level1Wallet = AffiliateWallet::where('referred_by_id', $level1Affiliate->id)->first();
        $this->assertEquals(25000, $level1Wallet->pending_balance);

        $topWallet = AffiliateWallet::where('referred_by_id', $topAffiliate->id)->first();
        $this->assertEquals(5000, $topWallet->pending_balance);
    }

    public function test_dynamic_commission_rate_from_settings(): void
    {
        // Update commission rate in settings
        Setting::updateOrCreate(
            ['key' => 'affiliate.commission_rate_level_1'],
            ['value' => '30', 'type' => 'float', 'group' => 'affiliate']
        );

        $service = app(CommissionCalculationService::class);

        // Use reflection to test private method
        $reflection = new \ReflectionClass($service);
        $method = $reflection->getMethod('getCommissionRate');
        $method->setAccessible(true);

        $rate = $method->invoke($service, 1);

        // Should return 0.30 (30%)
        $this->assertEquals(0.30, $rate);
    }

    public function test_no_commission_without_affiliate(): void
    {
        // Create customer without affiliate
        $customer = Customer::factory()->create([
            'name' => 'Test Customer',
            'email' => 'customer@test.com',
            'affiliator_id' => null,
        ]);

        $category = ProductCategory::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $plan = SubscriptionPlan::factory()->create([
            'product_id' => $product->id,
            'name' => 'Test Plan',
            'price' => 100000,
            'duration_months' => 1,
            'is_active' => true,
        ]);
        $license = License::factory()->create([
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'subscription_plan_id' => $plan->id,
            'status' => 'active',
        ]);

        $subscription = Subscription::create([
            'customer_id' => $customer->id,
            'license_id' => $license->id,
            'subscription_plan_id' => $plan->id,
            'status' => 'active',
            'started_at' => now(),
            'expires_at' => now()->addYear(),
        ]);

        $transaction = Transaction::create([
            'customer_id' => $customer->id,
            'subscription_id' => $subscription->id,
            'invoice_number' => 'INV-AFF-003',
            'type' => 'subscription_new',
            'status' => 'paid',
            'gross_amount' => 100000,
            'net_amount' => 100000,
            'payment_method' => 'bank_transfer',
            'paid_at' => now(),
        ]);

        // Calculate commission
        $service = app(CommissionCalculationService::class);
        $service->calculateForTransaction($transaction);

        // No commissions should be created
        $this->assertDatabaseCount('affiliate_commissions', 0);
    }
}
