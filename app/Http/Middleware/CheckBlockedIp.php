<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\BlockedIp;
use Illuminate\Support\Facades\Cache;

class CheckBlockedIp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();

        if ($ip) {
            $blocked = Cache::remember("blocked_ip_{$ip}", 60, function () use ($ip) {
                try {
                    $record = BlockedIp::where('ip_address', $ip)->first();
                } catch (\Throwable $e) {
                    return ['is_blocked' => false];
                }
                
                if ($record) {
                    if (is_null($record->blocked_until) || $record->blocked_until->isFuture()) {
                        return [
                            'is_blocked' => true,
                            'reason' => $record->reason ?? '',
                            'is_permanent' => is_null($record->blocked_until),
                        ];
                    }
                    // Clean up expired block record
                    $record->delete();
                }
                return ['is_blocked' => false];
            });

            if ($blocked && ($blocked['is_blocked'] ?? false)) {
                // If it's a temporary brute force login block (not permanent manual admin block),
                // allow public browsing (landing page, products, blog, about, contact)
                // and ONLY block login/auth/dashboard/API requests.
                $isPublicBrowsingRoute = $request->is('/') 
                    || $request->is('about') 
                    || $request->is('contact') 
                    || $request->is('faq') 
                    || $request->is('terms') 
                    || $request->is('privacy') 
                    || $request->is('products*') 
                    || $request->is('blog*') 
                    || $request->is('affiliate*')
                    || $request->is('sitemap.xml');

                if (!($blocked['is_permanent'] ?? false) && $isPublicBrowsingRoute) {
                    return $next($request);
                }

                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Akses ditolak. IP Anda telah diblokir sementara karena terdeteksi aktivitas brute force.',
                    ], 403);
                }

                abort(403, 'Akses ditolak. Terdeteksi percobaan login berulang (brute force attack). Silakan coba lagi beberapa saat.');
            }
        }

        return $next($request);
    }
}

