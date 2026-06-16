<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Affiliate Commission Rates
            [
                'key' => 'affiliate.commission.level_1',
                'value' => '25',
                'type' => 'float',
                'group' => 'affiliate',
                'description' => 'Commission percentage for level 1 affiliates',
                'is_public' => false,
            ],
            [
                'key' => 'affiliate.commission.level_2',
                'value' => '5',
                'type' => 'float',
                'group' => 'affiliate',
                'description' => 'Commission percentage for level 2 affiliates',
                'is_public' => false,
            ],
            [
                'key' => 'affiliate.commission.recurring',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'affiliate',
                'description' => 'Whether affiliates earn commission on renewals',
                'is_public' => false,
            ],

            // Trial Settings
            [
                'key' => 'trial.duration_days',
                'value' => '14',
                'type' => 'integer',
                'group' => 'trial',
                'description' => 'Number of days for free trial period',
                'is_public' => true,
            ],

            // Withdrawal Settings
            [
                'key' => 'withdrawal.minimum_amount',
                'value' => '50000',
                'type' => 'float',
                'group' => 'withdrawal',
                'description' => 'Minimum amount required for withdrawal request (IDR)',
                'is_public' => true,
            ],
            [
                'key' => 'withdrawal.fee_percent',
                'value' => '0',
                'type' => 'float',
                'group' => 'withdrawal',
                'description' => 'Fee percentage charged on withdrawals',
                'is_public' => true,
            ],

            // Notification Settings
            [
                'key' => 'notification.email_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'notification',
                'description' => 'Whether to send email notifications',
                'is_public' => false,
            ],
            [
                'key' => 'notification.whatsapp_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'notification',
                'description' => 'Whether to send WhatsApp notifications',
                'is_public' => false,
            ],

            // System Settings
            [
                'key' => 'system.maintenance_mode',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'system',
                'description' => 'Enable maintenance mode',
                'is_public' => false,
            ],
            [
                'key' => 'system.registration_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'system',
                'description' => 'Allow new user registrations',
                'is_public' => true,
            ],

            // SEO Settings
            [
                'key' => 'seo.title',
                'value' => 'COOCA - Business System That Works Like an Asset',
                'type' => 'string',
                'group' => 'seo',
                'description' => 'Website SEO title',
                'is_public' => true,
            ],
            [
                'key' => 'seo.description',
                'value' => 'Stop losing revenue to fragmented systems. COOCA is the integrated business infrastructure that gives you lifetime license protection, modular ERP, and a system that scales with your ambition.',
                'type' => 'string',
                'group' => 'seo',
                'description' => 'Website SEO description',
                'is_public' => true,
            ],
            [
                'key' => 'seo.og_image',
                'value' => 'images/og-default.jpg',
                'type' => 'string',
                'group' => 'seo',
                'description' => 'Open Graph image URL',
                'is_public' => true,
            ],

            // Branding Settings
            [
                'key' => 'branding.name',
                'value' => 'COOCA',
                'type' => 'string',
                'group' => 'branding',
                'description' => 'Company branding name',
                'is_public' => true,
            ],
            [
                'key' => 'branding.tagline',
                'value' => 'The Business System That Works Like an Asset',
                'type' => 'string',
                'group' => 'branding',
                'description' => 'Company tagline',
                'is_public' => true,
            ],
            [
                'key' => 'branding.favicon',
                'value' => 'favicon.ico',
                'type' => 'string',
                'group' => 'branding',
                'description' => 'Favicon file path',
                'is_public' => true,
            ],
            [
                'key' => 'branding.apple_touch_icon',
                'value' => 'images/apple-touch-icon.png',
                'type' => 'string',
                'group' => 'branding',
                'description' => 'Apple touch icon file path',
                'is_public' => true,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('Settings seeded successfully!');
    }
}
