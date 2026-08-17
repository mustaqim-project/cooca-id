<?php

declare(strict_types=1);

namespace App\Listeners\Auth;

use App\Models\Admin;
use App\Models\Affiliator;
use App\Models\Customer;
use App\Services\AuditLogService;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\PasswordReset;

final class LogAuthenticationActivity
{
    /**
     * Handle Login event.
     */
    public function handleLogin(Login $event): void
    {
        $user = $event->user;
        $userType = $this->resolveUserType($user, $event->guard);
        $userName = $user->name ?? $user->email ?? 'User';

        AuditLogService::log(
            action: 'User Login (' . ucfirst($userType) . ')',
            userType: $userType,
            userId: (string) $user->getKey(),
            modelType: get_class($user),
            modelId: (string) $user->getKey(),
            oldValues: null,
            newValues: [
                'guard' => $event->guard,
                'email' => $user->email ?? null,
                'name' => $user->name ?? null,
            ],
            riskLevel: 'low',
            module: 'Authentication',
            description: sprintf('%s (%s) logged in successfully via %s guard', $userName, $user->email ?? '', $event->guard)
        );
    }

    /**
     * Handle Logout event.
     */
    public function handleLogout(Logout $event): void
    {
        $user = $event->user;
        if (!$user) {
            return;
        }

        $userType = $this->resolveUserType($user, $event->guard);
        $userName = $user->name ?? $user->email ?? 'User';

        AuditLogService::log(
            action: 'User Logout (' . ucfirst($userType) . ')',
            userType: $userType,
            userId: (string) $user->getKey(),
            modelType: get_class($user),
            modelId: (string) $user->getKey(),
            oldValues: null,
            newValues: [
                'guard' => $event->guard,
                'email' => $user->email ?? null,
            ],
            riskLevel: 'low',
            module: 'Authentication',
            description: sprintf('%s logged out from %s guard', $userName, $event->guard)
        );
    }

    /**
     * Handle Failed login event.
     */
    public function handleFailed(Failed $event): void
    {
        $email = $event->credentials['email'] ?? $event->credentials['phone'] ?? 'unknown';
        $guard = $event->guard ?? 'web';

        AuditLogService::log(
            action: 'Failed Login Attempt',
            userType: $guard === 'admin' ? 'admin' : ($guard === 'affiliator' ? 'affiliator' : 'customer'),
            userId: $event->user ? (string) $event->user->getKey() : null,
            modelType: $event->user ? get_class($event->user) : null,
            modelId: $event->user ? (string) $event->user->getKey() : null,
            oldValues: null,
            newValues: [
                'guard' => $guard,
                'attempted_identity' => $email,
            ],
            riskLevel: 'medium',
            module: 'Security',
            description: sprintf('Failed login attempt for "%s" on [%s] guard from IP %s', $email, $guard, request()?->ip() ?? 'N/A')
        );
    }

    /**
     * Handle PasswordReset event.
     */
    public function handlePasswordReset(PasswordReset $event): void
    {
        $user = $event->user;
        $userType = $this->resolveUserType($user);

        AuditLogService::log(
            action: 'Password Reset',
            userType: $userType,
            userId: (string) $user->getKey(),
            modelType: get_class($user),
            modelId: (string) $user->getKey(),
            oldValues: null,
            newValues: [
                'email' => $user->email ?? null,
            ],
            riskLevel: 'high',
            module: 'Security',
            description: sprintf('Password reset completed for %s (%s)', $user->name ?? '', $user->email ?? '')
        );
    }

    private function resolveUserType(mixed $user, ?string $guard = null): string
    {
        if ($guard === 'admin' || $user instanceof Admin) {
            return 'admin';
        }
        if ($guard === 'affiliator' || $user instanceof Affiliator) {
            return 'affiliator';
        }
        if ($guard === 'customer' || $user instanceof Customer) {
            return 'customer';
        }
        return 'customer';
    }
}
