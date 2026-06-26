<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class LandingCmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Site Identity
            ['key' => 'site.logo', 'value' => '', 'type' => 'string', 'group' => 'landing'],
            ['key' => 'site.favicon', 'value' => '', 'type' => 'string', 'group' => 'landing'],
            ['key' => 'site.preloader_text', 'value' => 'COOCA', 'type' => 'string', 'group' => 'landing'],
            
            // Hero Section
            ['key' => 'home.hero.badge', 'value' => 'High-Ticket Business Infrastructure', 'type' => 'string', 'group' => 'landing'],
            ['key' => 'home.hero.title', 'value' => 'Your Business System<br />Deserves to Work<br /><span class="text-gradient">Like an Asset, Not a Liability.</span>', 'type' => 'string', 'group' => 'landing'],
            ['key' => 'home.hero.subtitle', 'value' => '<strong>Most businesses lose revenue through fragmented tools.</strong> Disconnected systems, recurring fees that never stop, and software that owns you - not the other way around. There\'s a better way.', 'type' => 'string', 'group' => 'landing'],
            ['key' => 'home.hero.description', 'value' => 'COOCA replaces the chaos with <strong>one integrated system</strong> - lifetime license, modular ERP, and full control over your digital business infrastructure.', 'type' => 'string', 'group' => 'landing'],
            ['key' => 'home.hero.btn_primary', 'value' => 'View Pricing', 'type' => 'string', 'group' => 'landing'],
            ['key' => 'home.hero.btn_outline', 'value' => 'How It Works', 'type' => 'string', 'group' => 'landing'],
            ['key' => 'home.stats.1.value', 'value' => '10,000+', 'type' => 'string', 'group' => 'landing'],
            ['key' => 'home.stats.1.label', 'value' => 'Businesses Running on COOCA', 'type' => 'string', 'group' => 'landing'],
            ['key' => 'home.stats.2.value', 'value' => '99.9%', 'type' => 'string', 'group' => 'landing'],
            ['key' => 'home.stats.2.label', 'value' => 'System Uptime', 'type' => 'string', 'group' => 'landing'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                    'group' => $setting['group']
                ]
            );
        }
    }
}
