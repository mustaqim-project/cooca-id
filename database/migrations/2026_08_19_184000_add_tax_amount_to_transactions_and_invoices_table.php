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
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'subtotal_amount')) {
                $table->decimal('subtotal_amount', 15, 2)->default(0)->after('voucher_discount');
            }
            if (!Schema::hasColumn('transactions', 'tax_amount')) {
                $table->decimal('tax_amount', 15, 2)->default(0)->after('subtotal_amount');
            }
        });

        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'subtotal_amount')) {
                $table->decimal('subtotal_amount', 15, 2)->default(0)->after('customer_id');
            }
            if (!Schema::hasColumn('invoices', 'tax_amount')) {
                $table->decimal('tax_amount', 15, 2)->default(0)->after('subtotal_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['subtotal_amount', 'tax_amount']);
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['subtotal_amount', 'tax_amount']);
        });
    }
};
