@extends('layouts.guest')
@section('content')

<section class="page-hero">
  <div class="page-hero-orb page-hero-orb-1"></div>
  <div class="page-hero-orb page-hero-orb-2"></div>
  <div class="grid-bg"></div>
  <div class="container text-center position-relative" style="z-index:2;">
    <div class="badge-glow reveal mb-4">
      <i class="bi bi-star-fill"></i> {{ __('Pricing') }}
    </div>
    <h1 class="hero-title reveal rv-delay-1">{!! __('Simple, <span class="text-gradient">Transparent</span> Pricing.') !!}</h1>
    <p class="hero-subtitle reveal rv-delay-2" style="font-size:1.1rem;max-width:640px;margin:16px auto 0;">
      {{ __('One lifetime license. All modules included. No monthly fees. No per-user charges. Enterprise infrastructure at a fraction of SaaS lifetime cost.') }}
    </p>
  </div>
</section>
<section class="section">
  <div class="container">
    @if(isset($products) && count($products))
    <div class="row g-4">
      @foreach($products as $product)
        @if($product->subscriptionPlans && $product->subscriptionPlans->count())
          @foreach($product->subscriptionPlans->take(1) as $plan)
          <div class="col-lg-4 col-md-6 reveal">
            <div class="card pricing-card {{ $loop->parent->index === 1 ? 'popular' : '' }}">
              @if($loop->parent->index === 1)<div class="pricing-badge">{{ __('Most Popular') }}</div>@endif
              <div class="pricing-name">{{ $product->name }}</div>
              <div class="pricing-price">
                <span class="currency">{{ AppHelperssetting('currency.symbol','Rp') }}</span>{{ number_format($plan->price,0,',','.') }}
                <span class="period">/{{ __('lifetime') }}</span>
              </div>
              <p class="pricing-desc">{{ Str::limit($product->short_description ?? $product->description, 80) }}</p>
              <ul class="pricing-features">
                <li><i class="bi bi-check-circle-fill"></i> {{ __('All 10 Modules') }}</li>
                <li><i class="bi bi-check-circle-fill"></i> {{ __('Unlimited Users') }}</li>
                <li><i class="bi bi-check-circle-fill"></i> {{ __('Isolated Infrastructure') }}</li>
                <li><i class="bi bi-check-circle-fill"></i> {{ __('Lifetime Updates') }}</li>
                <li><i class="bi bi-check-circle-fill"></i> {{ __('30-Day Setup Support') }}</li>
              </ul>
              <a href="{{ route('customer.register') }}" class="btn {{ $loop->parent->index === 1 ? 'btn-primary' : 'btn-outline' }} btn-block">{{ __('Start Free Trial') }}</a>
            </div>
          </div>
          @endforeach
        @endif
      @endforeach
    </div>
    @else
    <div class="text-center py-5 reveal">
      <div class="empty-state">
        <div class="empty-state-icon"><i class="bi bi-cash-stack"></i></div>
        <h4>{{ __('Pricing Coming Soon') }}</h4>
        <p>{{ __('Our pricing plans are being finalized. Contact sales for early access pricing.') }}</p>
      </div>
    </div>
    @endif
  </div>
</section>

<section class="section section-alt">
  <div class="container text-center">
    <div class="reveal">
      <div class="section-label"><i class="bi bi-question-circle"></i> {{ __('FAQ') }}</div>
      <h2 class="section-title">{{ __('Common <span class="text-gradient">Questions</span>') }}</h2>
      <div class="row justify-content-center mt-4">
        <div class="col-lg-8 text-start">
          <div class="accordion accordion-c" id="pricingFaq">
            <div class="accordion-item">
              <h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#pf1">{{ __('What does lifetime license include?') }}</button></h2>
              <div id="pf1" class="accordion-collapse collapse show" data-bs-parent="#pricingFaq"><div class="accordion-body">{{ __('All 10 modules, unlimited users, isolated infrastructure, support during setup, and updates for 1 year.') }}</div></div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pf2">{{ __('Are there hidden fees?') }}</button></h2>
              <div id="pf2" class="accordion-collapse collapse" data-bs-parent="#pricingFaq"><div class="accordion-body">{{ __('No. The price you see is the price you pay. No monthly fees, no per-user charges, no hidden costs.') }}</div></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="cta-section">
  <div class="cta-bg"></div>
  <div class="container cta-content text-center">
    <div class="reveal">
      <h2 class="section-title">{{ __('Ready to <span class="text-gradient">Stop Renting?</span>') }}</h2>
      <p class="section-subtitle">{{ __('One payment. Lifetime ownership. Enterprise infrastructure.') }}</p>
      <a href="{{ route('customer.register') }}" class="btn btn-primary btn-lg">{{ __('Start Free Trial') }} <i class="bi bi-arrow-right"></i></a>
    </div>
  </div>
</section>
@endsection