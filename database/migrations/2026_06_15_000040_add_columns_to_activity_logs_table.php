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
        Schema::table('activity_logs', function (Blueprint $table) {
            // Fields used in controllers but missing from original schema
            $table->string('action')->nullable()->after('description');
            $table->string('module')->nullable()->after('action');
            $table->string('ip_address', 45)->nullable()->after('properties');
            $table->text('user_agent')->nullable()->after('ip_address');
            $table->json('metadata')->nullable()->after('user_agent');
            $table->string('user_id', 36)->nullable()->after('id');
            $table->string('user_type', 20)->nullable()->after('user_id');

            // Indexes for searchability
            $table->index('action');
            $table->index('module');
            $table->index(['user_type', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex(['action']);
            $table->dropIndex(['module']);
            $table->dropIndex(['user_type', 'user_id']);
            $table->dropColumn(['action', 'module', 'ip_address', 'user_agent', 'metadata', 'user_id', 'user_type']);
        });
    }
};
