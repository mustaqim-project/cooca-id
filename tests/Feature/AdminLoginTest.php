<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_login_page()
    {
        $response = $this->get(route('admin.login'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.admin.login');
    }

    public function test_admin_can_login_and_redirect_to_dashboard()
    {
        $admin = Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@cooca.id',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('admin.login.submit'), [
            'email' => 'admin@cooca.id',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin, 'admin');

        $dashboardResponse = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));
        
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Admin/Dashboard/Index')
        );
    }
}
