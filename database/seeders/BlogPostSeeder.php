<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        BlogPost::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $admin = \App\Models\Admin::first();
        $adminId = $admin ? $admin->id : null;

        $posts = [
            [
                'title' => '5 Cara ERP Dapat Meningkatkan Efisiensi Bisnis Anda',
                'slug' => '5-cara-erp-tingkatkan-efisiensi',
                'excerpt' => 'Discover how ERP systems can streamline your business operations and boost productivity.',
                'content' => 'Sistem ERP modern seperti COOCA dirancang untuk mengintegrasikan semua aspek bisnis Anda dalam satu platform. Dengan menggunakan ERP, Anda dapat mengurangi duplikasi data, meningkatkan kolaborasi tim, dan membuat keputusan berdasarkan data real-time.

Berikut adalah 5 cara ERP dapat meningkatkan efisiensi bisnis:

1. **Otomasi Proses Bisnis** - Menghilangkan tugas manual yang memakan waktu
2. **Integrasi Data** - Menyatukan informasi dari berbagai departemen
3. **Real-time Reporting** - Akses laporan akurat kapan saja
4. **Inventory Management** - Kontrol stok dengan lebih baik
5. **Customer Management** - Tingkatkan kepuasan pelanggan

Investasi pada ERP bukan hanya biaya, tapi solusi jangka panjang untuk pertumbuhan bisnis Anda.',
                'featured_image' => 'https://via.placeholder.com/800x400?text=ERP+Efficiency',
                'author_id' => $adminId,
                'category' => 'Tips & Trik',
                'is_published' => true,
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'Kisah Sukses: Restoran Padang Sari Rasa dengan COOCA',
                'slug' => 'kisah-sukses-restoran-sari-rasa',
                'excerpt' => 'Bagaimana COOCA membantu meningkatkan operasional restoran dan kepuasan pelanggan.',
                'content' => 'Restoran Padang Sari Rasa adalah salah satu contoh sukses penggunaan COOCA. Sebelum menggunakan COOCA, mereka menghadapi berbagai tantangan:

- Inventory management yang sulit
- Proses order yang lambat
- Laporan keuangan tidak akurat
- Customer data tersebar di berbagai tempat

Setelah implementasi COOCA:
- Waktu order berkurang 50%
- Inventory accuracy naik menjadi 99%
- Revenue meningkat 35% dalam 6 bulan
- Customer satisfaction score naik dari 3.5 menjadi 4.8 dari 5

"COOCA benar-benar mengubah cara kami berbisnis," kata Ahmad Rizki, pemilik restoran.',
                'featured_image' => 'https://via.placeholder.com/800x400?text=Restaurant+Success',
                'author_id' => $adminId,
                'category' => 'Kisah Sukses',
                'is_published' => true,
                'published_at' => now()->subDays(15),
            ],
            [
                'title' => 'Keamanan Data di Era Digital: Bagaimana COOCA Melindungi Bisnis Anda',
                'slug' => 'keamanan-data-digital-cooca',
                'excerpt' => 'Pelajari tentang standar keamanan COOCA dan bagaimana data Anda dilindungi.',
                'content' => 'Keamanan data adalah prioritas utama kami di COOCA. Dengan meningkatnya ancaman cyber, bisnis Anda memerlukan perlindungan yang kuat.

COOCA mengimplementasikan:

- **Enkripsi End-to-End** - Data dienkripsi baik saat transit maupun penyimpanan
- **Regular Security Audit** - Audit keamanan dilakukan secara berkala oleh pihak ketiga
- **Backup Otomatis** - Data Anda selalu ter-backup untuk disaster recovery
- **Access Control** - Kelola siapa yang bisa akses data apa
- **Compliance** - Memenuhi standar GDPR, ISO, dan regulasi lokal

Percayakan keamanan data bisnis Anda kepada COOCA, spesialis dalam sistem ERP aman.',
                'featured_image' => 'https://via.placeholder.com/800x400?text=Data+Security',
                'author_id' => $adminId,
                'category' => 'Keamanan',
                'is_published' => true,
                'published_at' => now()->subDays(20),
            ],
            [
                'title' => 'Panduan Lengkap: Memulai Trial COOCA Dalam 5 Menit',
                'slug' => 'panduan-trial-cooca-5-menit',
                'excerpt' => 'Langkah-langkah mudah untuk memulai trial COOCA tanpa perlu kartu kredit.',
                'content' => 'Memulai dengan COOCA sangat mudah dan tidak memerlukan kartu kredit. Berikut langkah-langkahnya:

**Langkah 1: Kunjungi Website**
Buka www.cooca.id dan klik tombol "Mulai Trial Gratis"

**Langkah 2: Isi Formulir Registrasi**
Masukkan informasi bisnis dan email Anda. Proses validasi hanya membutuhkan 2 menit.

**Langkah 3: Verifikasi Email**
Cek email Anda dan klik link verifikasi dari COOCA.

**Langkah 4: Akses Dashboard**
Selamat! Anda sudah bisa mengakses dashboard COOCA dengan semua fitur premium.

**Langkah 5: Jelajahi Fitur**
Coba semua fitur dan integrasikan dengan bisnis Anda. Tim support kami siap membantu.

Mudah bukan? Mulai trial Anda sekarang juga!',
                'featured_image' => 'https://via.placeholder.com/800x400?text=Getting+Started',
                'author_id' => $adminId,
                'category' => 'Tutorial',
                'is_published' => true,
                'published_at' => now()->subDays(25),
            ],
            [
                'title' => 'Tren Teknologi ERP 2026: Apa yang Harus Anda Ketahui',
                'slug' => 'tren-teknologi-erp-2026',
                'excerpt' => 'Pelajari tren terbaru dalam teknologi ERP dan bagaimana COOCA beradaptasi.',
                'content' => 'Industri ERP terus berkembang dengan teknologi baru yang muncul setiap hari. Berikut adalah tren yang paling penting untuk 2026:

**1. Artificial Intelligence & Machine Learning**
AI semakin banyak digunakan untuk predictive analytics dan automation.

**2. Cloud-First Architecture**
Mayoritas perusahaan beralih ke cloud-based ERP untuk fleksibilitas lebih.

**3. API-First Integration**
Integrasi dengan sistem lain menjadi lebih mudah melalui API yang robust.

**4. Mobile-First Design**
Aplikasi mobile bukan lagi fitur tambahan, tapi kebutuhan utama.

**5. Real-Time Collaboration**
Tim dapat berkolaborasi secara real-time dari lokasi manapun.

COOCA telah mengadopsi semua tren ini untuk memberikan solusi terdepan kepada pelanggan kami.',
                'featured_image' => 'https://via.placeholder.com/800x400?text=ERP+Trends',
                'author_id' => $adminId,
                'category' => 'Tren Industri',
                'is_published' => true,
                'published_at' => now()->subDays(30),
            ],
        ];

        foreach ($posts as $post) {
            BlogPost::create($post);
        }

        $this->command->info('Blog posts seeded successfully.');
    }
}
