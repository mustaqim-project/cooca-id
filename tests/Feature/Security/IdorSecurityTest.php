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
 * Security Test Suite: Insecure Direct Object Reference (IDOR)
 *
 * OWASP A01: Broken Access Control — IDOR
 * Tests that users cannot access or manipulate data belonging to other users.
 */
final class IdorSecurityTest extends TestCase
{
    use RefreshDatabase;

    // ─────────────────────────────────────────────────────────────────────────
    // OWASP A01 — IDOR: Admin resources
    // ─────────────────────────────────────────────────────────────────────────

    public function test_customer_cannot_view_another_customers_profile_via_admin_route(): void
    {
        $victim    = Customer::factory()->create();
        $attacker  = Customer::factory()->create();

        $this->actingAs($attacker, 'customer');

        // Admin route to view a customer — should NOT be accessible by a customer
        $response = $this->get(route('admin.customers.show', $victim->id));

        // Must redirect to admin login, not show victim data
        $response->assertStatus(302);
        $this->assertGuest('admin');
    }

    public function test_customer_cannot_delete_another_customer_via_admin_route(): void
    {
        $victim   = Customer::factory()->create();
        $attacker = Customer::factory()->create();

        $this->actingAs($attacker, 'customer');

        $response = $this->delete(route('admin.customers.destroy', $victim->id));

        $response->assertStatus(302);

        // Victim must still exist
        $this->assertDatabaseHas('customers', ['id' => $victim->id]);
    }

    public function test_affiliator_cannot_access_admin_customer_list(): void
    {
        $affiliator = Affiliator::factory()->create(['status' => 'active']);

        $this->actingAs($affiliator, 'affiliator');

        $response = $this->get(route('admin.customers.index'));

        $response->assertStatus(302);
        $this->assertGuest('admin');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // OWASP A01 — IDOR: ERP Requests isolation
    // ─────────────────────────────────────────────────────────────────────────

    public function test_customer_cannot_access_erp_request_listing_of_other_customers(): void
    {
        $attacker = Customer::factory()->create();
        $this->actingAs($attacker, 'customer');

        // ERP requests are only accessible via admin routes
        // A customer acting as one must NOT reach admin ERP request list
        $response = $this->get(route('admin.erp-requests.index'));

        // Should redirect (not serve admin content to a customer)
        $response->assertStatus(302);
        $this->assertGuest('admin');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // OWASP A01 — IDOR: Affiliator data isolation
    // ─────────────────────────────────────────────────────────────────────────

    public function test_affiliator_cannot_view_another_affiliators_data(): void
    {
        $affiliator1 = Affiliator::factory()->create(['status' => 'active']);
        $affiliator2 = Affiliator::factory()->create(['status' => 'active']);

        $this->actingAs($affiliator1, 'affiliator');

        // Try to access admin affiliator show route (admin-only)
        $response = $this->get(route('admin.affiliators.show', $affiliator2->id));

        $response->assertStatus(302);
        $this->assertGuest('admin');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // OWASP A01 — IDOR: UUID makes enumeration harder
    // ─────────────────────────────────────────────────────────────────────────

    public function test_customer_ids_are_uuids_not_sequential_integers(): void
    {
        $customer1 = Customer::factory()->create();
        $customer2 = Customer::factory()->create();

        // UUIDs should not be sequential integers (prevents enumeration)
        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i',
            (string) $customer1->id,
            'Customer IDs must be UUIDs to prevent IDOR via sequential enumeration'
        );
        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i',
            (string) $customer2->id
        );
        $this->assertNotEquals($customer1->id, $customer2->id);
    }

    public function test_admin_ids_are_uuids(): void
    {
        $admin = Admin::factory()->create();

        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i',
            (string) $admin->id
        );
    }

    public function test_affiliator_ids_are_uuids(): void
    {
        $affiliator = Affiliator::factory()->create();

        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i',
            (string) $affiliator->id
        );
    }

    // ─────────────────────────────────────────────────────────────────────────
    // OWASP A01 — IDOR: Accessing non-existent resources returns 404/302
    // ─────────────────────────────────────────────────────────────────────────

    public function test_accessing_nonexistent_admin_resource_returns_404(): void
    {
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $fakeUuid = '00000000-0000-0000-0000-000000000000';
        $response = $this->get(route('admin.customers.show', $fakeUuid));

        $response->assertStatus(404);
    }
}
