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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['percent', 'nominal']);
            $table->decimal('value', 15, 2);
            $table->decimal('min_purchase', 15, 2)->default(0);
            $table->decimal('max_discount', 15, 2)->nullable();
            $table->unsignedInteger('max_usage')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->unsignedInteger('per_user_limit')->default(1);
            $table->timestamp('valid_from');
            $table->timestamp('valid_until');
            $table->boolean('is_active')->default(true);
            $table->json('applicable_products')->nullable();
            $table->uuid('created_by');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')
                ->references('id')
                ->on('admins')
                ->onDelete('cascade');

            $table->index('code');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
