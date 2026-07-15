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
        Schema::table('affiliators', function (Blueprint $table) {
            $table->string('status')->default('active')->after('google_id');
            $table->string('suspension_reason_type')->nullable()->after('status');
            $table->text('suspension_reason_notes')->nullable()->after('suspension_reason_type');
            $table->text('appeal_reason')->nullable()->after('suspension_reason_notes');
            $table->string('appeal_proof_path')->nullable()->after('appeal_reason');
            $table->timestamp('appealed_at')->nullable()->after('appeal_proof_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affiliators', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'suspension_reason_type',
                'suspension_reason_notes',
                'appeal_reason',
                'appeal_proof_path',
                'appealed_at',
            ]);
        });
    }
};
