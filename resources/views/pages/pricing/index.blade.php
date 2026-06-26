@extends('layouts.guest')

@push('styles')
<!-- Swiper CSS for Premium Slider -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    /* Custom Scoped Styles for Premium Pricing Table & Enhancements */
    .pricing-card-wrapper {
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .pricing-premium-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 42px 36px;
        transition: all var(--transition);
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
        backdrop-filter: blur(20px);
        box-shadow: var(--shadow);
    }
    .pricing-premium-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary), var(--accent), var(--secondary));
        opacity: 0.5;
        transition: opacity var(--transition);
    }
    .pricing-premium-card:hover::before {
        opacity: 1;
    }
    .pricing-premium-card:hover {
        transform: translateY(-10px);
        border-color: rgba(56, 189, 248, 0.4);
        box-shadow: 0 25px 60px rgba(56, 189, 248, 0.15), var(--shadow-lg);
    }
    .pricing-premium-card.popular {
        border-color: var(--accent);
        box-shadow: 0 0 45px rgba(56, 189, 248, 0.18);
    }
    .pricing-premium-card.popular::before {
        opacity: 1;
        height: 6px;
    }
    .pricing-badge-popular {
        position: absolute;
        top: 24px;
        right: 24px;
        padding: 6px 18px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: #fff;
        letter-spacing: 0.08em;
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4);
        z-index: 3;
    }
    .plan-title {
        font-size: 1.4rem;
        font-weight: 800;
        margin-bottom: 8px;
        color: var(--text);
        padding-right: 100px; /* Leave room for badge */
    }
    .plan-pricing {
        margin: 24px 0 8px;
        display: flex;
        align-items: baseline;
    }
    .plan-pricing .curr {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--text-muted);
        margin-right: 6px;
    }
    .plan-pricing .amount {
        font-size: clamp(2.2rem, 4vw, 3.2rem);
        font-weight: 900;
        color: var(--text);
        line-height: 1;
        letter-spacing: -0.03em;
    }
    .plan-pricing .freq {
        font-size: 0.9rem;
        color: var(--text-muted);
        margin-left: 8px;
        font-weight: 500;
    }
    .plan-subtitle {
        font-size: 0.9rem;
        color: var(--text-muted);
        margin-bottom: 28px;
        min-height: 48px;
        display: flex;
        align-items: center;
    }
    .feat-list {
        list-style: none;
        padding: 0;
        margin: 0 0 36px 0;
        flex-grow: 1;
    }
    .feat-list li {
        margin-bottom: 16px;
        font-size: 0.95rem;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        color: var(--text);
    }
    .feat-list li i {
        color: var(--success);
        font-size: 1.1rem;
        margin-top: 2px;
        filter: drop-shadow(0 2px 4px rgba(16, 185, 129, 0.3));
    }
    .addon-section {
        margin-top: auto;
        padding-top: 24px;
        border-top: 1px solid var(--border);
        margin-bottom: 28px;
    }
    .addon-heading {
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--accent);
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .addon-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.9rem;
        padding: 8px 12px;
        background: var(--card-alt);
        border-radius: var(--radius-sm);
        margin-bottom: 8px;
        border: 1px solid var(--border);
        transition: border-color var(--transition);
    }
    .addon-item:hover {
        border-color: var(--accent);
    }
    .addon-name {
        color: var(--text);
        font-weight: 500;
    }
    .addon-price {
        color: var(--accent);
        font-weight: 700;
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
    .pricing-swiper {
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
    
    /* Compare Table Styling */
    .compare-container {
        overflow-x: auto;
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        background: var(--card);
        box-shadow: var(--shadow-lg);
        margin-top: 60px;
    }
    .table-premium {
        width: 100%;
        min-width: 900px;
        border-collapse: separate;
        border-spacing: 0;
    }
    .table-premium th {
        padding: 28px 24px;
        text-align: center;
        font-weight: 800;
        font-size: 1.1rem;
        border-bottom: 2px solid var(--border);
        background: var(--table-th-bg);
        backdrop-filter: blur(12px);
        color: var(--table-th-color);
        position: sticky;
        top: 0;
        z-index: 10;
    }
    .table-premium th:first-child {
        text-align: left;
        position: sticky;
        left: 0;
        z-index: 11;
        background: var(--card);
    }
    .table-premium td {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border);
        color: var(--text-muted);
        font-size: 0.95rem;
        text-align: center;
        transition: background var(--transition);
    }
    .table-premium td:first-child {
        text-align: left;
        font-weight: 600;
        color: var(--text);
        position: sticky;
        left: 0;
        background: var(--card);
        z-index: 5;
    }
    .table-premium tbody tr:not(.group-header):hover td {
        background: var(--table-td-hover-bg);
    }
    .table-premium tbody tr:not(.group-header):hover td:first-child {
        background: var(--table-td-hover);
    }
    .table-premium tr:last-child td {
        border-bottom: none;
    }
    .table-premium i.icon-check {
        color: var(--success);
        font-size: 1.4rem;
        filter: drop-shadow(0 2px 6px rgba(16, 185, 129, 0.4));
    }
    .table-premium i.icon-minus {
        color: var(--text-muted);
        font-size: 1.2rem;
        opacity: 0.3;
    }
    .table-premium .group-header td {
        background: linear-gradient(90deg, rgba(37, 99, 235, 0.12), transparent);
        font-size: 0.85rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: var(--accent);
        padding: 16px 24px;
        text-align: left !important;
    }
    
    /* Guarantee Banner */
    .guarantee-box {
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.1), rgba(16, 185, 129, 0.1));
        border: 1px solid rgba(16, 185, 129, 0.3);
        border-radius: var(--radius-lg);
        padding: 40px;
        backdrop-filter: blur(20px);
        margin: 60px 0;
        box-shadow: 0 10px 40px rgba(16, 185, 129, 0.15);
    }
    .guarantee-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: linear-gradient(135deg, #10b981, var(--primary));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        color: #fff;
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.4);
        margin-bottom: 24px;
    }
