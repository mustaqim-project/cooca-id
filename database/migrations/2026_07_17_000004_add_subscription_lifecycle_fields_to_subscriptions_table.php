<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adds fields for subscription lifecycle management:
     * - Grace period tracking
     * - Upgrade/downgrade history
     * - Auto-renewal settings
     * - Prorated amount tracking
     */
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // Auto-renewal flag
            $table->boolean('auto_renew')->default(true)->after('status');
            
            // Grace period end date (for late payment grace period)
            $table->timestamp('grace_period_ends_at')->nullable()->after('expires_at');
            
            // Date when subscription was suspended (non-payment)
            $table->timestamp('suspended_at')->nullable()->after('cancelled_at');
            
            // Reason for suspension or cancellation
            $table->string('cancellation_reason')->nullable()->after('cancelled_at');
            
            // Previous plan ID (for upgrade/downgrade tracking)
            $table->uuid('previous_plan_id')->nullable()->after('subscription_plan_id');
            
            // Prorated amount for mid-cycle changes
            $table->decimal('prorated_amount', 15, 2)->nullable()->after('previous_plan_id');
            
            // Cycle number (for recurring tracking)
            $table->integer('cycle_number')->default(0)->after('prorated_amount');
            
            // Total renewal count
            $table->integer('renewal_count')->default(0)->after('cycle_number');
            
            // Last renewal date
            $table->timestamp('last_renewed_at')->nullable()->after('renewal_count');
            
            // Trial ID if converted from trial
            $table->uuid('trial_id')->nullable()->after('last_renewed_at');
            
            $table->foreign('previous_plan_id')
                ->references('id')
                ->on('subscription_plans')
                ->onDelete('set null');
                
            $table->foreign('trial_id')
                ->references('id')
                ->on('trials')
                ->onDelete('set null');
            
            $table->index('auto_renew');
            $table->index('grace_period_ends_at');
            $table->index('suspended_at');
            $table->index('last_renewed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign(['previous_plan_id']);
            $table->dropForeign(['trial_id']);
            $table->dropIndex(['auto_renew']);
            $table->dropIndex(['grace_period_ends_at']);
            $table->dropIndex(['suspended_at']);
            $table->dropIndex(['last_renewed_at']);
            $table->dropColumn([
                'auto_renew',
                'grace_period_ends_at',
                'suspended_at',
                'cancellation_reason',
                'previous_plan_id',
                'prorated_amount',
                'cycle_number',
                'renewal_count',
                'last_renewed_at',
                'trial_id'
            ]);
        });
    }
};
