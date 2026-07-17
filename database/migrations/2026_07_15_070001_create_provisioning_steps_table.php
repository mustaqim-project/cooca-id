<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provisioning_steps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('provisioning_job_id')->constrained('provisioning_jobs')->cascadeOnDelete();
            $table->string('step_name'); // e.g. create_db, deploy_app, configure_domain, seed_data, verify
            $table->unsignedSmallInteger('step_order');
            $table->enum('status', ['pending', 'running', 'completed', 'failed', 'skipped'])->default('pending');
            $table->text('output')->nullable();
            $table->text('error_message')->nullable();
            $table->unsignedInteger('retry_count')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('provisioning_steps');
    }
};
