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
        Schema::create('license_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('license_id');
            $table->enum('action', ['generated', 'activated', 'validated', 'suspended', 'revoked', 'expired']);
            $table->text('description')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->foreign('license_id')
                ->references('id')
                ->on('licenses')
                ->onDelete('cascade');

            $table->index('license_id');
            $table->index('action');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('license_logs');
    }
};
