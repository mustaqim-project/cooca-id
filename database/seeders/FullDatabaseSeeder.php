<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Affiliator;
use App\Models\Customer;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\SubscriptionPlan;
use App\Models\License;
use App\Models\Subscription;
use App\Models\Voucher;
use App\Models\Transaction;
use App\Models\Invoice;
use App\Models\AffiliateCommission;
use App\Models\AffiliateWithdrawal;
use App\Models\Page;
use App\Models\BlogPost;
use App\Models\EmailCampaign;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\Review;
use Illuminate\Database\Seeder;

class FullDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with comprehensive test data.
     */
    public function run(): void
    {
        $this->command->info('Starting database seeding...');

        // Seed Admins
        $this->command->info('Seeding Admins...');
        Admin::factory()->count(5)->create();

        // Seed Customers
        $this->command->info('Seeding Customers...');
        $customers = Customer::factory()->count(20)->create();

        // Seed Affiliators
        $this->command->info('Seeding Affiliators...');
        $affiliators = Affiliator::factory()->count(10)->create();

        // Use existing categories or create new ones
        $categories = ProductCategory::all();
        if ($categories->isEmpty()) {
            $this->command->info('No categories found, seeding categories...');
            $categories = ProductCategory::factory()->count(8)->create();
        }

        // Seed more Products
        $this->command->info('Seeding Additional Products...');
        Product::factory()
            ->count(15)
            ->sequence(fn($sequence) => [
                'category_id' => $categories->random()->id,
            ])
            ->create();

        // Use existing subscription plans or create new ones
        $plans = SubscriptionPlan::all();
        if ($plans->isEmpty()) {
            $this->command->info('No plans found, seeding plans...');
            $plans = SubscriptionPlan::factory()->count(4)->create();
        }

        // Seed Subscriptions
        $this->command->info('Seeding Subscriptions...');
        Subscription::factory()
            ->count(15)
            ->sequence(fn($sequence) => [
                'subscription_plan_id' => $plans->random()->id,
                'customer_id' => $customers->random()->id,
            ])
            ->create();

        // Seed Licenses
        $this->command->info('Seeding Licenses...');
        License::factory()
            ->count(25)
            ->sequence(fn($sequence) => [
                'customer_id' => $customers->random()->id,
            ])
            ->create();

        // Seed Vouchers
        $this->command->info('Seeding Vouchers...');
        Voucher::factory()->count(10)->create();

        // Seed Transactions
        $this->command->info('Seeding Transactions...');
        Transaction::factory()
            ->count(40)
            ->sequence(fn($sequence) => [
                'customer_id' => $customers->random()->id,
            ])
            ->create();

        // Seed Invoices
        $this->command->info('Seeding Invoices...');
        Invoice::factory()
            ->count(30)
            ->sequence(fn($sequence) => [
                'customer_id' => $customers->random()->id,
            ])
            ->create();

        // Seed Affiliate Commissions
        $this->command->info('Seeding Affiliate Commissions...');
        AffiliateCommission::factory()
            ->count(20)
            ->sequence(fn($sequence) => [
                'affiliator_id' => $affiliators->random()->id,
            ])
            ->create();

        // Seed Affiliate Withdrawals
        $this->command->info('Seeding Affiliate Withdrawals...');
        AffiliateWithdrawal::factory()
            ->count(8)
            ->sequence(fn($sequence) => [
                'affiliator_id' => $affiliators->random()->id,
            ])
            ->create();

        // Seed Pages
        $this->command->info('Seeding Pages...');
        Page::factory()->count(5)->create();

        // Seed Email Campaigns
        $this->command->info('Seeding Email Campaigns...');
        EmailCampaign::factory()->count(5)->create();

        // Seed Tickets
        $this->command->info('Seeding Tickets...');
        $tickets = Ticket::factory()
            ->count(15)
            ->sequence(fn($sequence) => [
                'customer_id' => $customers->random()->id,
            ])
            ->create();

        // Seed Ticket Replies
        $this->command->info('Seeding Ticket Replies...');
        TicketReply::factory()
            ->count(30)
            ->sequence(fn($sequence) => [
                'ticket_id' => $tickets->random()->id,
            ])
            ->create();

        // Seed Reviews
        $this->command->info('Seeding Reviews...');
        Review::factory()
            ->count(25)
            ->sequence(fn($sequence) => [
                'customer_id' => $customers->random()->id,
            ])
            ->create();

        $this->command->info('Database seeding completed successfully!');
    }
}
