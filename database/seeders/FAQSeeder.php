<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;

final class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'Apa itu Cooca.id dan bagaimana cara kerjanya?',
                'answer' => 'Cooca.id adalah platform Software-as-a-Service (SaaS) yang menyediakan sistem Enterprise Resource Planning (ERP) khusus untuk Restoran, Klinik, Bengkel, Notaris, dan usaha jasa. Setelah mendaftar, tenant Anda akan aktif secara otomatis beserta lisensi dan domain cloud terisolasi.',
                'category' => 'general',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'question' => 'Apakah ada masa percobaan gratis (Free Trial)?',
                'answer' => 'Ya! Kami menyediakan masa coba gratis 14 hari penuh tanpa perlu memasukkan kartu kredit. Anda dapat mencoba seluruh modul ERP dan fitur pengujian lisensi secara langsung.',
                'category' => 'billing',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'question' => 'Bagaimana keamanan dan privasi data bisnis saya?',
                'answer' => 'Setiap tenant diisolasi secara ketat menggunakan database terpisah / Multi-Tenant architecture. Seluruh transmisi data dienkripsi dengan SSL/TLS 256-bit dan backup otomatis setiap hari.',
                'category' => 'technical',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'question' => 'Metode pembayaran apa saja yang didukung?',
                'answer' => 'Kami mendukung semua metode pembayaran di Indonesia melalui Midtrans Gateway, seperti Bank Transfer (BCA, Mandiri, BNI, BRI), QRIS, E-Wallet (GoPay, OVO, ShopeePay), dan Kartu Kredit.',
                'category' => 'billing',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'question' => 'Apakah saya bisa meminta custom fitur atau domain sendiri?',
                'answer' => 'Tentu saja! Untuk paket Enterprise, Anda bisa menggunakan Custom Domain (contoh: erp.bisnisanda.com) dan mengajukan custom modul sesuai alur operasional bisnis Anda.',
                'category' => 'general',
                'order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::firstOrCreate(
                ['question' => $faq['question']],
                $faq
            );
        }

        echo "✅ FAQs successfully seeded.\n";
    }
}
