<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_and_redirect_to_dashboard()
    {
        $admin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@cooca.id',
            'user_type' => 'admin',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        ]);

        $response = $this->post(route('admin.login.submit'), [
            'email' => 'admin@cooca.id',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);

        $dashboardResponse = $this->actingAs($admin)->get(route('admin.dashboard'));
        
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertViewIs('admin.dashboard.index');
    }
}
