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
        Schema::create('licenses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('customer_id');
            $table->uuid('product_id');
            $table->uuid('subscription_plan_id');
            $table->uuid('erp_request_id')->nullable();
            $table->uuid('domain_id')->nullable();
            $table->string('license_code', 16)->unique();
            $table->string('token_code', 16)->unique();
            $table->string('domain')->unique();
            $table->enum('status', ['inactive', 'active', 'expired', 'revoked'])->default('inactive');
            $table->boolean('is_trial')->default(false);
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->uuid('revoked_by')->nullable();
            $table->text('revocation_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');

            $table->foreign('subscription_plan_id')
                ->references('id')
                ->on('subscription_plans')
                ->onDelete('cascade');

            $table->foreign('erp_request_id')
                ->references('id')
                ->on('erp_requests')
                ->onDelete('set null');

            $table->foreign('domain_id')
                ->references('id')
                ->on('domains')
                ->onDelete('set null');

            $table->foreign('revoked_by')
                ->references('id')
                ->on('admins')
                ->onDelete('set null');

            // Composite index for validation lookups
            $table->index(['domain', 'license_code', 'status']);
            $table->index('license_code');
            $table->index('token_code');
            $table->index('status');
            $table->index('erp_request_id');
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
        Schema::dropIfExists('licenses');
    }
};
