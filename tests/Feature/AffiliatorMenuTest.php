<?php

namespace Tests\Feature;

use App\Models\Affiliator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AffiliatorMenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_affiliator_can_access_all_menus()
    {
        $affiliator = Affiliator::factory()->create([
            'name' => 'Test Affiliator',
            'email' => 'affiliate@cooca.id',
            'password' => bcrypt('password123'),
            'status' => 'active',
        ]);

        $menus = [
            'affiliator.dashboard',
        ];

        foreach ($menus as $route) {
            $response = $this->actingAs($affiliator, 'affiliator')->get(route($route));
            $response->assertStatus(200);
        }
    }
}
