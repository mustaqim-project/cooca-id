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
        Schema::create('license_activations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('license_id');
            $table->string('domain');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('activated_at');
            $table->timestamp('deactivated_at')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->timestamps();

            $table->foreign('license_id')
                ->references('id')
                ->on('licenses')
                ->onDelete('cascade');

            $table->index('license_id');
            $table->index('domain');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('license_activations');
    }
};
