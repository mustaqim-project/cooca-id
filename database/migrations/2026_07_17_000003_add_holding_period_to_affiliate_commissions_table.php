<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adds holding period fields for commission clearance (14 days hold period)
     */
    public function up(): void
    {
        Schema::table('affiliate_commissions', function (Blueprint $table) {
            // Date when commission becomes available (after 14 days holding period)
            $table->timestamp('available_at')->nullable()->after('cleared_at');
            // Date when invoice was paid (starting point for holding period)
            $table->timestamp('invoice_paid_at')->nullable()->after('available_at');
            // Reference to invoice/transaction that generated this commission
            $table->uuid('invoice_id')->nullable()->after('customer_id');
            
            $table->foreign('invoice_id')
                ->references('id')
                ->on('invoices')
                ->onDelete('set null');
                
            $table->index('available_at');
            $table->index('invoice_paid_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affiliate_commissions', function (Blueprint $table) {
            $table->dropForeign(['invoice_id']);
            $table->dropIndex(['available_at']);
            $table->dropIndex(['invoice_paid_at']);
            $table->dropColumn(['available_at', 'invoice_paid_at', 'invoice_id']);
        });
    }
};
