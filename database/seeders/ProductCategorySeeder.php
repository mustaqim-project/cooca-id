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
                'name' => 'Cooca ERP & Business',
                'slug' => 'cooca-erp',
                'description' => 'Sistem ERP Terpadu, Kasir POS Multi-Outlet, Manajemen Inventori & HPP, dan Akuntansi Otomatis.',
                'icon' => 'fa-network-wired',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Cooca Booking & Services',
                'slug' => 'cooca-booking',
                'description' => 'Sistem Reservasi & Booking Jadwal Online, Notifikasi WhatsApp, dan Manajemen Komisi Terapis/Staf.',
                'icon' => 'fa-calendar-check',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Cooca Bengkel & Otomotif',
                'slug' => 'cooca-bengkel',
                'description' => 'Sistem Work Order (PKB/SPK) Bengkel Mobil & Motor, Stok Sparepart, dan Komisi Mekanik.',
                'icon' => 'fa-wrench',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        // Ensure allowed slugs
        $allowedSlugs = array_column($categories, 'slug');

        // Deactivate or clean unused categories that don't have linked foreign keys
        ProductCategory::whereNotIn('slug', $allowedSlugs)->delete();

        foreach ($categories as $cat) {
            ProductCategory::updateOrCreate(
                ['slug' => $cat['slug']],
                $cat
            );
        }

        echo "✅ Product Categories updated (Cooca ERP, Cooca Booking, Cooca Bengkel).\n";
    }
}
