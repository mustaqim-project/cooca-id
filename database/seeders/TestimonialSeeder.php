<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Testimonial;

final class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Budi Hendrawan',
                'position' => 'Owner Resto Dapur Nusantara',
                'company' => 'Resto Dapur Nusantara (3 Cabang)',
                'content' => 'Sejak beralih ke Cooca Resto ERP, pemesanan QR meja dan stok bahan dapur kami terkontrol 100%. Laporan HPP real-time sangat membantu menekan waste bahan baku!',
                'rating' => 5,
                'avatar' => null,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'dr. Anisa Rahmawati',
                'position' => 'Kepala Klinik Utama Medika',
                'company' => 'Klinik Medika Sejahtera',
                'content' => 'Sistem Rekam Medis Elektronik Cooca Medika sangat sesuai dengan regulasi Kemenkes. Dokter dan perawat kami bisa menangani pasien 2 kali lebih cepat.',
                'rating' => 5,
                'avatar' => null,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Hendra Setiawan',
                'position' => 'Managing Director Bengkel Setia Motor',
                'company' => 'Setia Motor Otomotif',
                'content' => 'Manajemen PKB Work Order dan komisi mekanik kini otomatis. Pelanggan sangat menyukai estimasi biaya yang kami kirim langsung via WhatsApp.',
                'rating' => 5,
                'avatar' => null,
                'is_featured' => true,
                'is_active' => true,
            ],
        ];

        foreach ($testimonials as $t) {
            Testimonial::firstOrCreate(
                ['name' => $t['name']],
                $t
            );
        }

        echo "✅ Testimonials successfully seeded.\n";
    }
}
