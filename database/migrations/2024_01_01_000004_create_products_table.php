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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->string('category')->nullable(); // retail, hospitality, professional
            $table->string('icon')->nullable();
            $table->string('featured_image')->nullable();
            $table->json('features')->nullable();
            $table->json('specifications')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->string('pricing_type')->default('one_time'); // one_time, subscription, custom
            $table->boolean('active')->default(true);
            $table->boolean('featured')->default(false);
            $table->integer('order')->default(0);
            $table->json('seo')->nullable();
            $table->timestamps();
            
            $table->index('slug');
            $table->index('active');
            $table->index('category');
            $table->index('featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
