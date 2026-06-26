<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds subscription_plan_id and plan_name columns to affiliate_commissions
     * so commissions can be tracked per pricing plan.
     */
    public function up(): void
    {
        Schema::table('affiliate_commissions', function (Blueprint $table) {
            // Link commission to the specific plan that was purchased
            $table->uuid('subscription_plan_id')->nullable()->after('customer_id');
            // Snapshot plan name for historical reference (plan may be renamed/deleted later)
            $table->string('plan_name')->nullable()->after('subscription_plan_id');

            $table->foreign('subscription_plan_id')
                ->references('id')
                ->on('subscription_plans')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affiliate_commissions', function (Blueprint $table) {
            $table->dropForeign(['subscription_plan_id']);
            $table->dropColumn(['subscription_plan_id', 'plan_name']);
        });
    }
};
