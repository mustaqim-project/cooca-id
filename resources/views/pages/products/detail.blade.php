@extends('layouts.guest')
@push('styles')
<style>
    .product-hero {
        padding: 160px 0 80px;
        position: relative;
        overflow: hidden;
        background: var(--hero-gradient);
        border-bottom: 1px solid var(--border);
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
    .product-badge-lg {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 16px;
        background: var(--glass);
        backdrop-filter: blur(12px);
        border: 1px solid var(--glass-border);
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--accent);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 20px;
    }
    .product-title {
        font-size: clamp(2.2rem, 5vw, 4rem);
        font-weight: 800;
        letter-spacing: -0.03em;
        color: var(--text);
        margin-bottom: 24px;
        line-height: 1.15;
    }
    .product-short-desc {
        font-size: 1.15rem;
        color: var(--text-muted);
        max-width: 700px;
        line-height: 1.7;
        margin-bottom: 36px;
    }
    .product-image-container {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid var(--border);
        box-shadow: 0 20px 80px rgba(0, 0, 0, 0.4);
        background: var(--card);
        aspect-ratio: 16 / 9;
    }
    .product-detail-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .content-section {
        padding: 100px 0;
        background: var(--bg);
    }
    .section-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 24px;
        position: relative;
        padding-bottom: 12px;
    }
    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 4px;
        background: var(--accent);
        border-radius: 2px;
    }
    .product-description-content {
        font-size: 1.05rem;
        color: var(--text-muted);
        line-height: 1.8;
    }
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
        margin-top: 32px;
    }
    .feature-item-card {
        background: var(--card-alt);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 24px;
        display: flex;
        align-items: flex-start;
        gap: 16px;
        transition: all var(--transition);
    }
    .feature-item-card:hover {
        transform: translateY(-4px);
        border-color: var(--accent);
        box-shadow: 0 12px 32px rgba(56, 189, 248, 0.1);
    }
    .feature-icon-wrapper {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.2);
        color: var(--success);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .feature-text {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--text);
        margin: 0;
        padding-top: 6px;
    }
    
    /* Pricing Section */
    .plans-section {
        padding: 100px 0;
        background: var(--card-alt);
        border-top: 1px solid var(--border);
    }
    .pricing-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 24px;
        padding: 40px;
        display: flex;
        flex-direction: column;
        height: 100%;
        position: relative;
        transition: all var(--transition);
    }
    .pricing-card:hover {
        transform: translateY(-8px);
        border-color: var(--accent);
        box-shadow: 0 20px 60px rgba(56, 189, 248, 0.12);
    }
    .pricing-card.popular {
        border-color: var(--primary);
        background: linear-gradient(145deg, var(--card) 0%, rgba(37, 99, 235, 0.05) 100%);
    }
    .popular-badge {
        position: absolute;
        top: -16px;
        left: 50%;
        transform: translateX(-50%);
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: #fff;
        padding: 6px 20px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.4);
    }
    .plan-name {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 8px;
    }
    .plan-desc {
        color: var(--text-muted);
        font-size: 0.9rem;
        margin-bottom: 28px;
    }
    .plan-price-box {
        margin-bottom: 32px;
        padding-bottom: 28px;
        border-bottom: 1px solid var(--border);
    }
    .plan-price {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--text);
        line-height: 1;
    }
    .plan-interval {
        font-size: 0.9rem;
        color: var(--text-muted);
        margin-left: 4px;
    }
    .plan-features {
        list-style: none;
        padding: 0;
        margin-bottom: 40px;
        flex: 1;
    }
    .plan-features li {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 14px;
        font-size: 0.95rem;
        color: var(--text-muted);
    }
    .plan-features i {
        color: var(--success);
        font-size: 1.1rem;
    }

    /* Slider Pricing Styles */
    .pricing-slider-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 50px;
        padding: 6px;
        max-width: 500px;
        margin: 0 auto 40px;
        position: relative;
    }
    .pricing-slider-btn {
        flex: 1;
        text-align: center;
        padding: 10px 15px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.95rem;
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        z-index: 2;
        border: none;
        background: transparent;
    }
    .pricing-slider-btn.active {
        color: #fff;
    }
    .pricing-slider-bg {
        position: absolute;
        top: 6px;
        left: 6px;
        height: calc(100% - 12px);
        background: var(--primary);
        border-radius: 50px;
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1), width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1;
    }
