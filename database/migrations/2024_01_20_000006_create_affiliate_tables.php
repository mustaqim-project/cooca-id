<?php

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
        Schema::create('affiliate_wallets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('affiliator_id')->constrained('affiliators')->onDelete('cascade');
            $table->decimal('balance', 15, 2)->default(0);
            $table->decimal('pending_balance', 15, 2)->default(0);
            $table->timestamps();

            $table->index('affiliator_id');
        });

        Schema::create('affiliate_commissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('affiliator_id')->constrained('affiliators')->onDelete('cascade');
            $table->foreignUuid('subscription_id')->nullable()->constrained('subscriptions')->onDelete('cascade');
            $table->foreignUuid('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->tinyInteger('level')->default(1); // 1 = direct, 2 = upline
            $table->decimal('gross_amount', 15, 2);
            $table->decimal('commission_rate', 5, 4);
            $table->decimal('commission_amount', 15, 2);
            $table->enum('status', ['pending', 'settled', 'cancelled'])->default('pending');
            $table->timestamp('settled_at')->nullable();
            $table->timestamp('calculated_at')->nullable();
            $table->timestamps();

            $table->index('affiliator_id');
            $table->index('subscription_id');
            $table->index('customer_id');
            $table->index('status');
            $table->index('calculated_at');
        });

        Schema::create('affiliate_withdrawals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('affiliator_id')->constrained('affiliators')->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('account_holder');
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignUuid('approved_by')->nullable()->constrained('admins')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->foreignUuid('rejected_by')->nullable()->constrained('admins')->onDelete('set null');
            $table->timestamp('rejected_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('requested_at')->nullable();
            $table->timestamps();

            $table->index('affiliator_id');
            $table->index('status');
            $table->index('requested_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_withdrawals');
        Schema::dropIfExists('affiliate_commissions');
        Schema::dropIfExists('affiliate_wallets');
    }
};
