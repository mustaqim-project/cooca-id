<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Track referral code from query parameter and persist to session.
 * This allows referral code to be available when user registers later.
 */
final class TrackReferralMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $refCode = $request->query('ref');

        if ($refCode) {
            // Store referral code in session for future use
            session(['referral_code' => $refCode]);
            
            // Optionally store timestamp
            session(['referral_code_time' => now()]);
        }

        return $next($request);
    }
}
