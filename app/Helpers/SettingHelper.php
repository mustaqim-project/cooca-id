<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

/**
 * Helper function to retrieve settings from database or cache.
 * 
 * @param string $key The setting key
 * @param mixed $default Default value if setting not found
 * @return mixed
 */
if (!function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        $settings = Cache::rememberForever('settings_all', function () {
            return Setting::all()->pluck('value', 'key')->toArray();
        });

        return $settings[$key] ?? $default;
    }
}

/**
 * Helper function to update a setting.
 * 
 * @param string $key The setting key
 * @param mixed $value The value to set
 * @return bool
 */
if (!function_exists('update_setting')) {
    function update_setting(string $key, mixed $value): bool
    {
        $setting = Setting::firstOrCreate(['key' => $key]);
        $setting->update(['value' => $value]);
        
        // Clear cache
        Cache::forget('settings_all');
        
        return true;
    }
}
