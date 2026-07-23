<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AffiliatorMenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_affiliator_can_access_all_menus()
    {
        $affiliator = User::factory()->create([
            'name' => 'Test Affiliator',
            'email' => 'affiliate@cooca.id',
            'password' => bcrypt('password123'),
            'status' => 'active',
        ]);

        $menus = [
            'affiliator.dashboard',
            'affiliator.referrals.index',
            'affiliator.referrals.stats',
            'affiliator.commissions.index',
            'affiliator.commissions.stats',
            'affiliator.commissions.export',
            'affiliator.downlines.index',
            'affiliator.downlines.tree',
            'affiliator.downlines.stats',
            'affiliator.withdrawals.index',
            'affiliator.withdrawals.create',
            'affiliator.withdrawals.history',
            'affiliator.reviews.index',
            'affiliator.profile.edit',
            'affiliator.marketing_materials.index',
            'affiliator.marketing_materials.banners',
            'affiliator.marketing_materials.links',
        ];

        foreach ($menus as $route) {
            $response = $this->actingAs($affiliator)->get(route($route));
            $response->assertStatus(200);
        }
    }
}
