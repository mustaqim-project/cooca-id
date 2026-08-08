<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use App\Models\Faq;
use App\Models\NewsletterSubscriber;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Landing Controller (Public Pages)
 *
 * Handles public-facing landing pages, product catalog showcase, and newsletter subscriptions.
 */
class LandingController extends Controller
{
    /**
     * Display the homepage.
     */
    public function index(Request $request): View
    {
        // All active products for showcase section
        $products = Product::where('is_active', true)
            ->with(['category', 'subscriptionPlans'])
            ->ordered()
            ->get();

        // Featured products (max 6) for hero product cards
        $featuredProducts = Product::where('is_active', true)
            ->where('is_featured', true)
            ->with(['category', 'subscriptionPlans'])
            ->ordered()
            ->take(6)
            ->get();

        // Testimonials for social proof section
        $testimonials = Testimonial::where('is_active', true)
            ->ordered()
            ->take(9)
            ->get();

        // Clients for trust marquee
        $clients = CompanyProfile::inRandomOrder()
            ->take(15)
            ->get();

        // FAQs for the accordion section
        $faqs = Faq::where('is_active', true)
            ->ordered()
            ->get();

        // Latest blog posts for blog preview
        $latestPosts = \App\Models\BlogPost::where('is_published', true)
            ->with(['author'])
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('public.pages.home.index', compact(
            'products',
            'featuredProducts',
            'testimonials',
            'faqs',
            'latestPosts',
            'clients'
        ));
    }

    /**
     * Display the about page.
     */
    public function about(): View
    {
        $testimonials = Testimonial::where('is_active', true)->ordered()->take(6)->get();
        return view('public.pages.about.index', compact('testimonials'));
    }

    /**
     * Display the contact page.
     */
    public function contact(): View
    {
        return view('public.pages.contact.index');
    }

    /**
     * Display the affiliate program page.
     */
    public function affiliate(): View
    {
        $testimonials = Testimonial::where('is_active', true)->ordered()->take(3)->get();
        return view('public.pages.affiliate.index', compact('testimonials'));
    }

    /**
     * Switch session language/locale.
     */
    public function switchLang(string $locale): RedirectResponse
    {
        if (in_array($locale, ['id', 'en'])) {
            session()->put('locale', $locale);
        }
        return Redirect::back();
    }

    /**
     * Display the FAQ page.
     */
    public function faq(): View
    {
        $faqs = Faq::where('is_active', true)->ordered()->get();
        $categories = $faqs->pluck('category')->filter()->unique()->values();
        return view('public.pages.faq.index', compact('faqs', 'categories'));
    }

    /**
     * Display the documentation page list.
     */
    public function docs(): View
    {
        $files = File::files(base_path('panduan sistem cooca'));
        $sidebarDocs = [];
        foreach ($files as $file) {
            if ($file->getExtension() === 'md') {
                $filename = $file->getFilenameWithoutExtension();
                $title = ucwords(str_replace('-', ' ', $filename));
                $sidebarDocs[] = [
                    'slug' => $filename,
                    'title' => $title
                ];
            }
        }
        usort($sidebarDocs, fn($a, $b) => strcmp($a['slug'], $b['slug']));

        return view('public.pages.docs.index', ['docs' => $sidebarDocs]);
    }

    /**
     * Display a specific documentation item.
     */
    public function showDoc(string $slug): View
    {
        $filePath = base_path('panduan sistem cooca/' . $slug . '.md');

        if (!file_exists($filePath)) {
            abort(404, 'Dokumentasi tidak ditemukan.');
        }

        $content = file_get_contents($filePath);
        $htmlContent = Str::markdown($content);

        $files = File::files(base_path('panduan sistem cooca'));
        $sidebarDocs = [];
        foreach ($files as $file) {
            if ($file->getExtension() === 'md') {
                $filename = $file->getFilenameWithoutExtension();
                $title = ucwords(str_replace('-', ' ', $filename));
                $sidebarDocs[] = [
                    'slug' => $filename,
                    'title' => $title
                ];
            }
        }
        usort($sidebarDocs, fn($a, $b) => strcmp($a['slug'], $b['slug']));

        $pageTitle = ucwords(str_replace('-', ' ', $slug));
        if (preg_match('/^#\s+(.+)$/m', $content, $matches)) {
            $pageTitle = trim($matches[1]);
        }

        return view('public.pages.docs.show', [
            'content' => $htmlContent,
            'sidebarDocs' => $sidebarDocs,
            'currentSlug' => $slug,
            'pageTitle' => $pageTitle
        ]);
    }

    /**
     * Display the Affiliate Terms of Service page.
     */
    public function affiliateTerms(): View
    {
        $page = Page::where('slug', 'affiliate-terms')->firstOrFail();
        return view('public.pages.legal.terms', compact('page'));
    }

    /**
     * Display the Terms of Service page.
     */
    public function terms(): View
    {
        $page = Page::where('slug', 'terms-of-service')->firstOrFail();
        return view('public.pages.legal.terms', compact('page'));
    }

    /**
     * Display the Privacy Policy page.
     */
    public function privacy(): View
    {
        $page = Page::where('slug', 'privacy-policy')->firstOrFail();
        return view('public.pages.legal.privacy', compact('page'));
    }

    /**
     * Display the products catalog page.
     */
    public function products(Request $request)
    {
        $query = Product::where('is_active', true)->with(['category', 'subscriptionPlans']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $slug = $request->category;
            $query->whereHas('category', fn($q) => $q->where('slug', $slug));
        } elseif ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('product_type')) {
            $query->where('product_type', $request->product_type);
        }
        if ($request->filled('min_price')) {
            $query->where(function ($q) use ($request) {
                $q->where('base_price', '>=', $request->min_price)
                    ->orWhereHas('subscriptionPlans', fn($q2) => $q2->where('price', '>=', $request->min_price));
            });
        }
        if ($request->filled('max_price')) {
            $query->where(function ($q) use ($request) {
                $q->where('base_price', '<=', $request->max_price)
                    ->orWhereHas('subscriptionPlans', fn($q2) => $q2->where('price', '<=', $request->max_price));
            });
        }

        $products = $query->ordered()->paginate(12)->withQueryString();
        $categories = ProductCategory::where('is_active', true)->orderBy('name')->get();
        $productTypes = Product::TYPES;

        if ($request->ajax()) {
            return view('public.pages.products.partials.grid', compact('products'))->render();
        }

        return view('public.pages.products.index', compact('products', 'categories', 'productTypes'));
    }

    /**
     * Show a single product detail.
     */
    public function productShow(string $slug, Request $request): View
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with(['category', 'subscriptionPlans'])
            ->firstOrFail();

        if (method_exists($product, 'increment')) {
            $product->increment('views');
        }

        $canonical = url()->current();

        return view('public.pages.products.detail', compact('product', 'canonical'));
    }

    /**
     * Handle newsletter subscription form submission.
     */
    public function subscribe(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'unique:newsletter_subscribers,email'],
        ]);

        NewsletterSubscriber::create([
            'email' => $validated['email'],
        ]);

        return Redirect::back()->with('status', 'Terima kasih! Anda telah berlangganan newsletter.');
    }
}
