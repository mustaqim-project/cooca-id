<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliator;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


/**
 * Affiliator Marketing Controller
 * 
 * Provides marketing materials and referral links for affiliators.
 */
class MarketingController extends Controller
{
    /**
     * Display marketing materials dashboard.
     */
    public function index()
    {
        $affiliator = Auth::user();

        $products = Product::where('is_active', true)
            ->with(['category', 'subscriptionPlans'])
            ->get()
            ->map(function ($product) use ($affiliator) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'description' => $product->description,
                    'thumbnail' => $product->thumbnail,
                    'referral_link' => route('home', ['ref' => $affiliator->referral_code]),
                    'direct_link' => route('customer.products.show', ['slug' => $product->slug, 'ref' => $affiliator->referral_code]),
                ];
            });

        $stats = [
            'total_products' => $products->count(),
            'total_clicks' => 0, // Would need tracking table
            'total_conversions' => 0,
        ];

        return view('affiliator.marketing.index', [
            'products' => $products,
            'stats' => $stats,
        ]);
    }

    /**
     * Display banner assets.
     */
    public function banners()
    {
        $affiliator = Auth::user();

        $banners = [
            [
                'id' => 1,
                'name' => 'Hero Banner - Cooca.id',
                'size' => '1200x600',
                'url' => '/images/banners/hero-banner.png',
                'html_code' => '<a href="' . route('home', ['ref' => $affiliator->referral_code]) . '" target="_blank"><img src="' . url('/images/banners/hero-banner.png') . '" alt="Cooca.id - Platform ERP SaaS" width="1200" height="600"></a>',
            ],
            [
                'id' => 2,
                'name' => 'Sidebar Banner',
                'size' => '300x250',
                'url' => '/images/banners/sidebar-banner.png',
                'html_code' => '<a href="' . route('home', ['ref' => $affiliator->referral_code]) . '" target="_blank"><img src="' . url('/images/banners/sidebar-banner.png') . '" alt="Cooca.id" width="300" height="250"></a>',
            ],
            [
                'id' => 3,
                'name' => 'Square Banner',
                'size' => '500x500',
                'url' => '/images/banners/square-banner.png',
                'html_code' => '<a href="' . route('home', ['ref' => $affiliator->referral_code]) . '" target="_blank"><img src="' . url('/images/banners/square-banner.png') . '" alt="Cooca.id" width="500" height="500"></a>',
            ],
        ];

        return view('affiliator.marketing.banners', [
            'banners' => $banners,
        ]);
    }

    /**
     * Display referral links generator.
     */
    public function links()
    {
        $affiliator = Auth::user();

        $baseReferralLink = route('home', ['ref' => $affiliator->referral_code]);
        
        $links = [
            [
                'name' => 'Homepage Referral Link',
                'description' => 'Link ke halaman utama Cooca.id',
                'url' => $baseReferralLink,
                'short_url' => null, // Could integrate with URL shortener
            ],
            [
                'name' => 'Pricing Page',
                'description' => 'Link ke halaman harga',
                'url' => route('pricing', ['ref' => $affiliator->referral_code]),
                'short_url' => null,
            ],
            [
                'name' => 'Affiliate Program',
                'description' => 'Link untuk mengajak affiliator lain',
                'url' => route('affiliate', ['ref' => $affiliator->referral_code]),
                'short_url' => null,
            ],
            [
                'name' => 'Registration Page',
                'description' => 'Link langsung ke pendaftaran customer',
                'url' => route('customer.register', ['ref' => $affiliator->referral_code]),
                'short_url' => null,
            ],
        ];

        $socialMediaTemplates = [
            'facebook' => "🚀 Tingkatkan bisnis Anda dengan Cooca.id! Platform ERP SaaS terintegrasi untuk Restoran, Klinik, Legal, Bengkel, dan banyak lagi. Coba gratis sekarang: {$baseReferralLink}",
            'twitter' => "🚀 Tingkatkan bisnis Anda dengan @cooca_id - Platform ERP SaaS terintegrasi. Coba gratis: {$baseReferralLink} #ERP #SaaS #Bisnis",
            'linkedin' => "Saya merekomendasikan Cooca.id untuk solusi ERP bisnis Anda. Platform SaaS yang lengkap dan mudah digunakan. Pelajari lebih lanjut: {$baseReferralLink}",
            'whatsapp' => "Halo! Saya ingin merekomendasikan Cooca.id untuk mengelola bisnis Anda. Platform ERP SaaS yang lengkap dengan fitur canggih. Coba gratis di sini: {$baseReferralLink}",
        ];

        return view('affiliator.marketing.links', [
            'links' => $links,
            'socialMediaTemplates' => $socialMediaTemplates,
            'referralCode' => $affiliator->referral_code,
        ]);
    }

}
