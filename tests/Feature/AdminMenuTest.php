<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_all_menus()
    {
        $admin = Admin::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@cooca.id',
            'password' => bcrypt('password123'),
        ]);

        $menus = [
            'admin.dashboard',
        ];

        foreach ($menus as $route) {
            $response = $this->actingAs($admin, 'admin')->get(route($route));
            $response->assertStatus(200);
        }
    }
}
