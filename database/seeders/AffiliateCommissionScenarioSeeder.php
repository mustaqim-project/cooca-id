<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SubscriptionPlan;
use App\Models\License;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Seeder for testing multi-level affiliate commission scenarios
 */
class AffiliateCommissionScenarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting Affiliate Commission Scenario Seeding...');

        // Create settings for commission rates (dynamic configuration)
        $this->seedSettings();

        // Create a hierarchical affiliate structure
        $this->command->info('Creating affiliate hierarchy...');
        
        // Level 0: Top affiliate (no parent)
        $topAffiliate = User::firstOrCreate(
            ['email' => 'top.affiliate@test.com'],
            [
                'name' => 'Top Affiliate',
                'password' => bcrypt('password123'),
                'referral_code' => 'TOP001',
                'balance' => 0,
                'parent_referred_by_id' => null,
            ]
        );

        // Level 1: Direct downline of top affiliate
        $level1Affiliate = User::firstOrCreate(
            ['email' => 'level1.affiliate@test.com'],
            [
                'name' => 'Level 1 Affiliate',
                'password' => bcrypt('password123'),
                'referral_code' => 'LVL001',
                'balance' => 0,
                'parent_referred_by_id' => $topAffiliate->id,
            ]
        );

        // Level 2: Downline of level 1 affiliate
        $level2Affiliate = User::firstOrCreate(
            ['email' => 'level2.affiliate@test.com'],
            [
                'name' => 'Level 2 Affiliate',
                'password' => bcrypt('password123'),
                'referral_code' => 'LVL002',
                'balance' => 0,
                'parent_referred_by_id' => $level1Affiliate->id,
            ]
        );

        $this->command->info("Created affiliate hierarchy: {$topAffiliate->name} -> {$level1Affiliate->name} -> {$level2Affiliate->name}");

        // Create subscription plans
        $this->command->info('Creating subscription plans...');
        
        $product = \App\Models\Product::first() ?? \App\Models\Product::factory()->create();

        $monthlyPlan = SubscriptionPlan::firstOrCreate(
            ['name' => 'Monthly Test Plan'],
            [
                'product_id' => $product->id,
                'duration_months' => 1,
                'price' => 100000,
                'discount_percent' => 0,
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        $yearlyPlan = SubscriptionPlan::firstOrCreate(
            ['name' => 'Yearly Test Plan'],
            [
                'product_id' => $product->id,
                'duration_months' => 12,
                'price' => 1000000,
                'discount_percent' => 10,
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        // Create customers linked to different affiliates
        $this->command->info('Creating test customers...');

        // Customer referred by Level 2 affiliate (tests both L1 and L2 commissions)
        $customerL2 = User::firstOrCreate(
            ['email' => 'customer.l2@test.com'],
            [
                'name' => 'Customer Level 2',
                'password' => bcrypt('password123'),
                'business_name' => 'Business L2',
                'domain' => 'l2.cooca.id',
                'referred_by_id' => $level2Affiliate->id,
            ]
        );

        // Customer referred by Level 1 affiliate (tests only L1 commission)
        $customerL1 = User::firstOrCreate(
            ['email' => 'customer.l1@test.com'],
            [
                'name' => 'Customer Level 1',
                'password' => bcrypt('password123'),
                'business_name' => 'Business L1',
                'domain' => 'l1.cooca.id',
                'referred_by_id' => $level1Affiliate->id,
            ]
        );

        // Customer referred by Top affiliate (tests only L1 commission)
        $customerTop = User::firstOrCreate(
            ['email' => 'customer.top@test.com'],
            [
                'name' => 'Customer Top',
                'password' => bcrypt('password123'),
                'business_name' => 'Business Top',
                'domain' => 'top.cooca.id',
                'referred_by_id' => $topAffiliate->id,
            ]
        );

        // Create subscriptions and transactions
        $this->command->info('Creating subscriptions and transactions...');

        $licenseL2 = License::firstOrCreate(
            ['license_code' => 'LIC-TEST-L2'],
            [
                'customer_id' => $customerL2->id,
                'product_id' => $product->id,
                'subscription_plan_id' => $monthlyPlan->id,
                'token_code' => strtoupper(Str::random(16)),
                'domain' => 'l2.cooca.id',
                'status' => 'active',
                'activated_at' => now(),
                'expires_at' => now()->addMonth(),
            ]
        );

        $licenseL1 = License::firstOrCreate(
            ['license_code' => 'LIC-TEST-L1'],
            [
                'customer_id' => $customerL1->id,
                'product_id' => $product->id,
                'subscription_plan_id' => $monthlyPlan->id,
                'token_code' => strtoupper(Str::random(16)),
                'domain' => 'l1.cooca.id',
                'status' => 'active',
                'activated_at' => now(),
                'expires_at' => now()->addMonth(),
            ]
        );

        // Subscription for customer L2 (should generate L1 and L2 commissions)
        $subscriptionL2 = Subscription::create([
            'customer_id' => $customerL2->id,
            'license_id' => $licenseL2->id,
            'subscription_plan_id' => $monthlyPlan->id,
            'status' => 'active',
            'started_at' => now(),
            'expires_at' => now()->addMonth(),
        ]);

        $transactionL2 = Transaction::create([
            'customer_id' => $customerL2->id,
            'subscription_id' => $subscriptionL2->id,
            'type' => 'subscription_new',
            'invoice_number' => 'INV-TEST-' . strtoupper(Str::random(8)),
            'status' => 'paid',
            'gross_amount' => 100000,
            'voucher_discount' => 0,
            'net_amount' => 100000,
            'payment_method' => 'bank_transfer',
            'payment_gateway' => 'midtrans',
            'paid_at' => now(),
        ]);

        // Subscription for customer L1 (should generate only L1 commission for level1Affiliate)
        $subscriptionL1 = Subscription::create([
            'customer_id' => $customerL1->id,
            'license_id' => $licenseL1->id,
            'subscription_plan_id' => $monthlyPlan->id,
            'status' => 'active',
            'started_at' => now(),
            'expires_at' => now()->addMonth(),
        ]);

        $transactionL1 = Transaction::create([
            'customer_id' => $customerL1->id,
            'subscription_id' => $subscriptionL1->id,
            'type' => 'subscription_new',
            'invoice_number' => 'INV-TEST-' . strtoupper(Str::random(8)),
            'status' => 'paid',
            'gross_amount' => 100000,
            'voucher_discount' => 0,
            'net_amount' => 100000,
            'payment_method' => 'bank_transfer',
            'payment_gateway' => 'midtrans',
            'paid_at' => now(),
        ]);

        // Create a renewal transaction to test recurring commission
        $this->command->info('Creating renewal transaction for recurring commission test...');
        
        $renewalTransaction = Transaction::create([
            'customer_id' => $customerL2->id,
            'subscription_id' => $subscriptionL2->id,
            'type' => 'subscription_renewal',
            'invoice_number' => 'INV-TEST-' . strtoupper(Str::random(8)),
            'status' => 'paid',
            'gross_amount' => 100000,
            'voucher_discount' => 0,
            'net_amount' => 100000,
            'payment_method' => 'bank_transfer',
            'payment_gateway' => 'midtrans',
            'paid_at' => now(),
        ]);

        $this->command->info('Affiliate Commission Scenario Seeding completed!');
        $this->command->info('');
        $this->command->info('Test Scenarios:');
        $this->command->info('1. Customer L2 -> Level 2 Affiliate -> Level 1 Affiliate -> Top Affiliate');
        $this->command->info('   Expected: Level 2 gets 25%, Level 1 gets 5%');
        $this->command->info('2. Customer L1 -> Level 1 Affiliate -> Top Affiliate');
        $this->command->info('   Expected: Level 1 gets 25%, Top gets 5%');
        $this->command->info('3. Renewal transaction created for recurring commission test');
        $this->command->info('');
        $this->command->info('Run: php artisan affiliate:recurring-commissions to process recurring commissions');
    }

    /**
     * Seed dynamic settings for commission rates
     */
    private function seedSettings(): void
    {
        $this->command->info('Seeding dynamic settings...');

        Setting::updateOrCreate(
            ['key' => 'affiliate.commission_rate_level_1'],
            [
                'value' => '25',
                'type' => 'float',
                'group' => 'affiliate',
                'description' => 'Commission rate for Level 1 (direct referral) in percentage',
                'is_public' => false,
            ]
        );

        Setting::updateOrCreate(
            ['key' => 'affiliate.commission_rate_level_2'],
            [
                'value' => '5',
                'type' => 'float',
                'group' => 'affiliate',
                'description' => 'Commission rate for Level 2 (upline) in percentage',
                'is_public' => false,
            ]
        );

        Setting::updateOrCreate(
            ['key' => 'subscription.trial_duration_days'],
            [
                'value' => '14',
                'type' => 'integer',
                'group' => 'subscription',
                'description' => 'Default trial duration in days',
                'is_public' => true,
            ]
        );

        $this->command->info('Settings seeded successfully!');
    }
}
