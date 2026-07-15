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
        Schema::create('api_integrations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique(); // e.g., 'fonnte', 'smtp', 'google_oauth'
            $table->string('label'); // e.g., 'Fonnte WhatsApp', 'SMTP Mail', 'Google OAuth'
            $table->string('category'); // e.g., 'messaging', 'email', 'authentication', 'payment'
            $table->boolean('is_active')->default(false);
            $table->json('credentials'); // Store API keys, secrets, etc.
            $table->json('config')->nullable(); // Additional configuration
            $table->text('description')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('tested_at')->nullable();
            $table->boolean('test_status')->nullable(); // null = not tested, true = success, false = failed
            $table->text('test_message')->nullable();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('name');
            $table->index('category');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_integrations');
    }
};
