<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerMenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_access_all_menus()
    {
        $customer = Customer::create([
            'name' => 'Test Customer',
            'email' => 'customer@cooca.id',
            'password' => bcrypt('password123'),
        ]);
        $customer->forceFill([
            'email_verified_at' => now(),
            'status' => 'active',
        ])->save();

        $menus = [
            'customer.dashboard',
            'customer.products.index',
            'customer.subscriptions.index',
            'customer.payments.index',
            'customer.invoices.index',
            'customer.licenses.index',
            'customer.reviews.index',
            'customer.profile.edit',
        ];

        foreach ($menus as $route) {
            $response = $this->actingAs($customer, 'customer')->get(route($route));
            $response->assertStatus(200);
        }
    }
}
