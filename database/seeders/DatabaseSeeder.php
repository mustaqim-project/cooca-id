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
            CmsSettingSeeder::class,
            LandingCmsSeeder::class,
            CompanyInfoSeeder::class,
            ProductCategorySeeder::class,
            ProductSeeder::class,
            SubscriptionPlanSeeder::class,
            TestimonialSeeder::class,
            FAQSeeder::class,
            UserSeeder::class,
            AdminAffiliatorCustomerSeeder::class,
            BlogPostSeeder::class,
            AffiliateCommissionScenarioSeeder::class,
            FullDatabaseSeeder::class,
        ]);
    }
}
