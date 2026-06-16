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
        Schema::create('menus', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('url');
            $table->string('location')->default('main'); // main, footer, mobile
            $table->uuid('parent_id')->nullable();
            $table->integer('order')->default(0);
            $table->string('icon')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('new_tab')->default(false);
            $table->json('attributes')->nullable();
            $table->timestamps();
            
            $table->foreign('parent_id')->references('id')->on('menus')->onDelete('cascade');
            $table->index('location');
            $table->index('active');
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
