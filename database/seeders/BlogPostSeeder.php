<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogPost;
use App\Models\Admin;

final class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Admin::first();

        $posts = [
            [
                'title' => ' Panduan Strategi Mengurangi Waste Bahan Baku Restoran Hingga 40%',
                'slug' => 'panduan-kurangi-waste-bahan-baku-restoran',
                'excerpt' => 'Pelajari bagaimana integrasi sistem POS kasir dan stok dapur otomatis membantu pengusaha F&B menekan biaya sisa bahan.',
                'content' => '
                    <p class="mb-4">Manajemen bahan baku merupakan jantung dari efisiensi finansial sebuah bisnis restoran. Banyak pengelola F&B tidak menyadari bahwa 15-20% margin keuntungan terbuang karena sisa bahan baku yang kadaluarsa atau tidak terdeteksi.</p>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">1. Gunakan Metode First-In, First-Out (FIFO) Berbasis Sistem</h3>
                    <p class="mb-4">Dengan menggunakan sistem ERP Restoran Cooca, setiap stok bahan baku yang masuk diberi tanggal penerimaan dan dikaitkan langsung dengan resep standar menu.</p>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">2. Pantau HPP Waktu Nyata</h3>
                    <p class="mb-4">Setiap kali kasir menekan tombol pembayaran pada POS, stok bahan dapur terpotong secara otomatis sesuai gramasi resep.</p>
                ',
                'category' => 'Restoran & F&B',
                'tags' => ['Restoran', 'POS Kasir', 'HPP', 'F&B'],
                'is_published' => true,
                'published_at' => now()->subDays(2),
                'views_count' => 1420,
                'author_id' => $admin?->id,
            ],
            [
                'title' => ' Pentingnya Rekam Medis Elektronik (RME) Terakreditasi Bagi Klinik Modern',
                'slug' => 'pentingnya-rekam-medis-elektronik-rme-klinik',
                'excerpt' => 'Memahami regulasi Kemenkes seputar RME dan manfaat digitalisasi layanan kesehatan untuk kecepatan antrean pasien.',
                'content' => '
                    <p class="mb-4">Pemerintah Indonesia melalui Kementerian Kesehatan telah mewajibkan seluruh fasilitas kesehatan mengadopsi Rekam Medis Elektronik (RME). Tidak hanya pemenuhan regulasi, RME memberikan manfaat luar biasa bagi keamanan data riwayat penyakit pasien.</p>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Kecepatan Pelayanan & Resep Digital</h3>
                    <p class="mb-4">Dokter dapat langsung mengirimkan resep obat digital ke unit farmasi klinik tanpa risiko salah baca tulisan tangan resep.</p>
                ',
                'category' => 'Klinik & Medis',
                'tags' => ['Klinik', 'RME', 'Kemenkes', 'Farmasi'],
                'is_published' => true,
                'published_at' => now()->subDays(5),
                'views_count' => 980,
                'author_id' => $admin?->id,
            ],
            [
                'title' => ' Cara Efektif Mengelola Ribuan Sparepart Bengkel Otomotif Tanpa Selisih Stok',
                'slug' => 'cara-efektif-kelola-sparepart-bengkel-otomotif',
                'excerpt' => 'Langkah praktis penggunaan Work Order (PKB) digital untuk mempercepat estimasi servis dan transparansi komisi mekanik.',
                'content' => '
                    <p class="mb-4">Masalah utama pemilik bengkel mobil dan motor adalah selisih stok sparepart dan lambatnya pembuatan kwitansi servis. Dengan Cooca Auto ERP, setiap sparepart terhubung langsung ke Surat Perintah Kerja (PKB).</p>
                ',
                'category' => 'Bengkel & Otomotif',
                'tags' => ['Bengkel', 'Otomotif', 'Sparepart', 'Servis'],
                'is_published' => true,
                'published_at' => now()->subDays(10),
                'views_count' => 750,
                'author_id' => $admin?->id,
            ],
        ];

        foreach ($posts as $post) {
            BlogPost::firstOrCreate(
                ['slug' => $post['slug']],
                $post
            );
        }

        echo "✅ Blog Posts successfully seeded.\n";
    }
}
