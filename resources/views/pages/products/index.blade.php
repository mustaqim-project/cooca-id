@extends('layouts.guest')
@push('styles')
<!-- Swiper CSS for Premium Slider -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    .hero-section {
        min-height: 55vh;
        display: flex;
        align-items: center;
        padding-top: 120px;
        padding-bottom: 60px;
        position: relative;
        overflow: hidden;
        background: var(--hero-gradient);
    }
    .hero-bg-orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.12;
        pointer-events: none;
    }
    .hero-bg-orb-1 {
        width: 500px;
        height: 500px;
        background: var(--primary);
        top: -200px;
        right: -100px;
    }
    .hero-bg-orb-2 {
        width: 400px;
        height: 400px;
        background: var(--accent);
        bottom: -100px;
        left: -100px;
    }
    .hero-content {
        position: relative;
        z-index: 2;
    }
    .product-card-wrapper {
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .product-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: all var(--transition);
        box-shadow: var(--shadow);
    }
    .product-card:hover {
        transform: translateY(-8px);
        border-color: rgba(56, 189, 248, 0.3);
        box-shadow: 0 24px 64px rgba(56, 189, 248, 0.08), var(--shadow-lg);
    }
    .product-image-wrapper {
        position: relative;
        padding-top: 56.25%; /* 16:9 Aspect Ratio */
        background: var(--card-alt);
        overflow: hidden;
    }
    .product-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .product-card:hover .product-image {
        transform: scale(1.05);
    }
    .product-badge {
        position: absolute;
        top: 16px;
        left: 16px;
        z-index: 2;
        padding: 6px 14px;
        background: var(--glass);
        backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
        border-radius: 50px;
        color: var(--text);
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }
    .product-card-body {
        padding: 32px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    .product-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 12px;
    }
    .product-desc {
        color: var(--text-muted);
        font-size: 0.95rem;
        line-height: 1.7;
        margin-bottom: 24px;
        flex: 1;
    }
    .product-features-list {
        list-style: none;
        padding: 0;
        margin-bottom: 28px;
    }
    .product-features-list li {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 10px;
        font-size: 0.9rem;
        color: var(--text-muted);
    }
    .product-features-list i {
        color: var(--success);
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .product-card-footer {
        padding-top: 20px;
        border-top: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .product-price-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-muted);
        margin-bottom: 4px;
    }
    .product-price {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--text);
    }
    .product-cta-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: rgba(37, 99, 235, 0.1);
        border: 1px solid rgba(37, 99, 235, 0.2);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        transition: all var(--transition);
    }
    .product-card:hover .product-cta-icon {
        background: var(--primary);
        color: #fff;
        transform: translateX(4px);
    }

    /* Category Tabs Styling */
    .category-tabs-wrapper {
        display: flex;
        justify-content: center;
        margin-bottom: 48px;
        padding: 0 16px;
    }
    .category-tabs-container {
        display: flex;
        gap: 8px;
        background: var(--card);
        border: 1px solid var(--border);
        padding: 8px;
        border-radius: 50px;
        box-shadow: var(--shadow);
        overflow-x: auto;
        max-width: 100%;
        scrollbar-width: none;
    }
    .category-tabs-container::-webkit-scrollbar {
        display: none;
    }
    .cat-tab-btn {
        padding: 12px 28px;
        border-radius: 50px;
        border: none;
        background: transparent;
        color: var(--text-muted);
        font-weight: 700;
        font-size: 0.95rem;
        white-space: nowrap;
        transition: all var(--transition);
        cursor: pointer;
    }
    .cat-tab-btn:hover {
        color: var(--text);
        background: rgba(56, 189, 248, 0.08);
    }
    .cat-tab-btn.active {
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: #fff;
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4);
    }

    /* Swiper Styling */
    .products-swiper {
        padding: 20px 10px 60px;
        overflow: hidden;
    }
    .swiper-slide {
        height: auto; /* Required for equal height flex child cards */
    }
    .swiper-pagination-bullet {
        background: var(--text-muted);
        width: 10px;
        height: 10px;
        transition: all var(--transition);
    }
    .swiper-pagination-bullet-active {
        background: var(--accent);
        width: 28px;
        border-radius: 5px;
    }
    .swiper-button-next, .swiper-button-prev {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: var(--card);
        border: 1px solid var(--border);
        color: var(--accent);
        box-shadow: var(--shadow);
        transition: all var(--transition);
    }
    .swiper-button-next:hover, .swiper-button-prev:hover {
        background: var(--primary);
        color: #fff;
        border-color: var(--primary);
        transform: scale(1.1);
    }
    .swiper-button-next::after, .swiper-button-prev::after {
        font-size: 1.2rem;
        font-weight: 800;
    }

    .empty-state {
        padding: 80px 0;
        text-align: center;
    }
    .empty-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: var(--card);
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: var(--text-muted);
        margin: 0 auto 24px;
        box-shadow: var(--shadow);
    }
</style>
@endpush
@section('content')
<section class="blog-hero">
    <div class="blog-hero-orb blog-hero-orb-1"></div>
    <div class="blog-hero-orb blog-hero-orb-2"></div>
    <div class="grid-bg"></div>
    <div class="container text-center position-relative" style="z-index:2;">
        <div class="badge-glow reveal mb-4">
            <i class="bi bi-box-seam-fill"></i> {{ __(setting('products.hero.badge', 'Our Products')) }}
        </div>
        <h1 class="hero-title reveal reveal-delay-1">
            {!! __(setting('products.hero.title', 'Powerful Solutions for <span class="text-gradient">Modern Businesses.</span>')) !!}
        </h1>
        <p class="hero-subtitle reveal reveal-delay-2" style="font-size:1.15rem;max-width:600px;margin:20px auto 0;">
            {{ __(setting('products.hero.subtitle', 'Explore our suite of business management tools designed to help you scale efficiently.')) }}
        </p>
    </div>
