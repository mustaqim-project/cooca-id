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
        Schema::create('affiliator_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('affiliator_id')->unique()->constrained('affiliators')->cascadeOnDelete();
            $table->decimal('balance', 15, 2)->default(0);
            $table->string('bank_account')->nullable();
            $table->string('bank_name')->nullable();
            $table->uuid('parent_referred_by_id')->nullable();
            $table->string('referral_code')->unique();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('parent_referred_by_id')
                ->references('id')
                ->on('affiliator_profiles')
                ->onDelete('set null');

            $table->index('referral_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliator_profiles');
    }
};
