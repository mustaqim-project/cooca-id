<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('product_type', 30)->default('saas')->after('category_id')
                ->comment('saas|lifetime|license|subscription|addon|bundle|custom_dev|maintenance|project');
            $table->string('version', 20)->nullable()->after('demo_url')
                ->comment('Current product version e.g. 1.0.0');
            $table->unsignedInteger('max_domains')->default(1)->after('version')
                ->comment('Max domains allowed per license');
            $table->boolean('is_bundleable')->default(false)->after('is_featured')
                ->comment('Can be included in bundles');
            $table->string('license_type', 30)->nullable()->after('product_type')
                ->comment('perpetual|annual|monthly|domain_based');
            $table->text('requirements')->nullable()->after('specifications')
                ->comment('System requirements or prerequisites');
            $table->decimal('setup_fee', 15, 2)->default(0)->after('base_price')
                ->comment('One-time setup fee for custom_dev/project');
            $table->decimal('maintenance_fee', 15, 2)->default(0)->after('setup_fee')
                ->comment('Monthly/annual maintenance fee');

            $table->index('product_type');
            $table->index('license_type');
        });

        // Make category_id nullable (some product types may not need category)
        Schema::table('products', function (Blueprint $table) {
            $table->uuid('category_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['product_type']);
            $table->dropIndex(['license_type']);
            $table->dropColumn([
                'product_type',
                'license_type',
                'version',
                'max_domains',
                'is_bundleable',
                'requirements',
                'setup_fee',
                'maintenance_fee',
            ]);
        });
    }
};
