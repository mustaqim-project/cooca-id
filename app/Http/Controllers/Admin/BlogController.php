<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;

/**
 * Admin Blog Controller
 * 
 * Manages blog posts and articles.
 */
class BlogController extends Controller
{
    /**
     * Display a listing of blog posts.
     */
    public function index(Request $request)
    {
        $query = BlogPost::with(['author'])->latest('created_at');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            if ($status === 'published') {
                $query->where('is_published', true);
            } elseif ($status === 'draft') {
                $query->where('is_published', false);
            }
        }

        if ($category = $request->get('category')) {
            $query->where('category', $category);
        }

        $posts = $query->paginate(20)->withQueryString();

        $categories = BlogPost::select('category')
            ->distinct()
            ->whereNotNull('category')
            ->pluck('category');

        return Inertia::render('Admin/Blog/Index', [
            'posts' => $posts,
            'categories' => $categories,
            'filters' => [
                'search' => $request->get('search'),
                'status' => $request->get('status'),
                'category' => $request->get('category'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new post.
     */
    public function create()
    {
        return Inertia::render('Admin/Blog/Create', [
            'post' => null,
            'categories' => BlogPost::select('category')
                ->distinct()
                ->whereNotNull('category')
                ->pluck('category'),
        ]);
    }

    /**
     * Store a newly created post in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'featured_image' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $validated['author_id'] = Auth::id();
        $validated['is_published'] = $request->boolean('is_published');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['tags'] = $request->input('tags', []);

        BlogPost::create($validated);

        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog post created successfully.');
    }

    /**
     * Display the specified post.
     */
    public function show(BlogPost $post)
    {
        return Inertia::render('Admin/Blog/Show', [
            'post' => $post->load('author'),
        ]);
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(BlogPost $post)
    {
        return Inertia::render('Admin/Blog/Edit', [
            'post' => $post,
            'categories' => BlogPost::select('category')
                ->distinct()
                ->whereNotNull('category')
                ->pluck('category'),
        ]);
    }

    /**
     * Update the specified post in storage.
     */
    public function update(Request $request, BlogPost $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => "nullable|string|max:255|unique:blog_posts,slug,{$post->id}",
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'featured_image' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $validated['is_published'] = $request->boolean('is_published');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['tags'] = $request->input('tags', []);

        $post->update($validated);

        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog post updated successfully.');
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy(BlogPost $post)
    {
        $post->delete();

        return back()->with('success', 'Blog post deleted successfully.');
    }
}
