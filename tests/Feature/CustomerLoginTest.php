<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia;

class CustomerLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_view_login_page()
    {
        $response = $this->get(route('customer.login'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.customer.login');
    }

    public function test_customer_can_login_and_redirect_to_dashboard()
    {
        $customer = Customer::create([
            'name' => 'Test Customer',
            'email' => 'test@cooca.id',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'status' => 'active',
        ]);

        $response = $this->post(route('customer.login.submit'), [
            'email' => 'test@cooca.id',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('customer.dashboard'));

        $this->assertAuthenticatedAs($customer, 'customer');

        $dashboardResponse = $this->actingAs($customer, 'customer')->get(route('customer.dashboard'));
        
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Customer/Dashboard/Index')
        );
    }
}
