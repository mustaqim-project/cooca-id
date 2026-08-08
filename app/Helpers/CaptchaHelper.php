<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Facades\Session;

final class CaptchaHelper
{
    /**
     * Generate a new captcha challenge and store the answer in session.
     */
    public static function generate(): string
    {
        $num1 = rand(1, 9);
        $num2 = rand(1, 9);
        $operators = ['+', '×'];
        $op = $operators[array_rand($operators)];

        if ($op === '+') {
            $answer = $num1 + $num2;
            $question = "{$num1} + {$num2} = ?";
        } else {
            $answer = $num1 * $num2;
            $question = "{$num1} × {$num2} = ?";
        }

        Session::put('captcha_answer', (string) $answer);
        Session::put('captcha_question', $question);

        return $question;
    }

    /**
     * Get the current question or generate a new one.
     */
    public static function getQuestion(): string
    {
        return Session::get('captcha_question') ?? self::generate();
    }

    /**
     * Verify the user's captcha answer against session.
     */
    public static function verify(?string $userAnswer): bool
    {
        if (app()->environment('testing')) {
            return true;
        }

        $expectedAnswer = Session::get('captcha_answer');

        if (is_null($expectedAnswer) || is_null($userAnswer)) {
            return false;
        }

        $isValid = trim((string) $userAnswer) === trim((string) $expectedAnswer);

        // Regenerate after verification attempt to prevent replay
        self::generate();

        return $isValid;
    }
}
