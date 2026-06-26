<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CmsSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General Settings
            ['key' => 'site.name', 'value' => 'COOCA', 'type' => 'string', 'group' => 'general', 'description' => 'Nama Perusahaan atau Website'],
            ['key' => 'site.logo', 'value' => '', 'type' => 'image', 'group' => 'general', 'description' => 'Logo Website'],
            ['key' => 'site.favicon', 'value' => '', 'type' => 'image', 'group' => 'general', 'description' => 'Favicon Website'],
            ['key' => 'site.preloader_text', 'value' => 'COOCA', 'type' => 'string', 'group' => 'general', 'description' => 'Teks Preloader'],

            // Contact & Footer Settings
            ['key' => 'contact.email', 'value' => 'hello@cooca.id', 'type' => 'string', 'group' => 'contact', 'description' => 'Email Support'],
            ['key' => 'contact.whatsapp', 'value' => '6281234567890', 'type' => 'string', 'group' => 'contact', 'description' => 'Nomor WhatsApp Admin (gunakan kode negara 62)'],
            ['key' => 'contact.whatsapp_link', 'value' => 'https://wa.me/6281234567890', 'type' => 'string', 'group' => 'contact', 'description' => 'Link WhatsApp Floating Button'],
            ['key' => 'footer.description', 'value' => 'The business system that works like an asset. Lifetime license, modular ERP, and complete digital infrastructure for serious businesses.', 'type' => 'text', 'group' => 'footer', 'description' => 'Deskripsi Singkat di Footer'],
            
            // Social Media
            ['key' => 'social.twitter', 'value' => 'https://twitter.com/cooca', 'type' => 'string', 'group' => 'social', 'description' => 'Link Twitter (X)'],
            ['key' => 'social.linkedin', 'value' => 'https://linkedin.com/company/cooca', 'type' => 'string', 'group' => 'social', 'description' => 'Link LinkedIn'],
            ['key' => 'social.github', 'value' => 'https://github.com/cooca', 'type' => 'string', 'group' => 'social', 'description' => 'Link GitHub'],
            ['key' => 'social.instagram', 'value' => 'https://instagram.com/cooca', 'type' => 'string', 'group' => 'social', 'description' => 'Link Instagram'],

            // Landing Page (Hero)
            ['key' => 'landing.hero_title', 'value' => 'Enterprise Business Infrastructure — Built for Ownership.', 'type' => 'string', 'group' => 'landing', 'description' => 'Judul Utama di Halaman Beranda (Hero)'],
            ['key' => 'landing.hero_subtitle', 'value' => 'Sistem bisnis yang bekerja layaknya aset.', 'type' => 'string', 'group' => 'landing', 'description' => 'Sub Judul di Halaman Beranda (Hero)'],
            ['key' => 'landing.hero_cta_text', 'value' => 'Explore Solutions', 'type' => 'string', 'group' => 'landing', 'description' => 'Teks Tombol CTA Utama'],
            ['key' => 'landing.hero_cta_link', 'value' => '/solutions', 'type' => 'string', 'group' => 'landing', 'description' => 'Link Tombol CTA Utama'],

            // SEO Settings
            ['key' => 'seo.home.title', 'value' => 'COOCA - Enterprise Business Infrastructure', 'type' => 'string', 'group' => 'seo', 'description' => 'Meta Title for Home Page'],
            ['key' => 'seo.home.description', 'value' => 'The business system that works like an asset. Lifetime license, modular ERP.', 'type' => 'text', 'group' => 'seo', 'description' => 'Meta Description for Home Page'],
            
            ['key' => 'seo.about.title', 'value' => 'About Us - COOCA', 'type' => 'string', 'group' => 'seo', 'description' => 'Meta Title for About Page'],
            ['key' => 'seo.about.description', 'value' => 'Learn more about COOCA and our mission to provide enterprise infrastructure.', 'type' => 'text', 'group' => 'seo', 'description' => 'Meta Description for About Page'],

            ['key' => 'seo.pricing.title', 'value' => 'Pricing - COOCA', 'type' => 'string', 'group' => 'seo', 'description' => 'Meta Title for Pricing Page'],
            ['key' => 'seo.pricing.description', 'value' => 'Transparent and affordable pricing for all your business needs.', 'type' => 'text', 'group' => 'seo', 'description' => 'Meta Description for Pricing Page'],
            
            ['key' => 'seo.contact.title', 'value' => 'Contact Us - COOCA', 'type' => 'string', 'group' => 'seo', 'description' => 'Meta Title for Contact Page'],
            ['key' => 'seo.contact.description', 'value' => 'Get in touch with our team for any inquiries.', 'type' => 'text', 'group' => 'seo', 'description' => 'Meta Description for Contact Page'],

            ['key' => 'seo.solutions.title', 'value' => 'Solutions - COOCA', 'type' => 'string', 'group' => 'seo', 'description' => 'Meta Title for Solutions Page'],
            ['key' => 'seo.solutions.description', 'value' => 'Discover our tailored solutions for retail, hospitality, education, and more.', 'type' => 'text', 'group' => 'seo', 'description' => 'Meta Description for Solutions Page'],
            
            ['key' => 'seo.features.title', 'value' => 'Features - COOCA', 'type' => 'string', 'group' => 'seo', 'description' => 'Meta Title for Features Page'],
            ['key' => 'seo.features.description', 'value' => 'Explore the powerful features of our business infrastructure.', 'type' => 'text', 'group' => 'seo', 'description' => 'Meta Description for Features Page'],
        ];

        foreach ($settings as $setting) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                    'group' => $setting['group'],
                    'description' => $setting['description'],
                    'is_public' => true,
                ]
            );
        }
    }
}
