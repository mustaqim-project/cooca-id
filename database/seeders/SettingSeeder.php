<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

final class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site.name', 'value' => 'COOCA.ID', 'type' => 'string', 'group' => 'general', 'is_public' => true],
            ['key' => 'site.tagline', 'value' => 'Platform Enterprise SaaS & Infrastructure ERP Modern', 'type' => 'string', 'group' => 'general', 'is_public' => true],
            ['key' => 'site.description', 'value' => 'Cooca.id menyediakan infrastruktur ERP terakreditasi untuk Restoran F&B, Klinik Medis RME, Bengkel Otomotif, Notaris Legal, dan Retail dengan lisensi cloud terisolasi.', 'type' => 'string', 'group' => 'general', 'is_public' => true],
            ['key' => 'site.keywords', 'value' => 'ERP Indonesia, POS Restoran, Rekam Medis Elektronik, Bengkel Otomotif, Notaris Legal, Enterprise SaaS, Cloud ERP', 'type' => 'string', 'group' => 'general', 'is_public' => true],
            ['key' => 'site.email', 'value' => 'support@cooca.id', 'type' => 'string', 'group' => 'general', 'is_public' => true],
            ['key' => 'site.phone', 'value' => '082114468467', 'type' => 'string', 'group' => 'general', 'is_public' => true],
            ['key' => 'site.address', 'value' => 'Jl. Jendral Sudirman No. 88, Jakarta Selatan, DKI Jakarta 12190', 'type' => 'string', 'group' => 'general', 'is_public' => true],
            ['key' => 'site.logo', 'value' => null, 'type' => 'string', 'group' => 'general', 'is_public' => true],
            ['key' => 'site.favicon', 'value' => null, 'type' => 'string', 'group' => 'general', 'is_public' => true],
            ['key' => 'social.facebook', 'value' => 'https://facebook.com/coocaid', 'type' => 'string', 'group' => 'social', 'is_public' => true],
            ['key' => 'social.instagram', 'value' => 'https://instagram.com/coocaid', 'type' => 'string', 'group' => 'social', 'is_public' => true],
            ['key' => 'social.linkedin', 'value' => 'https://linkedin.com/company/coocaid', 'type' => 'string', 'group' => 'social', 'is_public' => true],
            ['key' => 'social.youtube', 'value' => 'https://youtube.com/@coocaid', 'type' => 'string', 'group' => 'social', 'is_public' => true],
            ['key' => 'social.whatsapp', 'value' => 'https://wa.me/6282114468467', 'type' => 'string', 'group' => 'social', 'is_public' => true],
            ['key' => 'wa.worker_token', 'value' => 'cooca-wa-secret-token-12345', 'type' => 'string', 'group' => 'whatsapp', 'is_public' => false],
            ['key' => 'wa.server_url', 'value' => 'http://127.0.0.1:3000', 'type' => 'string', 'group' => 'whatsapp', 'is_public' => false],
            ['key' => 'midtrans.is_production', 'value' => 'false', 'type' => 'boolean', 'group' => 'payment', 'is_public' => false],
            ['key' => 'payment.bank_transfer.active', 'value' => 'true', 'type' => 'boolean', 'group' => 'payment', 'is_public' => true],
            ['key' => 'payment.bank_transfer.bank_name', 'value' => 'Bank Central Asia (BCA)', 'type' => 'string', 'group' => 'payment', 'is_public' => true],
            ['key' => 'payment.bank_transfer.account_number', 'value' => '8830-8899-8800', 'type' => 'string', 'group' => 'payment', 'is_public' => true],
            ['key' => 'payment.bank_transfer.account_name', 'value' => 'PT COOCA TECHNOLOGIES INDONESIA', 'type' => 'string', 'group' => 'payment', 'is_public' => true],
            ['key' => 'payment.bank_transfer.instructions', 'value' => 'Silakan transfer sesuai jumlah total tagihan hingga digit terakhir. Setelah melakukan transfer, wajib mengunggah foto / screenshot / file bukti bayar agar verifikasi dapat diproses oleh tim kami.', 'type' => 'string', 'group' => 'payment', 'is_public' => true],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        echo "✅ System & SEO Settings successfully seeded.\n";
    }
}
