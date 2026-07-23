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
            $table->string('business_name')->nullable()->after('email');
            $table->string('domain')->nullable()->after('business_name');
            $table->uuid('affiliator_id')->nullable()->after('domain');
        });

        Schema::table('affiliators', function (Blueprint $table) {
            $table->decimal('balance', 15, 2)->default(0)->after('status');
            $table->string('bank_account')->nullable()->after('balance');
            $table->string('bank_name')->nullable()->after('bank_account');
            $table->uuid('parent_affiliator_id')->nullable()->after('bank_name');
            $table->string('referral_code')->nullable()->after('parent_affiliator_id');
            $table->string('suspension_reason_type')->nullable()->after('suspension_reason');
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
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'business_name',
                'domain',
                'affiliator_id',
            ]);
        });

        Schema::table('affiliators', function (Blueprint $table) {
            $table->dropColumn([
                'balance',
                'bank_account',
                'bank_name',
                'parent_affiliator_id',
                'referral_code',
                'suspension_reason_type',
                'suspension_reason_notes',
                'appeal_reason',
                'appeal_proof_path',
                'appealed_at',
            ]);
        });
    }
};
