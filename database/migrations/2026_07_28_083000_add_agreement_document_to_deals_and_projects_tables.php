<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->uuid('contract_id')->nullable()->after('stage_id');
            $table->string('agreement_document')->nullable()->after('contract_id');

            $table->foreign('contract_id')
                ->references('id')
                ->on('contracts')
                ->onDelete('set null');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->uuid('contract_id')->nullable()->after('customer_id');
            $table->string('agreement_document')->nullable()->after('contract_id');

            $table->foreign('contract_id')
                ->references('id')
                ->on('contracts')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->dropForeign(['contract_id']);
            $table->dropColumn(['contract_id', 'agreement_document']);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['contract_id']);
            $table->dropColumn(['contract_id', 'agreement_document']);
        });
    }
};
