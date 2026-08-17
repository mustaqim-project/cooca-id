<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Admin;
use App\Models\Affiliator;
use App\Models\AuditLog;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

final class AuditLogService
{
    /**
     * Keys to mask/strip in payload for security.
     */
    private const SENSITIVE_KEYS = [
        'password',
        'password_confirmation',
        'current_password',
        'new_password',
        'token',
        'api_key',
        'secret',
        'remember_token',
        'card_number',
        'cvv',
        'payment_proof',
    ];

    /**
     * Record a direct audit log entry.
     */
    public static function log(
        string $action,
        ?string $userType = null,
        ?string $userId = null,
        ?string $modelType = null,
        ?string $modelId = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $ipAddress = null,
        ?string $userAgent = null,
        string $riskLevel = 'low',
        ?string $module = null,
        ?string $description = null
    ): ?AuditLog {
        try {
            $userType = $userType ?? self::resolveUserType();
            $userId = $userId ?? self::resolveUserId();
            $ipAddress = $ipAddress ?? request()?->ip() ?? '127.0.0.1';
            $userAgent = $userAgent ?? request()?->userAgent();

            $auditLog = AuditLog::create([
                'user_type' => $userType,
                'user_id' => $userId,
                'action' => $action,
                'model_type' => $modelType,
                'model_id' => $modelId,
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'risk_level' => $riskLevel,
            ]);

            // Mirror to ActivityLog
            try {
                ActivityLog::create([
                    'user_type' => $userType,
                    'user_id' => $userId,
                    'action' => $action,
                    'module' => $module ?? ($modelType ? class_basename($modelType) : 'System'),
                    'description' => $description ?? ($action . ' by ' . ($userType ?? 'system')),
                    'causer_type' => $userType,
                    'causer_id' => $userId,
                    'subject_type' => $modelType,
                    'subject_id' => $modelId,
                    'ip_address' => $ipAddress,
                    'user_agent' => $userAgent,
                    'properties' => $newValues,
                    'metadata' => [
                        'risk_level' => $riskLevel,
                    ],
                ]);
            } catch (\Throwable) {
                // Ignore secondary mirror failures
            }

            return $auditLog;
        } catch (\Throwable $e) {
            Log::channel('daily')->error('[AuditLogService] Failed to record audit log', [
                'error' => $e->getMessage(),
                'action' => $action,
            ]);
            return null;
        }
    }

    /**
     * Resolve the current authenticated user type.
     */
    public static function resolveUserType(): ?string
    {
        if (Auth::guard('admin')->check()) {
            return 'admin';
        }
        if (Auth::guard('customer')->check()) {
            return 'customer';
        }
        if (Auth::guard('affiliator')->check()) {
            return 'affiliator';
        }
        if (Auth::check()) {
            $user = Auth::user();
            if ($user instanceof Admin) {
                return 'admin';
            }
            if ($user instanceof Customer) {
                return 'customer';
            }
            if ($user instanceof Affiliator) {
                return 'affiliator';
            }
        }
        return null;
    }

    /**
     * Resolve the current authenticated user ID.
     */
    public static function resolveUserId(): ?string
    {
        foreach (['admin', 'customer', 'affiliator'] as $guard) {
            if (Auth::guard($guard)->check()) {
                return (string) Auth::guard($guard)->id();
            }
        }
        if (Auth::check()) {
            return (string) Auth::id();
        }
        return null;
    }

    /**
     * Sanitize request payload by stripping sensitive keys and large binaries.
     */
    public static function sanitizePayload(array $payload): array
    {
        $sanitized = [];
        foreach ($payload as $key => $value) {
            if (in_array(strtolower((string) $key), self::SENSITIVE_KEYS, true)) {
                $sanitized[$key] = '********';
            } elseif (is_array($value)) {
                $sanitized[$key] = self::sanitizePayload($value);
            } elseif (is_string($value) && strlen($value) > 2000) {
                $sanitized[$key] = substr($value, 0, 200) . '... [truncated]';
            } elseif ($value instanceof \Illuminate\Http\UploadedFile) {
                $sanitized[$key] = '[UploadedFile: ' . $value->getClientOriginalName() . ' (' . round($value->getSize() / 1024, 2) . ' KB)]';
            } else {
                $sanitized[$key] = $value;
            }
        }
        return $sanitized;
    }

    /**
     * Determine risk level from route name and method.
     */
    public static function determineRiskLevel(string $routeName, string $method): string
    {
        $criticalPatterns = [
            'destroy',
            'delete',
            'revoke',
            'refund',
            'reject',
            'suspend',
            'clear',
            'security',
            'blocked-ips',
            'password',
        ];

        $highPatterns = [
            'update',
            'approve',
            'generate',
            'verify',
            'mark-as-paid',
            'activate',
            'deactivate',
            'cancel',
            'toggle',
            'reset',
        ];

        $lowerRoute = strtolower($routeName);

        if ($method === 'DELETE') {
            return 'high';
        }

        foreach ($criticalPatterns as $pattern) {
            if (str_contains($lowerRoute, $pattern)) {
                return 'critical';
            }
        }

        foreach ($highPatterns as $pattern) {
            if (str_contains($lowerRoute, $pattern)) {
                return 'high';
            }
        }

        if (in_array($method, ['POST', 'PUT', 'PATCH'], true)) {
            return 'medium';
        }

        return 'low';
    }

    /**
     * Generate human-friendly action and module from route or URL.
     */
    public static function formatActionFromRoute(Request $request): array
    {
        $routeName = $request->route()?->getName();
        $method = $request->method();

        if ($routeName) {
            $parts = explode('.', $routeName);
            $module = count($parts) > 1 ? Str::headline($parts[count($parts) - 2]) : 'System';
            $actionVerb = end($parts);
            
            $actionVerbFormatted = match ($actionVerb) {
                'store' => 'Created ' . Str::singular($module),
                'update' => 'Updated ' . Str::singular($module),
                'destroy' => 'Deleted ' . Str::singular($module),
                'verify' => 'Verified ' . Str::singular($module),
                'approve' => 'Approved ' . Str::singular($module),
                'reject' => 'Rejected ' . Str::singular($module),
                'cancel' => 'Cancelled ' . Str::singular($module),
                'login' => 'User Logged In',
                'logout' => 'User Logged Out',
                default => Str::headline($actionVerb) . ' ' . Str::singular($module),
            };

            return [
                'action' => $actionVerbFormatted,
                'module' => $module,
                'risk' => self::determineRiskLevel($routeName, $method),
            ];
        }

        $path = trim($request->path(), '/');
        return [
            'action' => strtoupper($method) . ' /' . $path,
            'module' => 'HTTP',
            'risk' => $method === 'DELETE' ? 'high' : 'medium',
        ];
    }
}
