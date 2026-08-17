<?php

declare(strict_types=1);

namespace App\Http\Middleware\Ai;

use App\Models\AiApiKey;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class AuthenticateAiApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $header = $request->header('Authorization', '');

        if (!str_starts_with($header, 'Bearer ')) {
            return response()->json(['error' => ['message' => 'Missing API key']], 401);
        }

        $rawKey = substr($header, 7);
        $prefix = substr($rawKey, 0, 12);

        $apiKey = AiApiKey::where('key_prefix', $prefix)
            ->where('status', 'active')
            ->first();

        if (!$apiKey || !hash_equals($apiKey->key_hash, hash('sha256', $rawKey))) {
            return response()->json(['error' => ['message' => 'Invalid API key']], 401);
        }

        $license = $apiKey->license;
        // Assume STATUS_ACTIVE is mapped to 'active' or similar constant in License model
        if (!$license || $license->status !== \App\Models\License::STATUS_ACTIVE || ($license->expires_at && $license->expires_at->isPast())) {
            return response()->json(['error' => ['message' => 'AI module license is not active']], 403);
        }

        $apiKey->update(['last_used_at' => now()]);

        $request->attributes->set('ai_api_key', $apiKey);
        $request->attributes->set('ai_license', $license);

        return $next($request);
    }
}
