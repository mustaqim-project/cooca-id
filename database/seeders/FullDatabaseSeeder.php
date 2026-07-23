<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\User;
use App\Models\User;
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
use Illuminate\Support\Str;

class FullDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with comprehensive test data.
     */
    public function run(): void
    {
        $this->command->info('Starting database seeding with super complete data...');

        // Seed Admins
        $this->command->info('Seeding Admins...');
        $admins = User::factory()->count(5)->create();

        // Seed Customers
        $this->command->info('Seeding Customers...');
        $customers = User::factory()
            ->count(25)
            ->sequence(fn($sequence) => [
                'domain' => 'customer-' . ($sequence->index + 1) . '-' . Str::random(6) . '.cooca.id',
                'email' => 'customer.' . ($sequence->index + 1) . '.' . Str::random(4) . '@example.com',
            ])
            ->create();

        // Seed Affiliators
        $this->command->info('Seeding Affiliators...');
        $affiliators = User::factory()
            ->count(15)
            ->sequence(fn($sequence) => [
                'referral_code' => 'AFF-' . strtoupper(Str::random(6)) . '-' . ($sequence->index + 1),
                'email' => 'affiliator.' . ($sequence->index + 1) . '.' . Str::random(4) . '@example.com',
            ])
            ->create();

        // Use existing categories or create new ones
        $categories = ProductCategory::all();
        if ($categories->isEmpty()) {
            $this->command->info('No categories found, seeding categories...');
            $this->call(ProductCategorySeeder::class);
            $categories = ProductCategory::all();
        }

        // Use existing products
        $products = Product::all();
        if ($products->isEmpty()) {
            $this->command->info('No products found, seeding products...');
            $this->call(ProductSeeder::class);
            $products = Product::all();
        }

        // Use existing subscription plans or create new ones
        $plans = SubscriptionPlan::all();
        if ($plans->isEmpty()) {
            $this->command->info('No plans found, seeding plans...');
            $this->call(SubscriptionPlanSeeder::class);
            $plans = SubscriptionPlan::all();
        }

        // Seed Licenses
        $this->command->info('Seeding Licenses...');
        $licenses = License::factory()
            ->count(30)
            ->sequence(fn($sequence) => [
                'customer_id' => $customers->random()->id,
                'product_id' => $products->random()->id,
                'subscription_plan_id' => $plans->random()->id,
            ])
            ->create();

        // Seed Subscriptions
        $this->command->info('Seeding Subscriptions...');
        $subscriptions = Subscription::factory()
            ->count(30)
            ->sequence(fn($sequence) => [
                'subscription_plan_id' => $plans->random()->id,
                'customer_id' => $customers->random()->id,
                'license_id' => $licenses->random()->id,
            ])
            ->create();

        // Seed Vouchers
        $this->command->info('Seeding Vouchers...');
        Voucher::factory()->count(15)->create();

        // Seed Transactions and matching Invoices
        $this->command->info('Seeding Transactions and Invoices...');
        $transactions = Transaction::factory()
            ->count(50)
            ->sequence(fn($sequence) => [
                'customer_id' => $customers->random()->id,
                'subscription_id' => $subscriptions->random()->id,
            ])
            ->create();

        foreach ($transactions as $transaction) {
            Invoice::factory()->create([
                'transaction_id' => $transaction->id,
                'invoice_number' => $transaction->invoice_number,
                'customer_id' => $transaction->customer_id,
                'amount' => $transaction->net_amount,
                'status' => $transaction->status === 'paid' ? 'paid' : ($transaction->status === 'pending' ? 'issued' : 'cancelled'),
                'paid_at' => $transaction->paid_at,
            ]);
        }

        // Seed Affiliate Commissions
        $this->command->info('Seeding Affiliate Commissions...');
        foreach ($transactions->random(30) as $trans) {
            AffiliateCommission::factory()->create([
                'referred_by_id' => $affiliators->random()->id,
                'transaction_id' => $trans->id,
                'customer_id' => $trans->customer_id,
                'gross_amount' => $trans->net_amount,
                'commission_amount' => ($trans->net_amount * 0.20),
                'commission_percent' => 20,
            ]);
        }

        // Seed Affiliate Withdrawals
        $this->command->info('Seeding Affiliate Withdrawals...');
        AffiliateWithdrawal::factory()
            ->count(15)
            ->sequence(fn($sequence) => [
                'referred_by_id' => $affiliators->random()->id,
                'approved_by' => $admins->random()->id,
            ])
            ->create();

        // Seed Pages
        $this->command->info('Seeding Pages...');
        Page::factory()->count(5)->create();

        // Seed Email Campaigns
        $this->command->info('Seeding Email Campaigns...');
        EmailCampaign::factory()
            ->count(10)
            ->sequence(fn($sequence) => [
                'created_by' => $admins->random()->id,
            ])
            ->create();

        // Seed Tickets
        $this->command->info('Seeding Tickets...');
        $tickets = Ticket::factory()
            ->count(25)
            ->sequence(fn($sequence) => [
                'customer_id' => $customers->random()->id,
                'admin_id' => $admins->random()->id,
            ])
            ->create();

        // Seed Ticket Replies
        $this->command->info('Seeding Ticket Replies...');
        TicketReply::factory()
            ->count(60)
            ->sequence(fn($sequence) => [
                'ticket_id' => $tickets->random()->id,
                'user_id' => $sequence->index % 2 === 0 ? $customers->random()->id : $admins->random()->id,
                'user_type' => $sequence->index % 2 === 0 ? 'customer' : 'admin',
            ])
            ->create();

        // Seed Reviews
        $this->command->info('Seeding Reviews...');
        Review::factory()
            ->count(40)
            ->sequence(fn($sequence) => [
                'reviewable_id' => $products->random()->id,
                'reviewer_id' => $customers->random()->id,
                'reviewer_type' => 'customer',
            ])
            ->create();

        $this->command->info('Database seeding completed successfully with super complete and consistent data!');
    }
}
