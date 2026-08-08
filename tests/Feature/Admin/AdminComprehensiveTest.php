<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Customer;
use App\Models\Affiliator;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use App\Models\License;
use App\Models\Transaction;
use App\Models\Voucher;
use App\Models\Invoice;
use App\Models\Ticket;
use App\Models\Review;
use App\Models\BlogPost;
use App\Models\Page;
use App\Models\Faq;
use App\Models\Testimonial;
use App\Models\Setting;
use App\Models\AffiliateWithdrawal;
use App\Models\AffiliateCommission;
use App\Models\ErpRequest;
use App\Models\Trial;
use App\Models\EmailCampaign;
use App\Models\EmailTemplate;
use App\Models\ApiIntegration;
use App\Models\Deal;
use App\Models\Pipeline;
use App\Models\Stage;
use App\Models\Project;
use App\Models\ProjectTask;
use App\Models\AuditLog;
use App\Models\LicenseAppeal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminComprehensiveTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@cooca.id',
            'password' => Hash::make('password123'),
        ]);
    }

    /** @test */
    public function admin_can_view_dashboard()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_manage_profile()
    {
        // View profile edit page
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.profile.edit'));
        $response->assertStatus(200);

        // Update profile
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.profile.update'), [
                'name' => 'Updated Admin',
                'email' => 'admin@cooca.id',
            ]);
        $response->assertSessionHasNoErrors();
        $this->assertEquals('Updated Admin', $this->admin->fresh()->name);
    }

    /** @test */
    public function admin_can_manage_products()
    {
        $category = ProductCategory::factory()->create();

        // List products
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.products.index'));
        $response->assertStatus(200);

        // Create product
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.products.create'));
        $response->assertStatus(200);

        $productData = [
            'category_id' => $category->id,
            'name' => 'Test Product',
            'slug' => 'test-product',
            'description' => 'Test description',
            'base_price' => 100000,
            'is_active' => true,
        ];
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.products.store'), $productData);
        $response->assertSessionHasNoErrors();

        $product = Product::where('slug', 'test-product')->first();
        $this->assertNotNull($product);

        // Show product
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.products.show', $product));
        $response->assertStatus(200);

        // Edit product
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.products.edit', $product));
        $response->assertStatus(200);

        // Update product
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.products.update', $product), [
                'category_id' => $category->id,
                'name' => 'Updated Product',
                'slug' => 'updated-product',
                'description' => 'Updated description',
                'base_price' => 200000,
                'is_active' => true,
            ]);
        $response->assertSessionHasNoErrors();
        $this->assertEquals('Updated Product', $product->fresh()->name);

        // Delete product
        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.products.destroy', $product));
        $response->assertSessionHasNoErrors();
        $this->assertSoftDeleted($product);
    }

    /** @test */
    public function admin_can_manage_subscription_plans()
    {
        $category = ProductCategory::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        // List plans
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.products.plans.index', $product));
        $response->assertStatus(200);

        // Create plan
        $planData = [
            'name' => 'Basic Plan',
            'price' => 500000,
            'duration_months' => 1,
            'is_active' => true,
        ];
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.products.plans.store', $product), $planData);
        $response->assertSessionHasNoErrors();

        $plan = SubscriptionPlan::where('name', 'Basic Plan')->first();
        $this->assertNotNull($plan);

        // Update plan
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.products.plans.update', [$product, $plan]), [
                'name' => 'Updated Plan',
                'price' => 750000,
                'duration_months' => 3,
                'is_active' => true,
            ]);
        $response->assertSessionHasNoErrors();
        $this->assertEquals('Updated Plan', $plan->fresh()->name);

        // Toggle plan
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.products.plans.toggle', [$product, $plan]));
        $response->assertSessionHasNoErrors();

        // Delete plan
        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.products.plans.destroy', [$product, $plan]));
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function admin_can_manage_customers()
    {
        // List customers
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.customers.index'));
        $response->assertStatus(200);

        // Create customer
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.customers.create'));
        $response->assertStatus(200);

        $customerData = [
            'name' => 'Test Customer',
            'email' => 'customer@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.customers.store'), $customerData);
        $response->assertSessionHasNoErrors();

        $customer = Customer::where('email', 'customer@test.com')->first();
        $this->assertNotNull($customer);

        // Show customer
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.customers.show', $customer));
        $response->assertStatus(200);

        // Edit customer
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.customers.edit', $customer));
        $response->assertStatus(200);

        // Update customer
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.customers.update', $customer), [
                'name' => 'Updated Customer',
                'email' => 'customer@test.com',
            ]);
        $response->assertSessionHasNoErrors();
        $this->assertEquals('Updated Customer', $customer->fresh()->name);

        // Delete customer
        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.customers.destroy', $customer));
        $response->assertSessionHasNoErrors();
        $this->assertSoftDeleted($customer);
    }

    /** @test */
    public function admin_can_manage_affiliators()
    {
        // List affiliators
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.affiliators.index'));
        $response->assertStatus(200);

        // Create affiliator
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.affiliators.create'));
        $response->assertStatus(200);

        $affiliatorData = [
            'name' => 'Test Affiliator',
            'email' => 'affiliator@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.affiliators.store'), $affiliatorData);
        $response->assertSessionHasNoErrors();

        $affiliator = Affiliator::where('email', 'affiliator@test.com')->first();
        $this->assertNotNull($affiliator);

        // Show affiliator
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.affiliators.show', $affiliator));
        $response->assertStatus(200);

        // Edit affiliator
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.affiliators.edit', $affiliator));
        $response->assertStatus(200);

        // Update affiliator
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.affiliators.update', $affiliator), [
                'name' => 'Updated Affiliator',
                'email' => 'affiliator@test.com',
            ]);
        $response->assertSessionHasNoErrors();
        $this->assertEquals('Updated Affiliator', $affiliator->fresh()->name);

        // Suspend affiliator
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.affiliators.suspend', $affiliator));
        $response->assertSessionHasNoErrors();
        $this->assertEquals('suspended', $affiliator->fresh()->status);

        // Reactivate affiliator
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.affiliators.reactivate', $affiliator));
        $response->assertSessionHasNoErrors();
        $this->assertEquals('active', $affiliator->fresh()->status);

        // Delete affiliator
        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.affiliators.destroy', $affiliator));
        $response->assertSessionHasNoErrors();
        $this->assertSoftDeleted($affiliator);
    }

    /** @test */
    public function admin_can_manage_licenses()
    {
        $category = ProductCategory::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $customer = Customer::factory()->create();

        // List licenses
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.licenses.index'));
        $response->assertStatus(200);

        // Generate license
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.licenses.generate'), [
                'customer_id' => $customer->id,
                'product_id' => $product->id,
            ]);
        $response->assertSessionHasNoErrors();

        $license = License::first();
        $this->assertNotNull($license);

        // Show license
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.licenses.show', $license));
        $response->assertStatus(200);

        // Activate license
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.licenses.activate', $license));
        $response->assertSessionHasNoErrors();

        // Revoke license
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.licenses.revoke', $license));
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function admin_can_manage_subscriptions()
    {
        $category = ProductCategory::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $plan = SubscriptionPlan::factory()->create(['product_id' => $product->id]);
        $customer = Customer::factory()->create();
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

        // List subscriptions
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.subscriptions.index'));
        $response->assertStatus(200);

        // Show subscription
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.subscriptions.show', $subscription));
        $response->assertStatus(200);

        // Cancel subscription
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.subscriptions.cancel', $subscription));
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function admin_can_manage_transactions()
    {
        $category = ProductCategory::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $plan = SubscriptionPlan::factory()->create(['product_id' => $product->id]);
        $customer = Customer::factory()->create();
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
            'invoice_number' => 'INV-TEST-001',
            'type' => 'subscription_new',
            'status' => 'paid',
            'gross_amount' => 100000,
            'net_amount' => 100000,
            'payment_method' => 'bank_transfer',
            'paid_at' => now(),
        ]);

        // List transactions
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.transactions.index'));
        $response->assertStatus(200);

        // Show transaction
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.transactions.show', $transaction));
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_manage_vouchers()
    {
        // List vouchers
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.vouchers.index'));
        $response->assertStatus(200);

        // Create voucher
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.vouchers.create'));
        $response->assertStatus(200);

        $voucherData = [
            'code' => 'TEST50',
            'type' => 'percentage',
            'value' => 50,
            'max_uses' => 100,
            'is_active' => true,
        ];
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.vouchers.store'), $voucherData);
        $response->assertSessionHasNoErrors();

        $voucher = Voucher::where('code', 'TEST50')->first();
        $this->assertNotNull($voucher);

        // Show voucher
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.vouchers.show', $voucher));
        $response->assertStatus(200);

        // Edit voucher
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.vouchers.edit', $voucher));
        $response->assertStatus(200);

        // Update voucher
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.vouchers.update', $voucher), [
                'code' => 'TEST50',
                'type' => 'percentage',
                'value' => 25,
                'max_uses' => 50,
                'is_active' => true,
            ]);
        $response->assertSessionHasNoErrors();
        $this->assertEquals(25, $voucher->fresh()->value);

        // Deactivate voucher
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.vouchers.deactivate', $voucher));
        $response->assertSessionHasNoErrors();
        $this->assertFalse((bool) $voucher->fresh()->is_active);

        // Activate voucher
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.vouchers.activate', $voucher));
        $response->assertSessionHasNoErrors();
        $this->assertTrue((bool) $voucher->fresh()->is_active);

        // Delete voucher
        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.vouchers.destroy', $voucher));
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function admin_can_manage_settlements()
    {
        $affiliator = Affiliator::factory()->create(['status' => 'active']);
        $settlement = AffiliateWithdrawal::create([
            'affiliator_id' => $affiliator->id,
            'amount' => 50000,
            'status' => 'pending',
            'bank_account' => '1234567890',
            'bank_name' => 'Test Bank',
        ]);

        // List settlements
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.settlements.index'));
        $response->assertStatus(200);

        // Show settlement
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.settlements.show', $settlement));
        $response->assertStatus(200);

        // Approve settlement
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.settlements.approve', $settlement));
        $response->assertSessionHasNoErrors();

        // Mark as paid
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.settlements.markAsPaid', $settlement));
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function admin_can_manage_cms_pages()
    {
        // List pages
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.cms.pages.index'));
        $response->assertStatus(200);

        // Create page
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.cms.pages.create'));
        $response->assertStatus(200);

        $pageData = [
            'title' => 'Test Page',
            'slug' => 'test-page',
            'content' => 'Test content',
            'is_published' => true,
        ];
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.cms.pages.store'), $pageData);
        $response->assertSessionHasNoErrors();

        $page = Page::where('slug', 'test-page')->first();
        $this->assertNotNull($page);

        // Edit page
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.cms.pages.edit', $page));
        $response->assertStatus(200);

        // Update page
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.cms.pages.update', $page), [
                'title' => 'Updated Page',
                'slug' => 'updated-page',
                'content' => 'Updated content',
                'is_published' => true,
            ]);
        $response->assertSessionHasNoErrors();
        $this->assertEquals('Updated Page', $page->fresh()->title);

        // Delete page
        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.cms.pages.destroy', $page));
        $response->assertSessionHasNoErrors();
        $this->assertSoftDeleted($page);
    }

    /** @test */
    public function admin_can_manage_blog_posts()
    {
        // List blog posts
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.blog.index'));
        $response->assertStatus(200);

        // Create blog post
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.blog.create'));
        $response->assertStatus(200);

        $postData = [
            'title' => 'Test Blog Post',
            'slug' => 'test-blog-post',
            'content' => 'Test blog content',
            'is_published' => true,
        ];
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.blog.store'), $postData);
        $response->assertSessionHasNoErrors();

        $post = BlogPost::where('slug', 'test-blog-post')->first();
        $this->assertNotNull($post);

        // Show blog post
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.blog.show', $post));
        $response->assertStatus(200);

        // Edit blog post
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.blog.edit', $post));
        $response->assertStatus(200);

        // Update blog post
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.blog.update', $post), [
                'title' => 'Updated Blog Post',
                'slug' => 'updated-blog-post',
                'content' => 'Updated content',
                'is_published' => true,
            ]);
        $response->assertSessionHasNoErrors();
        $this->assertEquals('Updated Blog Post', $post->fresh()->title);

        // Delete blog post
        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.blog.destroy', $post));
        $response->assertSessionHasNoErrors();
        $this->assertSoftDeleted($post);
    }

    /** @test */
    public function admin_can_manage_tickets()
    {
        $customer = Customer::factory()->create();

        $ticket = Ticket::create([
            'customer_id' => $customer->id,
            'subject' => 'Test Ticket',
            'description' => 'Test description',
            'priority' => 'medium',
            'status' => 'open',
        ]);

        // List tickets
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.tickets.index'));
        $response->assertStatus(200);

        // Show ticket
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.tickets.show', $ticket));
        $response->assertStatus(200);

        // Reply to ticket
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.tickets.reply', $ticket), [
                'message' => 'Test reply',
            ]);
        $response->assertSessionHasNoErrors();

        // Resolve ticket
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.tickets.resolve', $ticket));
        $response->assertSessionHasNoErrors();

        // Close ticket
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.tickets.close', $ticket));
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function admin_can_manage_reviews()
    {
        $customer = Customer::factory()->create();
        $category = ProductCategory::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $review = Review::create([
            'reviewer_id' => $customer->id,
            'reviewer_type' => 'customer',
            'product_id' => $product->id,
            'rating' => 4,
            'content' => 'Great product!',
            'status' => 'pending',
        ]);

        // List reviews
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.reviews.index'));
        $response->assertStatus(200);

        // Show review
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.reviews.show', $review));
        $response->assertStatus(200);

        // Approve review
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.reviews.approve', $review));
        $response->assertSessionHasNoErrors();

        // Reject review
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.reviews.reject', $review));
        $response->assertSessionHasNoErrors();

        // Delete review
        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.reviews.destroy', $review));
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function admin_can_manage_settings()
    {
        // View settings
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.settings.index'));
        $response->assertStatus(200);

        // Update settings
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.settings.update'), [
                'app_name' => 'Cooca ID',
                'app_description' => 'Test description',
            ]);
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function admin_can_manage_product_categories()
    {
        // List categories
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.product-categories.index'));
        $response->assertStatus(200);

        // Create category
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.product-categories.create'));
        $response->assertStatus(200);

        $categoryData = [
            'name' => 'Test Category',
            'slug' => 'test-category',
            'is_active' => true,
        ];
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.product-categories.store'), $categoryData);
        $response->assertSessionHasNoErrors();

        $category = ProductCategory::where('slug', 'test-category')->first();
        $this->assertNotNull($category);

        // Show category
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.product-categories.show', $category));
        $response->assertStatus(200);

        // Edit category
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.product-categories.edit', $category));
        $response->assertStatus(200);

        // Update category
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.product-categories.update', $category), [
                'name' => 'Updated Category',
                'slug' => 'updated-category',
                'is_active' => true,
            ]);
        $response->assertSessionHasNoErrors();
        $this->assertEquals('Updated Category', $category->fresh()->name);

        // Toggle active
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.product-categories.toggle-active', $category));
        $response->assertSessionHasNoErrors();

        // Delete category
        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.product-categories.destroy', $category));
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function admin_can_manage_faqs()
    {
        // List FAQs
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.faqs.index'));
        $response->assertStatus(200);

        // Create FAQ
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.faqs.create'));
        $response->assertStatus(200);

        $faqData = [
            'question' => 'Test question?',
            'answer' => 'Test answer',
            'is_published' => true,
        ];
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.faqs.store'), $faqData);
        $response->assertSessionHasNoErrors();

        $faq = Faq::where('question', 'Test question?')->first();
        $this->assertNotNull($faq);

        // Edit FAQ
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.faqs.edit', $faq));
        $response->assertStatus(200);

        // Update FAQ
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.faqs.update', $faq), [
                'question' => 'Updated question?',
                'answer' => 'Updated answer',
                'is_published' => true,
            ]);
        $response->assertSessionHasNoErrors();
        $this->assertEquals('Updated question?', $faq->fresh()->question);

        // Delete FAQ
        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.faqs.destroy', $faq));
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function admin_can_manage_testimonials()
    {
        // List testimonials
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.testimonials.index'));
        $response->assertStatus(200);

        // Create testimonial
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.testimonials.create'));
        $response->assertStatus(200);

        $testimonialData = [
            'name' => 'Test User',
            'content' => 'Great service!',
            'rating' => 5,
            'is_published' => true,
        ];
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.testimonials.store'), $testimonialData);
        $response->assertSessionHasNoErrors();

        $testimonial = Testimonial::where('name', 'Test User')->first();
        $this->assertNotNull($testimonial);

        // Edit testimonial
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.testimonials.edit', $testimonial));
        $response->assertStatus(200);

        // Update testimonial
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.testimonials.update', $testimonial), [
                'name' => 'Updated User',
                'content' => 'Updated content',
                'rating' => 4,
                'is_published' => true,
            ]);
        $response->assertSessionHasNoErrors();
        $this->assertEquals('Updated User', $testimonial->fresh()->name);

        // Toggle featured
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.testimonials.toggle-featured', $testimonial));
        $response->assertSessionHasNoErrors();

        // Delete testimonial
        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.testimonials.destroy', $testimonial));
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function admin_can_manage_trials()
    {
        $customer = Customer::factory()->create();

        $trial = Trial::create([
            'customer_id' => $customer->id,
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        // List trials
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.trials.index'));
        $response->assertStatus(200);

        // Show trial
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.trials.show', $trial));
        $response->assertStatus(200);

        // Approve trial
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.trials.approve', $trial));
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function admin_can_manage_email_templates()
    {
        // List email templates
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.email-templates.index'));
        $response->assertStatus(200);

        // Create email template
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.email-templates.create'));
        $response->assertStatus(200);

        $templateData = [
            'name' => 'Welcome Email',
            'subject' => 'Welcome to Cooca ID',
            'content' => 'Welcome {{name}}!',
            'is_active' => true,
        ];
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.email-templates.store'), $templateData);
        $response->assertSessionHasNoErrors();

        $template = EmailTemplate::where('name', 'Welcome Email')->first();
        $this->assertNotNull($template);

        // Show template
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.email-templates.show', $template));
        $response->assertStatus(200);

        // Edit template
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.email-templates.edit', $template));
        $response->assertStatus(200);

        // Update template
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.email-templates.update', $template), [
                'name' => 'Updated Welcome Email',
                'subject' => 'Welcome!',
                'content' => 'Welcome {{name}} to Cooca!',
                'is_active' => true,
            ]);
        $response->assertSessionHasNoErrors();
        $this->assertEquals('Updated Welcome Email', $template->fresh()->name);

        // Toggle active
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.email-templates.toggle-active', $template));
        $response->assertSessionHasNoErrors();

        // Preview template
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.email-templates.preview', $template));
        $response->assertStatus(200);

        // Delete template
        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.email-templates.destroy', $template));
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function admin_can_manage_email_campaigns()
    {
        // List campaigns
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.email-campaigns.index'));
        $response->assertStatus(200);

        // Create campaign
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.email-campaigns.create'));
        $response->assertStatus(200);

        $campaignData = [
            'name' => 'Test Campaign',
            'subject' => 'Test Subject',
            'content' => 'Test content',
            'status' => 'draft',
        ];
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.email-campaigns.store'), $campaignData);
        $response->assertSessionHasNoErrors();

        $campaign = EmailCampaign::where('name', 'Test Campaign')->first();
        $this->assertNotNull($campaign);

        // Show campaign
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.email-campaigns.show', $campaign));
        $response->assertStatus(200);

        // Edit campaign
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.email-campaigns.edit', $campaign));
        $response->assertStatus(200);

        // Update campaign
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.email-campaigns.update', $campaign), [
                'name' => 'Updated Campaign',
                'subject' => 'Updated Subject',
                'content' => 'Updated content',
                'status' => 'draft',
            ]);
        $response->assertSessionHasNoErrors();
        $this->assertEquals('Updated Campaign', $campaign->fresh()->name);

        // Delete campaign
        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.email-campaigns.destroy', $campaign));
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function admin_can_manage_api_integrations()
    {
        $integration = ApiIntegration::create([
            'provider' => 'midtrans',
            'is_active' => false,
            'config' => ['server_key' => 'test'],
        ]);

        // List integrations
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.api-integrations.index'));
        $response->assertStatus(200);

        // Edit integration
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.api-integrations.edit', 'midtrans'));
        $response->assertStatus(200);

        // Update integration
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.api-integrations.update', 'midtrans'), [
                'config' => ['server_key' => 'updated_key'],
            ]);
        $response->assertSessionHasNoErrors();

        // Toggle integration
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.api-integrations.toggle', 'midtrans'));
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function admin_can_manage_audit_logs()
    {
        // List audit logs
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.audit-logs.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_manage_error_logs()
    {
        // List error logs
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.error-logs.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_manage_erp_requests()
    {
        $customer = Customer::factory()->create();
        $erpRequest = ErpRequest::create([
            'customer_id' => $customer->id,
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        // List ERP requests
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.erp-requests.index'));
        $response->assertStatus(200);

        // Show ERP request
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.erp-requests.show', $erpRequest));
        $response->assertStatus(200);

        // Approve ERP request
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.erp-requests.approve', $erpRequest));
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function admin_can_manage_landing_cms()
    {
        // View landing CMS
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.cms.landing.index'));
        $response->assertStatus(200);

        // Update landing CMS
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.cms.landing.update'), [
                'hero_title' => 'Updated Hero Title',
            ]);
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function admin_can_manage_pipelines_and_deals()
    {
        $pipeline = Pipeline::create([
            'name' => 'Test Pipeline',
            'description' => 'Test description',
        ]);

        $stage = Stage::create([
            'pipeline_id' => $pipeline->id,
            'name' => 'Test Stage',
            'order' => 1,
        ]);

        $customer = Customer::factory()->create();
        $deal = Deal::create([
            'pipeline_id' => $pipeline->id,
            'stage_id' => $stage->id,
            'customer_id' => $customer->id,
            'title' => 'Test Deal',
            'value' => 100000,
            'status' => 'open',
        ]);

        // List pipelines
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.pipelines.index'));
        $response->assertStatus(200);

        // List deals
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.deals.index'));
        $response->assertStatus(200);

        // List projects
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.projects.index'));
        $response->assertStatus(200);
    }
}
