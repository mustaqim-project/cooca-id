<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\AuditLogService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to automatically record user and admin activities in realtime.
 */
final class ActivityLogger
{
    /**
     * HTTP methods that modify state and should be tracked.
     */
    private const TRACKED_METHODS = ['POST', 'PUT', 'PATCH', 'DELETE'];

    /**
     * Routes that should not be logged (e.g. heartbeat, notifications polling, telemetry).
     */
    private const IGNORED_ROUTES = [
        'debugbar.*',
        'horizon.*',
        'telescope.*',
        'livewire.*',
        'admin.whatsapp-devices.status-ajax',
        'admin.live-chats.sessions-data',
        'admin.live-chats.messages',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log state-changing requests
        if (!in_array($request->method(), self::TRACKED_METHODS, true)) {
            return $response;
        }

        $routeName = $request->route()?->getName() ?? '';

        // Check if route should be ignored
        foreach (self::IGNORED_ROUTES as $pattern) {
            if (fnmatch($pattern, $routeName)) {
                return $response;
            }
        }

        try {
            $formatted = AuditLogService::formatActionFromRoute($request);
            $userType = AuditLogService::resolveUserType();
            $userId = AuditLogService::resolveUserId();

            // Extract payload
            $sanitizedInput = AuditLogService::sanitizePayload($request->except(['_token', '_method']));

            // Extract target model if bound in route parameters
            $modelType = null;
            $modelId = null;
            foreach ($request->route()?->parameters() ?? [] as $paramName => $paramValue) {
                if (is_object($paramValue) && method_exists($paramValue, 'getKey')) {
                    $modelType = get_class($paramValue);
                    $modelId = (string) $paramValue->getKey();
                    break;
                } elseif (is_string($paramValue) && strlen($paramValue) === 36) {
                    $modelId = $paramValue;
                }
            }

            AuditLogService::log(
                action: $formatted['action'],
                userType: $userType,
                userId: $userId,
                modelType: $modelType,
                modelId: $modelId,
                oldValues: null,
                newValues: !empty($sanitizedInput) ? $sanitizedInput : null,
                ipAddress: $request->ip(),
                userAgent: $request->userAgent(),
                riskLevel: $formatted['risk'],
                module: $formatted['module'],
                description: sprintf('%s performed by %s (%s)', $formatted['action'], $userType ?? 'Guest', $request->ip())
            );
        } catch (\Throwable) {
            // Never break request pipeline if logging encounters an issue
        }

        return $response;
    }
}
