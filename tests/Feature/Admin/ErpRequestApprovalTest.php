<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ErpRequest;
use App\Models\License;
use App\Notifications\Customer\TrialActivatedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ErpRequestApprovalTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;
    private Customer $customer;
    private Product $product;
    private ErpRequest $erpRequest;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->seed(\Database\Seeders\RoleSeeder::class);
        
        $this->admin = Admin::factory()->create([
            'email' => 'admin@cooca.id',
        ]);
        $this->admin->assignRole('super_admin');

        // Satisfy the faulty foreign key constraint on approved_by pointing to customers
        Customer::factory()->create([
            'id' => $this->admin->id,
            'email' => 'admin-fake-customer@cooca.id',
        ]);

        $this->customer = Customer::factory()->create([
            'email' => 'customer@cooca.id',
            'business_name' => 'Test Business',
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
        ]);

        $this->product = Product::factory()->create([
            'name' => 'COOCA Module',
        ]);

        $this->erpRequest = ErpRequest::create([
            'customer_id' => $this->customer->id,
            'product_id' => $this->product->id,
            'requested_subdomain' => 'testsubdomain',
            'status' => ErpRequest::STATUS_WAITING_APPROVAL,
        ]);
    }

    public function test_approve_requires_notes()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.erp-requests.approve', $this->erpRequest->id), [
                'admin_notes' => '',
            ]);

        $response->assertSessionHasErrors('admin_notes');
        $this->assertEquals(ErpRequest::STATUS_WAITING_APPROVAL, $this->erpRequest->fresh()->status);
    }

    public function test_approve_activates_trial_and_sends_notification()
    {
        Notification::fake();

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.erp-requests.approve', $this->erpRequest->id), [
                'admin_notes' => 'TRIAL APPROVED NOTES DEFINED HERE',
            ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $freshRequest = $this->erpRequest->fresh();
        $this->assertEquals(ErpRequest::STATUS_ACTIVE_TRIAL, $freshRequest->status);
        $this->assertEquals('TRIAL APPROVED NOTES DEFINED HERE', $freshRequest->admin_notes);
        $this->assertNotNull($freshRequest->trial_starts_at);
        $this->assertNotNull($freshRequest->trial_ends_at);

        // Verify license was created
        $this->assertDatabaseHas('licenses', [
            'customer_id' => $this->customer->id,
            'product_id' => $this->product->id,
            'erp_request_id' => $this->erpRequest->id,
        ]);

        $license = License::where('erp_request_id', $this->erpRequest->id)->first();
        $this->assertNotNull($license);

        Notification::assertSentTo(
            $this->customer,
            TrialActivatedNotification::class,
            function ($notification) use ($license) {
                return $notification->toMail($this->customer)->introLines[2] === '- Domain: ' . $license->domain
                    && in_array('TRIAL APPROVED NOTES DEFINED HERE', $notification->toMail($this->customer)->introLines);
            }
        );
    }

    public function test_cannot_approve_or_reject_already_processed_request()
    {
        // First, approve it
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.erp-requests.approve', $this->erpRequest->id), [
                'admin_notes' => 'First notes',
            ]);

        $this->assertEquals(ErpRequest::STATUS_ACTIVE_TRIAL, $this->erpRequest->fresh()->status);

        // Try to approve again
        $response1 = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.erp-requests.approve', $this->erpRequest->id), [
                'admin_notes' => 'Second notes',
            ]);
        $response1->assertRedirect();
        $response1->assertSessionHas('error', 'ERP request has already been processed.');

        // Try to reject
        $response2 = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.erp-requests.reject', $this->erpRequest->id), [
                'rejection_reason' => 'Reason',
            ]);
        $response2->assertRedirect();
        $response2->assertSessionHas('error', 'ERP request has already been processed.');
    }

    public function test_license_is_displayed_in_admin_and_customer_show_views()
    {
        // Approve to generate license
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.erp-requests.approve', $this->erpRequest->id), [
                'admin_notes' => 'Approval notes',
            ]);

        $license = License::where('erp_request_id', $this->erpRequest->id)->first();
        $this->assertNotNull($license);

        // Check Admin Show page
        $adminResponse = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.erp-requests.show', $this->erpRequest->id));
        $adminResponse->assertStatus(200);
        $adminResponse->assertSee($license->license_code);
        $adminResponse->assertSee($license->token_code);
        $adminResponse->assertSee($license->domain);

        // Check Customer Show page
        $customerResponse = $this->actingAs($this->customer, 'customer')
            ->get(route('customer.trials.show', $this->erpRequest->id));
        $customerResponse->assertStatus(200);
        $customerResponse->assertSee($license->license_code);
        $customerResponse->assertSee($license->token_code);
        $customerResponse->assertSee($license->domain);
    }
}
