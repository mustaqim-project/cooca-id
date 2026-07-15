<?php

namespace Tests\Feature;

use App\Models\Affiliator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia;

class AffiliatorLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_affiliator_can_view_login_page()
    {
        $response = $this->get(route('affiliator.login'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.affiliator.login');
    }

    public function test_affiliator_can_login_and_redirect_to_dashboard()
    {
        $affiliator = Affiliator::create([
            'name' => 'Test Affiliator',
            'email' => 'affiliate@cooca.id',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);

        $response = $this->post(route('affiliator.login.submit'), [
            'email' => 'affiliate@cooca.id',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('affiliator.dashboard'));

        $this->assertAuthenticatedAs($affiliator, 'affiliator');

        $dashboardResponse = $this->actingAs($affiliator, 'affiliator')->get(route('affiliator.dashboard'));
        
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertViewIs('affiliator.dashboard.index');
    }
}
