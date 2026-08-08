<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductCategory;

final class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $restoCat = ProductCategory::where('slug', 'restoran-fnb')->first();
        $medisCat = ProductCategory::where('slug', 'klinik-medis')->first();
        $autoCat  = ProductCategory::where('slug', 'bengkel-otomotif')->first();
        $legalCat = ProductCategory::where('slug', 'notaris-legal')->first();
        $bookCat  = ProductCategory::where('slug', 'booking-reservasi')->first();
        $retailCat= ProductCategory::where('slug', 'retail-minimarket')->first();

        $products = [
            [
                'name' => 'Cooca Resto ERP & POS Kasir',
                'slug' => 'cooca-resto-erp',
                'category_id' => $restoCat?->id,
                'product_type' => 'saas',
                'license_type' => 'monthly',
                'icon' => 'fa-utensils',
                'short_description' => 'Solusi lengkap kasir POS, Kitchen Display System (KDS), pesanan meja via QR Code, dan inventaris bahan dapur.',
                'description' => 'Cooca Resto ERP adalah platform SaaS manajemen restoran terpadu. Dilengkapi fitur kasir touchscreen multi-terminal, cetak struk otomatis, integrasi mesin EDC, laporan HPP stok dapur waktu nyata, dan pemesanan mandiri oleh pelanggan menggunakan Scan QR Code di meja.',
                'base_price' => 250000.00,
                'setup_fee' => 0.00,
                'maintenance_fee' => 0.00,
                'version' => '3.4.0',
                'max_domains' => 5,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'Cooca Medika EMR & Klinik',
                'slug' => 'cooca-medika-emr',
                'category_id' => $medisCat?->id,
                'product_type' => 'saas',
                'license_type' => 'monthly',
                'icon' => 'fa-hospital-user',
                'short_description' => 'Sistem Rekam Medis Elektronik (RME) terakreditasi, stok farmasi obat, antrean antarmuka layar TV, & cetak resep.',
                'description' => 'Cooca Medika EMR menyediakan manajemen pendaftaran pasien, pencatatan rekam medis standar ICD-10, kontrol racikan obat farmasi, manajemen klaim asuransi, dan laporan rekam medis pasien terenkripsi sesuai standar Kemenkes RI.',
                'base_price' => 450000.00,
                'setup_fee' => 0.00,
                'maintenance_fee' => 0.00,
                'version' => '2.8.1',
                'max_domains' => 3,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'Cooca Auto Bengkel & Sparepart',
                'slug' => 'cooca-auto-bengkel',
                'category_id' => $autoCat?->id,
                'product_type' => 'saas',
                'license_type' => 'monthly',
                'icon' => 'fa-wrench',
                'short_description' => 'Sistem Surat Perintah Kerja (PKB/Work Order), inventaris suku cadang otomotif, & kalkulasi komisi mekanik.',
                'description' => 'Cooca Auto mendigitalkan operasional bengkel mobil & motor. Pengelola dapat membuat estimasi biaya perbaikan, melacak status pengerjaan servis mekanik, mengelola ribuan nomor part sparepart, dan mengirim laporan estimasi otomatis via WhatsApp.',
                'base_price' => 350000.00,
                'setup_fee' => 0.00,
                'maintenance_fee' => 0.00,
                'version' => '2.1.0',
                'max_domains' => 5,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'Cooca Legal Notaris & Akta',
                'slug' => 'cooca-legal-notaris',
                'category_id' => $legalCat?->id,
                'product_type' => 'saas',
                'license_type' => 'monthly',
                'icon' => 'fa-scale-balanced',
                'short_description' => 'Manajemen proses pengurusan akta tanah/PT, repositori sertifikat digital, invoice biaya legal, & reminder Klien.',
                'description' => 'Dirancang khusus untuk Kantor Notaris & Pejabat Pembuat Akta Tanah (PPAT). Memudahkan penjejakan berkas perkara Klien dari BPN hingga SK Kemenkumham, penyimpanan dokumen digital aman, dan tagihan honorarium transparansi.',
                'base_price' => 500000.00,
                'setup_fee' => 0.00,
                'maintenance_fee' => 0.00,
                'version' => '1.9.0',
                'max_domains' => 2,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'Cooca Booking & Salon Services',
                'slug' => 'cooca-booking-services',
                'category_id' => $bookCat?->id,
                'product_type' => 'saas',
                'license_type' => 'monthly',
                'icon' => 'fa-calendar-check',
                'short_description' => 'Reservasi jadwal online, pembayaran DP Midtrans, pengingat janji temu WA otomatis, & komisi kapster/terapis.',
                'description' => 'Sistem booking mandiri untuk bisnis Salon Kecantikan, Barbershop, Spa, Petshop, dan Jasa Konsultasi. Pelanggan memilih tanggal, jam, & staf favorit secara online dengan integrasi pembayaran otomatis.',
                'base_price' => 200000.00,
                'setup_fee' => 0.00,
                'maintenance_fee' => 0.00,
                'version' => '4.0.2',
                'max_domains' => 5,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'Cooca Retail Cloud POS',
                'slug' => 'cooca-retail-cloud-pos',
                'category_id' => $retailCat?->id,
                'product_type' => 'saas',
                'license_type' => 'monthly',
                'icon' => 'fa-store',
                'short_description' => 'POS kasir toko fisik & online multi-cabang, pencetakan label barcode, stok opname, & laporan rugi laba.',
                'description' => 'Sistem penjualan retail terpusat. Cocok untuk toko pakaian, minimarket, toko elektronik, dan distributor barang konsumsi dengan sinkronisasi stok riil antar gudang.',
                'base_price' => 300000.00,
                'setup_fee' => 0.00,
                'maintenance_fee' => 0.00,
                'version' => '3.1.5',
                'max_domains' => 10,
                'is_active' => true,
                'is_featured' => false,
            ],
        ];

        foreach ($products as $prod) {
            Product::firstOrCreate(
                ['slug' => $prod['slug']],
                $prod
            );
        }

        echo "✅ Products successfully seeded.\n";
    }
}
