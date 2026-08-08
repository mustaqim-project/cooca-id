<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\BlogPost;

class SitemapController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)->get();
        $posts = BlogPost::where('is_published', true)->get();

        $content = view('public.sitemap', compact('products', 'posts'))->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }
}
