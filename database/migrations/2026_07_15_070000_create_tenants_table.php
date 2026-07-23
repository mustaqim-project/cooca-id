<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignUuid('erp_request_id')->nullable()->constrained('erp_requests')->nullOnDelete();
            $table->foreignUuid('product_id')->constrained('products');
            $table->string('subdomain')->unique();
            $table->string('custom_domain')->nullable()->unique();
            $table->string('db_name')->nullable();
            $table->string('db_host')->nullable();
            $table->enum('status', ['provisioning', 'active', 'suspended', 'deprovisioned'])->default('provisioning');
            $table->string('server_ip')->nullable();
            $table->json('config')->nullable();
            $table->timestamp('provisioned_at')->nullable();
            $table->timestamp('suspended_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};

