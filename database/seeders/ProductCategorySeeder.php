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
        $categories = [
            [
                'name' => 'Restaurant Management',
                'slug' => 'restaurant-management',
                'description' => 'Complete ERP solution for restaurant operations',
                'is_active' => true,
            ],
            [
                'name' => 'Healthcare System',
                'slug' => 'healthcare-system',
                'description' => 'Medical practice and clinic management system',
                'is_active' => true,
            ],
            [
                'name' => 'Automotive Services',
                'slug' => 'automotive-services',
                'description' => 'Auto repair and maintenance management',
                'is_active' => true,
            ],
            [
                'name' => 'Retail Management',
                'slug' => 'retail-management',
                'description' => 'Point of sale and inventory management',
                'is_active' => true,
            ],
            [
                'name' => 'Educational Institutions',
                'slug' => 'educational-institutions',
                'description' => 'School and university management system',
                'is_active' => true,
            ],
            [
                'name' => 'Hotel & Hospitality',
                'slug' => 'hotel-hospitality',
                'description' => 'Hotel booking and property management',
                'is_active' => true,
            ],
            [
                'name' => 'Manufacturing',
                'slug' => 'manufacturing',
                'description' => 'Production and supply chain management',
                'is_active' => true,
            ],
            [
                'name' => 'Professional Services',
                'slug' => 'professional-services',
                'description' => 'Consulting and service provider management',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            ProductCategory::create($category);
        }

        $this->command->info('Product categories seeded successfully.');
    }
}
