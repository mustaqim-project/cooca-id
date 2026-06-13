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

        // Seed Product Categories
        $this->command->info('Seeding Product Categories...');
        $categories = ProductCategory::factory()->count(8)->create();

        // Seed Products
        $this->command->info('Seeding Products...');
        Product::factory()
            ->count(30)
            ->for(array_rand($categories->all(), 1), 'category')
            ->create();

        // Seed Subscription Plans
        $this->command->info('Seeding Subscription Plans...');
        $plans = SubscriptionPlan::factory()->count(4)->create();

        // Seed Subscriptions
        $this->command->info('Seeding Subscriptions...');
        Subscription::factory()
            ->count(15)
            ->for(array_rand($plans->all(), 1), 'plan')
            ->for(array_rand($customers->all(), 1), 'customer')
            ->create();

        // Seed Licenses
        $this->command->info('Seeding Licenses...');
        License::factory()
            ->count(25)
            ->for(array_rand($customers->all(), 1), 'customer')
            ->create();

        // Seed Vouchers
        $this->command->info('Seeding Vouchers...');
        Voucher::factory()->count(10)->create();

        // Seed Transactions
        $this->command->info('Seeding Transactions...');
        Transaction::factory()
            ->count(40)
            ->for(array_rand($customers->all(), 1), 'customer')
            ->create();

        // Seed Invoices
        $this->command->info('Seeding Invoices...');
        Invoice::factory()
            ->count(30)
            ->for(array_rand($customers->all(), 1), 'customer')
            ->create();

        // Seed Affiliate Commissions
        $this->command->info('Seeding Affiliate Commissions...');
        AffiliateCommission::factory()
            ->count(20)
            ->for(array_rand($affiliators->all(), 1), 'affiliator')
            ->create();

        // Seed Affiliate Withdrawals
        $this->command->info('Seeding Affiliate Withdrawals...');
        AffiliateWithdrawal::factory()
            ->count(8)
            ->for(array_rand($affiliators->all(), 1), 'affiliator')
            ->create();

        // Seed Pages
        $this->command->info('Seeding Pages...');
        Page::factory()->count(5)->create();

        // Seed Blog Posts
        $this->command->info('Seeding Blog Posts...');
        BlogPost::factory()->count(10)->create();

        // Seed Email Campaigns
        $this->command->info('Seeding Email Campaigns...');
        EmailCampaign::factory()->count(5)->create();

        // Seed Tickets
        $this->command->info('Seeding Tickets...');
        $tickets = Ticket::factory()
            ->count(15)
            ->for(array_rand($customers->all(), 1), 'customer')
            ->create();

        // Seed Ticket Replies
        $this->command->info('Seeding Ticket Replies...');
        TicketReply::factory()
            ->count(30)
            ->for(array_rand($tickets->all(), 1), 'ticket')
            ->create();

        // Seed Reviews
        $this->command->info('Seeding Reviews...');
        Review::factory()
            ->count(25)
            ->for(array_rand($customers->all(), 1), 'customer')
            ->create();

        $this->command->info('Database seeding completed successfully!');
    }
}
