<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Ahmad Rizki',
                'company' => 'Padang Sari Rasa Restaurant',
                'position' => 'Owner',
                'content' => 'Sistem ERP yang sangat membantu operasional restoran kami. Trial langsung aktif dan tim support sangat responsif. Saya sangat merekomendasikan COOCA kepada pemilik bisnis lainnya.',
                'rating' => 5,
                'avatar' => 'https://ui-avatars.com/api/?name=Ahmad+Rizki&background=random',
                'product_type' => 'restoran',
                'is_featured' => true,
                'is_active' => true,
                'order' => 1,
            ],
            [
                'name' => 'Dr. Siti Nurhaliza',
                'company' => 'Klinik Sehat Mandiri',
                'position' => 'Dokter & Direktur',
                'content' => 'Management pasien dan rekam medis jadi lebih teratur. Worth it banget untuk klinik kecil seperti kami. Sistem ini sangat user-friendly dan tidak perlu training yang panjang.',
                'rating' => 5,
                'avatar' => 'https://ui-avatars.com/api/?name=Siti+Nurhaliza&background=random',
                'product_type' => 'klinik',
                'is_featured' => true,
                'is_active' => true,
                'order' => 2,
            ],
            [
                'name' => 'Budi Santoso',
                'company' => 'Bengkel Auto Care',
                'position' => 'Manager',
                'content' => 'Fitur booking dan tracking servis sangat berguna. Customer juga senang bisa monitor progress online. Produktivitas bengkel kami meningkat 40% setelah menggunakan COOCA.',
                'rating' => 4,
                'avatar' => 'https://ui-avatars.com/api/?name=Budi+Santoso&background=random',
                'product_type' => 'bengkel',
                'is_featured' => true,
                'is_active' => true,
                'order' => 3,
            ],
            [
                'name' => 'Yani Wijaya',
                'company' => 'Toko Elektronik Maju Jaya',
                'position' => 'Pemilik',
                'content' => 'Inventory management jadi lebih efisien. Saya bisa melihat stok real-time dari mana saja. Sangat membantu dalam mengambil keputusan bisnis yang lebih baik.',
                'rating' => 5,
                'avatar' => 'https://ui-avatars.com/api/?name=Yani+Wijaya&background=random',
                'product_type' => 'retail',
                'is_featured' => false,
                'is_active' => true,
                'order' => 4,
            ],
            [
                'name' => 'Prof. Bambang Sutrisno',
                'company' => 'SMK Teknologi Digital',
                'position' => 'Kepala Sekolah',
                'content' => 'Sistem manajemen sekolah yang sangat komprehensif. Data siswa dan nilai dapat diakses dengan mudah. Implementasi sangat cepat dan support team-nya responsif.',
                'rating' => 5,
                'avatar' => 'https://ui-avatars.com/api/?name=Bambang+Sutrisno&background=random',
                'product_type' => 'pendidikan',
                'is_featured' => false,
                'is_active' => true,
                'order' => 5,
            ],
            [
                'name' => 'Sinta Kusuma',
                'company' => 'Hotel Pantai Resort',
                'position' => 'General Manager',
                'content' => 'Booking management dan guest relations menjadi sangat mudah. Revenue management tools membantu kami maksimalkan occupancy rate. ROI-nya tercapai dalam 3 bulan pertama.',
                'rating' => 5,
                'avatar' => 'https://ui-avatars.com/api/?name=Sinta+Kusuma&background=random',
                'product_type' => 'hotel',
                'is_featured' => true,
                'is_active' => true,
                'order' => 6,
            ],
            [
                'name' => 'Rudi Hermawan',
                'company' => 'PT Manufaktur Jaya',
                'position' => 'Production Manager',
                'content' => 'Production planning dan quality control jadi lebih terstruktur. Kami bisa track setiap tahap produksi dengan detail. Waste berkurang dan efficiency meningkat signifikan.',
                'rating' => 4,
                'avatar' => 'https://ui-avatars.com/api/?name=Rudi+Hermawan&background=random',
                'product_type' => 'manufaktur',
                'is_featured' => false,
                'is_active' => true,
                'order' => 7,
            ],
            [
                'name' => 'Eka Prasetya',
                'company' => 'Konsultan Bisnis Mitra',
                'position' => 'Founding Partner',
                'content' => 'Platform COOCA sangat flexible untuk berbagai industri. Saya sudah merekomendasikan ke puluhan klien dan semua puas. Sistem ini benar-benar game changer untuk UMKM.',
                'rating' => 5,
                'avatar' => 'https://ui-avatars.com/api/?name=Eka+Prasetya&background=random',
                'product_type' => 'konsultasi',
                'is_featured' => true,
                'is_active' => true,
                'order' => 8,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }

        $this->command->info('Testimonials seeded successfully.');
    }
}
