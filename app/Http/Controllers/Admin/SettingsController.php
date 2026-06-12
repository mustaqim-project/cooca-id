<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

/**
 * Admin Settings Controller
 * 
 * Manages system settings and configurations.
 */
class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        $settings = [
            'general' => [
                'app_name' => config('app.name'),
                'app_url' => config('app.url'),
                'timezone' => config('app.timezone'),
                'locale' => config('app.locale'),
            ],
            'mail' => [
                'mail_host' => config('mail.mailers.smtp.host'),
                'mail_port' => config('mail.mailers.smtp.port'),
                'mail_from_address' => config('mail.from.address'),
                'mail_from_name' => config('mail.from.name'),
            ],
            'payment' => [
                'midtrans_server_key' => config('services.midtrans.server_key') ? 'configured' : 'not_configured',
                'midtrans_client_key' => config('services.midtrans.client_key') ? 'configured' : 'not_configured',
                'midtrans_is_production' => config('services.midtrans.is_production') ? 'yes' : 'no',
            ],
            'whatsapp' => [
                'fonnte_token' => config('services.fonnte.token') ? 'configured' : 'not_configured',
            ],
            'affiliate' => [
                'commission_rate_l1' => config('affiliate.commission_rate_level_1', 25),
                'commission_rate_l2' => config('affiliate.commission_rate_level_2', 5),
                'withdrawal_fee_bank' => config('affiliate.withdrawal_fee_bank', 2500),
                'withdrawal_fee_ewallet' => config('affiliate.withdrawal_fee_ewallet', 1000),
                'minimum_withdrawal' => config('affiliate.minimum_withdrawal', 50000),
            ],
        ];

        return Inertia::render('Admin/Settings/Index', [
            'settings' => $settings,
        ]);
    }

    /**
     * Update the specified settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            // General settings
            'general.app_name' => 'nullable|string|max:255',
            'general.timezone' => 'nullable|timezone',
            'general.locale' => 'nullable|string|max:10',
            
            // Mail settings
            'mail.mail_host' => 'nullable|string',
            'mail.mail_port' => 'nullable|integer',
            'mail.mail_from_address' => 'nullable|email',
            'mail.mail_from_name' => 'nullable|string|max:255',
            
            // Payment settings
            'payment.midtrans_server_key' => 'nullable|string',
            'payment.midtrans_client_key' => 'nullable|string',
            'payment.midtrans_is_production' => 'boolean',
            
            // WhatsApp settings
            'whatsapp.fonnte_token' => 'nullable|string',
            
            // Affiliate settings
            'affiliate.commission_rate_l1' => 'nullable|numeric|min:0|max:100',
            'affiliate.commission_rate_l2' => 'nullable|numeric|min:0|max:100',
            'affiliate.withdrawal_fee_bank' => 'nullable|numeric|min:0',
            'affiliate.withdrawal_fee_ewallet' => 'nullable|numeric|min:0',
            'affiliate.minimum_withdrawal' => 'nullable|numeric|min:0',
        ]);

        // Note: In production, you would want to store these in a settings table
        // and update the config dynamically. For now, we'll just simulate the update.
        
        // Store settings in cache or database for dynamic configuration
        foreach ($validated as $section => $values) {
            foreach ($values as $key => $value) {
                $settingKey = "{$section}.{$key}";
                
                // Store in database settings table (if exists) or cache
                DB::table('settings')->updateOrInsert(
                    ['key' => $settingKey],
                    ['value' => is_bool($value) ? ($value ? '1' : '0') : $value]
                );
            }
        }

        // Clear config cache
        // Artisan::call('config:clear');

        return back()->with('success', 'Settings updated successfully.');
    }
}
