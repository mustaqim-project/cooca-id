<?php

declare(strict_types=1);

namespace Tests\Feature\Security;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Customer;
use App\Models\Affiliator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

/**
 * Security Test Suite: Authentication & Authorization
 *
 * OWASP A01: Broken Access Control
 * OWASP A07: Identification and Authentication Failures
 */
final class AuthenticationSecurityTest extends TestCase
{
    use RefreshDatabase;

    // ─────────────────────────────────────────────────────────────────────────
    // OWASP A01 — Unauthenticated access to protected routes
    // ─────────────────────────────────────────────────────────────────────────

    public function test_unauthenticated_cannot_access_admin_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));
        // Must redirect (302) — not serve the page
        $response->assertStatus(302);
        $this->assertGuest('admin');
    }

    public function test_unauthenticated_cannot_access_customer_dashboard(): void
    {
        $response = $this->get(route('customer.dashboard'));
        $response->assertStatus(302);
        $this->assertGuest('customer');
    }

    public function test_unauthenticated_cannot_access_affiliator_dashboard(): void
    {
        $response = $this->get(route('affiliator.dashboard'));
        $response->assertStatus(302);
        $this->assertGuest('affiliator');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // OWASP A01 — Cross-role privilege escalation
    // ─────────────────────────────────────────────────────────────────────────

    public function test_customer_cannot_access_admin_dashboard(): void
    {
        $customer = Customer::factory()->create();
        $this->actingAs($customer, 'customer');

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(302);
        $this->assertGuest('admin');
    }

    public function test_customer_cannot_access_affiliator_dashboard(): void
    {
        $customer = Customer::factory()->create();
        $this->actingAs($customer, 'customer');

        $response = $this->get(route('affiliator.dashboard'));

        $response->assertStatus(302);
        $this->assertGuest('affiliator');
    }

    public function test_affiliator_cannot_access_admin_dashboard(): void
    {
        $affiliator = Affiliator::factory()->create(['status' => 'active']);
        $this->actingAs($affiliator, 'affiliator');

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(302);
        $this->assertGuest('admin');
    }

    public function test_affiliator_cannot_access_customer_dashboard(): void
    {
        $affiliator = Affiliator::factory()->create(['status' => 'active']);
        $this->actingAs($affiliator, 'affiliator');

        $response = $this->get(route('customer.dashboard'));

        $response->assertStatus(302);
        $this->assertGuest('customer');
    }

    public function test_admin_session_does_not_bleed_into_other_guards(): void
    {
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $this->assertAuthenticated('admin');
        $this->assertGuest('customer');
        $this->assertGuest('affiliator');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // OWASP A07 — Authentication failures: invalid credentials
    // ─────────────────────────────────────────────────────────────────────────

    public function test_admin_login_fails_with_wrong_password(): void
    {
        Admin::factory()->create([
            'email'    => 'admin@test.com',
            'password' => Hash::make('correct-password'),
        ]);

        $response = $this->post(route('admin.login.submit'), [
            'email'    => 'admin@test.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest('admin');
    }

    public function test_customer_login_fails_with_nonexistent_email(): void
    {
        $response = $this->post(route('customer.login.submit'), [
            'email'    => 'nobody@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest('customer');
    }

    public function test_suspended_affiliator_cannot_login(): void
    {
        Affiliator::factory()->create([
            'email'    => 'suspended@test.com',
            'password' => Hash::make('password123'),
            'status'   => 'suspended',
        ]);

        $response = $this->post(route('affiliator.login.submit'), [
            'email'    => 'suspended@test.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest('affiliator');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // OWASP A07 — Session invalidation on logout
    // ─────────────────────────────────────────────────────────────────────────

    public function test_customer_session_invalidated_after_logout(): void
    {
        $customer = Customer::factory()->create();
        $this->actingAs($customer, 'customer');

        $this->assertAuthenticated('customer');
        $this->post(route('customer.logout'));
        $this->assertGuest('customer');
    }

    public function test_admin_session_invalidated_after_logout(): void
    {
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $this->assertAuthenticated('admin');
        $this->post(route('admin.logout'));
        $this->assertGuest('admin');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // OWASP A02 — Cryptographic Failures: Password hashing
    // ─────────────────────────────────────────────────────────────────────────

    public function test_admin_password_stored_as_bcrypt_hash(): void
    {
        $admin = Admin::factory()->create([
            'password' => Hash::make('plaintext'),
        ]);

        $this->assertNotEquals('plaintext', $admin->password);
        $this->assertTrue(Hash::check('plaintext', $admin->password));
        $this->assertStringStartsWith('$2y$', $admin->password);
    }

    public function test_customer_password_stored_as_bcrypt_hash(): void
    {
        $customer = Customer::factory()->create([
            'password' => Hash::make('plaintext'),
        ]);

        $this->assertNotEquals('plaintext', $customer->password);
        $this->assertTrue(Hash::check('plaintext', $customer->password));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // OWASP A02 — Sensitive fields hidden from model serialization
    // ─────────────────────────────────────────────────────────────────────────

    public function test_admin_password_hidden_from_serialization(): void
    {
        $admin = Admin::factory()->create(['password' => Hash::make('secret')]);

        $json = $admin->toJson();

        $this->assertStringNotContainsString('secret', $json);
        $this->assertStringNotContainsString('remember_token', $json);
    }

    public function test_customer_password_hidden_from_serialization(): void
    {
        $customer = Customer::factory()->create(['password' => Hash::make('secret')]);

        $json = $customer->toJson();

        $this->assertStringNotContainsString('secret', $json);
        $this->assertStringNotContainsString('remember_token', $json);
    }

    public function test_affiliator_password_hidden_from_serialization(): void
    {
        $affiliator = Affiliator::factory()->create(['password' => Hash::make('secret')]);

        $json = $affiliator->toJson();

        $this->assertStringNotContainsString('secret', $json);
        $this->assertStringNotContainsString('remember_token', $json);
    }
}
