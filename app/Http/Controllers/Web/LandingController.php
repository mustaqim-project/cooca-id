<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Page;
use Illuminate\Http\Request;

final class LandingController extends Controller
{
    /**
     * Display the landing page homepage.
     */
    public function index(Request $request): \Illuminate\View\View
    {
        $featuredProducts = Product::query()
            ->where('is_featured', true)
            ->where('is_active', true)
            ->with(['category', 'subscriptionPlans' => function ($query) {
                $query->where('is_active', true)->limit(3);
            }])
            ->limit(6)
            ->get();

        $categories = ProductCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->limit(8)
            ->get();

        $recentPosts = BlogPost::query()
            ->where('is_published', true)
            ->published()
            ->with('author')
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('landing.home', compact('featuredProducts', 'categories', 'recentPosts'));
    }

    /**
     * Display the about page.
     */
    public function about(): \Illuminate\View\View
    {
        $aboutPage = Page::query()
            ->where('slug', 'about')
            ->where('is_published', true)
            ->first();

        return view('landing.about', compact('aboutPage'));
    }

    /**
     * Display the pricing page.
     */
    public function pricing(Request $request): \Illuminate\View\View
    {
        $categoryId = $request->input('category');

        $products = Product::query()
            ->when($categoryId, fn ($query) => $query->where('category_id', $categoryId))
            ->where('is_active', true)
            ->with(['category', 'subscriptionPlans' => function ($query) {
                $query->where('is_active', true)->orderBy('price');
            }])
            ->orderBy('sort_order')
            ->paginate(12);

        $categories = ProductCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('landing.pricing', compact('products', 'categories'));
    }

    /**
     * Display the contact page.
     */
    public function contact(): \Illuminate\View\View
    {
        return view('landing.contact');
    }

    /**
     * Display the terms of service page.
     */
    public function terms(): \Illuminate\View\View
    {
        $termsPage = Page::query()
            ->where('slug', 'terms-of-service')
            ->where('is_published', true)
            ->first();

        return view('landing.terms', compact('termsPage'));
    }

    /**
     * Display the privacy policy page.
     */
    public function privacy(): \Illuminate\View\View
    {
        $privacyPage = Page::query()
            ->where('slug', 'privacy-policy')
            ->where('is_published', true)
            ->first();

        return view('landing.privacy', compact('privacyPage'));
    }
}
