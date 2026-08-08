<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePhoneIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip phone verification check if WhatsApp is globally disabled
        if (! (bool) \App\Models\Setting::get('whatsapp.notifications_active', true)) {
            return $next($request);
        }

        $user = $request->user('customer');
        if (! $user || ! $user->phone_verified_at) {
            return $request->expectsJson()
                    ? abort(403, 'Your phone number is not verified.')
                    : redirect()->route('customer.otp.notice');
        }

        return $next($request);
    }
}
