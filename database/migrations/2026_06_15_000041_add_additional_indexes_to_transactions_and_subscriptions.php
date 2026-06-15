<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Add additional indexes for performance optimization on frequently queried columns
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Composite index for payment status queries
            if (!$this->hasIndex('transactions', 'idx_status_paid_at')) {
                $table->index(['status', 'paid_at'], 'idx_status_paid_at');
            }
            
            // Index for subscription_id lookups
            if (!$this->hasIndex('transactions', 'idx_subscription_id')) {
                $table->index('subscription_id');
            }
            
            // Index for created_at ordering in transaction history
            if (!$this->hasIndex('transactions', 'idx_created_at')) {
                $table->index('created_at');
            }
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            // Index for expires_at to find expiring subscriptions
            if (!$this->hasIndex('subscriptions', 'idx_expires_at')) {
                $table->index('expires_at');
            }
            
            // Composite index for active subscriptions by customer
            if (!$this->hasIndex('subscriptions', 'idx_customer_status')) {
                $table->index(['customer_id', 'status'], 'idx_customer_status');
            }
            
            // Index for started_at
            if (!$this->hasIndex('subscriptions', 'idx_started_at')) {
                $table->index('started_at');
            }
        });

        Schema::table('affiliate_commissions', function (Blueprint $table) {
            // Composite index for pending commissions by affiliator
            if (!$this->hasIndex('affiliate_commissions', 'idx_affiliator_status')) {
                $table->index(['affiliator_id', 'status'], 'idx_affiliator_status');
            }
            
            // Index for cleared_at to calculate cleared commissions
            if (!$this->hasIndex('affiliate_commissions', 'idx_cleared_at')) {
                $table->index('cleared_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex('idx_status_paid_at');
            $table->dropIndex('idx_subscription_id');
            $table->dropIndex('idx_created_at');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropIndex('idx_expires_at');
            $table->dropIndex('idx_customer_status');
            $table->dropIndex('idx_started_at');
        });

        Schema::table('affiliate_commissions', function (Blueprint $table) {
            $table->dropIndex('idx_affiliator_status');
            $table->dropIndex('idx_cleared_at');
        });
    }

    /**
     * Check if index exists
     */
    private function hasIndex(string $table, string $indexName): bool
    {
        $schemaManager = DB::getDoctrineSchemaManager();
        $indexes = $schemaManager->listTableIndexes($table);
        
        foreach ($indexes as $index) {
            if ($index->getName() === $indexName) {
                return true;
            }
        }
        
        return false;
    }
};
