<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('license_id');
            $table->uuid('customer_id');
            $table->string('contract_number')->unique();
            $table->enum('status', ['draft', 'signed', 'revoked'])->default('draft');
            $table->string('pdf_path')->nullable();
            $table->text('customer_signature_data')->nullable(); // base64 from canvas
            $table->string('cooca_signature_path')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('license_id')
                ->references('id')
                ->on('licenses')
                ->onDelete('cascade');

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('cascade');

            $table->index('license_id');
            $table->index('customer_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};

