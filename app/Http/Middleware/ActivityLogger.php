<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to automatically log critical user activities.
 * Handles: Login, Payment, ERP Setup, and other critical actions.
 */
final class ActivityLogger
{
    /**
     * Route patterns to log and their descriptions.
     */
    private const LOGGABLE_ROUTES = [
        // Authentication actions
        'customer.login'         => ['module' => 'Auth',    'action' => 'customer_login'],
        'affiliator.login'       => ['module' => 'Auth',    'action' => 'affiliator_login'],
        'admin.login'            => ['module' => 'Auth',    'action' => 'admin_login'],

        // Payment actions
        'customer.payment.store' => ['module' => 'Payment', 'action' => 'payment_initiated'],

        // ERP / Admin actions
        'admin.erp.approve'      => ['module' => 'ERP',     'action' => 'erp_approved'],
        'admin.erp.reject'       => ['module' => 'ERP',     'action' => 'erp_rejected'],
        'admin.erp.confirm-ready'=> ['module' => 'ERP',     'action' => 'trial_activated'],
    ];

    /**
     * HTTP methods that modify state and should be logged.
     */
    private const LOGGABLE_METHODS = ['POST', 'PUT', 'PATCH', 'DELETE'];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log state-changing requests
        if (!in_array($request->method(), self::LOGGABLE_METHODS, true)) {
            return $response;
        }

        // Only log successful responses (2xx and 3xx)
        $statusCode = $response->getStatusCode();
        if ($statusCode >= 400) {
            return $response;
        }

        try {
            $routeName = $request->route()?->getName() ?? '';
            $logConfig = self::LOGGABLE_ROUTES[$routeName] ?? null;

            if ($logConfig === null) {
                return $response;
            }

            [$causerType, $causerId] = $this->resolveCauser($request);

            ActivityLog::create([
                'causer_type'   => $causerType,
                'causer_id'     => $causerId,
                'user_type'     => $this->resolveUserType($causerType),
                'user_id'       => $causerId,
                'action'        => $logConfig['action'],
                'module'        => $logConfig['module'],
                'description'   => $this->buildDescription($logConfig, $request),
                'ip_address'    => $request->ip(),
                'user_agent'    => $request->userAgent(),
                'metadata'      => [
                    'route'     => $routeName,
                    'method'    => $request->method(),
                    'url'       => $request->fullUrl(),
                    'status'    => $statusCode,
                ],
            ]);
        } catch (\Throwable $e) {
            // Never let logging break the request pipeline
            Log::channel('daily')->error('[ActivityLogger] Failed to log activity', [
                'error'   => $e->getMessage(),
                'route'   => $request->route()?->getName(),
                'method'  => $request->method(),
            ]);
        }

        return $response;
    }

    /**
     * Resolve the authenticated causer (admin, customer, or affiliator).
     *
     * @return array{0: string|null, 1: string|null}
     */
    private function resolveCauser(Request $request): array
    {
        foreach (['admin', 'customer', 'affiliator'] as $guard) {
            $user = Auth::guard($guard)->user();
            if ($user !== null) {
                $causerClass = match ($guard) {
                    'admin'      => \App\Models\Admin::class,
                    'customer'   => \App\Models\Customer::class,
                    'affiliator' => \App\Models\Affiliator::class,
                };
                return [$causerClass, $user->getKey()];
            }
        }

        return [null, null];
    }

    /**
     * Convert model class name to user type string.
     */
    private function resolveUserType(?string $causerClass): ?string
    {
        if ($causerClass === null) {
            return null;
        }

        return match ($causerClass) {
            \App\Models\Admin::class      => 'admin',
            \App\Models\Customer::class   => 'customer',
            \App\Models\Affiliator::class => 'affiliator',
            default                        => null,
        };
    }

    /**
     * Build a human-readable description for the activity.
     */
    private function buildDescription(array $logConfig, Request $request): string
    {
        $action  = $logConfig['action'];
        $module  = $logConfig['module'];
        $ip      = $request->ip();
        $method  = $request->method();

        return "[{$module}] {$action} via {$method} from {$ip}";
    }
}


