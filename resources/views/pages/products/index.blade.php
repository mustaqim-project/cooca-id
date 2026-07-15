@extends('layouts.guest')
@section('content')

<section class="page-hero">
  <div class="page-hero-orb page-hero-orb-1"></div>
  <div class="page-hero-orb page-hero-orb-2"></div>
  <div class="grid-bg"></div>
  <div class="container text-center position-relative" style="z-index:2;">
    <div class="badge-glow reveal mb-4">
      <i class="bi bi-star-fill"></i> {{ __('Products') }}
    </div>
    <h1 class="hero-title reveal rv-delay-1">{!! __('Product <span class="text-gradient">Catalog.</span>') !!}</h1>
    <p class="hero-subtitle reveal rv-delay-2" style="font-size:1.1rem;max-width:640px;margin:16px auto 0;">
      {{ __('Explore our complete range of business management software solutions. Lifetime license. All modules included.') }}
    </p>
  </div>
</section>
<section class="section">
  <div class="container">
    @if(isset($products) && count($products))
    <div class="row g-4">
      @foreach($products as $product)
      <div class="col-lg-4 col-md-6 reveal">
        <div class="card card-3d product-card card-hover-glow">
          <div class="card-icon">
            @if($product->category && $product->category->icon)
              <i class="bi bi-{{ $product->category->icon }}"></i>
            @else
              <i class="bi bi-box"></i>
            @endif
          </div>
          <h3 class="card-title">{{ $product->name }}</h3>
          <p class="card-desc">{{ Str::limit($product->description ?? $product->short_description, 100) }}</p>
          <div class="card-actions">
            <a href="{{ route('products.show', $product->slug) }}" class="btn btn-outline btn-sm">{{ __('Details') }} <i class="bi bi-arrow-right"></i></a>
            @if($product->subscriptionPlans && $product->subscriptionPlans->count())
              <span style="font-size:0.85rem;color:var(--accent);display:flex;align-items:center;">
                {{ __('From') }} {{ AppHelperssetting('currency.symbol','Rp') }} {{ number_format($product->subscriptionPlans->min('price'),0,',','.') }}
              </span>
            @endif
          </div>
        </div>
      </div>
      @endforeach
    </div>
    @else
    <div class="text-center py-5 reveal">
      <div class="empty-state">
        <div class="empty-state-icon"><i class="bi bi-box-seam"></i></div>
        <h4>{{ __('Products Coming Soon') }}</h4>
        <p>{{ __('Our product catalog is being prepared. Check back soon or contact sales.') }}</p>
      </div>
    </div>
    @endif
  </div>
</section>
@endsection