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
        Schema::create('reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->morphs('reviewable');

            $table->enum('reviewer_type', ['customer', 'affiliator']);
            $table->uuid('reviewer_id');

            $table->tinyInteger('rating')->unsigned();

            $table->string('title')->nullable();
            $table->text('comment')->nullable();

            $table->boolean('is_approved')->default(false);

            $table->uuid('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            

            $table->index('reviewer_type');
            $table->index('rating');
            $table->index('is_approved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
