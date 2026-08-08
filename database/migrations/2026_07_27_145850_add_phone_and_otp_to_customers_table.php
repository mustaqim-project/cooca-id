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
        Schema::table('customers', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->unique()->after('email');
            $table->timestamp('phone_verified_at')->nullable()->after('phone');
            $table->string('wa_otp_code', 10)->nullable()->after('phone_verified_at');
            $table->timestamp('wa_otp_expires_at')->nullable()->after('wa_otp_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (\Illuminate\Support\Facades\DB::getDriverName() !== 'sqlite') {
                $table->dropUnique(['phone']);
            }
            $table->dropColumn(['phone', 'phone_verified_at', 'wa_otp_code', 'wa_otp_expires_at']);
        });
    }
};
