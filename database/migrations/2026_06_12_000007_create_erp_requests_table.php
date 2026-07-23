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
        Schema::create('erp_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('customer_id');
            $table->uuid('product_id')->nullable();
            $table->uuid('affiliate_id')->nullable();
            $table->string('requested_domain')->nullable();
            $table->string('requested_subdomain')->nullable();
            $table->enum('status', [
                'submitted',
                'waiting_approval',
                'waiting_setup',
                'in_setup',
                'domain_setup',
                'testing',
                'active_trial',
                'trial_expired',
                'rejected'
            ])->default('submitted');
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->uuid('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('setup_started_at')->nullable();
            $table->timestamp('testing_at')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('trial_starts_at')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('set null');

            $table->foreign('affiliate_id')
                ->references('id')
                ->on('customers')
                ->onDelete('set null');

            $table->foreign('approved_by')
                ->references('id')
                ->on('customers')
                ->onDelete('set null');

            $table->index('customer_id');
            $table->index('status');
            $table->index('requested_domain');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('erp_requests');
    }
};

