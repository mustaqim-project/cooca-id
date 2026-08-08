<?php

namespace Tests\Unit;

use App\Helpers\CaptchaHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class CaptchaHelperTest extends TestCase
{
    use RefreshDatabase;

    public function test_captcha_generation_stores_question_and_answer_in_session()
    {
        $question = CaptchaHelper::generate();

        $this->assertNotEmpty($question);
        $this->assertTrue(Session::has('captcha_answer'));
        $this->assertTrue(Session::has('captcha_question'));
    }

    public function test_captcha_verification_succeeds_with_correct_answer()
    {
        CaptchaHelper::generate();
        $correctAnswer = Session::get('captcha_answer');

        $this->assertTrue(CaptchaHelper::verify($correctAnswer));
    }

    public function test_captcha_verification_fails_with_incorrect_answer()
    {
        CaptchaHelper::generate();

        $this->assertFalse(CaptchaHelper::verify('999999'));
    }

    public function test_captcha_refresh_endpoint_returns_json()
    {
        $response = $this->get(route('captcha.refresh'));

        $response->assertStatus(200);
        $response->assertJsonStructure(['question']);
    }
}
