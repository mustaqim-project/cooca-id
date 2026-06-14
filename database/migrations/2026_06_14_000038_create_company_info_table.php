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
        Schema::create('company_info', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('key')->unique(); // company_name, address, phone, email, etc
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, html, image, file
            $table->string('group')->default('general'); // general, contact, social_media, seo
            $table->boolean('is_active')->default(true);
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('key');
            $table->index('group');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_info');
    }
};
