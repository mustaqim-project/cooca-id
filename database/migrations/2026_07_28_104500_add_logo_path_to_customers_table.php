<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('customers', 'logo_path')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->string('logo_path')->nullable()->after('business_name');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('customers', 'logo_path')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropColumn('logo_path');
            });
        }
    }
};
