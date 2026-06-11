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
        Schema::create('affiliate_commissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('affiliator_id');
            $table->uuid('transaction_id');
            $table->uuid('customer_id');
            $table->tinyInteger('level')->default(1); // 1 = L1, 2 = L2
            $table->decimal('gross_amount', 15, 2);
            $table->decimal('commission_percent', 5, 2);
            $table->decimal('commission_amount', 15, 2);
            $table->enum('status', ['pending', 'cleared', 'cancelled'])->default('pending');
            $table->timestamp('cleared_at')->nullable();
            $table->timestamps();

            $table->foreign('affiliator_id')
                ->references('id')
                ->on('affiliators')
                ->onDelete('cascade');

            $table->foreign('transaction_id')
                ->references('id')
                ->on('transactions')
                ->onDelete('cascade');

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('cascade');

            $table->index('affiliator_id');
            $table->index('transaction_id');
            $table->index('level');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_commissions');
    }
};
