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
        // Ensure categories exist
        $categories = ProductCategory::all();
        if ($categories->isEmpty()) {
            $this->call(ProductCategorySeeder::class);
            $categories = ProductCategory::all();
        }

        // Truncate and reseed to match home page
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        Product::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $commerce  = $categories->where('slug', 'commerce-retail')->first();
        $hospitality = $categories->where('slug', 'hospitality-services')->first();
        $health    = $categories->where('slug', 'health-professional')->first();

        $products = [
            // ── Commerce & Retail ─────────────────────────────
            [
                'category_id'       => $commerce->id,
                'name'              => 'COOCA for Retail',
                'slug'              => 'cooca-for-retail',
                'icon'              => 'bi-cart3',
                'short_description' => 'Replace fragmented POS, inventory, and loyalty tools with one unified system.',
                'description'       => 'Replace fragmented POS, inventory, and loyalty tools with one unified system that tracks every transaction across every outlet in real time. Designed for multi-outlet retail businesses that need full control over operations.',
                'base_price'        => 6900000,
                'features'          => ['Multi-Outlet POS', 'Real-Time Inventory', 'Customer Loyalty Program', 'Sales Analytics', 'Supplier Management', 'Barcode & QR Support'],
                'specifications'    => ['Users' => 'Unlimited', 'Outlets' => 'Unlimited', 'Uptime SLA' => '99.9%'],
                'is_active'         => true,
                'is_featured'       => true,
                'sort_order'        => 1,
                'demo_url'          => 'https://demo.cooca.id/retail',
            ],
            [
                'category_id'       => $commerce->id,
                'name'              => 'COOCA for Salon & Beauty',
                'slug'              => 'cooca-for-salon',
                'icon'              => 'bi-scissors',
                'short_description' => 'Appointment booking, stylist management, and membership programs — all in one.',
                'description'       => 'Appointment booking, stylist management, and membership programs — turn walk-ins into loyal customers without the administrative overhead. Manage schedules, track services, and grow your salon business.',
                'base_price'        => 5900000,
                'features'          => ['Online Booking', 'Stylist Scheduling', 'Membership & Loyalty', 'Service Revenue Tracking', 'Customer History', 'SMS/WA Reminder'],
                'specifications'    => ['Users' => 'Unlimited', 'Branches' => 'Unlimited', 'Uptime SLA' => '99.9%'],
                'is_active'         => true,
                'is_featured'       => false,
                'sort_order'        => 2,
                'demo_url'          => 'https://demo.cooca.id/salon',
            ],
            [
                'category_id'       => $commerce->id,
                'name'              => 'COOCA for Laundry',
                'slug'              => 'cooca-for-laundry',
                'icon'              => 'bi-water',
                'short_description' => 'Order tracking, delivery scheduling, and customer loyalty — one system.',
                'description'       => 'Order tracking, delivery scheduling, and customer loyalty — one system that turns daily chaos into predictable, recurring revenue. Track every order from pick-up to delivery with full customer visibility.',
                'base_price'        => 4900000,
                'features'          => ['Order Tracking', 'Delivery Scheduling', 'Customer Loyalty', 'Multi-Outlet Support', 'WhatsApp Notifications', 'Revenue Reports'],
                'specifications'    => ['Users' => 'Unlimited', 'Outlets' => 'Unlimited', 'Uptime SLA' => '99.9%'],
                'is_active'         => true,
                'is_featured'       => false,
                'sort_order'        => 3,
                'demo_url'          => 'https://demo.cooca.id/laundry',
            ],

            // ── Hospitality & Services ────────────────────────
            [
                'category_id'       => $hospitality->id,
                'name'              => 'COOCA for Restaurant',
                'slug'              => 'cooca-for-restaurant',
                'icon'              => 'bi-cup-hot',
                'short_description' => 'Table management, kitchen display system, and multi-outlet control.',
                'description'       => 'Table management, kitchen display system, and multi-outlet control — know exactly which location is profitable at any given moment. From ordering to billing, every step is automated and tracked.',
                'base_price'        => 6900000,
                'features'          => ['Table & Reservation Management', 'Kitchen Display System', 'Multi-Outlet Dashboard', 'Menu Management', 'Split Bill', 'Food Cost Analysis'],
                'specifications'    => ['Users' => 'Unlimited', 'Outlets' => 'Unlimited', 'Uptime SLA' => '99.9%'],
                'is_active'         => true,
                'is_featured'       => true,
                'sort_order'        => 4,
                'demo_url'          => 'https://demo.cooca.id/restaurant',
            ],
            [
                'category_id'       => $hospitality->id,
                'name'              => 'COOCA for Hotel',
                'slug'              => 'cooca-for-hotel',
                'icon'              => 'bi-building',
                'short_description' => 'Room booking, housekeeping workflow, and revenue management.',
                'description'       => 'Room booking, housekeeping workflow, and revenue management — stop leaving money on the table with manual occupancy tracking. A complete Property Management System for modern hotels.',
                'base_price'        => 9900000,
                'features'          => ['Room Booking & PMS', 'Housekeeping Management', 'Revenue Management', 'OTA Channel Manager', 'Guest Profile', 'Front Desk Dashboard'],
                'specifications'    => ['Users' => 'Unlimited', 'Rooms' => 'Unlimited', 'Uptime SLA' => '99.9%'],
                'is_active'         => true,
                'is_featured'       => false,
                'sort_order'        => 5,
                'demo_url'          => 'https://demo.cooca.id/hotel',
            ],
            [
                'category_id'       => $hospitality->id,
                'name'              => 'COOCA for Rental',
                'slug'              => 'cooca-for-rental',
                'icon'              => 'bi-key',
                'short_description' => 'Asset tracking, contract automation, and maintenance alerts.',
                'description'       => 'Asset tracking, contract automation, and maintenance alerts — always know where every asset is and when it\'s generating revenue. Manage cars, equipment, property, or any rental asset with ease.',
                'base_price'        => 5900000,
                'features'          => ['Asset Tracking', 'Digital Contracts', 'Maintenance Scheduling', 'Revenue per Asset', 'Customer Management', 'Late Return Alerts'],
                'specifications'    => ['Users' => 'Unlimited', 'Assets' => 'Unlimited', 'Uptime SLA' => '99.9%'],
                'is_active'         => true,
                'is_featured'       => false,
                'sort_order'        => 6,
                'demo_url'          => 'https://demo.cooca.id/rental',
            ],

            // ── Health & Professional ─────────────────────────
            [
                'category_id'       => $health->id,
                'name'              => 'COOCA for Clinic',
                'slug'              => 'cooca-for-clinic',
                'icon'              => 'bi-hospital',
                'short_description' => 'Patient records, pharmacy, and billing — fully integrated.',
                'description'       => 'Patient records, pharmacy, and billing — fully integrated so your staff stops juggling four different apps for a single patient visit. A complete EMR system for clinics and healthcare providers.',
                'base_price'        => 7900000,
                'features'          => ['Electronic Medical Records', 'Pharmacy Management', 'Integrated Billing', 'Appointment Scheduling', 'Doctor Dashboard', 'Insurance Claims'],
                'specifications'    => ['Users' => 'Unlimited', 'Patients' => 'Unlimited', 'Uptime SLA' => '99.9%'],
                'is_active'         => true,
                'is_featured'       => true,
                'sort_order'        => 7,
                'demo_url'          => 'https://demo.cooca.id/clinic',
            ],
            [
                'category_id'       => $health->id,
                'name'              => 'COOCA for Workshop',
                'slug'              => 'cooca-for-workshop',
                'icon'              => 'bi-tools',
                'short_description' => 'Service orders, spare parts management, and full customer history.',
                'description'       => 'Service orders, spare parts management, and full customer history — nothing slips through the cracks, and every job gets paid on time. Built for auto workshops, electronics repair, and general service businesses.',
                'base_price'        => 4900000,
                'features'          => ['Work Order Management', 'Spare Parts Inventory', 'Customer Vehicle History', 'Technician Assignments', 'Invoice & Payment', 'Service Reminders'],
                'specifications'    => ['Users' => 'Unlimited', 'Locations' => 'Unlimited', 'Uptime SLA' => '99.9%'],
                'is_active'         => true,
                'is_featured'       => false,
                'sort_order'        => 8,
                'demo_url'          => 'https://demo.cooca.id/workshop',
            ],
            [
                'category_id'       => $health->id,
                'name'              => 'COOCA for Education',
                'slug'              => 'cooca-for-education',
                'icon'              => 'bi-mortarboard',
                'short_description' => 'Student enrollment, tuition billing, and grade management.',
                'description'       => 'Student enrollment, tuition billing, and grade management — one platform that parents, teachers, and administrators actually enjoy using. From TK to SMA and university, all educational levels supported.',
                'base_price'        => 5900000,
                'features'          => ['Student Enrollment', 'Tuition Billing', 'Grade & Report Cards', 'Attendance Tracking', 'Teacher Scheduling', 'Parent Portal'],
                'specifications'    => ['Users' => 'Unlimited', 'Students' => 'Unlimited', 'Uptime SLA' => '99.9%'],
                'is_active'         => true,
                'is_featured'       => false,
                'sort_order'        => 9,
                'demo_url'          => 'https://demo.cooca.id/education',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        $this->command->info('Products seeded successfully (9 products matching home page).');
    }
}
