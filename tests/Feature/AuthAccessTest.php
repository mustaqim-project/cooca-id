<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Affiliator;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class AuthAccessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_admin_can_authenticate()
    {
        $admin = Admin::factory()->create([
            'name' => 'Admin Test',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->assertTrue(
            Auth::guard('admin')->attempt([
                'email' => 'admin@example.com',
                'password' => 'password123',
            ]),
            'Admin should be able to authenticate via admin guard'
        );
    }

    /** @test */
    public function test_affiliator_can_authenticate_via_guard()
    {
        $affiliator = Affiliator::factory()->create([
            'name' => 'Affiliator Test',
            'email' => 'affiliator@example.com',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);

        $this->assertTrue(
            Auth::guard('affiliator')->attempt([
                'email' => 'affiliator@example.com',
                'password' => 'password123',
            ]),
            'Affiliator should be able to authenticate via affiliator guard'
        );
    }

    /** @test */
    public function test_customer_can_authenticate()
    {
        $customer = Customer::factory()->create([
            'name' => 'Customer Test',
            'email' => 'customer@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->assertTrue(
            Auth::guard('customer')->attempt([
                'email' => 'customer@example.com',
                'password' => 'password123',
            ]),
            'Customer should be able to authenticate via customer guard'
        );
    }

    /** @test */
    public function test_admin_login_route_works()
    {
        Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('admin.login.submit'), [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect();
    }

    /** @test */
    public function test_invalid_credentials_fail()
    {
        Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->assertFalse(
            Auth::guard('admin')->attempt([
                'email' => 'admin@example.com',
                'password' => 'wrongpassword',
            ]),
            'Invalid password should not authenticate'
        );

        $this->assertFalse(
            Auth::guard('affiliator')->attempt([
                'email' => 'nonexistent@example.com',
                'password' => 'password123',
            ]),
            'Non-existent email should not authenticate'
        );
    }
}
