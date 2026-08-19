<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\ImageService;
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
            // General
            'platform_name'  => Setting::get('site.name', config('app.name')),
            'logo_url'       => Setting::get('site.logo', ''),       // legacy/fallback
            'logo_light_url' => Setting::get('site.logo_light', ''), // logo for light theme
            'logo_dark_url'  => Setting::get('site.logo_dark', ''),  // logo for dark theme
            'preloader_image_light_url' => Setting::get('site.preloader_image_light', ''),
            'preloader_image_dark_url'  => Setting::get('site.preloader_image_dark', ''),
            'favicon_url'    => Setting::get('site.favicon', ''),
            'preloader_text' => Setting::get('site.preloader_text', 'COOCA'),

            // Contact & Footer
            'email_support' => Setting::get('contact.email', 'hello@cooca.id'),
            'whatsapp_number' => Setting::get('contact.whatsapp', '6281234567890'),
            'whatsapp_link' => Setting::get('contact.whatsapp_link', 'https://wa.me/6281234567890'),
            'whatsapp_notifications_active' => (bool) Setting::get('whatsapp.notifications_active', true),
            'contact_address' => Setting::get('contact.address', 'Jl. Jend. Sudirman No. 52, Jakarta Selatan, DKI Jakarta 12920'),
            'footer_description' => Setting::get('footer.description', ''),

            // Social Media
            'social_twitter' => Setting::get('social.twitter', ''),
            'social_linkedin' => Setting::get('social.linkedin', ''),
            'social_github' => Setting::get('social.github', ''),
            'social_instagram' => Setting::get('social.instagram', ''),
            'social_facebook' => Setting::get('social.facebook', ''),
            'social_youtube' => Setting::get('social.youtube', ''),

            // Landing Page
            'landing_hero_title' => Setting::get('landing.hero_title', ''),
            'landing_hero_subtitle' => Setting::get('landing.hero_subtitle', ''),
            'landing_hero_cta_text' => Setting::get('landing.hero_cta_text', ''),
            'landing_hero_cta_link' => Setting::get('landing.hero_cta_link', ''),

            // Affiliate (Existing)
            'affiliate_commission_l1' => (float) Setting::get('affiliate.commission_rate_level_1', config('affiliate.commission_rate_level_1', 25)),
            'affiliate_commission_l2' => (float) Setting::get('affiliate.commission_rate_level_2', config('affiliate.commission_rate_level_2', 5)),
            'withdrawal_fee_bank' => (float) Setting::get('affiliate.withdrawal_fee_bank', config('affiliate.withdrawal_fee_bank', 2500)),
            'withdrawal_fee_ewallet' => (float) Setting::get('affiliate.withdrawal_fee_ewallet', config('affiliate.withdrawal_fee_ewallet', 1000)),
            'minimum_withdrawal' => (float) Setting::get('affiliate.minimum_withdrawal', config('affiliate.minimum_withdrawal', 50000)),

            // SEO Options
            'google_no_follow' => (bool) Setting::get('seo.google_no_follow', false),

            // Bank Transfer (Manual Payment)
            'bank_transfer_active' => (bool) Setting::get('payment.bank_transfer.active', true),
            'bank_transfer_bank_name' => Setting::get('payment.bank_transfer.bank_name', 'Bank Central Asia (BCA)'),
            'bank_transfer_account_number' => Setting::get('payment.bank_transfer.account_number', '8830-8899-8800'),
            'bank_transfer_account_name' => Setting::get('payment.bank_transfer.account_name', 'PT COOCA TECHNOLOGIES INDONESIA'),
            'bank_transfer_instructions' => Setting::get('payment.bank_transfer.instructions', 'Silakan transfer sesuai jumlah total tagihan hingga digit terakhir. Setelah melakukan transfer, wajib mengunggah foto / screenshot / file bukti bayar agar verifikasi dapat diproses oleh tim kami.'),

            // Hostinger API Settings
            'hostinger_api_token' => Setting::get('hostinger.api_token', ''),
            'hostinger_api_url' => Setting::get('hostinger.api_url', 'https://developers.hostinger.com/api'),
            'hostinger_usd_to_idr_rate' => Setting::get('hostinger.usd_to_idr_rate', 16000),
        ];

        $seoPages = ['home', 'about', 'pricing', 'contact', 'solutions', 'features', 'affiliate', 'faq', 'docs', 'terms', 'privacy'];
        foreach ($seoPages as $page) {
            $settings['seo_' . $page . '_title'] = Setting::get('seo.' . $page . '.title', '');
            $settings['seo_' . $page . '_description'] = Setting::get('seo.' . $page . '.description', '');
            $settings['seo_' . $page . '_keywords'] = Setting::get('seo.' . $page . '.keywords', '');
        }

        $integrations = \App\Models\ApiIntegration::orderBy('provider')->get();
        $schemas = \App\Http\Controllers\Admin\ApiIntegrationController::PROVIDER_SCHEMAS;

        // Ensure all known providers exist in the list
        $existingProviders = $integrations->pluck('provider')->toArray();
        foreach ($schemas as $provider => $schema) {
            if (!in_array($provider, $existingProviders)) {
                $integrations->push(new \App\Models\ApiIntegration([
                    'provider'  => $provider,
                    'name'      => $schema['name'],
                    'config'    => [],
                    'is_active' => false,
                ]));
            }
        }

        $bankAccounts = \App\Models\CompanyBankAccount::ordered()->get();

        return view('admin.settings.index', [
            'settings' => $settings,
            'seoPages' => $seoPages,
            'integrations' => $integrations,
            'schemas' => $schemas,
            'bankAccounts' => $bankAccounts,
        ]);
    }

    /**
     * Update the specified settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            // General
            'platform_name'  => ['sometimes', 'nullable', 'string', 'max:255'],
            'logo'           => ['sometimes', 'nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:2048'],
            'logo_light'     => ['sometimes', 'nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:2048'],
            'logo_dark'      => ['sometimes', 'nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:2048'],
            'preloader_image_light' => ['sometimes', 'nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp,gif', 'max:2048'],
            'preloader_image_dark'  => ['sometimes', 'nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp,gif', 'max:2048'],
            'favicon'        => ['sometimes', 'nullable', 'image', 'max:1024'],
            'preloader_text' => ['sometimes', 'nullable', 'string', 'max:255'],

            // Contact
            'email_support' => ['sometimes', 'nullable', 'email'],
            'whatsapp_number' => ['sometimes', 'nullable', 'string', 'max:50'],
            'whatsapp_link' => ['sometimes', 'nullable', 'url', 'max:255'],
            'contact_address' => ['sometimes', 'nullable', 'string'],
            'footer_description' => ['sometimes', 'nullable', 'string'],

            // Social
            'social_twitter' => ['sometimes', 'nullable', 'string', 'max:255'],
            'social_linkedin' => ['sometimes', 'nullable', 'string', 'max:255'],
            'social_github' => ['sometimes', 'nullable', 'string', 'max:255'],
            'social_instagram' => ['sometimes', 'nullable', 'string', 'max:255'],
            'social_facebook' => ['sometimes', 'nullable', 'string', 'max:255'],
            'social_youtube' => ['sometimes', 'nullable', 'string', 'max:255'],

            // Landing
            'landing_hero_title' => ['sometimes', 'nullable', 'string'],
            'landing_hero_subtitle' => ['sometimes', 'nullable', 'string'],
            'landing_hero_cta_text' => ['sometimes', 'nullable', 'string', 'max:255'],
            'landing_hero_cta_link' => ['sometimes', 'nullable', 'string', 'max:255'],

            // Affiliate
            'affiliate_commission_l1' => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:100'],
            'affiliate_commission_l2' => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:100'],
            'withdrawal_fee_bank' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'withdrawal_fee_ewallet' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'minimum_withdrawal' => ['sometimes', 'nullable', 'numeric', 'min:0'],

            // SEO Options
            'google_no_follow' => ['sometimes', 'boolean'],

            // WhatsApp Settings
            'whatsapp_notifications_active' => ['sometimes', 'boolean'],

            // Bank Transfer
            'bank_transfer_active' => ['sometimes', 'boolean'],
            'bank_transfer_bank_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'bank_transfer_account_number' => ['sometimes', 'nullable', 'string', 'max:100'],
            'bank_transfer_account_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'bank_transfer_instructions' => ['sometimes', 'nullable', 'string'],

            // Hostinger API Settings
            'hostinger_api_token' => ['sometimes', 'nullable', 'string'],
            'hostinger_api_url' => ['sometimes', 'nullable', 'string', 'max:255'],
            'hostinger_usd_to_idr_rate' => ['sometimes', 'nullable', 'numeric', 'min:1'],
        ]);

        // Handle File Uploads
        $imageService = app(ImageService::class);
        $imageDir     = public_path('assets/image');

        if ($request->hasFile('logo')) {
            $filename = $imageService->saveToPublic($request->file('logo'), $imageDir, time() . '_logo');
            Setting::updateOrCreate(
                ['key' => 'site.logo'],
                ['value' => '/assets/image/' . $filename, 'type' => 'image', 'group' => 'general']
            );
        }

        if ($request->hasFile('logo_light')) {
            $filename = $imageService->saveToPublic($request->file('logo_light'), $imageDir, time() . '_logo_light');
            Setting::updateOrCreate(
                ['key' => 'site.logo_light'],
                ['value' => '/assets/image/' . $filename, 'type' => 'image', 'group' => 'general']
            );
        }

        if ($request->hasFile('logo_dark')) {
            $filename = $imageService->saveToPublic($request->file('logo_dark'), $imageDir, time() . '_logo_dark');
            Setting::updateOrCreate(
                ['key' => 'site.logo_dark'],
                ['value' => '/assets/image/' . $filename, 'type' => 'image', 'group' => 'general']
            );
        }

        if ($request->hasFile('preloader_image_light')) {
            $filename = $imageService->saveToPublic($request->file('preloader_image_light'), $imageDir, time() . '_preloader_light');
            Setting::updateOrCreate(
                ['key' => 'site.preloader_image_light'],
                ['value' => '/assets/image/' . $filename, 'type' => 'image', 'group' => 'general']
            );
        }

        if ($request->hasFile('preloader_image_dark')) {
            $filename = $imageService->saveToPublic($request->file('preloader_image_dark'), $imageDir, time() . '_preloader_dark');
            Setting::updateOrCreate(
                ['key' => 'site.preloader_image_dark'],
                ['value' => '/assets/image/' . $filename, 'type' => 'image', 'group' => 'general']
            );
        }

        if ($request->hasFile('favicon')) {
            $filename = $imageService->saveToPublic($request->file('favicon'), $imageDir, time() . '_favicon');
            Setting::updateOrCreate(
                ['key' => 'site.favicon'],
                ['value' => '/assets/image/' . $filename, 'type' => 'image', 'group' => 'general']
            );
        }

        // Mapping textual fields
        $map = [
            'platform_name' => ['site.name', 'string', 'general'],
            'preloader_text' => ['site.preloader_text', 'string', 'general'],

            'email_support' => ['contact.email', 'string', 'contact'],
            'whatsapp_number' => ['contact.whatsapp', 'string', 'contact'],
            'whatsapp_link' => ['contact.whatsapp_link', 'string', 'contact'],
            'contact_address' => ['contact.address', 'text', 'contact'],
            'footer_description' => ['footer.description', 'text', 'footer'],

            'social_twitter' => ['social.twitter', 'string', 'social'],
            'social_linkedin' => ['social.linkedin', 'string', 'social'],
            'social_github' => ['social.github', 'string', 'social'],
            'social_instagram' => ['social.instagram', 'string', 'social'],
            'social_facebook' => ['social.facebook', 'string', 'social'],
            'social_youtube' => ['social.youtube', 'string', 'social'],

            'landing_hero_title' => ['landing.hero_title', 'string', 'landing'],
            'landing_hero_subtitle' => ['landing.hero_subtitle', 'string', 'landing'],
            'landing_hero_cta_text' => ['landing.hero_cta_text', 'string', 'landing'],
            'landing_hero_cta_link' => ['landing.hero_cta_link', 'string', 'landing'],

            'affiliate_commission_l1' => ['affiliate.commission_rate_level_1', 'float', 'affiliate'],
            'affiliate_commission_l2' => ['affiliate.commission_rate_level_2', 'float', 'affiliate'],
            'withdrawal_fee_bank' => ['affiliate.withdrawal_fee_bank', 'float', 'affiliate'],
            'withdrawal_fee_ewallet' => ['affiliate.withdrawal_fee_ewallet', 'float', 'affiliate'],
            'minimum_withdrawal' => ['affiliate.minimum_withdrawal', 'float', 'affiliate'],

            'bank_transfer_bank_name' => ['payment.bank_transfer.bank_name', 'string', 'payment'],
            'bank_transfer_account_number' => ['payment.bank_transfer.account_number', 'string', 'payment'],
            'bank_transfer_account_name' => ['payment.bank_transfer.account_name', 'string', 'payment'],
            'bank_transfer_instructions' => ['payment.bank_transfer.instructions', 'text', 'payment'],

            'hostinger_api_token' => ['hostinger.api_token', 'string', 'hostinger'],
            'hostinger_api_url' => ['hostinger.api_url', 'string', 'hostinger'],
            'hostinger_usd_to_idr_rate' => ['hostinger.usd_to_idr_rate', 'integer', 'hostinger'],

            'google_no_follow' => ['seo.google_no_follow', 'boolean', 'seo'],
        ];

        foreach ($validated as $field => $value) {
            if (in_array($field, ['logo', 'logo_light', 'logo_dark', 'preloader_image_light', 'preloader_image_dark', 'favicon', 'google_no_follow', 'whatsapp_notifications_active', 'bank_transfer_active'])) continue;

            if (!isset($map[$field])) continue;

            [$key, $type, $group] = $map[$field];

            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => (string) $value,
                    'type' => $type,
                    'group' => $group,
                    'updated_by' => $request->user('admin')?->id,
                ]
            );
        }

        // Handle Booleans explicitly
        Setting::updateOrCreate(
            ['key' => 'seo.google_no_follow'],
            [
                'value' => $request->boolean('google_no_follow') ? '1' : '0',
                'type' => 'boolean',
                'group' => 'seo',
                'updated_by' => $request->user('admin')?->id,
            ]
        );

        Setting::updateOrCreate(
            ['key' => 'whatsapp.notifications_active'],
            [
                'value' => $request->boolean('whatsapp_notifications_active') ? '1' : '0',
                'type' => 'boolean',
                'group' => 'contact',
                'updated_by' => $request->user('admin')?->id,
            ]
        );

        Setting::updateOrCreate(
            ['key' => 'payment.bank_transfer.active'],
            [
                'value' => $request->boolean('bank_transfer_active') ? '1' : '0',
                'type' => 'boolean',
                'group' => 'payment',
                'updated_by' => $request->user('admin')?->id,
            ]
        );

        $seoPages = ['home', 'about', 'pricing', 'contact', 'solutions', 'features', 'affiliate', 'faq', 'docs', 'terms', 'privacy'];
        foreach ($seoPages as $page) {
            if ($request->has('seo_' . $page . '_title')) {
                Setting::updateOrCreate(
                    ['key' => 'seo.' . $page . '.title'],
                    [
                        'value' => (string) $request->input('seo_' . $page . '_title'),
                        'type' => 'string',
                        'group' => 'seo',
                        'updated_by' => $request->user('admin')?->id,
                    ]
                );
            }
            if ($request->has('seo_' . $page . '_description')) {
                Setting::updateOrCreate(
                    ['key' => 'seo.' . $page . '.description'],
                    [
                        'value' => (string) $request->input('seo_' . $page . '_description'),
                        'type' => 'text',
                        'group' => 'seo',
                        'updated_by' => $request->user('admin')?->id,
                    ]
                );
            }
            if ($request->has('seo_' . $page . '_keywords')) {
                Setting::updateOrCreate(
                    ['key' => 'seo.' . $page . '.keywords'],
                    [
                        'value' => (string) $request->input('seo_' . $page . '_keywords'),
                        'type' => 'string',
                        'group' => 'seo',
                        'updated_by' => $request->user('admin')?->id,
                    ]
                );
            }
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