</style>
@endpush
@section('content')
<!-- Product Hero -->
<section class="product-hero">
    <div class="hero-bg-orb hero-bg-orb-1"></div>
    <div class="hero-bg-orb hero-bg-orb-2"></div>
    <div class="grid-bg"></div>
    <div class="container position-relative" style="z-index: 2;">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="product-badge-lg reveal">
                    <i class="bi bi-tag-fill"></i> {{ $product->category->name ?? __('Premium Product') }}
                </div>
                <h1 class="product-title reveal reveal-delay-1">{{ $product->name }}</h1>
                <p class="product-short-desc reveal reveal-delay-2">
                    {{ $product->short_description ?? Str::limit($product->description, 150) }}
                </p>
                <div class="reveal reveal-delay-3" style="display: flex; gap: 16px; flex-wrap: wrap;">
                    <a href="#pricing" class="btn-cooca btn-cooca-primary"><i class="bi bi-cart-fill"></i> {{ __('View Pricing Plans') }}</a>
                    @if($product->demo_url)
                        <a href="{{ $product->demo_url }}" target="_blank" class="btn-cooca btn-cooca-outline"><i class="bi bi-laptop"></i> {{ __('Live Demo') }}</a>
                    @endif
                    <a href="{{ route('customer.register') }}" class="btn-cooca btn-cooca-outline"><i class="bi bi-person-plus"></i> {{ __('Request Free Trial') }}</a>
                </div>
            </div>
            <div class="col-lg-6 reveal reveal-delay-2">
                <div class="product-image-container tilt-card">
                    @if($product->thumbnail)
                        <img src="{{ Storage::url($product->thumbnail) }}" alt="{{ $product->name }}" class="product-detail-image">
                    @else
                        <div class="d-flex align-items-center justify-content-center h-100" style="color:var(--text-muted);font-size:4rem;">
                            <i class="bi bi-laptop"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Product Details & Features -->
