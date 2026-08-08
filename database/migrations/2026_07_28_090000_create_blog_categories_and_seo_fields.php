<?php

declare(strict_types=1);

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
        // 1. Create blog_categories table
        if (!Schema::hasTable('blog_categories')) {
            Schema::create('blog_categories', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->string('icon')->nullable();
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // 2. Add SEO columns and category_id to blog_posts
        Schema::table('blog_posts', function (Blueprint $table) {
            if (!Schema::hasColumn('blog_posts', 'blog_category_id')) {
                $table->uuid('blog_category_id')->nullable()->after('author_id');
            }
            if (!Schema::hasColumn('blog_posts', 'meta_title')) {
                $table->string('meta_title')->nullable();
            }
            if (!Schema::hasColumn('blog_posts', 'meta_description')) {
                $table->text('meta_description')->nullable();
            }
            if (!Schema::hasColumn('blog_posts', 'meta_keywords')) {
                $table->string('meta_keywords')->nullable();
            }
            if (!Schema::hasColumn('blog_posts', 'canonical_url')) {
                $table->string('canonical_url')->nullable();
            }
            if (!Schema::hasColumn('blog_posts', 'og_title')) {
                $table->string('og_title')->nullable();
            }
            if (!Schema::hasColumn('blog_posts', 'og_description')) {
                $table->text('og_description')->nullable();
            }
            if (!Schema::hasColumn('blog_posts', 'og_image')) {
                $table->string('og_image')->nullable();
            }
            if (!Schema::hasColumn('blog_posts', 'reading_time_minutes')) {
                $table->unsignedInteger('reading_time_minutes')->default(1);
            }
            if (!Schema::hasColumn('blog_posts', 'is_featured')) {
                $table->boolean('is_featured')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn([
                'blog_category_id',
                'meta_title',
                'meta_description',
                'meta_keywords',
                'canonical_url',
                'og_title',
                'og_description',
                'og_image',
                'reading_time_minutes',
                'is_featured',
            ]);
        });

        Schema::dropIfExists('blog_categories');
    }
};
