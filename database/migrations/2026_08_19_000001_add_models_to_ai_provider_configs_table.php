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
        Schema::table('ai_provider_configs', function (Blueprint $table) {
            if (!Schema::hasColumn('ai_provider_configs', 'models')) {
                $table->json('models')->nullable()->after('base_url');
            }
            if (!Schema::hasColumn('ai_provider_configs', 'total_token_quota')) {
                $table->unsignedBigInteger('total_token_quota')->default(0)->after('models');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_provider_configs', function (Blueprint $table) {
            if (Schema::hasColumn('ai_provider_configs', 'total_token_quota')) {
                $table->dropColumn('total_token_quota');
            }
            if (Schema::hasColumn('ai_provider_configs', 'models')) {
                $table->dropColumn('models');
            }
        });
    }
};
