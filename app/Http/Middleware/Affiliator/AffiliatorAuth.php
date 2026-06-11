<?php

declare(strict_types=1);

namespace App\Http\Middleware\Affiliator;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

final class AffiliatorAuth
{
    /**
     * Handle an incoming request for affiliator guard.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('affiliator')->check()) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Unauthenticated'], 401)
                : redirect()->route('affiliator.login');
        }

        return $next($request);
    }
}
