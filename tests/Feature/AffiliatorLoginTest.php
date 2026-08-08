<?php

namespace Tests\Feature;

use App\Models\Affiliator;
use App\Helpers\CaptchaHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class AffiliatorLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_affiliator_can_view_login_page()
    {
        $response = $this->get(route('affiliator.login'));

        $response->assertStatus(200);
    }

    public function test_affiliator_can_login_and_redirect_to_dashboard()
    {
        $affiliator = Affiliator::factory()->create([
            'name' => 'Test Affiliator',
            'email' => 'affiliate@cooca.id',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);

        CaptchaHelper::generate();
        $captchaAnswer = Session::get('captcha_answer');

        $response = $this->post(route('affiliator.login.submit'), [
            'email' => 'affiliate@cooca.id',
            'password' => 'password123',
            'captcha' => $captchaAnswer,
        ]);

        $response->assertRedirect(route('affiliator.dashboard'));

        $this->assertAuthenticatedAs($affiliator, 'affiliator');
    }

    public function test_affiliator_login_fails_with_invalid_credentials()
    {
        Affiliator::factory()->create([
            'email' => 'affiliate@cooca.id',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);

        CaptchaHelper::generate();
        $captchaAnswer = Session::get('captcha_answer');

        $response = $this->post(route('affiliator.login.submit'), [
            'email' => 'affiliate@cooca.id',
            'password' => 'wrongpassword',
            'captcha' => $captchaAnswer,
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest('affiliator');
    }
}
