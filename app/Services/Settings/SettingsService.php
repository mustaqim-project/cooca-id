<?php

namespace App\Services\Settings;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    protected const CACHE_PREFIX = 'settings_';

    /**
     * Get a setting value
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember(
            self::CACHE_PREFIX . $key,
            now()->addHours(24),
            fn () => Setting::get($key, $default)
        );
    }

    /**
     * Set a setting value
     */
    public function set(string $key, mixed $value, string $type = 'string'): void
    {
        Setting::set($key, $value, $type);
        Cache::forget(self::CACHE_PREFIX . $key);
    }

    /**
     * Get all settings in a group
     */
    public function getGroup(string $group): array
    {
        return Cache::remember(
            self::CACHE_PREFIX . 'group_' . $group,
            now()->addHours(24),
            fn () => Setting::getGroup($group)
        );
    }

    /**
     * Clear cache for a setting
     */
    public function clearCache(string $key): void
    {
        Cache::forget(self::CACHE_PREFIX . $key);
    }

    /**
     * Clear all settings cache
     */
    public function clearAllCache(): void
    {
        Cache::tags(['settings'])->flush();
    }

    /**
     * Get affiliate commission rate
     */
    public function getCommissionRate(int $level): float
    {
        $key = match ($level) {
            1 => 'affiliate.commission_rate_level_1',
            2 => 'affiliate.commission_rate_level_2',
            default => 'affiliate.commission_rate_level_' . $level,
        };

        return (float) $this->get($key, $level === 1 ? 25.0 : 5.0);
    }

    /**
     * Get trial duration in days
     */
    public function getTrialDuration(): int
    {
        return (int) $this->get('subscription.trial_duration_days', config('affiliate.trial_duration_days', 14));
    }

    /**
     * Get withdrawal fee
     */
    public function getWithdrawalFee(): float
    {
        return (float) $this->get('affiliate.withdrawal_fee_bank', config('affiliate.withdrawal_fee_bank', 0));
    }

    /**
     * Get company information
     */
    public function getCompanyInfo(string $key, mixed $default = null): mixed
    {
        return $this->get('company_' . $key, $default);
    }

    /**
     * Get email settings
     */
    public function getEmailSettings(): array
    {
        return $this->getGroup('email');
    }

    /**
     * Get notification settings
     */
    public function getNotificationSettings(): array
    {
        return $this->getGroup('notification');
    }

    /**
     * Get landing page settings
     */
    public function getLandingSettings(): array
    {
        return $this->getGroup('landing');
    }
}
