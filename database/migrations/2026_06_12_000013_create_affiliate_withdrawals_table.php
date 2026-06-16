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
        Schema::create('affiliate_withdrawals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('affiliator_id');
            $table->decimal('amount', 15, 2);
            $table->decimal('fee', 15, 2)->default(0);
            $table->decimal('net_amount', 15, 2);
            $table->enum('withdrawal_method', ['bank', 'ewallet']);
            $table->string('account_number');
            $table->string('account_name');
            $table->enum('status', ['pending', 'approved', 'rejected', 'paid'])->default('pending');
            $table->uuid('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->foreign('affiliator_id')
                ->references('id')
                ->on('affiliators')
                ->onDelete('cascade');

            $table->foreign('approved_by')
                ->references('id')
                ->on('admins')
                ->onDelete('set null');

            $table->index('affiliator_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_withdrawals');
    }
};
