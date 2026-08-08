<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Blog Web Controller
 *
 * Handles public blog listing and detail pages.
 */
class BlogWebController extends Controller
{
    /**
     * Display a listing of blog posts.
     */
    public function index(Request $request): View
    {
        $query = BlogPost::where('is_published', true)->with(['author']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('excerpt', 'like', '%' . $search . '%')
                    ->orWhere('content', 'like', '%' . $search . '%');
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $posts = $query->latest('published_at')->paginate(6)->withQueryString();

        // Categories
        $categories = BlogPost::select('category')
            ->distinct()
            ->whereNotNull('category')
            ->where('is_published', true)
            ->pluck('category');

        // Recent Posts for sidebar
        $recentPosts = BlogPost::where('is_published', true)
            ->latest('published_at')
            ->limit(5)
            ->get();

        // Popular Posts for sidebar (by views_count)
        $popularPosts = BlogPost::where('is_published', true)
            ->orderByDesc('views_count')
            ->latest('published_at')
            ->limit(5)
            ->get();

        return view('public.pages.blog.index', compact('posts', 'categories', 'recentPosts', 'popularPosts'));
    }

    /**
     * Display the specified blog post.
     */
    public function show(string $slug): View
    {
        $post = BlogPost::where('slug', $slug)
            ->where('is_published', true)
            ->with(['author'])
            ->firstOrFail();

        // Increment view count
        $post->incrementViews();

        // Get related posts
        $relatedPosts = BlogPost::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->where(function ($query) use ($post) {
                $query->where('category', $post->category)
                    ->orWhereJsonContains('tags', $post->tags);
            })
            ->latest('published_at')
            ->limit(4)
            ->get();

        // Recent & Popular Posts for sidebar
        $recentPosts = BlogPost::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->limit(5)
            ->get();

        $popularPosts = BlogPost::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->orderByDesc('views_count')
            ->latest('published_at')
            ->limit(5)
            ->get();

        $categories = BlogPost::select('category')
            ->distinct()
            ->whereNotNull('category')
            ->where('is_published', true)
            ->pluck('category');

        return view('public.pages.blog.detail', compact('post', 'relatedPosts', 'recentPosts', 'popularPosts', 'categories'));
    }
}
