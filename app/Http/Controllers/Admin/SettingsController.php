<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;


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
            'platform_name' => Setting::get('general.platform_name', config('app.name')),
            'logo_url' => Setting::get('general.logo_url'),
            'email_support' => Setting::get('general.email_support', config('mail.from.address')),
            'email_info' => Setting::get('general.email_info', config('mail.from.address')),
            'email_marketing' => Setting::get('general.email_marketing', config('mail.from.address')),
            'email_noreply' => Setting::get('general.email_noreply', config('mail.from.address')),
            'whatsapp_number' => Setting::get('general.whatsapp_number', ''),
            'midtrans_server_key' => '',
            'midtrans_client_key' => '',
            'midtrans_sandbox' => (bool) Setting::get('payment.midtrans_sandbox', config('services.midtrans.sandbox', true)),
            'affiliate_commission_l1' => (float) Setting::get('affiliate.commission_rate_level_1', config('affiliate.commission_rate_level_1', 25)),
            'affiliate_commission_l2' => (float) Setting::get('affiliate.commission_rate_level_2', config('affiliate.commission_rate_level_2', 5)),
            'withdrawal_fee_bank' => (float) Setting::get('affiliate.withdrawal_fee_bank', config('affiliate.withdrawal_fee_bank', 2500)),
            'withdrawal_fee_ewallet' => (float) Setting::get('affiliate.withdrawal_fee_ewallet', config('affiliate.withdrawal_fee_ewallet', 1000)),
            'minimum_withdrawal' => (float) Setting::get('affiliate.minimum_withdrawal', config('affiliate.minimum_withdrawal', 50000)),
        ];

        return view('admin.settings.index', [
            'settings' => $settings,
        ]);
    }

    /**
     * Update the specified settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'platform_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'logo_url' => ['sometimes', 'nullable', 'url', 'max:2048'],
            'email_support' => ['sometimes', 'nullable', 'email'],
            'email_info' => ['sometimes', 'nullable', 'email'],
            'email_marketing' => ['sometimes', 'nullable', 'email'],
            'email_noreply' => ['sometimes', 'nullable', 'email'],
            'whatsapp_number' => ['sometimes', 'nullable', 'string', 'max:50'],
            'midtrans_server_key' => ['sometimes', 'nullable', 'string'],
            'midtrans_client_key' => ['sometimes', 'nullable', 'string'],
            'midtrans_sandbox' => ['sometimes', 'boolean'],
            'affiliate_commission_l1' => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:100'],
            'affiliate_commission_l2' => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:100'],
            'withdrawal_fee_bank' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'withdrawal_fee_ewallet' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'minimum_withdrawal' => ['sometimes', 'nullable', 'numeric', 'min:0'],
        ]);

        $map = [
            'platform_name' => ['general.platform_name', 'string', 'general'],
            'logo_url' => ['general.logo_url', 'string', 'general'],
            'email_support' => ['general.email_support', 'string', 'general'],
            'email_info' => ['general.email_info', 'string', 'general'],
            'email_marketing' => ['general.email_marketing', 'string', 'general'],
            'email_noreply' => ['general.email_noreply', 'string', 'general'],
            'whatsapp_number' => ['general.whatsapp_number', 'string', 'general'],
            'midtrans_server_key' => ['payment.midtrans_server_key', 'string', 'payment'],
            'midtrans_client_key' => ['payment.midtrans_client_key', 'string', 'payment'],
            'midtrans_sandbox' => ['payment.midtrans_sandbox', 'boolean', 'payment'],
            'affiliate_commission_l1' => ['affiliate.commission_rate_level_1', 'float', 'affiliate'],
            'affiliate_commission_l2' => ['affiliate.commission_rate_level_2', 'float', 'affiliate'],
            'withdrawal_fee_bank' => ['affiliate.withdrawal_fee_bank', 'float', 'affiliate'],
            'withdrawal_fee_ewallet' => ['affiliate.withdrawal_fee_ewallet', 'float', 'affiliate'],
            'minimum_withdrawal' => ['affiliate.minimum_withdrawal', 'float', 'affiliate'],
        ];

        foreach ($validated as $field => $value) {
            if (($field === 'midtrans_server_key' || $field === 'midtrans_client_key') && blank($value)) {
                continue;
            }

            [$key, $type, $group] = $map[$field];

            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => is_bool($value) ? ($value ? '1' : '0') : (string) $value,
                    'type' => $type,
                    'group' => $group,
                    'updated_by' => $request->user('admin')?->id,
                ]
            );
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
