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
        Schema::create('whatsapp_devices', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('owner_type'); // 'admin' or 'customer'
            $table->string('owner_id', 64); // Supports both Integer & UUID string IDs
            $table->string('name'); // e.g. "Customer Service WA", "Main Billing"
            $table->string('session_id')->unique(); // e.g. "session_adm_1_a8x9"
            $table->string('api_key', 64)->unique(); // Secret API token for external API calls
            $table->string('phone_number')->nullable();
            $table->string('status')->default('disconnected'); // 'disconnected', 'connecting', 'scan_qr', 'connected'
            $table->longText('qr_code')->nullable(); // Base64 QR Code string
            $table->string('webhook_url')->nullable();
            $table->timestamps();

            $table->index(['owner_type', 'owner_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_devices');
    }
};
