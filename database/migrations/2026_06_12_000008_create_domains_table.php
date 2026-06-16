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
        Schema::create('domains', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('customer_id');
            $table->uuid('erp_request_id')->nullable();
            $table->string('domain')->unique();
            $table->enum('type', ['subdomain', 'custom_domain'])->default('subdomain');
            $table->enum('status', [
                'pending',
                'verification_required',
                'waiting_setup',
                'in_setup',
                'active',
                'failed'
            ])->default('pending');
            $table->text('dns_notes')->nullable();
            $table->text('ssl_notes')->nullable();
            $table->text('setup_notes')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->text('failure_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('cascade');

            $table->foreign('erp_request_id')
                ->references('id')
                ->on('erp_requests')
                ->onDelete('set null');

            $table->index('customer_id');
            $table->index('erp_request_id');
            $table->index('status');
            $table->index('domain');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};
