@extends('layouts.guest')
@section('content')
@if(isset($product))
<section class="page-hero">
  <div class="page-hero-orb page-hero-orb-1"></div>
  <div class="page-hero-orb page-hero-orb-2"></div>
  <div class="grid-bg"></div>
  <div class="container" style="position:relative;z-index:2;">
    <div class="row align-items-center">
      <div class="col-lg-7 reveal">
        <div class="badge-glow mb-4"><i class="bi bi-box"></i> {{ $product->category->name ?? __('Product') }}</div>
        <h1 class="hero-title">{{ $product->name }}</h1>
        <p class="hero-subtitle">{{ $product->description ?? $product->short_description }}</p>
        <div class="hero-cta">
          <a href="{{ route('customer.register') }}" class="btn btn-primary btn-lg">{{ __('Start Free Trial') }} <i class="bi bi-arrow-right"></i></a>
          <a href="{{ route('contact') }}" class="btn btn-outline btn-lg">{{ __('Request Demo') }}</a>
        </div>
      </div>
      <div class="col-lg-5 reveal rv-delay-2">
        <div class="card" style="padding:0;overflow:hidden;border-radius:var(--radius-lg);">
          <div class="dashboard-header"><div class="dashboard-dot red"></div><div class="dashboard-dot yellow"></div><div class="dashboard-dot green"></div><span style="margin-left:8px;font-size:.75rem;color:var(--text-muted);">{{ $product->name }} {{ __('Dashboard') }}</span></div>
          <div class="dashboard-body">
            <div class="dashboard-grid">
              <div class="dash-widget"><div class="dash-widget-title">{{ __('Status') }}</div><div class="dash-widget-value" style="color:var(--success);">{{ __('Active') }}</div></div>
              <div class="dash-widget"><div class="dash-widget-title">{{ __('Version') }}</div><div class="dash-widget-value">{{ __('v3.2.1') }}</div></div>
              <div class="dash-chart">
                <div class="dash-chart-bar" style="height:60%"></div><div class="dash-chart-bar" style="height:80%"></div><div class="dash-chart-bar" style="height:45%"></div><div class="dash-chart-bar" style="height:90%"></div><div class="dash-chart-bar" style="height:70%"></div><div class="dash-chart-bar" style="height:55%"></div><div class="dash-chart-bar" style="height:85%"></div><div class="dash-chart-bar" style="height:65%"></div><div class="dash-chart-bar" style="height:40%"></div><div class="dash-chart-bar" style="height:75%"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="text-center reveal">
      <div class="section-label"><i class="bi bi-stars"></i> {{ __('Key Features') }}</div>
      <h2 class="section-title">{{ __('What Makes <span class="text-gradient">This Product</span> Great') }}</h2>
    </div>
    <div class="row g-4 mt-3">
      <div class="col-md-6 col-lg-4 reveal"><div class="card"><div class="feature-item"><div class="feature-icon"><i class="bi bi-cloud-check"></i></div><div><div class="feature-title">{{ __('Isolated Infrastructure') }}</div><p class="feature-desc">{{ __('Your own dedicated container and database.') }}</p></div></div></div></div>
      <div class="col-md-6 col-lg-4 reveal rv-delay-1"><div class="card"><div class="feature-item"><div class="feature-icon"><i class="bi bi-shield-check"></i></div><div><div class="feature-title">{{ __('Enterprise Security') }}</div><p class="feature-desc">{{ __('AES-256 encryption, daily backups.') }}</p></div></div></div></div>
      <div class="col-md-6 col-lg-4 reveal rv-delay-2"><div class="card"><div class="feature-item"><div class="feature-icon"><i class="bi bi-infinity"></i></div><div><div class="feature-title">{{ __('Lifetime License') }}</div><p class="feature-desc">{{ __('Pay once. Use forever. No recurring fees.') }}</p></div></div></div></div>
    </div>
  </div>
</section>

<section class="cta-section">
  <div class="cta-bg"></div>
  <div class="container cta-content text-center">
    <div class="reveal">
      <h2 class="section-title">{{ __('Ready to Try <span class="text-gradient">{{ $product->name }}</span>?') }}</h2>
      <p class="section-subtitle">{{ __('Start your 30-day free trial. Full access. No credit card. 30-minute setup.') }}</p>
      <a href="{{ route('customer.register') }}" class="btn btn-primary btn-lg">{{ __('Start Free Trial') }} <i class="bi bi-arrow-right"></i></a>
    </div>
  </div>
</section>
@else
<section class="page-hero"><div class="container text-center"><h1>{{ __('Product Not Found') }}</h1><p>{{ __('The product you're looking for doesn't exist or has been removed.') }}</p></div></section>
@endif
@endsection