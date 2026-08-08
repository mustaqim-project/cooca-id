<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Admin Blog Controller
 *
 * Manages blog posts with:
 * - Image upload (filename based on post title slug)
 * - Alt text for featured + OG images
 * - Full SEO metadata management
 * - Analytics field tracking
 */
class BlogController extends Controller
{
    // -----------------------------------------------------------------------
    // Index
    // -----------------------------------------------------------------------

    public function index(Request $request)
    {
        $query = BlogPost::with(['author', 'blogCategory'])->latest('created_at');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            match ($status) {
                'published' => $query->where('is_published', true),
                'draft'     => $query->where('is_published', false),
                'featured'  => $query->where('is_featured', true),
                default     => null,
            };
        }

        if ($categoryId = $request->get('blog_category_id')) {
            $query->where('blog_category_id', $categoryId);
        }

        $posts      = $query->paginate(20)->withQueryString();
        $categories = BlogCategory::where('is_active', true)->orderBy('name')->get();

        return view('admin.blog.index', [
            'posts'      => $posts,
            'categories' => $categories,
            'filters'    => [
                'search'           => $request->get('search'),
                'status'           => $request->get('status'),
                'blog_category_id' => $request->get('blog_category_id'),
            ],
        ]);
    }

    // -----------------------------------------------------------------------
    // Create
    // -----------------------------------------------------------------------

    public function create()
    {
        return view('admin.blog.create', [
            'post'       => null,
            'categories' => BlogCategory::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    // -----------------------------------------------------------------------
    // Store
    // -----------------------------------------------------------------------

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'               => 'required|string|max:255',
            'slug'                => 'nullable|string|max:255|unique:blog_posts,slug',
            'content'             => 'required|string',
            'excerpt'             => 'nullable|string|max:500',
            'blog_category_id'    => 'nullable|exists:blog_categories,id',
            'category'            => 'nullable|string|max:100',
            'tags'                => 'nullable|array',
            'tags.*'              => 'string|max:50',
            // Image Upload
            'featured_image'      => 'nullable|image|mimes:jpeg,jpg,png,webp|max:3072',
            'featured_image_alt'  => 'nullable|string|max:255',
            'og_image'            => 'nullable|image|mimes:jpeg,jpg,png,webp|max:3072',
            'og_image_alt'        => 'nullable|string|max:255',
            // SEO
            'meta_title'          => 'nullable|string|max:255',
            'meta_description'    => 'nullable|string|max:500',
            'meta_keywords'       => 'nullable|string|max:255',
            'canonical_url'       => 'nullable|url|max:255',
            'og_title'            => 'nullable|string|max:255',
            'og_description'      => 'nullable|string|max:500',
            'focus_keyword'       => 'nullable|string|max:100',
            'is_published'        => 'boolean',
            'is_featured'         => 'boolean',
            'published_at'        => 'nullable|date',
        ]);

        // --- Generate slug ---
        $slug = Str::slug($validated['slug'] ?? $validated['title']);
        $validated['slug'] = $slug;

        // --- Upload featured image (converted to WebP) ---
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = app(ImageService::class)->saveToStorage(
                $request->file('featured_image'), 'public', 'blog/images', $slug
            );
        } else {
            unset($validated['featured_image']);
        }

        // --- Upload OG image (converted to WebP) ---
        if ($request->hasFile('og_image')) {
            $validated['og_image'] = app(ImageService::class)->saveToStorage(
                $request->file('og_image'), 'public', 'blog/og', "{$slug}-og"
            );
        } else {
            unset($validated['og_image']);
        }

        $validated['author_id']            = Auth::id();
        $validated['is_published']         = $request->boolean('is_published');
        $validated['is_featured']          = $request->boolean('is_featured');
        $validated['tags']                 = $request->input('tags', []);

        if ($validated['is_published'] && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        // Calculate reading time
        $words = str_word_count(strip_tags($validated['content']));
        $validated['reading_time_minutes'] = (int) max(1, ceil($words / 200));

        $post = BlogPost::create($validated);

        // Compute and save SEO score
        $post->seo_score = $post->computeSeoScore();
        $post->saveQuietly();

        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog post created successfully.');
    }

    // -----------------------------------------------------------------------
    // Show
    // -----------------------------------------------------------------------

    public function show(BlogPost $post)
    {
        return view('admin.blog.show', [
            'post' => $post->load('author', 'blogCategory'),
        ]);
    }

    // -----------------------------------------------------------------------
    // Edit
    // -----------------------------------------------------------------------

    public function edit(BlogPost $post)
    {
        return view('admin.blog.edit', [
            'post'       => $post,
            'categories' => BlogCategory::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    // -----------------------------------------------------------------------
    // Update
    // -----------------------------------------------------------------------

    public function update(Request $request, BlogPost $post)
    {
        $validated = $request->validate([
            'title'               => 'required|string|max:255',
            'slug'                => "nullable|string|max:255|unique:blog_posts,slug,{$post->id}",
            'content'             => 'required|string',
            'excerpt'             => 'nullable|string|max:500',
            'blog_category_id'    => 'nullable|exists:blog_categories,id',
            'category'            => 'nullable|string|max:100',
            'tags'                => 'nullable|array',
            'tags.*'              => 'string|max:50',
            // Image Upload
            'featured_image'      => 'nullable|image|mimes:jpeg,jpg,png,webp|max:3072',
            'featured_image_alt'  => 'nullable|string|max:255',
            'og_image'            => 'nullable|image|mimes:jpeg,jpg,png,webp|max:3072',
            'og_image_alt'        => 'nullable|string|max:255',
            // SEO
            'meta_title'          => 'nullable|string|max:255',
            'meta_description'    => 'nullable|string|max:500',
            'meta_keywords'       => 'nullable|string|max:255',
            'canonical_url'       => 'nullable|url|max:255',
            'og_title'            => 'nullable|string|max:255',
            'og_description'      => 'nullable|string|max:500',
            'focus_keyword'       => 'nullable|string|max:100',
            'is_published'        => 'boolean',
            'is_featured'         => 'boolean',
            'published_at'        => 'nullable|date',
        ]);

        // --- Update slug ---
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }
        $slug = $validated['slug'];

        // --- Replace featured image (converted to WebP) ---
        if ($request->hasFile('featured_image')) {
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $validated['featured_image'] = app(ImageService::class)->saveToStorage(
                $request->file('featured_image'), 'public', 'blog/images', $slug
            );
        } else {
            unset($validated['featured_image']);
        }

        // --- Replace OG image (converted to WebP) ---
        if ($request->hasFile('og_image')) {
            if ($post->og_image) {
                Storage::disk('public')->delete($post->og_image);
            }
            $validated['og_image'] = app(ImageService::class)->saveToStorage(
                $request->file('og_image'), 'public', 'blog/og', "{$slug}-og"
            );
        } else {
            unset($validated['og_image']);
        }

        $validated['is_published'] = $request->boolean('is_published');
        $validated['is_featured']  = $request->boolean('is_featured');
        $validated['tags']         = $request->input('tags', []);

        if ($validated['is_published'] && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        // Calculate reading time
        $words = str_word_count(strip_tags($validated['content']));
        $validated['reading_time_minutes'] = (int) max(1, ceil($words / 200));

        $post->update($validated);

        // Refresh and recompute SEO score
        $post->seo_score = $post->fresh()->computeSeoScore();
        $post->saveQuietly();

        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog post updated successfully.');
    }

    // -----------------------------------------------------------------------
    // Destroy
    // -----------------------------------------------------------------------

    public function destroy(BlogPost $post)
    {
        // Delete associated images from storage
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }
        if ($post->og_image) {
            Storage::disk('public')->delete($post->og_image);
        }

        $post->delete();

        return back()->with('success', 'Blog post deleted successfully.');
    }
}
