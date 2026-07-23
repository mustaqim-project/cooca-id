<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\CompanyProfile;
use App\Models\ErpRequest;
use App\Models\Product;
use App\Services\TrialEligibilityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrialEligibilityServiceTest extends TestCase
{
    use RefreshDatabase;

    private TrialEligibilityService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(TrialEligibilityService::class);
    }

    public function test_customer_without_company_profile_cannot_trial()
    {
        $customer = User::factory()->create();
        $product = Product::factory()->create();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Trial profile incomplete');

        $this->service->checkEligibility($customer, $product);
    }

    public function test_customer_with_existing_active_trial_cannot_trial_same_product()
    {
        $customer = User::factory()->create();
        CompanyProfile::create(['user_id' => $customer->id, 'company_name' => 'PT Test']);
        $product = Product::factory()->create();

        ErpRequest::create([
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'status' => 'active_trial',
            'requested_subdomain' => 'test'
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Trial quota exceeded');

        $this->service->checkEligibility($customer, $product);
    }

    public function test_customer_can_trial_if_eligible()
    {
        $customer = User::factory()->create();
        CompanyProfile::create(['user_id' => $customer->id, 'company_name' => 'PT Test']);
        $product = Product::factory()->create();

        $this->assertTrue($this->service->checkEligibility($customer, $product));
    }
}
