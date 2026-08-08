<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Helpers\CaptchaHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class CustomerLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_view_login_page()
    {
        $response = $this->get(route('customer.login'));

        $response->assertStatus(200);
    }

    public function test_customer_can_login_and_redirect_to_dashboard()
    {
        $customer = Customer::factory()->create([
            'name' => 'Test Customer',
            'email' => 'test@cooca.id',
            'password' => Hash::make('password123'),
        ]);

        CaptchaHelper::generate();
        $captchaAnswer = Session::get('captcha_answer');

        $response = $this->post(route('customer.login.submit'), [
            'email' => 'test@cooca.id',
            'password' => 'password123',
            'captcha' => $captchaAnswer,
        ]);

        $response->assertRedirect(route('customer.dashboard'));

        $this->assertAuthenticatedAs($customer, 'customer');
    }

    public function test_customer_login_fails_with_invalid_credentials()
    {
        Customer::factory()->create([
            'email' => 'test@cooca.id',
            'password' => Hash::make('password123'),
        ]);

        CaptchaHelper::generate();
        $captchaAnswer = Session::get('captcha_answer');

        $response = $this->post(route('customer.login.submit'), [
            'email' => 'test@cooca.id',
            'password' => 'wrongpassword',
            'captcha' => $captchaAnswer,
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest('customer');
    }

    public function test_customer_login_fails_with_invalid_captcha()
    {
        Customer::factory()->create([
            'email' => 'test@cooca.id',
            'password' => Hash::make('password123'),
        ]);

        CaptchaHelper::generate();

        $response = $this->post(route('customer.login.submit'), [
            'email' => 'test@cooca.id',
            'password' => 'password123',
            'captcha' => 'wrong_captcha',
        ]);

        $response->assertSessionHasErrors(['captcha']);
        $this->assertGuest('customer');
    }
}
