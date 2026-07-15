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
        Schema::create('provisioning_jobs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            // Link to the ERP Request or Subscription
            $table->uuid('erp_request_id')->nullable()->constrained('erp_requests')->onDelete('cascade');
            
            // Details about the tenant
            $table->string('tenant_uuid');
            $table->string('db_name');
            $table->string('db_user');
            $table->string('db_password');
            $table->string('subdomain');
            
            // State tracking
            $table->string('current_step')->default('init'); // init, create_db, migrate, seed, generate_license, set_domain, verify, complete
            $table->string('status')->default('queued'); // queued, running, failed, completed, rolled_back
            
            // Error handling & retries
            $table->integer('attempts')->default(0);
            $table->text('error_message')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provisioning_jobs');
    }
};
