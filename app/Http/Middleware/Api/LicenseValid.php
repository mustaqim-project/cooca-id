<?php

declare(strict_types=1);

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

final class LicenseValid
{
    /**
     * Handle an incoming request with license validation.
     * Validates domain + license_code + token_code triple-check.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $domain = $request->getHost();
        $licenseCode = $request->header('X-License-Code');
        $tokenCode = $request->header('X-Token-Code');

        if (!$licenseCode || !$tokenCode) {
            return response()->json([
                'valid' => false,
                'message' => 'Missing license or token code',
            ], 403);
        }

        // Check cache first
        $cacheKey = "license:{$domain}:{$licenseCode}";
        $cachedResult = Cache::get($cacheKey);

        if ($cachedResult !== null) {
            if (!$cachedResult['valid']) {
                return response()->json([
                    'valid' => false,
                    'message' => $cachedResult['message'] ?? 'Invalid license',
                ], 403);
            }

            return $next($request);
        }

        // Validate via service (will check DB if cache miss)
        $licenseService = app(\App\Services\LicenseService::class);
        $result = $licenseService->validateLicense($domain, $licenseCode, $tokenCode);

        // Cache result for 1 hour
        Cache::put($cacheKey, $result, 3600);

        if (!$result['valid']) {
            return response()->json([
                'valid' => false,
                'message' => $result['message'] ?? 'Invalid license',
            ], 403);
        }

        return $next($request);
    }
}
