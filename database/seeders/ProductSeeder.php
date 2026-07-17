<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ProductCategory::all();
        if ($categories->isEmpty()) {
            $this->call(ProductCategorySeeder::class);
            $categories = ProductCategory::all();
        }

        Schema::disableForeignKeyConstraints();
        Product::truncate();
        Schema::enableForeignKeyConstraints();

        $commerce = $categories->where('slug', 'commerce-retail')->first() ?? $categories->first();
        $hospitality = $categories->where('slug', 'hospitality-services')->first() ?? $categories->first();
        $health = $categories->where('slug', 'health-professional')->first() ?? $categories->first();

        $products = [
            [
                'category_id' => $commerce->id,
                'name' => 'COOCA POS Enterprise',
                'slug' => 'cooca-pos-enterprise',
                'icon' => 'bi-cart3',
                'short_description' => 'Multi-outlet cloud POS system with real-time inventory and loyalty program.',
                'description' => 'Unified cloud system that tracks every transaction across every outlet in real time.',
                'base_price' => 6900000,
                'product_type' => 'saas',
                'features' => json_encode(['Multi-Outlet POS', 'Real-Time Inventory', 'Customer Loyalty Program', 'Sales Analytics', 'Supplier Management', 'Barcode & QR Support']),
                'specifications' => json_encode(['Users' => 'Up to 50', 'Outlets' => '10 Branches', 'Uptime SLA' => '99.9%']),
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1,
                'demo_url' => 'https://demo.cooca.id/retail',
            ],
            [
                'category_id' => $commerce->id,
                'name' => 'COOCA Salon Cloud',
                'slug' => 'cooca-salon-cloud',
                'icon' => 'bi-scissors',
                'short_description' => 'Appointment booking, stylist scheduling, and customer management.',
                'description' => 'Manage schedules, track services, and grow your salon business.',
                'base_price' => 5900000,
                'product_type' => 'saas',
                'features' => json_encode(['Online Booking', 'Stylist Scheduling', 'Membership & Loyalty', 'Service Revenue Tracking', 'Customer History', 'SMS/WA Reminder']),
                'specifications' => json_encode(['Users' => 'Up to 20', 'Branches' => 'Unlimited', 'Uptime SLA' => '99.9%']),
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 2,
                'demo_url' => 'https://demo.cooca.id/salon',
            ],
            [
                'category_id' => $hospitality->id,
                'name' => 'COOCA Resto & Kitchen Display',
                'slug' => 'cooca-restaurant-suite',
                'icon' => 'bi-cup-hot',
                'short_description' => 'Table management, kitchen display system, and automated split billing.',
                'description' => 'From ordering to billing, every step is automated and synchronized.',
                'base_price' => 7900000,
                'product_type' => 'saas',
                'features' => json_encode(['Table & Reservation Management', 'Kitchen Display System', 'Multi-Outlet Dashboard', 'Menu Management', 'Split Bill', 'Food Cost Analysis']),
                'specifications' => json_encode(['Users' => 'Unlimited', 'Outlets' => 'Unlimited', 'Uptime SLA' => '99.9%']),
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 3,
                'demo_url' => 'https://demo.cooca.id/restaurant',
            ],
            [
                'category_id' => $commerce->id,
                'name' => 'COOCA Retail Desktop (Perpetual)',
                'slug' => 'cooca-retail-desktop-perpetual',
                'icon' => 'bi-pc-display',
                'short_description' => 'Standalone desktop POS with lifetime license key and local database.',
                'description' => 'No monthly fees. Install on your local Windows terminal.',
                'base_price' => 12500000,
                'product_type' => 'lifetime',
                'features' => json_encode(['Lifetime License Key', 'Offline Local Database', 'Barcode Scanner & Cash Drawer Ready', 'Receipt Customization', 'Excel Data Export/Import']),
                'specifications' => json_encode(['Platform' => 'Windows 10/11', 'License' => '1 PC / Terminal Key', 'Support' => '1 Year Free Updates']),
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 4,
                'demo_url' => 'https://demo.cooca.id/desktop',
            ],
            [
                'category_id' => $health->id,
                'name' => 'COOCA Clinic EMR Lifetime Edition',
                'slug' => 'cooca-clinic-emr-lifetime',
                'icon' => 'bi-hospital',
                'short_description' => 'Complete patient records, pharmacy inventory, and medical billing.',
                'description' => 'Fully integrated medical management software with perpetual licensing.',
                'base_price' => 24900000,
                'product_type' => 'lifetime',
                'features' => json_encode(['Perpetual License Code', 'Electronic Medical Records (EMR)', 'Pharmacy & Stock Alerts', 'Insurance & BPJS Export', 'Doctor Fee Computation']),
                'specifications' => json_encode(['License' => '3 Terminals Included', 'Database' => 'MySQL/PostgreSQL Local', 'Support' => '1 Year Technical Support']),
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 5,
                'demo_url' => 'https://demo.cooca.id/clinic',
            ],
            [
                'category_id' => $hospitality->id,
                'name' => 'COOCA Hotel Connector API Key',
                'slug' => 'cooca-hotel-connector-key',
                'icon' => 'bi-key-fill',
                'short_description' => 'Instant digital license key for connecting PMS with OTA channel managers.',
                'description' => 'Activation license key for real-time two-way synchronization.',
                'base_price' => 3500000,
                'product_type' => 'license',
                'features' => json_encode(['Instant Digital Key Delivery', 'Two-Way OTA Synchronization', 'Real-Time Rate & Availability Update', 'Automated Reservation Import', 'Zero Booking Commission']),
                'specifications' => json_encode(['Delivery' => 'Instant Email Key', 'Validity' => '365 Days Activation', 'Support' => '24/7 API Support']),
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 6,
                'demo_url' => null,
            ],
            [
                'category_id' => $hospitality->id,
                'name' => 'Enterprise ERP Custom Integration',
                'slug' => 'enterprise-erp-custom-integration',
                'icon' => 'bi-boxes',
                'short_description' => 'Bespoke custom software development for complex enterprise workflows.',
                'description' => 'End-to-end custom development with dedicated project management.',
                'base_price' => 75000000,
                'product_type' => 'custom_dev',
                'features' => json_encode(['Dedicated Project Manager & Tech Lead', 'Custom System Architecture Design', 'Milestone-Based Billing & Delivery', 'Full Source Code & IP Transfer', 'SLA Dedicated 24/7 Support']),
                'specifications' => json_encode(['Timeline' => '8-16 Weeks Project', 'Methodology' => 'Agile Scrum Delivery', 'Warranty' => '6 Months Post-Launch Warranty']),
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 7,
                'demo_url' => null,
            ],
            [
                'category_id' => $commerce->id,
                'name' => 'COOCA WhatsApp AI Bot Addon',
                'slug' => 'cooca-whatsapp-ai-bot-addon',
                'icon' => 'bi-whatsapp',
                'short_description' => 'Automate order taking, reservation confirmation, and customer FAQ via WhatsApp.',
                'description' => 'AI-powered WhatsApp chatbot for any COOCA product.',
                'base_price' => 1500000,
                'product_type' => 'addon',
                'features' => json_encode(['Automated WhatsApp AI Auto-Responder', 'One-Click Integration to COOCA POS/Resto', 'Custom Flow & Template Builder', 'Broadcast & Marketing Campaign Ready']),
                'specifications' => json_encode(['Compatibility' => 'All COOCA Products', 'Setup Time' => 'Instant / 1 Business Day']),
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 8,
                'demo_url' => null,
            ],
            [
                'category_id' => $health->id,
                'name' => 'Annual Priority Support SLA & Maintenance',
                'slug' => 'annual-priority-support-sla',
                'icon' => 'bi-headset',
                'short_description' => 'Dedicated priority phone/remote support with 1-hour response guarantee.',
                'description' => 'Direct hotline access to senior engineers and proactive monthly health checks.',
                'base_price' => 12000000,
                'product_type' => 'service',
                'features' => json_encode(['1-Hour Maximum Response Guarantee', 'Dedicated Technical Account Manager', 'Monthly Database Backup Audit & Optimization', 'Free Version Upgrades & Patching']),
                'specifications' => json_encode(['Coverage' => '24/7/365 Support', 'Contract' => '12 Months SLA']),
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 9,
                'demo_url' => null,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        $this->command->info('Products seeded successfully.');
    }
}
