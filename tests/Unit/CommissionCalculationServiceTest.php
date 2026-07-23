<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Product;
use App\Models\Subscription;
use App\Services\CommissionCalculationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Setting;

class CommissionCalculationServiceTest extends TestCase
{
    use RefreshDatabase;

    private CommissionCalculationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(CommissionCalculationService::class);
    }

    public function test_calculate_recurring_commission()
    {
        Setting::set('affiliate_commission_rate', 10.0, 'float');

        // Use an object instead of DB record since DB schema doesn't match yet
        $subscription = new \stdClass();
        $subscription->amount = 1000000;

        $affiliator = new \stdClass();

        $subscription->referred_by_id = 1;
        $subscription->affiliator = $affiliator;

        $commissionAmount = $this->service->calculateCommission($subscription);

        $this->assertEquals(100000, $commissionAmount);
    }

    public function test_calculate_without_affiliator_returns_zero()
    {
        $subscription = new \stdClass();
        $subscription->amount = 1000000;
        $subscription->referred_by_id = null;

        $commissionAmount = $this->service->calculateCommission($subscription);

        $this->assertEquals(0, $commissionAmount);
    }
}
