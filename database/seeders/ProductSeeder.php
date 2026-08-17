<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\SubscriptionPlan;

final class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $erpCat     = ProductCategory::where('slug', 'cooca-erp')->first();
        $bookingCat = ProductCategory::where('slug', 'cooca-booking')->first();
        $bengkelCat = ProductCategory::where('slug', 'cooca-bengkel')->first();

        $products = [
            [
                'name' => 'Cooca ERP',
                'slug' => 'cooca-erp',
                'category_id' => $erpCat?->id,
                'product_type' => 'saas',
                'license_type' => 'monthly',
                'icon' => 'fa-network-wired',
                'short_description' => 'Platform ERP terpadu untuk operasional bisnis, kasir POS touchscreen, manajemen stok HPP, dan laporan akuntansi otomatis.',
                'description' => 'Cooca ERP adalah sistem Enterprise Resource Planning komprehensif yang menghubungkan manajemen kasir POS multi-outlet, kontrol inventori & bahan baku real-time, pencatatan otomatis Jurnal Umum / Laba Rugi, serta integrasi pemesanan meja QR mandiri.',
                'base_price' => 350000.00,
                'setup_fee' => 0.00,
                'maintenance_fee' => 0.00,
                'version' => '3.5.0',
                'max_domains' => 5,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'Cooca Booking',
                'slug' => 'cooca-booking',
                'category_id' => $bookingCat?->id,
                'product_type' => 'saas',
                'license_type' => 'monthly',
                'icon' => 'fa-calendar-check',
                'short_description' => 'Sistem reservasi dan booking jadwal online untuk salon, spa, barbershop, treatment, dan jasa dengan notifikasi WhatsApp.',
                'description' => 'Cooca Booking memfasilitasi pelanggan memilih tanggal, jam layanan, dan staf favorit secara mandiri. Dilengkapi dengan pembayaran DP online, pengingat janji temu otomatis via WhatsApp, kalender jadwal terpadu, dan kalkulasi komisi terapis/staf.',
                'base_price' => 250000.00,
                'setup_fee' => 0.00,
                'maintenance_fee' => 0.00,
                'version' => '4.1.0',
                'max_domains' => 5,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'Cooca Bengkel',
                'slug' => 'cooca-bengkel',
                'category_id' => $bengkelCat?->id,
                'product_type' => 'saas',
                'license_type' => 'monthly',
                'icon' => 'fa-wrench',
                'short_description' => 'Software manajemen bengkel mobil & motor, Surat Perintah Kerja (PKB/Work Order), stok suku cadang, dan komisi mekanik.',
                'description' => 'Cooca Bengkel mendigitalkan alur operasional bengkel secara menyeluruh. Pembuatan estimasi biaya servis, pencatatan histori servis kendaraan pelanggan, pengelolaan ribuan nomor suku cadang/sparepart, dan pengiriman invoice estimasi langsung via WhatsApp.',
                'base_price' => 300000.00,
                'setup_fee' => 0.00,
                'maintenance_fee' => 0.00,
                'version' => '2.2.0',
                'max_domains' => 5,
                'is_active' => true,
                'is_featured' => true,
            ],
        ];

        $allowedSlugs = array_column($products, 'slug');

        // Clean up or deactivate any products not in the 3 allowed products
        $oldProducts = Product::whereNotIn('slug', $allowedSlugs)->get();
        foreach ($oldProducts as $old) {
            // Check if there are subscriptions before deleting
            if ($old->subscriptions()->count() === 0 && $old->subscriptionPlans()->count() === 0) {
                $old->delete();
            } else {
                // If it has historical data, deactivate and hide from catalog
                $old->update(['is_active' => false, 'is_featured' => false]);
            }
        }

        foreach ($products as $prod) {
            $productModel = Product::updateOrCreate(
                ['slug' => $prod['slug']],
                $prod
            );

            // Seed default plans for this product if not existing
            $basePrice = (float) $prod['base_price'];
            $plans = [
                [
                    'name' => 'Paket Bulanan (Monthly)',
                    'duration_months' => 1,
                    'price' => $basePrice,
                    'discount_percent' => 0.00,
                    'is_active' => true,
                    'sort_order' => 1,
                ],
                [
                    'name' => 'Paket Tahunan (1 Tahun - Hemat 2 Bulan)',
                    'duration_months' => 12,
                    'price' => $basePrice * 10,
                    'discount_percent' => 16.67,
                    'is_active' => true,
                    'sort_order' => 2,
                ],
                [
                    'name' => 'Paket Enterprise (2 Tahun)',
                    'duration_months' => 24,
                    'price' => $basePrice * 18,
                    'discount_percent' => 25.00,
                    'is_active' => true,
                    'sort_order' => 3,
                ],
            ];

            foreach ($plans as $planData) {
                SubscriptionPlan::updateOrCreate(
                    [
                        'product_id' => $productModel->id,
                        'name' => $planData['name'],
                    ],
                    array_merge($planData, ['product_id' => $productModel->id])
                );
            }
        }

        echo "✅ Products & Plans updated: Only Cooca ERP, Cooca Booking, Cooca Bengkel are active.\n";
    }
}
