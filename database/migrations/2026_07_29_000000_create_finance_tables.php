<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Chart of Account Types
        Schema::create('chart_of_account_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('created_by')->default(0);
            $table->timestamps();
        });

        // 2. Chart of Account Sub Types
        Schema::create('chart_of_account_sub_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('type');
            $table->integer('created_by')->default(0);
            $table->timestamps();
        });

        // 3. Chart of Accounts
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('code')->default(0);
            $table->integer('type')->default(0);
            $table->integer('sub_type')->default(0);
            $table->integer('is_enabled')->default(1);
            $table->text('description')->nullable();
            $table->integer('parent')->default(0);
            $table->integer('created_by')->default(0);
            $table->timestamps();
        });

        // 4. Journal Entries
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('reference')->nullable();
            $table->text('description')->nullable();
            $table->integer('journal_id')->default(0);
            $table->integer('created_by')->default(0);
            $table->timestamps();
        });

        // 5. Journal Items
        Schema::create('journal_items', function (Blueprint $table) {
            $table->id();
            $table->integer('journal')->default(0); // References journal_entries.id
            $table->integer('account')->default(0); // References chart_of_accounts.id
            $table->text('description')->nullable();
            $table->decimal('debit', 15, 2)->default(0.00);
            $table->decimal('credit', 15, 2)->default(0.00);
            $table->timestamps();
        });

        // 6. Bank Accounts
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('holder_name');
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('opening_balance')->default('0.00');
            $table->string('contact_number');
            $table->text('bank_address');
            $table->integer('chart_account_id')->default(0);
            $table->string('payment_name')->nullable();
            $table->integer('created_by')->default(0);
            $table->timestamps();
        });

        // 7. Revenues (Manual revenues outside of subscription)
        Schema::create('revenues', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->decimal('amount', 15, 2)->default(0.00);
            $table->integer('account_id')->default(0); // References bank_accounts.id
            $table->integer('customer_id')->default(0);
            $table->integer('category_id')->default(0);
            $table->string('payment_method')->default('0');
            $table->string('reference')->nullable();
            $table->text('description')->nullable();
            $table->integer('created_by')->default(0);
            $table->timestamps();
        });

        // 8. Expenses
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->decimal('amount', 15, 2)->default(0.00);
            $table->integer('account_id')->default(0); // References bank_accounts.id
            $table->integer('vender_id')->default(0);
            $table->integer('category_id')->default(0);
            $table->string('payment_method')->default('0');
            $table->string('reference')->nullable();
            $table->text('description')->nullable();
            $table->integer('created_by')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('revenues');
        Schema::dropIfExists('bank_accounts');
        Schema::dropIfExists('journal_items');
        Schema::dropIfExists('journal_entries');
        Schema::dropIfExists('chart_of_accounts');
        Schema::dropIfExists('chart_of_account_sub_types');
        Schema::dropIfExists('chart_of_account_types');
    }
};
