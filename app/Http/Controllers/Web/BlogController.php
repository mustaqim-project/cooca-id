<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

final class BlogController extends Controller
{
    /**
     * Display a listing of blog posts.
     */
    public function index(Request $request): \Illuminate\View\View
    {
        $category = $request->input('category');
        $tag = $request->input('tag');
        $search = $request->input('search');

        $query = BlogPost::query()
            ->where('is_published', true)
            ->published()
            ->with(['author', 'category']);

        if ($category) {
            $query->where('category', $category);
        }

        if ($tag) {
            $query->whereJsonContains('tags', $tag);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $posts = $query->latest('published_at')->paginate(12);

        $categories = BlogPost::query()
            ->selectRaw('category, COUNT(*) as count')
            ->where('is_published', true)
            ->groupBy('category')
            ->get();

        $popularPosts = BlogPost::query()
            ->where('is_published', true)
            ->published()
            ->orderByDesc('views_count')
            ->limit(5)
            ->get();

        return view('blog.index', compact('posts', 'categories', 'popularPosts'));
    }

    /**
     * Display the specified blog post.
     */
    public function show(string $slug): \Illuminate\View\View
    {
        $post = BlogPost::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->with(['author'])
            ->firstOrFail();

        // Increment view count
        $post->increment('views_count');

        $relatedPosts = BlogPost::query()
            ->where('is_published', true)
            ->where('id', '!=', $post->id)
            ->where('category', $post->category)
            ->published()
            ->limit(3)
            ->get();

        return view('blog.show', compact('post', 'relatedPosts'));
    }
}