</style>
@endpush

@section('content')

    <!-- HERO SECTION -->
    <section class="blog-hero">
        <div class="blog-hero-orb blog-hero-orb-1"></div>
        <div class="blog-hero-orb blog-hero-orb-2"></div>
        <div class="grid-bg"></div>
        <div class="container text-center position-relative" style="z-index:2;">
            <div class="badge-glow reveal mb-4">
                <i class="bi bi-tags-fill"></i> {{ __(setting('pricing.hero.badge', 'Transparent Investment Plans')) }}
            </div>
            <h1 class="hero-title reveal reveal-delay-1" style="font-size: clamp(2.6rem, 5.5vw, 4.5rem); font-weight: 900; line-height: 1.1; margin-bottom: 24px;">
                {!! __(setting('pricing.hero.title', 'One Powerful Platform. <span class="text-gradient">Limitless Scaling.</span>')) !!}
            </h1>
            <p class="hero-subtitle reveal reveal-delay-2" style="font-size: 1.25rem; max-width: 720px; margin: 0 auto 40px; color: var(--text-muted);">
                {!! __(setting('pricing.hero.subtitle', 'No hidden transaction cut, no surprise seat fees. Acquire an enterprise-grade operating asset designed to multiply your business net valuation.')) !!}
            </p>
            <div class="reveal reveal-delay-3 d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('customer.register') }}" class="btn-cooca btn-cooca-primary" style="padding: 18px 42px; font-size: 1.05rem; border-radius: 50px;">
                    <i class="bi bi-lightning-charge-fill"></i> {{ __(setting('home.freetrial.cta', 'Start Free 30-Day Trial — No Credit Card')) }}
                </a>
                <a href="#compare" class="btn-cooca btn-cooca-outline" style="padding: 18px 36px; font-size: 1.05rem; border-radius: 50px;">
                    <i class="bi bi-arrow-down-circle"></i> {{ __('Compare Plan Breakdown') }}
                </a>
            </div>
        </div>
    </section>

    <!-- PRICING TIERS WITH CATEGORY TABS & SWIPER SLIDER -->
    <section class="section-padding">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-label reveal"><i class="bi bi-shield-check"></i> {{ __('Flexible Licensing') }}</div>
                <h2 class="section-title reveal reveal-delay-1" style="font-size: clamp(2rem, 4vw, 3.2rem);">
                    {!! __('Choose The Suite For <span class="text-gradient">Your Scale</span>') !!}
                </h2>
                <p class="section-subtitle reveal reveal-delay-2">
                    {{ __('Filter by your specific industry solution below. Every core license grants permanent base infrastructure ownership.') }}
                </p>
            </div>

            @php
                $groupedProducts = collect([]);
                if (isset($products) && count($products) > 0) {
                    $groupedProducts = $products->groupBy(function($item) {
                        return $item->category ? $item->category->name : __('General Suite');
                    });
                }
            @endphp

            @if($groupedProducts->count() > 0)
                <!-- Category Tabs Navigation -->
                <div class="category-tabs-wrapper reveal reveal-delay-2">
                    <div class="category-tabs-container" role="tablist">
                        @foreach($groupedProducts as $catName => $catProducts)
                            @php 
                                $tabId = \Illuminate\Support\Str::slug($catName); 
                                $catIcon = $catProducts->first()->category->icon ?? 'bi-grid-3x3-gap-fill';
                            @endphp
                            <button class="cat-tab-btn {{ $loop->first ? 'active' : '' }}" id="tab-btn-{{ $tabId }}" data-bs-toggle="tab" data-bs-target="#tab-pane-{{ $tabId }}" type="button" role="tab" aria-controls="tab-pane-{{ $tabId }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                <i class="bi {{ $catIcon }} me-2"></i>{{ $catName }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Tab Content with Swiper Sliders -->
                <div class="tab-content reveal reveal-delay-3" id="pricingTabContent">
                    @foreach($groupedProducts as $catName => $catProducts)
                        @php $tabId = \Illuminate\Support\Str::slug($catName); @endphp
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="tab-pane-{{ $tabId }}" role="tabpanel" aria-labelledby="tab-btn-{{ $tabId }}">
                            <div class="position-relative">
                                <div class="swiper pricing-swiper" id="swiper-{{ $tabId }}">
                                    <div class="swiper-wrapper">
                                        @foreach($catProducts as $product)
                                            <div class="swiper-slide">
                                                <div class="pricing-card-wrapper">
                                                    <div class="pricing-premium-card {{ $product->is_featured ? 'popular' : '' }}">
                                                        @if($product->is_featured)
                                                            <div class="pricing-badge-popular"><i class="bi bi-star-fill me-1"></i> {{ __('Most Popular') }}</div>
                                                        @endif
                                                        
                                                        <h3 class="plan-title">{{ $product->name }}</h3>
                                                        <div class="plan-subtitle">
                                                            {{ $product->short_description ?? Str::limit($product->description, 90) }}
                                                        </div>

                                                        <div class="plan-pricing">
                                                            <span class="curr">Rp</span>
                                                            <span class="amount">{{ number_format($product->plans->where('is_active', true)->min('price') ?? $product->base_price, 0, ',', '.') }}</span>
                                                            <span class="freq">/ {{ __('Base License') }}</span>
                                                        </div>

                                                        <hr style="border-color: var(--border); margin: 24px 0;">

                                                        <ul class="feat-list">
                                                            @if($product->features && is_array($product->features))
                                                                @foreach($product->features as $feature)
                                                                    <li><i class="bi bi-check-circle-fill"></i> <span>{{ $feature }}</span></li>
                                                                @endforeach
                                                            @else
                                                                <li><i class="bi bi-check-circle-fill"></i> <span>{{ __('Advanced Real-time Analytics Dashboard') }}</span></li>
                                                                <li><i class="bi bi-check-circle-fill"></i> <span>{{ __('Multi-user Roles & Custom Permissions') }}</span></li>
                                                                <li><i class="bi bi-check-circle-fill"></i> <span>{{ __('Priority 24/7 Support & Updates') }}</span></li>
                                                                <li><i class="bi bi-check-circle-fill"></i> <span>{{ __('Complete API & Webhooks Access') }}</span></li>
                                                                <li><i class="bi bi-check-circle-fill"></i> <span>{{ __('99.9% Uptime Guarantee SLA') }}</span></li>
                                                            @endif
                                                        </ul>

                                                        @if($product->subscriptionPlans && count($product->subscriptionPlans) > 0)
                                                            <div class="addon-section">
                                                                <div class="addon-heading">
                                                                    <i class="bi bi-plus-circle-dotted"></i> {{ __('Optional Core Add-ons') }}:
                                                                </div>
                                                                @foreach($product->subscriptionPlans as $plan)
                                                                    <div class="addon-item">
                                                                        <span class="addon-name">{{ $plan->name }} ({{ $plan->duration_months }}{{ __('mo') }})</span>
                                                                        <span class="addon-price">+Rp {{ number_format($plan->price, 0, ',', '.') }}</span>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                        
                                                        <div style="margin-top: auto;">
                                                             <a href="{{ route('customer.register') }}" class="btn-cooca {{ $product->is_featured ? 'btn-cooca-primary' : 'btn-cooca-outline' }}" style="width: 100%; justify-content: center; padding: 14px; border-radius: 12px; font-size: 1rem; margin-bottom: 12px;">
                                                                 <i class="bi bi-rocket-takeoff-fill me-1"></i> {{ $product->is_featured ? __('Claim Featured Suite') : __('Deploy Base License') }}
                                                             </a>
                                                             <div class="d-flex gap-2" style="margin-bottom: 12px;">
                                                                 <a href="{{ route('products.show', $product->slug) }}" class="btn-cooca btn-cooca-outline btn-cooca-sm" style="flex: 1; justify-content: center; padding: 10px; border-radius: 10px; font-size: 0.85rem;">
                                                                     <i class="bi bi-info-circle me-1"></i> {{ __('Learn More') }}
                                                                 </a>
                                                                 @if($product->demo_url)
                                                                     <a href="{{ $product->demo_url }}" target="_blank" class="btn-cooca btn-cooca-success btn-cooca-sm" style="flex: 1; justify-content: center; padding: 10px; border-radius: 10px; font-size: 0.85rem;">
                                                                         <i class="bi bi-play-circle me-1"></i> {{ __('Live Demo') }}
                                                                     </a>
                                                                 @endif
                                                             </div>
                                                             <p style="font-size: 0.75rem; text-align: center; color: var(--text-muted); margin: 0;">
                                                                 <i class="bi bi-shield-lock-fill text-success"></i> {{ __('Secure setup. Instant activation.') }}
                                                             </p>
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
                                <div class="swiper-button-prev d-none d-xl-flex" id="prev-{{ $tabId }}" style="left: -25px;"></div>
                                <div class="swiper-button-next d-none d-xl-flex" id="next-{{ $tabId }}" style="right: -25px;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="glow-card text-center py-5 reveal">
                    <i class="bi bi-inbox-fill" style="font-size: 3rem; color: var(--border);"></i>
                    <p class="text-muted mt-3">{{ __('No investment packages available at the moment. Please check back later.') }}</p>
                </div>
            @endif

            <!-- MONEY BACK GUARANTEE BANNER -->
            <div class="guarantee-box reveal">
                <div class="row align-items-center">
                    <div class="col-lg-3 text-center text-lg-start">
                        <div class="guarantee-icon mx-auto mx-lg-0">
                            <i class="bi bi-shield-check"></i>
                        </div>
                    </div>
                    <div class="col-lg-6 text-center text-lg-start">
                        <h3 style="font-size: 1.6rem; font-weight: 800; color: var(--text); margin-bottom: 10px;">
                            {{ __('30-Day Enterprise Money-Back Guarantee') }}
                        </h3>
                        <p style="color: var(--text-muted); margin: 0; font-size: 1rem;">
                            {{ __('We are 100% confident in our architecture. If COOCA does not optimize your team workflows or reduce operational friction within 30 days, get a complete immediate refund. No questions asked.') }}
                        </p>
                    </div>
                    <div class="col-lg-3 text-center text-lg-end mt-4 mt-lg-0">
                        <a href="{{ route('contact') }}" class="btn-cooca btn-cooca-outline" style="border-radius: 50px; padding: 14px 32px;">
                            <i class="bi bi-chat-dots-fill"></i> {{ __('Contact Advisory') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- COMPREHENSIVE COMPARISON TABLE -->
    <section id="compare" class="section-padding" style="background: var(--card-alt);">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-label reveal"><i class="bi bi-diagram-3"></i> {{ __('In-Depth Architecture') }}</div>
                <h2 class="section-title reveal reveal-delay-1" style="font-size: clamp(2rem, 4vw, 3.2rem);">
                    {!! __('Side-by-Side <span class="text-gradient">Feature Matrix</span>') !!}
                </h2>
                <p class="section-subtitle reveal reveal-delay-2">
                    {{ __('Examine the underlying capabilities, compliance measures, and dedicated performance parameters of each COOCA product suite.') }}
                </p>
            </div>
            
            <div class="compare-container reveal reveal-delay-3">
                <table class="table-premium">
                    <thead>
                        <tr>
                            <th style="min-width: 250px;">{{ __('Capability / Specification') }}</th>
                            @if(isset($products) && count($products) > 0)
                                @foreach($products as $product)
                                    <th style="min-width: 200px;">
                                        <div style="font-size: 1.2rem; font-weight: 800; color: var(--text);">{{ $product->name }}</div>
                                        <div style="font-size: 0.85rem; color: var(--accent); margin-top: 4px;">Rp {{ number_format($product->plans->where('is_active', true)->min('price') ?? $product->base_price, 0, ',', '.') }}</div>
                                    </th>
                                @endforeach
                            @else
                                <th>{{ __('Product Suite') }}</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        <!-- GROUP 1: Core System & Infrastructure -->
                        <tr class="group-header">
                            <td colspan="{{ (isset($products) ? count($products) : 0) + 1 }}"><i class="bi bi-hdd-rack-fill me-2"></i> {{ __('Core System & Infrastructure') }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Multi-Tenant Isolated Environment') }}</td>
                            @if(isset($products))
                                @foreach($products as $product)
                                    <td><i class="bi bi-check-circle-fill icon-check"></i></td>
                                @endforeach
                            @endif
                        </tr>
                        <tr>
                            <td>{{ __('Unlimited User Seats & Terminal Access') }}</td>
                            @if(isset($products))
                                @foreach($products as $product)
                                    <td><i class="bi bi-check-circle-fill icon-check"></i></td>
                                @endforeach
                            @endif
                        </tr>
                        <tr>
                            <td>{{ __('Custom Branding & Domain CNAME') }}</td>
                            @if(isset($products))
                                @foreach($products as $product)
                                    <td><i class="bi bi-check-circle-fill icon-check"></i></td>
                                @endforeach
                            @endif
                        </tr>
                        <tr>
                            <td>{{ __('Automated Hourly Backup Snapshots') }}</td>
                            @if(isset($products))
                                @foreach($products as $product)
                                    <td><i class="bi bi-check-circle-fill icon-check"></i></td>
                                @endforeach
                            @endif
                        </tr>

                        <!-- GROUP 2: Security & Compliance -->
                        <tr class="group-header">
                            <td colspan="{{ (isset($products) ? count($products) : 0) + 1 }}"><i class="bi bi-shield-lock-fill me-2"></i> {{ __('Security & Compliance') }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('End-to-End Encryption (AES-256)') }}</td>
                            @if(isset($products))
                                @foreach($products as $product)
                                    <td><i class="bi bi-check-circle-fill icon-check"></i></td>
                                @endforeach
                            @endif
                        </tr>
                        <tr>
                            <td>{{ __('Role-Based Access Control (RBAC)') }}</td>
                            @if(isset($products))
                                @foreach($products as $product)
                                    <td><i class="bi bi-check-circle-fill icon-check"></i></td>
                                @endforeach
                            @endif
                        </tr>
                        <tr>
                            <td>{{ __('Activity Audit Logs & Forensics') }}</td>
                            @if(isset($products))
                                @foreach($products as $product)
                                    <td><i class="bi bi-check-circle-fill icon-check"></i></td>
                                @endforeach
                            @endif
                        </tr>
                        <tr>
                            <td>{{ __('ISO 27001 & SOC 2 Type II Readiness') }}</td>
                            @if(isset($products))
                                @foreach($products as $product)
                                    <td><i class="bi bi-check-circle-fill icon-check"></i></td>
                                @endforeach
                            @endif
                        </tr>

                        <!-- GROUP 3: Support & Integrations -->
                        <tr class="group-header">
                            <td colspan="{{ (isset($products) ? count($products) : 0) + 1 }}"><i class="bi bi-cpu-fill me-2"></i> {{ __('Support & Integrations') }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('REST & GraphQL API Webhooks') }}</td>
                            @if(isset($products))
                                @foreach($products as $product)
                                    <td><i class="bi bi-check-circle-fill icon-check"></i></td>
                                @endforeach
                            @endif
                        </tr>
                        <tr>
                            <td>{{ __('Priority VIP 24/7 Account Manager') }}</td>
                            @if(isset($products))
                                @foreach($products as $product)
                                    <td>{!! $product->is_featured ? '<i class="bi bi-check-circle-fill icon-check"></i>' : '<span style="font-size:0.85rem;color:var(--text-muted);">'.__('Standard Support').'</span>' !!}</td>
                                @endforeach
                            @endif
                        </tr>
                        <tr>
                            <td>{{ __('Custom ERP / Third-Party Sync') }}</td>
                            @if(isset($products))
                                @foreach($products as $product)
                                    <td>{!! $product->is_featured ? '<i class="bi bi-check-circle-fill icon-check"></i>' : '<i class="bi bi-dash icon-minus"></i>' !!}</td>
                                @endforeach
                            @endif
                        </tr>
                        <tr>
                            <td>{{ __('99.9% Service Level Agreement (SLA)') }}</td>
                            @if(isset($products))
                                @foreach($products as $product)
                                    <td><i class="bi bi-check-circle-fill icon-check"></i></td>
                                @endforeach
                            @endif
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- PRICING FAQ -->
    <section class="section-padding">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-label reveal"><i class="bi bi-question-circle-fill"></i> {{ __('Investment FAQ') }}</div>
                <h2 class="section-title reveal reveal-delay-1" style="font-size: clamp(2rem, 4vw, 3.2rem);">
                    {!! __('Questions About <span class="text-gradient">Your Licensing?</span>') !!}
                </h2>
                <p class="section-subtitle reveal reveal-delay-2">
                    {{ __('Get complete clarity on our transparent billing structure, optional cloud add-ons, and zero-risk trial setup.') }}
                </p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion accordion-cooca" id="faqAcc">
                        <div class="accordion-item reveal">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#pf1">
                                    {{ __('Is there really no credit card required for the 30-day free trial?') }}
                                </button>
                            </h2>
                            <div id="pf1" class="accordion-collapse collapse show" data-bs-parent="#faqAcc">
                                <div class="accordion-body">
                                    {{ __('Absolutely. Sign up in seconds, deploy your selected industry suite, and experience 30 days of unhindered access with zero billing details required. We only request payment information if you decide to fully commit post-trial.') }}
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item reveal reveal-delay-1">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pf2">
                                    {{ __('Can I switch or upgrade my product suite mid-cycle?') }}
                                </button>
                            </h2>
                            <div id="pf2" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
                                <div class="accordion-body">
                                    {{ __('Yes, our licensing architecture is fully modular. You can instantly upgrade to a higher suite or bolt on optional cloud add-ons at any point. Our automated billing engine will seamlessly prorate any differential amounts.') }}
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item reveal reveal-delay-2">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pf3">
                                    {{ __('How do the optional cloud add-ons work alongside the base license?') }}
                                </button>
                            </h2>
                            <div id="pf3" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
                                <div class="accordion-body">
                                    {{ __('Your base license grants you complete core infrastructure access. The optional add-on subscriptions (ranging from 1 to 12 months) allow you to activate specialized advanced modules, extended server processing tiers, or premium integrations as your business scales.') }}
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item reveal reveal-delay-3">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pf4">
                                    {{ __('What happens to my configured data if I decide not to continue after the trial?') }}
                                </button>
                            </h2>
                            <div id="pf4" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
                                <div class="accordion-body">
                                    {{ __('Your data remains securely archived in an isolated staging state for 60 days post-trial, giving you ample time to reactivate your license without losing any setup configurations or operational metrics. You can also request complete immediate data deletion at any time.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FINAL CTA -->
    <section class="section-padding" style="background: var(--card-alt); position: relative; overflow: hidden;">
        <div class="hero-bg-orb hero-bg-orb-3" style="width: 500px; height: 500px; opacity: 0.08;"></div>
        <div class="container text-center" style="position: relative; z-index: 2;">
            <h2 class="reveal" style="font-size: clamp(2.2rem, 4.5vw, 3.5rem); font-weight: 900;">
                {!! __('Experience Enterprise Scale. <span class="text-gradient">Zero Risk.</span>') !!}
            </h2>
            <p class="reveal reveal-delay-1" style="max-width: 600px; margin: 20px auto 40px; font-size: 1.15rem; color: var(--text-muted);">
                {{ __('30 days of complete, unrestricted access. Discover firsthand how much efficiency and net revenue COOCA unlocks for your operations.') }}
            </p>
            <div class="d-flex justify-content-center gap-4 flex-wrap reveal reveal-delay-2">
                <a href="{{ route('customer.register') }}" class="btn-cooca btn-cooca-primary" style="padding: 18px 48px; font-size: 1.1rem; border-radius: 50px;">
                    <i class="bi bi-rocket-fill"></i> {{ __('Start Free Trial Now') }}
                </a>
                <a href="{{ route('contact') }}" class="btn-cooca btn-cooca-outline" style="padding: 18px 42px; font-size: 1.1rem; border-radius: 50px;">
                    <i class="bi bi-headset"></i> {{ __('Talk to Enterprise Sales') }}
                </a>
            </div>
            <div class="mt-5 reveal reveal-delay-3 d-flex justify-content-center gap-4 flex-wrap" style="color: var(--text-muted); font-size: 0.85rem;">
                <span><i class="bi bi-check-circle-fill text-success me-1"></i> {{ __('No Credit Card Required') }}</span>
                <span><i class="bi bi-check-circle-fill text-success me-1"></i> {{ __('Instant Automated Deployment') }}</span>
                <span><i class="bi bi-check-circle-fill text-success me-1"></i> {{ __('Cancel Anytime') }}</span>
            </div>
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
                new Swiper('#swiper-{{ $tabId }}', {
                    slidesPerView: 1,
                    spaceBetween: 24,
                    observer: true,
                    observeParents: true,
                    watchSlidesProgress: true,
                    pagination: {
                        el: '#swiper-{{ $tabId }} .swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '#next-{{ $tabId }}',
                        prevEl: '#prev-{{ $tabId }}',
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

        // Button ripple effect
        document.querySelectorAll('.btn-cooca').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                const rect = btn.getBoundingClientRect();
                const ripple = document.createElement('span');
                ripple.classList.add('btn-ripple');
                const size = Math.max(rect.width, rect.height);
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = (e.clientX - rect.left - size / 2) + 'px';
                ripple.style.top = (e.clientY - rect.top - size / 2) + 'px';
                btn.appendChild(ripple);
                setTimeout(function() { ripple.remove(); }, 600);
            });
        });
    });
</script>
@endpush
