<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Track referral code from query parameter and persist to session & cookie.
 * This ensures referral code is preserved even if user navigates through multiple pages
 * or registers after reopening the browser within 30 days.
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
        $refCode = $request->query('ref') ?? $request->query('referral');

        if (!empty($refCode) && is_string($refCode)) {
            $cleanRef = strtoupper(trim($refCode));

            // Store referral code in session for immediate use
            session(['referral_code' => $cleanRef]);
            session(['referral_code_time' => now()]);

            // Queue a 30-day cookie for attribution durability
            cookie()->queue('referral_code', $cleanRef, 60 * 24 * 30);
        }

        return $next($request);
    }
}

