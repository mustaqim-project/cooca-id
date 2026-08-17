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
        Schema::table('ai_api_keys', function (Blueprint $table) {
            // Drop composite index and unique index on key_prefix if they exist
            $table->dropIndex(['status', 'key_prefix']);
            $table->dropUnique('ai_api_keys_key_prefix_unique');

            // Widen key_prefix to 32 characters
            $table->string('key_prefix', 32)->change();

            // Add index on key_hash and new index on status
            $table->index('key_hash');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_api_keys', function (Blueprint $table) {
            $table->dropIndex(['key_hash']);
            $table->dropIndex(['status']);
            $table->string('key_prefix', 12)->unique()->change();
            $table->index(['status', 'key_prefix']);
        });
    }
};
