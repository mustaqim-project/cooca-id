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
        // 1. Create ai_wallets table
        if (!Schema::hasTable('ai_wallets')) {
            Schema::create('ai_wallets', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('customer_id')->unique();
                $table->unsignedBigInteger('total_balance')->default(0);
                $table->unsignedBigInteger('total_purchased')->default(0);
                $table->unsignedBigInteger('total_used')->default(0);
                $table->unsignedBigInteger('total_expired')->default(0);
                $table->timestamps();

                $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            });
        }

        // 2. Create ai_token_lots table
        if (!Schema::hasTable('ai_token_lots')) {
            Schema::create('ai_token_lots', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('wallet_id');
                $table->uuid('customer_id');
                $table->uuid('license_id')->nullable();
                $table->string('lot_number', 50)->unique();
                $table->string('name', 255);
                $table->enum('source_type', ['topup', 'subscription', 'bonus', 'promotion', 'refund', 'admin_adjustment'])->default('topup');
                $table->string('source_id', 100)->nullable();
                $table->unsignedBigInteger('original_tokens')->default(0);
                $table->unsignedBigInteger('remaining_tokens')->default(0);
                $table->unsignedBigInteger('used_tokens')->default(0);
                $table->timestamp('purchased_at')->useCurrent();
                $table->timestamp('starts_at')->useCurrent();
                $table->timestamp('expires_at');
                $table->enum('status', ['active', 'depleted', 'expired', 'cancelled'])->default('active');
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->foreign('wallet_id')->references('id')->on('ai_wallets')->onDelete('cascade');
                $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
                $table->foreign('license_id')->references('id')->on('licenses')->onDelete('set null');

                $table->index(['customer_id', 'status']);
                $table->index(['status', 'expires_at']);
                $table->index('source_type');
            });
        }

        // 3. Create ai_token_transactions table
        if (!Schema::hasTable('ai_token_transactions')) {
            Schema::create('ai_token_transactions', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('wallet_id');
                $table->uuid('customer_id');
                $table->uuid('lot_id')->nullable();
                $table->enum('type', [
                    'purchase',
                    'subscription',
                    'usage',
                    'bonus',
                    'promotion',
                    'refund',
                    'expiration',
                    'adjustment',
                    'reversal'
                ]);
                $table->bigInteger('tokens');
                $table->unsignedBigInteger('balance_before')->default(0);
                $table->unsignedBigInteger('balance_after')->default(0);
                $table->string('reference_type', 100)->nullable();
                $table->string('reference_id', 100)->nullable();
                $table->string('idempotency_key', 120)->nullable()->unique();
                $table->text('description')->nullable();
                $table->string('created_by', 100)->nullable();
                $table->timestamps();

                $table->foreign('wallet_id')->references('id')->on('ai_wallets')->onDelete('cascade');
                $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
                $table->foreign('lot_id')->references('id')->on('ai_token_lots')->onDelete('set null');

                $table->index(['customer_id', 'created_at']);
                $table->index(['lot_id', 'created_at']);
                $table->index('type');
            });
        }

        // 4. Update ai_usage_logs table
        if (Schema::hasTable('ai_usage_logs')) {
            Schema::table('ai_usage_logs', function (Blueprint $table) {
                if (!Schema::hasColumn('ai_usage_logs', 'customer_id')) {
                    $table->uuid('customer_id')->nullable()->after('id');
                    $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
                }
                if (!Schema::hasColumn('ai_usage_logs', 'token_lot_id')) {
                    $table->uuid('token_lot_id')->nullable()->after('license_id');
                    $table->foreign('token_lot_id')->references('id')->on('ai_token_lots')->onDelete('set null');
                }
                if (!Schema::hasColumn('ai_usage_logs', 'input_tokens')) {
                    $table->unsignedInteger('input_tokens')->default(0)->after('model');
                }
                if (!Schema::hasColumn('ai_usage_logs', 'output_tokens')) {
                    $table->unsignedInteger('output_tokens')->default(0)->after('input_tokens');
                }
                if (!Schema::hasColumn('ai_usage_logs', 'cached_tokens')) {
                    $table->unsignedInteger('cached_tokens')->default(0)->after('output_tokens');
                }
                if (!Schema::hasColumn('ai_usage_logs', 'estimated_cost')) {
                    $table->decimal('estimated_cost', 12, 6)->nullable()->after('total_tokens');
                }
                if (!Schema::hasColumn('ai_usage_logs', 'actual_cost')) {
                    $table->decimal('actual_cost', 12, 6)->nullable()->after('estimated_cost');
                }
                if (!Schema::hasColumn('ai_usage_logs', 'request_id')) {
                    $table->string('request_id', 100)->nullable()->after('actual_cost');
                }
                if (!Schema::hasColumn('ai_usage_logs', 'user_identifier')) {
                    $table->string('user_identifier', 100)->nullable()->after('request_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('ai_usage_logs')) {
            Schema::table('ai_usage_logs', function (Blueprint $table) {
                $columns = ['customer_id', 'token_lot_id', 'input_tokens', 'output_tokens', 'cached_tokens', 'estimated_cost', 'actual_cost', 'request_id', 'user_identifier'];
                foreach ($columns as $col) {
                    if (Schema::hasColumn('ai_usage_logs', $col)) {
                        if ($col === 'customer_id' || $col === 'token_lot_id') {
                            $table->dropForeign([$col]);
                        }
                        $table->dropColumn($col);
                    }
                }
            });
        }

        Schema::dropIfExists('ai_token_transactions');
        Schema::dropIfExists('ai_token_lots');
        Schema::dropIfExists('ai_wallets');
    }
};
