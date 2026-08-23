<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('live_chats', function (Blueprint $table) {
            if (!Schema::hasColumn('live_chats', 'customer_email')) {
                $table->string('customer_email')->nullable()->after('customer_phone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('live_chats', function (Blueprint $table) {
            if (Schema::hasColumn('live_chats', 'customer_email')) {
                $table->dropColumn('customer_email');
            }
        });
    }
};
