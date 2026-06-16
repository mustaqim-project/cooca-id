<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run seeders in the correct order
        $this->call([
            SettingsSeeder::class,
            CompanyInfoSeeder::class,
            ProductCategorySeeder::class,
            ProductSeeder::class,
            SubscriptionPlanSeeder::class,
            TestimonialSeeder::class,
            FAQSeeder::class,
            BlogPostSeeder::class,
        ]);
    }
}
