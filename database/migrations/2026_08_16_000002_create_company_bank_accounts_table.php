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
        Schema::create('company_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name'); // e.g. BCA, Bank Mandiri, BNI, BRI, BSI, QRIS
            $table->string('bank_code', 20)->nullable(); // e.g. 014, 008
            $table->string('account_number'); // e.g. 8830-8899-8800
            $table->string('account_holder'); // e.g. PT COOCA TECHNOLOGIES INDONESIA
            $table->string('branch')->nullable(); // e.g. KCP Sudirman
            $table->string('logo')->nullable(); // path in storage
            $table->string('qr_code_image')->nullable(); // path to QR code in storage
            $table->text('instructions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_primary')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_bank_accounts');
    }
};
