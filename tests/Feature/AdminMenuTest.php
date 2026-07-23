<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_all_menus()
    {
        $admin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@cooca.id',
            'password' => bcrypt('password123'),
        ]);

        $menus = [
            'admin.dashboard',
            'admin.erp-requests.index',
            'admin.products.index',
            'admin.customers.index',
            'admin.affiliators.index',
            'admin.licenses.index',
            'admin.subscriptions.index',
            'admin.transactions.index',
            'admin.vouchers.index',
            'admin.settlements.index',
            'admin.cms.pages.index',
            'admin.blog.index',
            'admin.email-campaigns.index',
            'admin.tickets.index',
            'admin.reviews.index',
            'admin.settings.index',
            'admin.product-categories.index',
            'admin.email-templates.index',
            'admin.faqs.index',
            'admin.testimonials.index',
        ];

        foreach ($menus as $route) {
            $response = $this->actingAs($admin)->get(route($route));
            $response->assertStatus(200);
        }
    }
}
