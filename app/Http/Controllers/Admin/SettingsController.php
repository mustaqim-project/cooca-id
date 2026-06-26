<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'footer_description' => Setting::get('footer.description', ''),

            // Social Media
            'social_twitter' => Setting::get('social.twitter', ''),
            'social_linkedin' => Setting::get('social.linkedin', ''),
            'social_github' => Setting::get('social.github', ''),
            'social_instagram' => Setting::get('social.instagram', ''),

            // Landing Page
            'landing_hero_title' => Setting::get('landing.hero_title', ''),
            'landing_hero_subtitle' => Setting::get('landing.hero_subtitle', ''),
            'landing_hero_cta_text' => Setting::get('landing.hero_cta_text', ''),
            'landing_hero_cta_link' => Setting::get('landing.hero_cta_link', ''),

            // Affiliate & Payment (Existing)
            'midtrans_server_key' => '',
            'midtrans_client_key' => '',
            'midtrans_sandbox' => (bool) Setting::get('payment.midtrans_sandbox', config('services.midtrans.sandbox', true)),
            'affiliate_commission_l1' => (float) Setting::get('affiliate.commission_rate_level_1', config('affiliate.commission_rate_level_1', 25)),
            'affiliate_commission_l2' => (float) Setting::get('affiliate.commission_rate_level_2', config('affiliate.commission_rate_level_2', 5)),
            'withdrawal_fee_bank' => (float) Setting::get('affiliate.withdrawal_fee_bank', config('affiliate.withdrawal_fee_bank', 2500)),
            'withdrawal_fee_ewallet' => (float) Setting::get('affiliate.withdrawal_fee_ewallet', config('affiliate.withdrawal_fee_ewallet', 1000)),
            'minimum_withdrawal' => (float) Setting::get('affiliate.minimum_withdrawal', config('affiliate.minimum_withdrawal', 50000)),
        ];

        $seoPages = ['home', 'about', 'pricing', 'contact', 'solutions', 'features', 'affiliate', 'faq', 'docs', 'terms', 'privacy'];
        foreach ($seoPages as $page) {
            $settings['seo_' . $page . '_title'] = Setting::get('seo.' . $page . '.title', '');
            $settings['seo_' . $page . '_description'] = Setting::get('seo.' . $page . '.description', '');
            $settings['seo_' . $page . '_keywords'] = Setting::get('seo.' . $page . '.keywords', '');
        }

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
            'footer_description' => ['sometimes', 'nullable', 'string'],

            // Social
            'social_twitter' => ['sometimes', 'nullable', 'string', 'max:255'],
            'social_linkedin' => ['sometimes', 'nullable', 'string', 'max:255'],
            'social_github' => ['sometimes', 'nullable', 'string', 'max:255'],
            'social_instagram' => ['sometimes', 'nullable', 'string', 'max:255'],

            // Landing
            'landing_hero_title' => ['sometimes', 'nullable', 'string'],
            'landing_hero_subtitle' => ['sometimes', 'nullable', 'string'],
            'landing_hero_cta_text' => ['sometimes', 'nullable', 'string', 'max:255'],
            'landing_hero_cta_link' => ['sometimes', 'nullable', 'string', 'max:255'],

            // Affiliate & Payment
            'midtrans_server_key' => ['sometimes', 'nullable', 'string'],
            'midtrans_client_key' => ['sometimes', 'nullable', 'string'],
            'midtrans_sandbox' => ['sometimes', 'boolean'],
            'affiliate_commission_l1' => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:100'],
            'affiliate_commission_l2' => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:100'],
            'withdrawal_fee_bank' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'withdrawal_fee_ewallet' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'minimum_withdrawal' => ['sometimes', 'nullable', 'numeric', 'min:0'],
        ]);

        // Handle File Uploads
        if ($request->hasFile('logo')) {
            $file     = $request->file('logo');
            $filename = time() . '_logo.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/image'), $filename);
            Setting::updateOrCreate(
                ['key' => 'site.logo'],
                ['value' => '/assets/image/' . $filename, 'type' => 'image', 'group' => 'general']
            );
        }

        if ($request->hasFile('logo_light')) {
            $file     = $request->file('logo_light');
            $filename = time() . '_logo_light.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/image'), $filename);
            Setting::updateOrCreate(
                ['key' => 'site.logo_light'],
                ['value' => '/assets/image/' . $filename, 'type' => 'image', 'group' => 'general']
            );
        }

        if ($request->hasFile('logo_dark')) {
            $file     = $request->file('logo_dark');
            $filename = time() . '_logo_dark.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/image'), $filename);
            Setting::updateOrCreate(
                ['key' => 'site.logo_dark'],
                ['value' => '/assets/image/' . $filename, 'type' => 'image', 'group' => 'general']
            );
        }

        if ($request->hasFile('preloader_image_light')) {
            $file     = $request->file('preloader_image_light');
            $filename = time() . '_preloader_light.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/image'), $filename);
            Setting::updateOrCreate(
                ['key' => 'site.preloader_image_light'],
                ['value' => '/assets/image/' . $filename, 'type' => 'image', 'group' => 'general']
            );
        }

        if ($request->hasFile('preloader_image_dark')) {
            $file     = $request->file('preloader_image_dark');
            $filename = time() . '_preloader_dark.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/image'), $filename);
            Setting::updateOrCreate(
                ['key' => 'site.preloader_image_dark'],
                ['value' => '/assets/image/' . $filename, 'type' => 'image', 'group' => 'general']
            );
        }

        if ($request->hasFile('favicon')) {
            $file     = $request->file('favicon');
            $filename = time() . '_favicon.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/image'), $filename);
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
            'footer_description' => ['footer.description', 'text', 'footer'],

            'social_twitter' => ['social.twitter', 'string', 'social'],
            'social_linkedin' => ['social.linkedin', 'string', 'social'],
            'social_github' => ['social.github', 'string', 'social'],
            'social_instagram' => ['social.instagram', 'string', 'social'],

            'landing_hero_title' => ['landing.hero_title', 'string', 'landing'],
            'landing_hero_subtitle' => ['landing.hero_subtitle', 'string', 'landing'],
            'landing_hero_cta_text' => ['landing.hero_cta_text', 'string', 'landing'],
            'landing_hero_cta_link' => ['landing.hero_cta_link', 'string', 'landing'],

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
            if (in_array($field, ['logo', 'logo_light', 'logo_dark', 'preloader_image_light', 'preloader_image_dark', 'favicon'])) continue;

            if (($field === 'midtrans_server_key' || $field === 'midtrans_client_key') && blank($value)) {
                continue;
            }

            if (!isset($map[$field])) continue;

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
