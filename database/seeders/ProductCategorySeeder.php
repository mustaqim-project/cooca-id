<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate and reseed to match home page tabs
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        ProductCategory::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $categories = [
            [
                'name'        => 'Commerce & Retail',
                'slug'        => 'commerce-retail',
                'description' => 'Solusi ERP terintegrasi untuk bisnis ritel, salon, laundry, dan usaha perdagangan.',
                'icon'        => 'bi-bag-check',
                'sort_order'  => 1,
                'is_active'   => true,
            ],
            [
                'name'        => 'Hospitality & Services',
                'slug'        => 'hospitality-services',
                'description' => 'Sistem manajemen untuk restoran, hotel, dan bisnis penyewaan aset.',
                'icon'        => 'bi-building',
                'sort_order'  => 2,
                'is_active'   => true,
            ],
            [
                'name'        => 'Health & Professional',
                'slug'        => 'health-professional',
                'description' => 'Platform digital untuk klinik, bengkel, dan lembaga pendidikan.',
                'icon'        => 'bi-briefcase',
                'sort_order'  => 3,
                'is_active'   => true,
            ],
        ];

        foreach ($categories as $category) {
            ProductCategory::create($category);
        }

        $this->command->info('Product categories seeded successfully (3 categories).');
    }
}
