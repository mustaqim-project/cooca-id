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
        Schema::create('trials', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('customer_id');
            $table->uuid('erp_product_id');
            $table->uuid('subscription_plan_id');
            $table->string('subdomain', 63)->unique();
            $table->uuid('affiliate_code_id')->nullable();
            $table->enum('status', [
                'draft',
                'submitted',
                'waiting_approval',
                'waiting_provisioning',
                'provisioning',
                'domain_setup',
                'testing',
                'active_trial',
                'converted_to_subscription',
                'rejected',
                'expired',
                'failed'
            ])->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('converted_at')->nullable();
            $table->uuid('subscription_id')->nullable();
            $table->json('provisioning_config')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('cascade');

            $table->foreign('erp_product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');

            $table->foreign('subscription_plan_id')
                ->references('id')
                ->on('subscription_plans')
                ->onDelete('cascade');

            $table->foreign('affiliate_code_id')
                ->references('id')
                ->on('affiliate_codes')
                ->onDelete('set null');

            $table->foreign('subscription_id')
                ->references('id')
                ->on('subscriptions')
                ->onDelete('set null');

            $table->index('customer_id');
            $table->index('erp_product_id');
            $table->index('status');
            $table->index(['customer_id', 'status']);
            $table->index('expires_at');
            $table->index('submitted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trials');
    }
};
