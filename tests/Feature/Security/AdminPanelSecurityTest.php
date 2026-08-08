<?php

declare(strict_types=1);

namespace Tests\Feature\Security;

use Tests\TestCase;
use App\Models\Admin;
use App\Models\Customer;
use App\Models\Affiliator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/**
 * Security Test Suite: Admin Panel Hardening
 *
 * OWASP A01: Broken Access Control
 * OWASP A05: Security Misconfiguration
 * Tests all sensitive admin routes are protected.
 */
final class AdminPanelSecurityTest extends TestCase
{
    use RefreshDatabase;

    private array $protectedAdminRoutes = [];

    protected function setUp(): void
    {
        parent::setUp();

        // Enumerate admin routes that must never be accessible without auth
        $this->protectedAdminRoutes = [
            'admin.dashboard'           => ['GET', []],
            'admin.customers.index'     => ['GET', []],
            'admin.affiliators.index'   => ['GET', []],
            'admin.audit-logs.index'    => ['GET', []],
            'admin.api-integrations.index' => ['GET', []],
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // OWASP A01 — All admin routes require authentication
    // ─────────────────────────────────────────────────────────────────────────

    public function test_admin_dashboard_requires_authentication(): void
    {
        $this->get(route('admin.dashboard'))->assertStatus(302);
    }

    public function test_admin_customers_index_requires_authentication(): void
    {
        $this->get(route('admin.customers.index'))->assertStatus(302);
    }

    public function test_admin_affiliators_index_requires_authentication(): void
    {
        $this->get(route('admin.affiliators.index'))->assertStatus(302);
    }

    public function test_admin_audit_logs_requires_authentication(): void
    {
        $this->get(route('admin.audit-logs.index'))->assertStatus(302);
    }

    public function test_admin_api_integrations_requires_authentication(): void
    {
        $this->get(route('admin.api-integrations.index'))->assertStatus(302);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // OWASP A01 — Customer cannot perform admin actions
    // ─────────────────────────────────────────────────────────────────────────

    public function test_authenticated_customer_cannot_access_admin_routes(): void
    {
        $customer = Customer::factory()->create();
        $this->actingAs($customer, 'customer');

        $adminRoutes = [
            route('admin.dashboard'),
            route('admin.customers.index'),
            route('admin.affiliators.index'),
            route('admin.audit-logs.index'),
        ];

        foreach ($adminRoutes as $route) {
            $response = $this->get($route);
            $this->assertNotEquals(200, $response->status(),
                "Customer should NOT access admin route: {$route}"
            );
        }
    }

    public function test_authenticated_affiliator_cannot_access_admin_routes(): void
    {
        $affiliator = Affiliator::factory()->create(['status' => 'active']);
        $this->actingAs($affiliator, 'affiliator');

        $adminRoutes = [
            route('admin.dashboard'),
            route('admin.customers.index'),
            route('admin.affiliators.index'),
        ];

        foreach ($adminRoutes as $route) {
            $response = $this->get($route);
            $this->assertNotEquals(200, $response->status(),
                "Affiliator should NOT access admin route: {$route}"
            );
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // OWASP A01 — Destructive admin operations require authentication
    // ─────────────────────────────────────────────────────────────────────────

    public function test_unauthenticated_cannot_delete_customer(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->delete(route('admin.customers.destroy', $customer->id));

        // Must redirect to login
        $response->assertStatus(302);
        // Customer must still exist
        $this->assertDatabaseHas('customers', ['id' => $customer->id]);
    }

    public function test_unauthenticated_cannot_suspend_affiliator(): void
    {
        $affiliator = Affiliator::factory()->create(['status' => 'active']);

        $response = $this->post(route('admin.affiliators.suspend', $affiliator->id));

        $response->assertStatus(302);
        // Status should not have changed
        $this->assertDatabaseHas('affiliators', [
            'id'     => $affiliator->id,
            'status' => 'active',
        ]);
    }

    public function test_customer_cannot_suspend_another_affiliator(): void
    {
        $customer   = Customer::factory()->create();
        $affiliator = Affiliator::factory()->create(['status' => 'active']);

        $this->actingAs($customer, 'customer');

        $response = $this->post(route('admin.affiliators.suspend', $affiliator->id));

        $response->assertStatus(302);
        $this->assertDatabaseHas('affiliators', [
            'id'     => $affiliator->id,
            'status' => 'active',
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // OWASP A05 — Admin login page is accessible (not misconfigured)
    // ─────────────────────────────────────────────────────────────────────────

    public function test_admin_login_page_returns_200(): void
    {
        $response = $this->get(route('admin.login'));
        $response->assertStatus(200);
    }

    public function test_admin_login_page_has_email_and_password_fields(): void
    {
        $response = $this->get(route('admin.login'));

        $response->assertSee('email', false);
        $response->assertSee('password', false);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // OWASP A01 — Admin can access their own protected resources after auth
    // ─────────────────────────────────────────────────────────────────────────

    public function test_authenticated_admin_can_access_dashboard(): void
    {
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);
    }

    public function test_authenticated_admin_can_list_customers(): void
    {
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $response = $this->get(route('admin.customers.index'));

        $response->assertStatus(200);
    }
}
