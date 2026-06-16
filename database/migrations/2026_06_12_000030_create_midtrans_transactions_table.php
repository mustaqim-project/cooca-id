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
        Schema::create('midtrans_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('transaction_id');
            $table->string('order_id')->index(); // Changed from unique() to index() to allow multiple status updates per order
            $table->string('gross_amount');
            $table->string('currency')->default('IDR');
            $table->string('payment_type')->nullable();
            $table->string('transaction_status')->index();
            $table->string('fraud_status')->nullable();
            $table->string('status_code')->nullable();
            $table->json('raw_response')->nullable(); // Changed from text to json for better querying
            $table->timestamp('transaction_time')->nullable();
            $table->timestamp('settlement_time')->nullable();
            $table->timestamp('expire_time')->nullable();
            $table->timestamps();

            // Composite index for idempotency checks (order_id + transaction_status)
            $table->index(['order_id', 'transaction_status'], 'idx_order_status');

            $table->foreign('transaction_id')
                ->references('id')
                ->on('transactions')
                ->onDelete('cascade');

            $table->index('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('midtrans_transactions');
    }
};
