<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Admin Blog Category Controller
 *
 * Full CMS for blog categories:
 * - Cover image upload (filename = category slug)
 * - Full SEO: meta, OG, canonical, focus keyword, schema markup
 * - Analytics aggregation
 */
final class BlogCategoryController extends Controller
{
    // -----------------------------------------------------------------------
    // Index
    // -----------------------------------------------------------------------

    public function index()
    {
        $categories = BlogCategory::withCount('posts')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.blog.categories.index', [
            'categories' => $categories,
        ]);
    }

    // -----------------------------------------------------------------------
    // Create
    // -----------------------------------------------------------------------

    public function create()
    {
        return view('admin.blog.categories.create');
    }

    // -----------------------------------------------------------------------
    // Store
    // -----------------------------------------------------------------------

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:blog_categories,slug',
            'description'      => 'nullable|string',
            'icon'             => 'nullable|string|max:100',
            // Cover image
            'cover_image'      => 'nullable|image|mimes:jpeg,jpg,png,webp|max:3072',
            'cover_image_alt'  => 'nullable|string|max:255',
            // SEO
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords'    => 'nullable|string|max:255',
            'canonical_url'    => 'nullable|url|max:255',
            'og_title'         => 'nullable|string|max:255',
            'og_description'   => 'nullable|string|max:500',
            'og_image'         => 'nullable|image|mimes:jpeg,jpg,png,webp|max:3072',
            'focus_keyword'    => 'nullable|string|max:100',
            'is_active'        => 'boolean',
            'sort_order'       => 'nullable|integer',
        ]);

        $slug = Str::slug($validated['slug'] ?? $validated['name']);
        $validated['slug'] = $slug;

        // Upload cover image (converted to WebP)
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = app(ImageService::class)->saveToStorage(
                $request->file('cover_image'),
                'public',
                'blog/categories',
                $slug
            );
        } else {
            unset($validated['cover_image']);
        }

        // Upload OG image (converted to WebP)
        if ($request->hasFile('og_image')) {
            $validated['og_image'] = app(ImageService::class)->saveToStorage(
                $request->file('og_image'),
                'public',
                'blog/categories/og',
                "{$slug}-og"
            );
        } else {
            unset($validated['og_image']);
        }

        $validated['is_active']   = $request->boolean('is_active', true);
        $validated['sort_order']  = (int) ($validated['sort_order'] ?? 0);

        BlogCategory::create($validated);

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Blog Category created successfully.');
    }

    // -----------------------------------------------------------------------
    // Edit
    // -----------------------------------------------------------------------

    public function edit(BlogCategory $blogCategory)
    {
        return view('admin.blog.categories.edit', [
            'category' => $blogCategory,
        ]);
    }

    /**
     * Display the specified category.
     */
    public function show(BlogCategory $blogCategory)
    {
        return view('admin.blog.categories.show', [
            'category' => $blogCategory,
        ]);
    }

    // -----------------------------------------------------------------------
    // Update
    // -----------------------------------------------------------------------

    public function update(Request $request, BlogCategory $blogCategory)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'slug'             => "nullable|string|max:255|unique:blog_categories,slug,{$blogCategory->id}",
            'description'      => 'nullable|string',
            'icon'             => 'nullable|string|max:100',
            // Cover image
            'cover_image'      => 'nullable|image|mimes:jpeg,jpg,png,webp|max:3072',
            'cover_image_alt'  => 'nullable|string|max:255',
            // SEO
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords'    => 'nullable|string|max:255',
            'canonical_url'    => 'nullable|url|max:255',
            'og_title'         => 'nullable|string|max:255',
            'og_description'   => 'nullable|string|max:500',
            'og_image'         => 'nullable|image|mimes:jpeg,jpg,png,webp|max:3072',
            'focus_keyword'    => 'nullable|string|max:100',
            'is_active'        => 'boolean',
            'sort_order'       => 'nullable|integer',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        $slug = $validated['slug'];

        // Replace cover image (converted to WebP)
        if ($request->hasFile('cover_image')) {
            if ($blogCategory->cover_image) {
                Storage::disk('public')->delete($blogCategory->cover_image);
            }
            $validated['cover_image'] = app(ImageService::class)->saveToStorage(
                $request->file('cover_image'),
                'public',
                'blog/categories',
                $slug
            );
        } else {
            unset($validated['cover_image']);
        }

        // Replace OG image (converted to WebP)
        if ($request->hasFile('og_image')) {
            if ($blogCategory->og_image) {
                Storage::disk('public')->delete($blogCategory->og_image);
            }
            $validated['og_image'] = app(ImageService::class)->saveToStorage(
                $request->file('og_image'),
                'public',
                'blog/categories/og',
                "{$slug}-og"
            );
        } else {
            unset($validated['og_image']);
        }

        $validated['is_active']  = $request->boolean('is_active', true);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        $blogCategory->update($validated);

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Blog Category updated successfully.');
    }

    // -----------------------------------------------------------------------
    // Destroy
    // -----------------------------------------------------------------------

    public function destroy(BlogCategory $blogCategory)
    {
        if ($blogCategory->cover_image) {
            Storage::disk('public')->delete($blogCategory->cover_image);
        }
        if ($blogCategory->og_image) {
            Storage::disk('public')->delete($blogCategory->og_image);
        }

        $blogCategory->delete();

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Blog Category deleted successfully.');
    }
}
