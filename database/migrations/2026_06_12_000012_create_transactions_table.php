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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('customer_id');
            $table->uuid('subscription_id')->nullable();
            $table->string('type')->default('subscription_new');
            $table->string('invoice_number')->unique();
            $table->decimal('gross_amount', 15, 2);
            $table->decimal('voucher_discount', 15, 2)->default(0);
            $table->decimal('net_amount', 15, 2);
            $table->uuid('voucher_id')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_gateway')->nullable();
            $table->string('midtrans_order_id')->nullable()->unique();
            $table->string('midtrans_transaction_id')->nullable();
            $table->string('midtrans_status')->nullable();
            $table->enum('status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('cascade');

            $table->foreign('subscription_id')
                ->references('id')
                ->on('subscriptions')
                ->onDelete('set null');

            $table->foreign('voucher_id')
                ->references('id')
                ->on('vouchers')
                ->onDelete('set null');

            $table->index('customer_id');
            $table->index('type');
            $table->index('invoice_number');
            $table->index('status');
            $table->index('midtrans_order_id');
            $table->index(['status', 'paid_at']);
            $table->index('subscription_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

