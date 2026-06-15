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
                'label' => 'Level 1 Commission Rate (%)',
                'description' => 'Commission percentage for level 1 affiliates',
                'is_public' => false,
            ],
            [
                'key' => 'affiliate.commission.level_2',
                'value' => '5',
                'type' => 'float',
                'group' => 'affiliate',
                'label' => 'Level 2 Commission Rate (%)',
                'description' => 'Commission percentage for level 2 affiliates',
                'is_public' => false,
            ],
            [
                'key' => 'affiliate.commission.recurring',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'affiliate',
                'label' => 'Enable Recurring Commission',
                'description' => 'Whether affiliates earn commission on renewals',
                'is_public' => false,
            ],
            
            // Trial Settings
            [
                'key' => 'trial.duration_days',
                'value' => '14',
                'type' => 'integer',
                'group' => 'trial',
                'label' => 'Trial Duration (Days)',
                'description' => 'Number of days for free trial period',
                'is_public' => true,
            ],
            
            // Withdrawal Settings
            [
                'key' => 'withdrawal.minimum_amount',
                'value' => '50000',
                'type' => 'float',
                'group' => 'withdrawal',
                'label' => 'Minimum Withdrawal Amount',
                'description' => 'Minimum amount required for withdrawal request (IDR)',
                'is_public' => true,
            ],
            [
                'key' => 'withdrawal.fee_percent',
                'value' => '0',
                'type' => 'float',
                'group' => 'withdrawal',
                'label' => 'Withdrawal Fee (%)',
                'description' => 'Fee percentage charged on withdrawals',
                'is_public' => true,
            ],
            
            // Notification Settings
            [
                'key' => 'notification.email_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'notification',
                'label' => 'Enable Email Notifications',
                'description' => 'Whether to send email notifications',
                'is_public' => false,
            ],
            [
                'key' => 'notification.whatsapp_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'notification',
                'label' => 'Enable WhatsApp Notifications',
                'description' => 'Whether to send WhatsApp notifications',
                'is_public' => false,
            ],
            
            // System Settings
            [
                'key' => 'system.maintenance_mode',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'system',
                'label' => 'Maintenance Mode',
                'description' => 'Enable maintenance mode',
                'is_public' => false,
            ],
            [
                'key' => 'system.registration_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'system',
                'label' => 'Registration Enabled',
                'description' => 'Allow new user registrations',
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
