<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('project_name');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('project_image')->nullable();
            $table->decimal('budget', 15, 2)->nullable();
            $table->uuid('customer_id')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('pending');
            $table->string('estimated_hrs')->nullable();
            $table->string('password')->nullable();
            $table->text('copylinksetting')->nullable();
            $table->text('tags')->nullable();
            $table->uuid('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
