<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductCategory;

final class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Restoran & F&B',
                'slug' => 'restoran-fnb',
                'description' => 'Sistem POS Kasir, KDS Dapur, Reservasi Meja, & Manajemen Bahan Baku Restoran.',
                'icon' => 'fa-utensils',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Klinik & Medis',
                'slug' => 'klinik-medis',
                'description' => 'Rekam Medis Elektronik (EMR), Apotek & Farmasi, Antrean Pasien, & Bridging BPJS.',
                'icon' => 'fa-hospital-user',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Bengkel & Otomotif',
                'slug' => 'bengkel-otomotif',
                'description' => 'Work Order Servis Kendaraan, Stok Sparepart, Estimasi Biaya, & Komisi Mekanik.',
                'icon' => 'fa-wrench',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Notaris & Legal',
                'slug' => 'notaris-legal',
                'description' => 'Manajemen Berkas Akta, Process Tracking Sertifikat, Billing Klien, & Filing Digital.',
                'icon' => 'fa-scale-balanced',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Booking & Reservasi Jasa',
                'slug' => 'booking-reservasi',
                'description' => 'Penjadwalan Online Salon/Spa, DP Midtrans, Notifikasi WA Gateway, & Kalender Usaha.',
                'icon' => 'fa-calendar-check',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Retail & Minimarket',
                'slug' => 'retail-minimarket',
                'description' => 'Point of Sales Multi-Cabang, Barcode Scanner, Stok Opname, & Laporan Keuangan.',
                'icon' => 'fa-store',
                'sort_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $cat) {
            ProductCategory::firstOrCreate(
                ['slug' => $cat['slug']],
                $cat
            );
        }

        echo "✅ Product Categories successfully seeded.\n";
    }
}
