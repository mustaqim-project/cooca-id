@extends('layouts.base')
@section('title', $product->name ?? 'Product Detail')
@section('content')

<!-- HERO -->
<section class="hero-section" style="padding: 120px 0 80px; position: relative; overflow: hidden;">
    <div class="hero-bg-orb hero-bg-orb-1"></div>
    <div class="hero-bg-orb hero-bg-orb-2"></div>
    <div class="grid-bg"></div>
    <div class="container position-relative" style="z-index:2;">
        <div class="row align-items-center">
            <div class="col-lg-6 hero-content">
                <div class="badge-glow mb-4">
                    <i class="bi bi-box-seam"></i> {{ $product->category->name ?? 'COOCA Business System' }}
                </div>
                <h1 class="hero-title">
                    {{ $product->name ?? 'COOCA Business' }}
                </h1>
                <p class="hero-subtitle">
                    {{ $product->description ?? 'The ultimate business system tailored for your specific industry needs.' }}
                </p>
                <div class="hero-cta mt-4">
                    @if($product->demo_url)
                    <a href="{{ $product->demo_url }}" target="_blank" class="btn-cooca btn-cooca-primary">
                        Live Demo <i class="bi bi-box-arrow-up-right"></i>
                    </a>
                    @endif
                    <a href="{{ route('customer.register') }}" class="btn-cooca btn-cooca-outline">
                        Start Free Trial
                    </a>
                </div>
            </div>
            <div class="col-lg-6 hero-visual text-center">
                <div class="card-3d" style="padding: 40px; background: rgba(15, 23, 42, 0.4); border: 1px solid rgba(255,255,255,0.1); border-radius: 24px;">
                    <i class="bi bi-rocket-takeoff" style="font-size: 8rem; color: var(--accent); opacity: 0.8; text-shadow: 0 0 40px var(--accent);"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- PRICING REDIRECT -->
<section class="section-padding" style="background: var(--card-alt);">
    <div class="container text-center">
        <h2 class="section-title">Ready to transform your business?</h2>
        <p class="section-subtitle">Get full access to all capabilities with unlimited users.</p>
        <div class="mt-4">
            <a href="{{ route('pricing') }}" class="btn-cooca btn-cooca-primary">View Pricing Plans</a>
        </div>
    </div>
</section>

@endsection
