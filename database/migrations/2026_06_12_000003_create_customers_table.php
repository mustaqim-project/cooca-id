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
        Schema::create('customers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('business_name')->nullable();
            $table->string('domain')->unique()->nullable();
            $table->uuid('affiliator_id')->nullable();
            $table->string('google_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('affiliator_id')
                ->references('id')
                ->on('affiliators')
                ->onDelete('set null');

            $table->index('email');
            $table->index('domain');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
