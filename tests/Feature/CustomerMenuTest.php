<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerMenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_access_all_panel_views()
    {
        $customer = Customer::factory()->create([
            'name' => 'Test Enterprise Customer',
            'email' => 'customer@cooca.id',
            'password' => bcrypt('password123'),
        ]);
        
        $customer->forceFill([
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
        ])->save();

        $routes = [
            'customer.dashboard',
            'customer.products.index',
            'customer.subscriptions.index',
            'customer.subscriptions.create',
            'customer.payments.index',
            'customer.invoices.index',
            'customer.licenses.index',
            'customer.reviews.index',
            'customer.company-profile.edit',
            'customer.trials.index',
            'customer.trials.create',
            'customer.domains.index',
            'customer.tickets.index',
            'customer.tickets.create',
            'customer.profile.edit',
            'customer.projects.index',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($customer, 'customer')->get(route($route));
            $response->assertStatus(200, "Failed accessing route: {$route}");
        }
    }
}
