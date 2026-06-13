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
            $table->string('order_id')->unique();
            $table->string('gross_amount');
            $table->string('currency')->default('IDR');
            $table->string('payment_type')->nullable();
            $table->string('transaction_status')->nullable();
            $table->string('fraud_status')->nullable();
            $table->string('status_code')->nullable();
            $table->text('raw_response')->nullable();
            $table->timestamp('transaction_time')->nullable();
            $table->timestamp('settlement_time')->nullable();
            $table->timestamp('expire_time')->nullable();
            $table->timestamps();

            $table->foreign('transaction_id')
                ->references('id')
                ->on('transactions')
                ->onDelete('cascade');

            $table->index('order_id');
            $table->index('transaction_id');
            $table->index('transaction_status');
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
