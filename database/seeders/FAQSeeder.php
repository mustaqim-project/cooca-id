<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FAQSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        Faq::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $faqs = [
            [
                'category' => 'General',
                'question' => 'Apa itu COOCA?',
                'answer' => 'COOCA adalah platform ERP terintegrasi yang menyediakan solusi bisnis lengkap untuk berbagai industri. Dengan sistem modular, Anda dapat memilih fitur yang sesuai kebutuhan bisnis Anda.',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'category' => 'General',
                'question' => 'Siapa yang bisa menggunakan COOCA?',
                'answer' => 'COOCA dirancang untuk bisnis dari semua ukuran mulai dari startup hingga enterprise. Baik Anda pemilik toko, restoran, klinik, atau manufacturer, COOCA memiliki solusi yang tepat untuk Anda.',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'category' => 'Trial & Pricing',
                'question' => 'Berapa lama periode trial?',
                'answer' => 'Kami menawarkan trial gratis selama 14 hari. Tidak perlu kartu kredit untuk memulai trial. Semua fitur premium tersedia selama periode trial.',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'category' => 'Trial & Pricing',
                'question' => 'Bagaimana cara upgrade dari trial ke paket berbayar?',
                'answer' => 'Upgrade bisa dilakukan langsung dari dashboard Anda dengan klik tombol "Upgrade Plan". Anda bisa memilih paket mana yang sesuai kebutuhan. Pembayaran dapat dilakukan melalui transfer bank atau kartu kredit.',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'category' => 'Technical',
                'question' => 'Apakah COOCA aman untuk data bisnis saya?',
                'answer' => 'Ya, COOCA menggunakan enkripsi tingkat enterprise dan compliance dengan standar internasional. Data Anda tersimpan di server yang dilindungi dengan sistem keamanan berlapis.',
                'order' => 5,
                'is_active' => true,
            ],
            [
                'category' => 'Technical',
                'question' => 'Bisakah saya mengakses COOCA dari mobile?',
                'answer' => 'Ya, COOCA fully responsive dan dapat diakses dari smartphone atau tablet. Kami juga menyediakan aplikasi mobile native untuk iOS dan Android.',
                'order' => 6,
                'is_active' => true,
            ],
            [
                'category' => 'Support',
                'question' => 'Apa saja channel support yang tersedia?',
                'answer' => 'Kami menyediakan support melalui email, live chat, WhatsApp, dan telepon. Untuk paket premium, Anda mendapatkan dedicated account manager.',
                'order' => 7,
                'is_active' => true,
            ],
            [
                'category' => 'Support',
                'question' => 'Apakah ada training untuk pengguna baru?',
                'answer' => 'Ya, kami menyediakan video tutorial, webinar, dan dokumentasi lengkap. Tim support kami siap membantu onboarding Anda tanpa biaya tambahan.',
                'order' => 8,
                'is_active' => true,
            ],
            [
                'category' => 'Affiliate',
                'question' => 'Bagaimana cara menjadi affiliate COOCA?',
                'answer' => 'Daftar sebagai affiliator melalui menu "Menjadi Mitra" di website kami. Setelah verifikasi, Anda bisa mulai mendapatkan komisi dari setiap customer yang Anda referensikan.',
                'order' => 9,
                'is_active' => true,
            ],
            [
                'category' => 'Affiliate',
                'question' => 'Berapa komisi yang didapat sebagai affiliate?',
                'answer' => 'Komisi level 1 adalah 25% dari subscription value, dan level 2 adalah 5%. Untuk paket recurring, Anda terus mendapatkan komisi setiap bulan dari renewal customer.',
                'order' => 10,
                'is_active' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }

        $this->command->info('FAQs seeded successfully.');
    }
}
