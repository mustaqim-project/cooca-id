<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Helpers\CaptchaHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_login_page()
    {
        $response = $this->get(route('admin.login'));
        $response->assertStatus(200);
    }

    public function test_admin_can_login_and_redirect_to_dashboard()
    {
        $admin = Admin::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@cooca.id',
            'password' => Hash::make('password123'),
        ]);

        CaptchaHelper::generate();
        $captchaAnswer = Session::get('captcha_answer');

        $response = $this->post(route('admin.login.submit'), [
            'email' => 'admin@cooca.id',
            'password' => 'password123',
            'captcha' => $captchaAnswer,
        ]);

        $response->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin, 'admin');

        $dashboardResponse = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));
        $dashboardResponse->assertStatus(200);
    }

    public function test_admin_login_fails_with_invalid_credentials()
    {
        Admin::factory()->create([
            'email' => 'admin@cooca.id',
            'password' => Hash::make('password123'),
        ]);

        CaptchaHelper::generate();
        $captchaAnswer = Session::get('captcha_answer');

        $response = $this->post(route('admin.login.submit'), [
            'email' => 'admin@cooca.id',
            'password' => 'wrongpassword',
            'captcha' => $captchaAnswer,
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest('admin');
    }
}
