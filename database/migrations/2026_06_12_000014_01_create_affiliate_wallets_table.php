<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affiliate_wallets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('referred_by_id')->unique();
            $table->decimal('balance', 15, 2)->default(0);
            $table->decimal('pending_balance', 15, 2)->default(0);
            $table->timestamps();

            $table->foreign('referred_by_id')
                ->references('id')
                ->on('affiliators')
                ->cascadeOnDelete();

            $table->index('referred_by_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_wallets');
    }
};

