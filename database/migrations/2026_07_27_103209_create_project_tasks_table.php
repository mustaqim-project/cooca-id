<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('estimated_hrs')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('priority')->nullable();
            $table->string('priority_color')->nullable();
            $table->uuid('project_id');
            $table->uuid('stage_id')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_favourite')->default(0);
            $table->boolean('is_complete')->default(0);
            $table->uuid('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_tasks');
    }
};
