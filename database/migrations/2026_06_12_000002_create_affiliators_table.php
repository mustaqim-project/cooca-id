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
        Schema::create('affiliators', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->decimal('balance', 15, 2)->default(0);
            $table->string('bank_account')->nullable();
            $table->string('bank_name')->nullable();
            $table->uuid('parent_affiliator_id')->nullable();
            $table->string('referral_code')->unique();
            $table->string('google_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('parent_affiliator_id')
                ->references('id')
                ->on('affiliators')
                ->onDelete('set null');

            $table->index('email');
            $table->index('referral_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliators');
    }
};
