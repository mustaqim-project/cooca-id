<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;

/**
 * Blog Controller
 * 
 * Handles public blog pages for content marketing and SEO.
 * Uses Blade templates for optimal SEO performance.
 */
class BlogController extends Controller
{
    /**
     * Display a listing of blog posts.
     */
    public function index(Request $request)
    {
        $posts = BlogPost::where('is_published', true)
            ->with(['author'])
            ->latest('published_at')
            ->paginate(9);

        $categories = BlogPost::select('category')
            ->distinct()
            ->whereNotNull('category')
            ->where('is_published', true)
            ->pluck('category');

        $featuredPosts = BlogPost::where('is_published', true)
            ->where('is_featured', true)
            ->with(['author'])
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('blog.index', compact('posts', 'categories', 'featuredPosts'));
    }

    /**
     * Display the specified blog post.
     */
    public function show(string $slug)
    {
        $post = BlogPost::where('slug', $slug)
            ->where('is_published', true)
            ->with(['author'])
            ->firstOrFail();

        // Increment view count
        $post->increment('view_count');

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

        return view('blog.show', compact('post', 'relatedPosts'));
    }
}