</section>

<!-- Products Tabbed Category Slider -->
<section class="section-padding" style="background:var(--card-alt);">
    <div class="container">
        @php
            $groupedProducts = collect([]);
            if (isset($products) && count($products) > 0) {
                $groupedProducts = $products->groupBy(function($item) {
                    return $item->category ? $item->category->name : __('General Solutions');
                });
            }
        @endphp

        @if($groupedProducts->count() > 0)
            <!-- Category Tabs Navigation -->
            <div class="category-tabs-wrapper reveal">
                <div class="category-tabs-container" role="tablist">
                    @foreach($groupedProducts as $catName => $catProducts)
                        @php $tabId = \Illuminate\Support\Str::slug($catName); @endphp
                        <button class="cat-tab-btn {{ $loop->first ? 'active' : '' }}" id="tab-btn-{{ $tabId }}" data-bs-toggle="tab" data-bs-target="#tab-pane-{{ $tabId }}" type="button" role="tab" aria-controls="tab-pane-{{ $tabId }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                            {{ $catName }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Tab Content with Swiper Sliders -->
            <div class="tab-content reveal reveal-delay-1" id="productsTabContent">
                @foreach($groupedProducts as $catName => $catProducts)
                    @php $tabId = \Illuminate\Support\Str::slug($catName); @endphp
                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="tab-pane-{{ $tabId }}" role="tabpanel" aria-labelledby="tab-btn-{{ $tabId }}">
                        <div class="position-relative">
                            <div class="swiper products-swiper" id="swiper-prod-{{ $tabId }}">
                                <div class="swiper-wrapper">
                                    @foreach($catProducts as $product)
                                        <div class="swiper-slide">
                                            <div class="product-card-wrapper">
                                                <div class="product-card">
                                                    <!-- Image -->
                                                    <div class="product-image-wrapper">
                                                        @if($product->thumbnail)
                                                            <img src="{{ Storage::url($product->thumbnail) }}" alt="{{ $product->name }}" class="product-image">
                                                        @else
                                                            <div class="d-flex align-items-center justify-content-center h-100" style="position:absolute;inset:0;color:var(--text-muted);font-size:3rem;">
                                                                <i class="bi bi-image"></i>
                                                            </div>
                                                        @endif
                                                        
                                                        @if($product->category)
                                                        <div class="product-badge">
                                                            {{ $product->category->name }}
                                                        </div>
                                                        @endif
                                                    </div>
                                                    
                                                    <!-- Content -->
                                                    <div class="product-card-body">
                                                        <h3 class="product-title">{{ $product->name }}</h3>
                                                        <p class="product-desc">{{ $product->short_description ?? Str::limit($product->description, 100) }}</p>
                                                        
                                                        @if($product->features && is_array($product->features))
                                                        <ul class="product-features-list">
                                                            @foreach(array_slice($product->features, 0, 3) as $feature)
                                                            <li>
                                                                <i class="bi bi-check-circle-fill"></i>
                                                                <span>{{ $feature }}</span>
                                                            </li>
                                                            @endforeach
                                                        </ul>
                                                        @endif
                                                        
                                                        <div class="product-card-footer">
                                                            <div>
                                                                <div class="product-price-label">{{ __('Starting from') }}</div>
                                                                <div class="product-price">Rp {{ number_format($product->plans->where('is_active', true)->min('price') ?? $product->base_price, 0, ',', '.') }}</div>
                                                            </div>
                                                            <a href="{{ route('products.show', $product->slug) }}" class="product-cta-icon" aria-label="{{ __('View Product') }}">
                                                                <i class="bi bi-arrow-right"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <!-- Add Pagination -->
                                <div class="swiper-pagination"></div>
                            </div>
                            <!-- Add Navigation -->
                            <div class="swiper-button-prev d-none d-xl-flex" id="prev-prod-{{ $tabId }}" style="left: -25px;"></div>
                            <div class="swiper-button-next d-none d-xl-flex" id="next-prod-{{ $tabId }}" style="right: -25px;"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Fallback Empty State -->
            <div class="empty-state reveal">
                <div class="empty-icon">
                    <i class="bi bi-inbox"></i>
                </div>
                <h3 style="font-size:1.6rem;font-weight:700;color:var(--text);margin-bottom:12px;">{{ __('No Products Available') }}</h3>
                <p style="color:var(--text-muted);max-width:400px;margin:0 auto;">{{ __('We are currently updating our product catalog. Please check back later.') }}</p>
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        'use strict';
        
        // Initialize Swiper for each category tab
        @if(isset($groupedProducts) && $groupedProducts->count() > 0)
            @foreach($groupedProducts as $catName => $catProducts)
                @php $tabId = \Illuminate\Support\Str::slug($catName); @endphp
                new Swiper('#swiper-prod-{{ $tabId }}', {
                    slidesPerView: 1,
                    spaceBetween: 24,
                    observer: true,
                    observeParents: true,
                    watchSlidesProgress: true,
                    pagination: {
                        el: '#swiper-prod-{{ $tabId }} .swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '#next-prod-{{ $tabId }}',
                        prevEl: '#prev-prod-{{ $tabId }}',
                    },
                    breakpoints: {
                        768: {
                            slidesPerView: 2,
                        },
                        1024: {
                            slidesPerView: 3,
                        },
                    },
                });
            @endforeach
        @endif

        // Reveal animations
        const revealElements = document.querySelectorAll('.reveal');
        const revealObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
        revealElements.forEach(function(el) { revealObserver.observe(el); });
    });
</script>
@endpush
