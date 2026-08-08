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
        Schema::table('erp_requests', function (Blueprint $table) {
            // Drop foreign key referencing customers
            $table->dropForeign('erp_requests_approved_by_foreign');

            // Add new foreign key referencing admins
            $table->foreign('approved_by')
                ->references('id')
                ->on('admins')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('erp_requests', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            
            $table->foreign('approved_by')
                ->references('id')
                ->on('customers')
                ->onDelete('set null');
        });
    }
};
