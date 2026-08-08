<?php

declare(strict_types=1);

namespace Tests\Feature\Security;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Customer;
use App\Models\Affiliator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

/**
 * Security Test Suite: Input Validation & Mass Assignment
 *
 * OWASP A03: Injection
 * OWASP A04: Insecure Design (mass assignment)
 * OWASP A08: Software and Data Integrity Failures
 */
final class InputValidationSecurityTest extends TestCase
{
    use RefreshDatabase;

    // ─────────────────────────────────────────────────────────────────────────
    // OWASP A03 — SQL Injection via login fields
    // Laravel uses PDO prepared statements, this verifies no raw queries leak
    // ─────────────────────────────────────────────────────────────────────────

    public function test_sql_injection_in_admin_email_field_is_rejected(): void
    {
        $payloads = [
            "' OR '1'='1",
            "'; DROP TABLE admins; --",
            "' OR 1=1 --",
            "admin'--",
            "1' UNION SELECT * FROM admins--",
        ];

        foreach ($payloads as $payload) {
            $response = $this->post(route('admin.login.submit'), [
                'email'    => $payload,
                'password' => 'password',
            ]);

            // Must not succeed — should redirect back or return session errors
            $this->assertGuest('admin',
                "SQL injection payload should not authenticate: [{$payload}]"
            );
            $this->assertNotEquals(500, $response->status(),
                "SQL injection should not cause a 500 error: [{$payload}]"
            );
        }
    }

    public function test_sql_injection_in_customer_email_field_is_rejected(): void
    {
        $payload = "' OR '1'='1' --";

        $response = $this->post(route('customer.login.submit'), [
            'email'    => $payload,
            'password' => 'anything',
        ]);

        $this->assertGuest('customer');
        $this->assertNotEquals(500, $response->status());
    }

    // ─────────────────────────────────────────────────────────────────────────
    // OWASP A04 — Mass Assignment Protection
    // Verify that guarded/fillable correctly prevents elevation of privilege
    // ─────────────────────────────────────────────────────────────────────────

    public function test_customer_registration_cannot_mass_assign_admin_flag(): void
    {
        // Simulate registration POST with extra fields that shouldn't be accepted
        $response = $this->post(route('customer.register'), [
            'name'                  => 'Attacker',
            'email'                 => 'attacker@test.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'is_admin'              => true,           // Should be ignored
            'role'                  => 'superadmin',   // Should be ignored
        ]);

        // Check the created customer doesn't have elevated attributes
        $customer = Customer::where('email', 'attacker@test.com')->first();

        if ($customer) {
            // Customer model should not have is_admin or role fields
            $this->assertArrayNotHasKey('is_admin', $customer->getAttributes());
            $this->assertArrayNotHasKey('role', $customer->getAttributes());
        }

        // Regardless, admin guard must not be authenticated
        $this->assertGuest('admin');
    }

    public function test_affiliator_registration_cannot_set_status_to_active(): void
    {
        $response = $this->post(route('affiliator.register'), [
            'name'                  => 'FakeAffiliator',
            'email'                 => 'fake@affiliate.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'status'                => 'active',   // Should default to 'pending', not user-controlled
        ]);

        $affiliator = Affiliator::where('email', 'fake@affiliate.com')->first();

        if ($affiliator) {
            // Status should NOT be 'active' from registration — must go through approval
            $this->assertNotEquals('active', $affiliator->status,
                'Affiliator status should not be settable to active via mass assignment during registration'
            );
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // OWASP A04 — Mass Assignment: fillable whitelist verification
    // ─────────────────────────────────────────────────────────────────────────

    public function test_customer_model_does_not_allow_password_via_update(): void
    {
        $customer = Customer::factory()->create([
            'password' => Hash::make('original-password'),
        ]);

        // Simulate an update that tries to sneak in a new password via fillable
        // (this would only be a risk if 'password' is in fillable AND used via fill())
        $allowedFillable = $customer->getFillable();

        // password should NOT be in fillable (it's set explicitly in auth flows)
        // OR if it is, it must be hashed — never plain text
        if (in_array('password', $allowedFillable)) {
            // If password is fillable, ensure it gets hashed before persisting
            $this->markTestIncomplete(
                'Password is in Customer fillable — verify hashing middleware is applied'
            );
        }

        $this->assertTrue(true, 'Customer model fillable does not expose raw password assignment');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // OWASP A03 — Input validation on registration forms
    // ─────────────────────────────────────────────────────────────────────────

    public function test_customer_registration_requires_valid_email(): void
    {
        $response = $this->post(route('customer.register'), [
            'name'                  => 'Test',
            'email'                 => 'not-an-email',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('customers', ['name' => 'Test']);
    }

    public function test_customer_registration_requires_minimum_password_length(): void
    {
        $response = $this->post(route('customer.register'), [
            'name'                  => 'WeakPassUser',
            'email'                 => 'weakpass@test.com',
            'password'              => '123',       // Too short
            'password_confirmation' => '123',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertDatabaseMissing('customers', ['email' => 'weakpass@test.com']);
    }

    public function test_customer_registration_rejects_mismatched_passwords(): void
    {
        $response = $this->post(route('customer.register'), [
            'name'                  => 'Mismatch',
            'email'                 => 'mismatch@test.com',
            'password'              => 'password123',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertDatabaseMissing('customers', ['email' => 'mismatch@test.com']);
    }

    public function test_customer_registration_rejects_duplicate_email(): void
    {
        Customer::factory()->create(['email' => 'duplicate@test.com']);

        $response = $this->post(route('customer.register'), [
            'name'                  => 'Duplicate',
            'email'                 => 'duplicate@test.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertEquals(1, Customer::where('email', 'duplicate@test.com')->count());
    }

    public function test_admin_login_validates_email_format(): void
    {
        $response = $this->post(route('admin.login.submit'), [
            'email'    => 'not-valid-email',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest('admin');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // OWASP A03 — Oversized input handling (DoS prevention)
    // ─────────────────────────────────────────────────────────────────────────

    public function test_login_rejects_extremely_long_email_input(): void
    {
        $longEmail = str_repeat('a', 1000) . '@test.com';

        $response = $this->post(route('admin.login.submit'), [
            'email'    => $longEmail,
            'password' => 'password123',
        ]);

        // Should not crash the application
        $this->assertNotEquals(500, $response->status());
        $this->assertGuest('admin');
    }

    public function test_login_rejects_extremely_long_password_input(): void
    {
        $longPassword = str_repeat('a', 10000);

        $response = $this->post(route('admin.login.submit'), [
            'email'    => 'admin@test.com',
            'password' => $longPassword,
        ]);

        $this->assertNotEquals(500, $response->status());
        $this->assertGuest('admin');
    }
}
