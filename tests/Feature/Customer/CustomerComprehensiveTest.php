<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Models\Affiliator;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use App\Models\License;
use App\Models\Transaction;
use App\Models\Invoice;
use App\Models\Review;
use App\Models\Ticket;
use App\Models\Trial;
use App\Models\CompanyProfile;
use App\Models\Domain;
use App\Models\Tenant;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CustomerComprehensiveTest extends TestCase
{
    use RefreshDatabase;

    private Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->customer = Customer::factory()->create([
            'name' => 'Test Customer',
            'email' => 'customer@cooca.id',
            'password' => Hash::make('password123'),
        ]);
        $this->customer->forceFill([
            'email_verified_at' => now(),
            'status' => 'active',
        ])->save();
    }

    /** @test */
    public function customer_can_view_dashboard()
    {
        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('customer.dashboard'));

        $response->assertStatus(200);
    }

    /** @test */
    public function customer_can_view_products()
    {
        $category = ProductCategory::factory()->create();
        Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
        ]);

        // List products
        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('customer.products.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function customer_can_view_subscriptions()
    {
        $category = ProductCategory::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $plan = SubscriptionPlan::factory()->create(['product_id' => $product->id]);
        $license = License::factory()->create([
            'customer_id' => $this->customer->id,
            'product_id' => $product->id,
        ]);

        Subscription::create([
            'customer_id' => $this->customer->id,
            'license_id' => $license->id,
            'subscription_plan_id' => $plan->id,
            'status' => 'active',
        ]);

        // List subscriptions
        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('customer.subscriptions.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function customer_can_view_payments()
    {
        $category = ProductCategory::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $plan = SubscriptionPlan::factory()->create(['product_id' => $product->id]);
        $license = License::factory()->create([
            'customer_id' => $this->customer->id,
            'product_id' => $product->id,
        ]);
        $subscription = Subscription::create([
            'customer_id' => $this->customer->id,
            'license_id' => $license->id,
            'subscription_plan_id' => $plan->id,
            'status' => 'active',
        ]);

        Transaction::create([
            'customer_id' => $this->customer->id,
            'subscription_id' => $subscription->id,
            'invoice_number' => 'INV-CUST-001',
            'type' => 'subscription_new',
            'status' => 'paid',
            'gross_amount' => 100000,
            'net_amount' => 100000,
            'payment_method' => 'bank_transfer',
            'paid_at' => now(),
        ]);

        // List payments
        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('customer.payments.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function customer_can_view_invoices()
    {
        $category = ProductCategory::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $plan = SubscriptionPlan::factory()->create(['product_id' => $product->id]);
        $license = License::factory()->create([
            'customer_id' => $this->customer->id,
            'product_id' => $product->id,
        ]);
        $subscription = Subscription::create([
            'customer_id' => $this->customer->id,
            'license_id' => $license->id,
            'subscription_plan_id' => $plan->id,
            'status' => 'active',
        ]);
        $transaction = Transaction::create([
            'customer_id' => $this->customer->id,
            'subscription_id' => $subscription->id,
            'invoice_number' => 'INV-CUST-002',
            'type' => 'subscription_new',
            'status' => 'paid',
            'gross_amount' => 100000,
            'net_amount' => 100000,
            'payment_method' => 'bank_transfer',
            'paid_at' => now(),
        ]);

        Invoice::create([
            'customer_id' => $this->customer->id,
            'transaction_id' => $transaction->id,
            'invoice_number' => 'INV-001',
            'amount' => 100000,
            'status' => 'paid',
        ]);

        // List invoices
        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('customer.invoices.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function customer_can_view_licenses()
    {
        $category = ProductCategory::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        License::factory()->create([
            'customer_id' => $this->customer->id,
            'product_id' => $product->id,
            'status' => 'active',
        ]);

        // List licenses
        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('customer.licenses.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function customer_can_manage_profile()
    {
        // View profile edit page
        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('customer.profile.edit'));
        $response->assertStatus(200);

        // Update profile
        $response = $this->actingAs($this->customer, 'customer')
            ->put(route('customer.profile.update'), [
                'name' => 'Updated Customer',
                'email' => 'customer@cooca.id',
            ]);
        $response->assertSessionHasNoErrors();
        $this->assertEquals('Updated Customer', $this->customer->fresh()->name);

        // Update password
        $response = $this->actingAs($this->customer, 'customer')
            ->put(route('customer.profile.password.update'), [
                'current_password' => 'password123',
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function customer_can_manage_reviews()
    {
        $category = ProductCategory::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        // List my reviews
        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('customer.reviews.index'));
        $response->assertStatus(200);

        // Create review
        $response = $this->actingAs($this->customer, 'customer')
            ->post(route('customer.reviews.store'), [
                'product_id' => $product->id,
                'rating' => 5,
                'content' => 'Excellent product!',
            ]);
        $response->assertSessionHasNoErrors();

        $review = Review::where('reviewer_id', $this->customer->id)->first();
        $this->assertNotNull($review);
        $this->assertEquals(5, $review->rating);

        // Update review
        $response = $this->actingAs($this->customer, 'customer')
            ->put(route('customer.reviews.update', $review), [
                'rating' => 4,
                'content' => 'Good product!',
            ]);
        $response->assertSessionHasNoErrors();
        $this->assertEquals(4, $review->fresh()->rating);

        // Delete review
        $response = $this->actingAs($this->customer, 'customer')
            ->delete(route('customer.reviews.destroy', $review));
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function customer_can_manage_company_profile()
    {
        // View company profile edit page
        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('customer.company-profile.edit'));
        $response->assertStatus(200);

        // Update company profile
        $response = $this->actingAs($this->customer, 'customer')
            ->put(route('customer.company-profile.update'), [
                'company_name' => 'Test Company',
                'business_type' => 'technology',
                'address' => 'Test Address',
                'phone' => '08123456789',
            ]);
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function customer_can_manage_tickets()
    {
        // List tickets
        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('customer.tickets.index'));
        $response->assertStatus(200);

        // Create ticket
        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('customer.tickets.create'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->customer, 'customer')
            ->post(route('customer.tickets.store'), [
                'subject' => 'Test Ticket',
                'description' => 'Test description',
                'priority' => 'medium',
            ]);
        $response->assertSessionHasNoErrors();

        $ticket = Ticket::where('customer_id', $this->customer->id)->first();
        $this->assertNotNull($ticket);

        // Show ticket
        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('customer.tickets.show', $ticket));
        $response->assertStatus(200);
    }

    /** @test */
    public function customer_can_manage_trials()
    {
        // List trials
        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('customer.trials.index'));
        $response->assertStatus(200);

        // Create trial
        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('customer.trials.create'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->customer, 'customer')
            ->post(route('customer.trials.store'), [
                'product_name' => 'Test Product',
                'notes' => 'Test trial notes',
            ]);
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function customer_can_manage_domains()
    {
        // List domains
        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('customer.domains.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function customer_can_view_projects()
    {
        // List projects
        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('customer.projects.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function customer_with_referral_has_affiliator()
    {
        $affiliator = Affiliator::factory()->create([
            'status' => 'active',
        ]);

        $customerWithReferral = Customer::factory()->create([
            'affiliator_id' => $affiliator->id,
        ]);

        $this->assertNotNull($customerWithReferral->affiliator);
        $this->assertEquals($affiliator->id, $customerWithReferral->affiliator_id);
    }

    /** @test */
    public function customer_cannot_access_admin_routes()
    {
        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('admin.dashboard'));
        $response->assertStatus(302); // Redirected to admin login
    }

    /** @test */
    public function customer_cannot_access_affiliator_routes()
    {
        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('affiliator.dashboard'));
        $response->assertStatus(302); // Redirected to affiliator login
    }

    /** @test */
    public function unverified_customer_cannot_access_dashboard()
    {
        $unverifiedCustomer = Customer::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $response = $this->actingAs($unverifiedCustomer, 'customer')
            ->get(route('customer.dashboard'));

        // Should redirect to email verification notice
        $response->assertStatus(302);
    }

    /** @test */
    public function customer_can_view_product_details()
    {
        $category = ProductCategory::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->customer, 'customer')
            ->get(route('customer.products.show', $product->slug));
        $response->assertStatus(200);
    }
}
