<?php

namespace Tests\Feature\Affiliator;

use App\Models\Affiliator;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use App\Models\License;
use App\Models\Transaction;
use App\Models\Review;
use App\Models\AffiliateCommission;
use App\Models\AffiliateWallet;
use App\Models\AffiliateWithdrawal;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AffiliatorComprehensiveTest extends TestCase
{
    use RefreshDatabase;

    private Affiliator $affiliator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->affiliator = Affiliator::factory()->create([
            'name' => 'Test Affiliator',
            'email' => 'affiliator@cooca.id',
            'password' => Hash::make('password123'),
            'status' => 'active',
            'balance' => 0,
        ]);
    }

    /** @test */
    public function affiliator_can_view_dashboard()
    {
        $response = $this->actingAs($this->affiliator, 'affiliator')
            ->get(route('affiliator.dashboard'));

        $response->assertStatus(200);
    }

    /** @test */
    public function affiliator_can_view_referrals()
    {
        // Create some referred customers
        Customer::factory()->create([
            'affiliator_id' => $this->affiliator->id,
        ]);
        Customer::factory()->create([
            'affiliator_id' => $this->affiliator->id,
        ]);

        // List referrals
        $response = $this->actingAs($this->affiliator, 'affiliator')
            ->get(route('affiliator.referrals.index'));
        $response->assertStatus(200);

        // Referral stats
        $response = $this->actingAs($this->affiliator, 'affiliator')
            ->get(route('affiliator.referrals.stats'));
        $response->assertStatus(200);
    }

    /** @test */
    public function affiliator_can_view_commissions()
    {
        // Set up commission rate setting
        Setting::updateOrCreate(
            ['key' => 'affiliate.commission_rate_level_1'],
            ['value' => '25', 'type' => 'float', 'group' => 'affiliate']
        );

        // Create customer and transaction for commission
        $customer = Customer::factory()->create([
            'affiliator_id' => $this->affiliator->id,
        ]);

        $category = ProductCategory::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $plan = SubscriptionPlan::factory()->create(['product_id' => $product->id]);
        $license = License::factory()->create([
            'customer_id' => $customer->id,
            'product_id' => $product->id,
        ]);
        $subscription = Subscription::create([
            'customer_id' => $customer->id,
            'license_id' => $license->id,
            'subscription_plan_id' => $plan->id,
            'status' => 'active',
        ]);

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

        // Create affiliate wallet
        AffiliateWallet::create([
            'affiliator_id' => $this->affiliator->id,
            'balance' => 0,
            'pending_balance' => 25000,
        ]);

        // Create commission
        AffiliateCommission::create([
            'affiliator_id' => $this->affiliator->id,
            'transaction_id' => $transaction->id,
            'level' => 1,
            'gross_amount' => 100000,
            'commission_percent' => 25,
            'commission_amount' => 25000,
            'status' => 'pending',
        ]);

        // List commissions
        $response = $this->actingAs($this->affiliator, 'affiliator')
            ->get(route('affiliator.commissions.index'));
        $response->assertStatus(200);

        // Commission stats
        $response = $this->actingAs($this->affiliator, 'affiliator')
            ->get(route('affiliator.commissions.stats'));
        $response->assertStatus(200);
    }

    /** @test */
    public function affiliator_can_view_downlines()
    {
        // Create downline (sub-affiliator)
        Affiliator::factory()->create([
            'parent_affiliator_id' => $this->affiliator->id,
            'status' => 'active',
        ]);

        // List downlines
        $response = $this->actingAs($this->affiliator, 'affiliator')
            ->get(route('affiliator.downlines.index'));
        $response->assertStatus(200);

        // Downline tree
        $response = $this->actingAs($this->affiliator, 'affiliator')
            ->get(route('affiliator.downlines.tree'));
        $response->assertStatus(200);

        // Downline stats
        $response = $this->actingAs($this->affiliator, 'affiliator')
            ->get(route('affiliator.downlines.stats'));
        $response->assertStatus(200);
    }

    /** @test */
    public function affiliator_can_manage_withdrawals()
    {
        // Create wallet with balance
        AffiliateWallet::create([
            'affiliator_id' => $this->affiliator->id,
            'balance' => 100000,
            'pending_balance' => 0,
        ]);

        // List withdrawals
        $response = $this->actingAs($this->affiliator, 'affiliator')
            ->get(route('affiliator.withdrawals.index'));
        $response->assertStatus(200);

        // Create withdrawal page
        $response = $this->actingAs($this->affiliator, 'affiliator')
            ->get(route('affiliator.withdrawals.create'));
        $response->assertStatus(200);

        // Withdrawal history
        $response = $this->actingAs($this->affiliator, 'affiliator')
            ->get(route('affiliator.withdrawals.history'));
        $response->assertStatus(200);
    }

    /** @test */
    public function affiliator_can_manage_reviews()
    {
        // List reviews
        $response = $this->actingAs($this->affiliator, 'affiliator')
            ->get(route('affiliator.reviews.index'));
        $response->assertStatus(200);

        // My reviews
        $response = $this->actingAs($this->affiliator, 'affiliator')
            ->get(route('affiliator.reviews.my_reviews'));
        $response->assertStatus(200);
    }

    /** @test */
    public function affiliator_can_manage_profile()
    {
        // View profile edit page
        $response = $this->actingAs($this->affiliator, 'affiliator')
            ->get(route('affiliator.profile.edit'));
        $response->assertStatus(200);

        // Update profile
        $response = $this->actingAs($this->affiliator, 'affiliator')
            ->put(route('affiliator.profile.update'), [
                'name' => 'Updated Affiliator',
                'email' => 'affiliator@cooca.id',
            ]);
        $response->assertSessionHasNoErrors();
        $this->assertEquals('Updated Affiliator', $this->affiliator->fresh()->name);

        // Update bank account
        $response = $this->actingAs($this->affiliator, 'affiliator')
            ->put(route('affiliator.profile.bank_account.update'), [
                'bank_account' => '1234567890',
                'bank_name' => 'Test Bank',
            ]);
        $response->assertSessionHasNoErrors();

        // Update password
        $response = $this->actingAs($this->affiliator, 'affiliator')
            ->put(route('affiliator.profile.password.update'), [
                'current_password' => 'password123',
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function affiliator_can_view_marketing_materials()
    {
        // List marketing materials
        $response = $this->actingAs($this->affiliator, 'affiliator')
            ->get(route('affiliator.marketing_materials.index'));
        $response->assertStatus(200);

        // Banners
        $response = $this->actingAs($this->affiliator, 'affiliator')
            ->get(route('affiliator.marketing_materials.banners'));
        $response->assertStatus(200);

        // Links
        $response = $this->actingAs($this->affiliator, 'affiliator')
            ->get(route('affiliator.marketing_materials.links'));
        $response->assertStatus(200);
    }

    /** @test */
    public function affiliator_can_submit_appeal()
    {
        // Suspend the affiliator first
        $this->affiliator->update(['status' => 'suspended']);

        // View appeal page
        $response = $this->actingAs($this->affiliator, 'affiliator')
            ->get(route('affiliator.appeal.index'));
        $response->assertStatus(200);

        // Submit appeal
        $response = $this->actingAs($this->affiliator, 'affiliator')
            ->post(route('affiliator.appeal.store'), [
                'reason' => 'I want to be reactivated',
            ]);
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function affiliator_with_downlines_can_earn_level2_commission()
    {
        // Set up commission rate settings
        Setting::updateOrCreate(
            ['key' => 'affiliate.commission_rate_level_1'],
            ['value' => '25', 'type' => 'float', 'group' => 'affiliate']
        );
        Setting::updateOrCreate(
            ['key' => 'affiliate.commission_rate_level_2'],
            ['value' => '5', 'type' => 'float', 'group' => 'affiliate']
        );

        // Create downline
        $downline = Affiliator::factory()->create([
            'parent_affiliator_id' => $this->affiliator->id,
            'status' => 'active',
        ]);

        // Customer referred by downline
        $customer = Customer::factory()->create([
            'affiliator_id' => $downline->id,
        ]);

        $category = ProductCategory::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $plan = SubscriptionPlan::factory()->create(['product_id' => $product->id]);
        $license = License::factory()->create([
            'customer_id' => $customer->id,
            'product_id' => $product->id,
        ]);
        $subscription = Subscription::create([
            'customer_id' => $customer->id,
            'license_id' => $license->id,
            'subscription_plan_id' => $plan->id,
            'status' => 'active',
        ]);

        $transaction = Transaction::create([
            'customer_id' => $customer->id,
            'subscription_id' => $subscription->id,
            'invoice_number' => 'INV-AFF-002',
            'type' => 'subscription_new',
            'status' => 'paid',
            'gross_amount' => 100000,
            'net_amount' => 100000,
            'payment_method' => 'bank_transfer',
            'paid_at' => now(),
        ]);

        // Create wallet for upline
        AffiliateWallet::create([
            'affiliator_id' => $this->affiliator->id,
            'balance' => 0,
            'pending_balance' => 0,
        ]);

        // Check the wallet exists
        $wallet = AffiliateWallet::where('affiliator_id', $this->affiliator->id)->first();
        $this->assertNotNull($wallet);
    }

    /** @test */
    public function affiliator_cannot_access_admin_routes()
    {
        $response = $this->actingAs($this->affiliator, 'affiliator')
            ->get(route('admin.dashboard'));
        $response->assertStatus(302);
    }

    /** @test */
    public function affiliator_cannot_access_customer_routes()
    {
        $response = $this->actingAs($this->affiliator, 'affiliator')
            ->get(route('customer.dashboard'));
        $response->assertStatus(302);
    }

    /** @test */
    public function suspended_affiliator_cannot_access_dashboard()
    {
        $suspendedAffiliator = Affiliator::factory()->create([
            'status' => 'suspended',
        ]);

        $response = $this->actingAs($suspendedAffiliator, 'affiliator')
            ->get(route('affiliator.dashboard'));
        $response->assertStatus(302);
    }
}
