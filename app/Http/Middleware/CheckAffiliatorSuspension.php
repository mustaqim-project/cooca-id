<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAffiliatorSuspension
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->guard('affiliator')->check()) {
            $affiliator = auth()->guard('affiliator')->user();
            
            if ($affiliator->status === 'suspended') {
                // If they are not already on the appeal page or submitting an appeal, redirect them
                if (!$request->routeIs('affiliate.appeal.*') && !$request->routeIs('affiliate.logout')) {
                    return redirect()->route('affiliate.appeal.index');
                }
            }
        }

        return $next($request);
    }
}
