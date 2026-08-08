<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add image upload + alt text + analytics columns to blog_posts
        Schema::table('blog_posts', function (Blueprint $table) {
            if (!Schema::hasColumn('blog_posts', 'featured_image_alt')) {
                $table->string('featured_image_alt')->nullable()->after('featured_image');
            }
            if (!Schema::hasColumn('blog_posts', 'og_image_alt')) {
                $table->string('og_image_alt')->nullable()->after('og_image');
            }
            if (!Schema::hasColumn('blog_posts', 'focus_keyword')) {
                $table->string('focus_keyword')->nullable();
            }
            if (!Schema::hasColumn('blog_posts', 'schema_markup')) {
                $table->json('schema_markup')->nullable();
            }
            if (!Schema::hasColumn('blog_posts', 'seo_score')) {
                $table->unsignedTinyInteger('seo_score')->default(0);
            }
            if (!Schema::hasColumn('blog_posts', 'page_views')) {
                $table->unsignedBigInteger('page_views')->default(0);
            }
            if (!Schema::hasColumn('blog_posts', 'unique_visitors')) {
                $table->unsignedBigInteger('unique_visitors')->default(0);
            }
            if (!Schema::hasColumn('blog_posts', 'avg_read_duration_seconds')) {
                $table->unsignedInteger('avg_read_duration_seconds')->default(0);
            }
            if (!Schema::hasColumn('blog_posts', 'bounce_rate')) {
                $table->decimal('bounce_rate', 5, 2)->default(0);
            }
        });

        // Add cover image + OG image + analytics fields to blog_categories
        Schema::table('blog_categories', function (Blueprint $table) {
            if (!Schema::hasColumn('blog_categories', 'cover_image')) {
                $table->string('cover_image')->nullable();
            }
            if (!Schema::hasColumn('blog_categories', 'cover_image_alt')) {
                $table->string('cover_image_alt')->nullable();
            }
            if (!Schema::hasColumn('blog_categories', 'og_image')) {
                $table->string('og_image')->nullable();
            }
            if (!Schema::hasColumn('blog_categories', 'og_title')) {
                $table->string('og_title')->nullable();
            }
            if (!Schema::hasColumn('blog_categories', 'og_description')) {
                $table->text('og_description')->nullable();
            }
            if (!Schema::hasColumn('blog_categories', 'meta_keywords')) {
                $table->string('meta_keywords')->nullable();
            }
            if (!Schema::hasColumn('blog_categories', 'canonical_url')) {
                $table->string('canonical_url')->nullable();
            }
            if (!Schema::hasColumn('blog_categories', 'schema_markup')) {
                $table->json('schema_markup')->nullable();
            }
            if (!Schema::hasColumn('blog_categories', 'focus_keyword')) {
                $table->string('focus_keyword')->nullable();
            }
            if (!Schema::hasColumn('blog_categories', 'total_post_views')) {
                $table->unsignedBigInteger('total_post_views')->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn([
                'featured_image_alt', 'og_image_alt', 'focus_keyword',
                'schema_markup', 'seo_score', 'page_views', 'unique_visitors',
                'avg_read_duration_seconds', 'bounce_rate',
            ]);
        });

        Schema::table('blog_categories', function (Blueprint $table) {
            $table->dropColumn([
                'cover_image', 'cover_image_alt', 'og_image', 'og_title',
                'og_description', 'meta_keywords', 'canonical_url',
                'schema_markup', 'focus_keyword', 'total_post_views',
            ]);
        });
    }
};
