<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            // Add erp_request_id for trial activation tracking
            $table->uuid('erp_request_id')->nullable()->after('subscription_plan_id');
            
            // Add subscription_id for direct subscription linking
            $table->uuid('subscription_id')->nullable()->after('erp_request_id');
            
            // Add domain_id for proper domain relationship
            $table->uuid('domain_id')->nullable()->after('subscription_id');
            
            // Add starts_at for trial/subscription start tracking
            $table->timestamp('starts_at')->nullable()->after('activated_at');
            
            // Add is_trial flag
            $table->boolean('is_trial')->default(false)->after('status');
            
            // Add foreign keys
            $table->foreign('erp_request_id')
                ->references('id')
                ->on('erp_requests')
                ->onDelete('set null');
                
            $table->foreign('subscription_id')
                ->references('id')
                ->on('subscriptions')
                ->onDelete('set null');
                
            $table->foreign('domain_id')
                ->references('id')
                ->on('domains')
                ->onDelete('set null');
                
            // Add indexes
            $table->index('erp_request_id');
            $table->index('subscription_id');
            $table->index('domain_id');
            $table->index('is_trial');
            $table->index('starts_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->dropForeign(['erp_request_id']);
            $table->dropForeign(['subscription_id']);
            $table->dropForeign(['domain_id']);
            
            $table->dropIndex(['erp_request_id']);
            $table->dropIndex(['subscription_id']);
            $table->dropIndex(['domain_id']);
            $table->dropIndex(['is_trial']);
            $table->dropIndex(['starts_at']);
            
            $table->dropColumn([
                'erp_request_id',
                'subscription_id',
                'domain_id',
                'starts_at',
                'is_trial',
            ]);
        });
    }
};
