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
        Schema::create('ai_plan_configs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('subscription_plan_id')->unique();
            $table->unsignedBigInteger('monthly_token_quota');
            $table->unsignedSmallInteger('requests_per_minute')->default(20);
            $table->json('allowed_models');
            $table->enum('overage_policy', ['hard_stop', 'soft_allow'])->default('hard_stop');
            $table->timestamps();

            $table->foreign('subscription_plan_id')->references('id')->on('subscription_plans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_plan_configs');
    }
};
