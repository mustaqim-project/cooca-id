<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ProductCategory::all();
        if ($categories->isEmpty()) {
            $this->call(ProductCategorySeeder::class);
            $categories = ProductCategory::all();
        }

        $products = [
            [
                'category_id' => $categories->where('slug', 'restaurant-management')->first()->id ?? $categories->first()->id,
                'name' => 'Restaurant Pro',
                'slug' => 'restaurant-pro',
                'description' => 'Complete restaurant management system with POS, inventory, and customer management',
                'short_description' => 'Restaurant management with POS & inventory',
                'base_price' => 149000,
                'features' => json_encode(['POS System', 'Inventory Management', 'Customer Management', 'Kitchen Display']),
                'is_active' => true,
            ],
            [
                'category_id' => $categories->where('slug', 'healthcare-system')->first()->id ?? $categories->last()->id,
                'name' => 'MediClinic System',
                'slug' => 'mediclinic-system',
                'description' => 'Medical practice management with patient records and appointment scheduling',
                'short_description' => 'Medical practice management system',
                'base_price' => 199000,
                'features' => json_encode(['Patient Records', 'Appointment Scheduling', 'Billing', 'Reports']),
                'is_active' => true,
            ],
            [
                'category_id' => $categories->where('slug', 'automotive-services')->first()->id ?? $categories->first()->id,
                'name' => 'AutoWorks Manager',
                'slug' => 'autoworks-manager',
                'description' => 'Auto repair shop management with job tracking and parts inventory',
                'short_description' => 'Auto repair shop management',
                'base_price' => 129000,
                'features' => json_encode(['Job Tracking', 'Parts Inventory', 'Customer History', 'Invoice Generation']),
                'is_active' => true,
            ],
            [
                'category_id' => $categories->where('slug', 'retail-management')->first()->id ?? $categories->first()->id,
                'name' => 'Retail Analytics Pro',
                'slug' => 'retail-analytics-pro',
                'description' => 'Advanced retail management with multi-store support and analytics',
                'short_description' => 'Retail management with analytics',
                'base_price' => 179000,
                'features' => json_encode(['Multi-Store', 'Analytics', 'Promotions', 'Customer Loyalty']),
                'is_active' => true,
            ],
            [
                'category_id' => $categories->where('slug', 'educational-institutions')->first()->id ?? $categories->first()->id,
                'name' => 'EduHub Manager',
                'slug' => 'eduhub-manager',
                'description' => 'School management system with student records and academic tracking',
                'short_description' => 'School management system',
                'base_price' => 99000,
                'features' => json_encode(['Student Records', 'Academic Tracking', 'Attendance', 'Report Cards']),
                'is_active' => true,
            ],
            [
                'category_id' => $categories->where('slug', 'hotel-hospitality')->first()->id ?? $categories->first()->id,
                'name' => 'HotelMax System',
                'slug' => 'hotelmax-system',
                'description' => 'Hotel property management with booking and guest management',
                'short_description' => 'Hotel property management',
                'base_price' => 249000,
                'features' => json_encode(['Booking System', 'Guest Management', 'Housekeeping', 'Revenue Management']),
                'is_active' => true,
            ],
            [
                'category_id' => $categories->where('slug', 'manufacturing')->first()->id ?? $categories->first()->id,
                'name' => 'ProManu ERP',
                'slug' => 'promanu-erp',
                'description' => 'Manufacturing ERP with production planning and quality control',
                'short_description' => 'Manufacturing ERP system',
                'base_price' => 499000,
                'features' => json_encode(['Production Planning', 'Quality Control', 'Supply Chain', 'BOM Management']),
                'is_active' => true,
            ],
            [
                'category_id' => $categories->where('slug', 'professional-services')->first()->id ?? $categories->first()->id,
                'name' => 'ConsultHub Pro',
                'slug' => 'consulthub-pro',
                'description' => 'Professional services management with project tracking and billing',
                'short_description' => 'Professional services management',
                'base_price' => 169000,
                'features' => json_encode(['Project Tracking', 'Time Tracking', 'Billing', 'Client Management']),
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        $this->command->info('Products seeded successfully.');
    }
}
