<?php

namespace Database\Seeders;

use App\Models\CompanyInfo;
use Illuminate\Database\Seeder;

class CompanyInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companyData = [
            // General Information
            [
                'key' => 'company_name',
                'value' => 'PT COOCA Indonesia',
                'type' => 'text',
                'group' => 'general',
                'is_active' => true,
            ],
            [
                'key' => 'tagline',
                'value' => 'The Business System That Works Like an Asset',
                'type' => 'text',
                'group' => 'general',
                'is_active' => true,
            ],
            [
                'key' => 'description',
                'value' => 'COOCA adalah platform ERP terintegrasi yang menyediakan solusi bisnis komprehensif untuk berbagai industri.',
                'type' => 'text',
                'group' => 'general',
                'is_active' => true,
            ],

            // Contact Information
            [
                'key' => 'email',
                'value' => 'support@cooca.id',
                'type' => 'text',
                'group' => 'contact',
                'is_active' => true,
            ],
            [
                'key' => 'phone',
                'value' => '+62 21 1234 5678',
                'type' => 'text',
                'group' => 'contact',
                'is_active' => true,
            ],
            [
                'key' => 'whatsapp',
                'value' => '+62 812 3456 7890',
                'type' => 'text',
                'group' => 'contact',
                'is_active' => true,
            ],
            [
                'key' => 'address',
                'value' => 'Jl. Merdeka No. 123, Jakarta Pusat, DKI Jakarta 12345',
                'type' => 'text',
                'group' => 'contact',
                'is_active' => true,
            ],
            [
                'key' => 'city',
                'value' => 'Jakarta',
                'type' => 'text',
                'group' => 'contact',
                'is_active' => true,
            ],
            [
                'key' => 'province',
                'value' => 'DKI Jakarta',
                'type' => 'text',
                'group' => 'contact',
                'is_active' => true,
            ],
            [
                'key' => 'country',
                'value' => 'Indonesia',
                'type' => 'text',
                'group' => 'contact',
                'is_active' => true,
            ],
            [
                'key' => 'website_url',
                'value' => 'https://cooca.id',
                'type' => 'text',
                'group' => 'contact',
                'is_active' => true,
            ],

            // Social Media
            [
                'key' => 'facebook_url',
                'value' => 'https://facebook.com/coocaid',
                'type' => 'text',
                'group' => 'social_media',
                'is_active' => true,
            ],
            [
                'key' => 'twitter_url',
                'value' => 'https://twitter.com/coocaid',
                'type' => 'text',
                'group' => 'social_media',
                'is_active' => true,
            ],
            [
                'key' => 'instagram_url',
                'value' => 'https://instagram.com/cooca.id',
                'type' => 'text',
                'group' => 'social_media',
                'is_active' => true,
            ],
            [
                'key' => 'linkedin_url',
                'value' => 'https://linkedin.com/company/cooca-indonesia',
                'type' => 'text',
                'group' => 'social_media',
                'is_active' => true,
            ],
            [
                'key' => 'youtube_url',
                'value' => 'https://youtube.com/coocaid',
                'type' => 'text',
                'group' => 'social_media',
                'is_active' => true,
            ],

            // SEO Information
            [
                'key' => 'seo_title',
                'value' => 'COOCA - Business System That Works Like an Asset',
                'type' => 'text',
                'group' => 'seo',
                'is_active' => true,
            ],
            [
                'key' => 'seo_description',
                'value' => 'Stop losing revenue to fragmented systems. COOCA is the integrated business infrastructure that gives you lifetime license protection, modular ERP, and a system that scales with your ambition.',
                'type' => 'text',
                'group' => 'seo',
                'is_active' => true,
            ],
            [
                'key' => 'og_image',
                'value' => 'images/og-default.jpg',
                'type' => 'text',
                'group' => 'seo',
                'is_active' => true,
            ],
            [
                'key' => 'favicon',
                'value' => 'favicon.ico',
                'type' => 'text',
                'group' => 'seo',
                'is_active' => true,
            ],
        ];

        foreach ($companyData as $data) {
            CompanyInfo::updateOrCreate(
                ['key' => $data['key']],
                $data
            );
        }

        $this->command->info('Company information seeded successfully.');
    }
}
