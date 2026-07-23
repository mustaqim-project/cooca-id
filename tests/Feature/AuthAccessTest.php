<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class AuthAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
        // Seed the test accounts
        $this->artisan('db:seed', ['--class' => 'AdminAffiliatorCustomerSeeder']);
    }

    /** @test */
    public function test_admin_can_authenticate()
    {
        $admin = User::where('email', 'admin@example.com')->first();
        $this->assertNotNull($admin, 'Admin record should exist in database');
        $this->assertTrue(Hash::check('password123', $admin->password), 'Admin password hash should match');

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
        $affiliator = User::where('email', 'affiliator@example.com')->first();
        $this->assertNotNull($affiliator, 'Affiliator record should exist in database');
        $this->assertTrue(Hash::check('password123', $affiliator->password), 'Affiliator password hash should match');

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
        $customer = User::where('email', 'customer@example.com')->first();
        $this->assertNotNull($customer, 'Customer record should exist in database');
        $this->assertTrue(Hash::check('password123', $customer->password), 'Customer password hash should match');

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
        $this->withoutExceptionHandling();

        $response = $this->post(route('admin.login.submit'), [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        // Should redirect on success (not get a 422/401)
        $response->assertRedirect();
    }

    /** @test */
    public function test_invalid_credentials_fail()
    {
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