<section class="content-section">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8">
                <div class="reveal">
                    <h2 class="section-title">{{ __('Product Overview') }}</h2>
                    <div class="product-description-content">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>

                @if($product->features && is_array($product->features) && count($product->features) > 0)
                <div class="reveal" style="margin-top: 60px;">
                    <h2 class="section-title">{{ __('Key Features') }}</h2>
                    <div class="features-grid">
                        @foreach($product->features as $feature)
                        <div class="feature-item-card">
                            <div class="feature-icon-wrapper">
                                <i class="bi bi-check-lg"></i>
                            </div>
                            <div class="feature-text">{{ $feature }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            <div class="col-lg-4">
                <div class="reveal" style="background: var(--card); border: 1px solid var(--border); border-radius: 24px; padding: 32px; position: sticky; top: 110px;">
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text); margin-bottom: 20px;">{{ __('Need a Custom Setup?') }}</h3>
                    <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 24px;">
                        {{ __('Our engineering team can tailor this solution to fit your existing enterprise infrastructure and security compliance requirements.') }}
                    </p>
                    <ul style="list-style: none; padding: 0; margin-bottom: 32px; font-size: 0.9rem; color: var(--text-muted);">
                        <li style="margin-bottom: 12px; display: flex; align-items: center; gap: 10px;">
                            <i class="bi bi-shield-check" style="color: var(--accent);"></i> {{ __('Dedicated isolated environment') }}
                        </li>
                        <li style="margin-bottom: 12px; display: flex; align-items: center; gap: 10px;">
                            <i class="bi bi-headset" style="color: var(--accent);"></i> {{ __('24/7 Priority SLA Support') }}
                        </li>
                        <li style="margin-bottom: 12px; display: flex; align-items: center; gap: 10px;">
                            <i class="bi bi-plug" style="color: var(--accent);"></i> {{ __('Custom API integrations') }}
                        </li>
                    </ul>
                    <a href="{{ route('contact') }}" class="btn-cooca btn-cooca-primary" style="width: 100%; justify-content: center;">
                        <i class="bi bi-building"></i> {{ __('Contact Sales') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pricing / Subscription Plans -->
<section class="plans-section" id="pricing">
    <div class="container">
        <div class="text-center reveal mb-5">
            <h2 style="font-size: clamp(2rem, 4vw, 3rem); font-weight: 800; color: var(--text); margin-bottom: 16px;">
                {{ __('Flexible Subscription Plans') }}
            </h2>
            <p style="color: var(--text-muted); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">
                {{ __('Transparent pricing with zero hidden fees. Pick the plan that scales with your business.') }}
            </p>
        </div>

        @if($product->subscriptionPlans && count($product->subscriptionPlans) > 0)
            <div class="pricing-slider-container reveal reveal-delay-1">
                <div class="pricing-slider-bg" id="slider-bg"></div>
                @foreach($product->subscriptionPlans as $index => $plan)
                    <button class="pricing-slider-btn {{ $index === 0 ? 'active' : '' }}" onclick="selectPlan({{ $index }}, this)">
                        @if($plan->duration_months == 1) 1 Bulan
                        @elseif($plan->duration_months == 3) 3 Bulan
                        @elseif($plan->duration_months == 12) 1 Tahun
                        @elseif($plan->duration_months == 999) Lifetime
                        @else {{ $plan->name }} @endif
                    </button>
                @endforeach
            </div>

            <div class="row justify-content-center reveal reveal-delay-2">
                <div class="col-lg-5">
                    @foreach($product->subscriptionPlans as $index => $plan)
                    <div class="pricing-card popular plan-card-item" id="plan-card-{{ $index }}" style="{{ $index !== 0 ? 'display:none;' : 'display:flex;' }}">
                        <div class="popular-badge">{{ $plan->duration_months == 999 ? 'Best Value' : ($plan->duration_months == 12 ? 'Most Popular' : 'Flexible') }}</div>
                        <h3 class="plan-name">{{ $product->name }}</h3>
                        <p class="plan-desc">
                            @if($plan->duration_months == 999)
                                {{ __('Bayar sekali untuk selamanya. Opsi hemat jangka panjang.') }}
                            @else
                                {{ __('Berlangganan untuk ') }} {{ $plan->duration_months }} {{ __(' bulan.') }}
                            @endif
                        </p>
                        
                        <div class="plan-price-box">
                            <span class="plan-price">Rp {{ number_format($plan->price, 0, ',', '.') }}</span>
                            <span class="plan-interval">/ {{ $plan->duration_months == 999 ? __('lifetime') : ($plan->duration_months . ' ' . __('bulan')) }}</span>
                        </div>

                        <ul class="plan-features">
                            <li><i class="bi bi-check-circle-fill"></i> <span>{{ __('All core features included') }}</span></li>
                            <li><i class="bi bi-check-circle-fill"></i> <span>{{ __('Dedicated isolated environment') }}</span></li>
                            <li><i class="bi bi-check-circle-fill"></i> <span>{{ __('Regular automated security updates') }}</span></li>
                            <li><i class="bi bi-check-circle-fill"></i> <span>{{ __('Customer success support') }}</span></li>
                            @if($plan->duration_months == 999)
                            <li><i class="bi bi-check-circle-fill"></i> <span style="color:var(--accent);font-weight:bold;">{{ __('Maintenance tahunan ringan') }}</span></li>
                            @endif
                        </ul>

                        <a href="{{ route('customer.register') }}" class="btn-cooca btn-cooca-primary" style="width: 100%; justify-content: center;">
                            <i class="bi bi-rocket-takeoff-fill"></i> {{ __('Choose Plan') }}
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>

            <script>
                function selectPlan(index, element) {
                    // Update buttons
                    document.querySelectorAll('.pricing-slider-btn').forEach(btn => btn.classList.remove('active'));
                    element.classList.add('active');

                    // Move slider background
                    const sliderBg = document.getElementById('slider-bg');
                    const btnWidth = element.offsetWidth;
                    sliderBg.style.width = btnWidth + 'px';
                    sliderBg.style.transform = `translateX(${element.offsetLeft - 6}px)`;

                    // Show correct card
                    document.querySelectorAll('.plan-card-item').forEach(card => {
                        card.style.display = 'none';
                    });
                    document.getElementById('plan-card-' + index).style.display = 'flex';
                }

                // Initial setup for slider width
                document.addEventListener('DOMContentLoaded', () => {
                    const firstBtn = document.querySelector('.pricing-slider-btn.active');
                    if(firstBtn) {
                        const sliderBg = document.getElementById('slider-bg');
                        sliderBg.style.width = firstBtn.offsetWidth + 'px';
                        sliderBg.style.transform = `translateX(${firstBtn.offsetLeft - 6}px)`;
                    }
                });
            </script>
        @else
            <!-- Base Price Fallback Card -->
            <div class="row justify-content-center reveal">
                <div class="col-lg-5">
                    <div class="pricing-card popular">
                        <div class="popular-badge">{{ __('Full Access') }}</div>
                        <h3 class="plan-name">{{ $product->name }}</h3>
                        <p class="plan-desc">{{ __('Get complete access to all powerful capabilities of this product.') }}</p>
                        
                        <div class="plan-price-box">
                            <span class="plan-price">Rp {{ number_format($product->plans->where('is_active', true)->min('price') ?? $product->base_price, 0, ',', '.') }}</span>
                            <span class="plan-interval">/ {{ __('package') }}</span>
                        </div>

                        <ul class="plan-features">
                            <li><i class="bi bi-check-circle-fill"></i> <span>{{ __('All core features included') }}</span></li>
                            <li><i class="bi bi-check-circle-fill"></i> <span>{{ __('Dedicated isolated environment') }}</span></li>
                            <li><i class="bi bi-check-circle-fill"></i> <span>{{ __('Regular automated security updates') }}</span></li>
                            <li><i class="bi bi-check-circle-fill"></i> <span>{{ __('Customer success support') }}</span></li>
                        </ul>

                        <a href="{{ route('customer.register') }}" class="btn-cooca btn-cooca-primary" style="width: 100%; justify-content: center;">
                            <i class="bi bi-rocket-takeoff-fill"></i> {{ __('Get Started Now') }}
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
