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
        Schema::create('trial_status_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('trial_id');
            $table->string('from_status')->nullable();
            $table->string('to_status');
            $table->text('reason')->nullable();
            $table->uuid('actor_id')->nullable();
            $table->string('actor_type')->nullable(); // Admin, System, Customer
            $table->timestamps();

            $table->foreign('trial_id')
                ->references('id')
                ->on('trials')
                ->onDelete('cascade');

            $table->index('trial_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trial_status_histories');
    }
};
