@extends('layouts.guest')
@section('content')

<section class="page-hero">
  <div class="page-hero-orb page-hero-orb-1"></div>
  <div class="page-hero-orb page-hero-orb-2"></div>
  <div class="grid-bg"></div>
  <div class="container text-center position-relative" style="z-index:2;">
    <div class="badge-glow reveal mb-4">
      <i class="bi bi-star-fill"></i> {{ __('Affiliate Program') }}
    </div>
    <h1 class="hero-title reveal rv-delay-1">{!! __('Earn <span class="text-gradient">While You Refer.</span>') !!}</h1>
    <p class="hero-subtitle reveal rv-delay-2" style="font-size:1.1rem;max-width:640px;margin:16px auto 0;">
      {{ __('Join the COOCA Affiliate Program. Refer businesses and earn generous commissions on every lifetime license sale.') }}
    </p>
  </div>
</section>
<section class="section">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-6 reveal">
        <div class="affiliate-highlight">
          <div class="affiliate-percent">{{ setting('affiliate.commission_percent', '20') }}%</div>
          <p style="font-size:1.1rem;font-weight:600;color:var(--text);">{{ __('Commission Per Sale') }}</p>
          <p style="font-size:0.9rem;">{{ __('On every lifetime license purchased through your referral link.') }}</p>
        </div>
      </div>
      <div class="col-lg-6 reveal rv-delay-2">
        <div class="section-label"><i class="bi bi-cash-coin"></i> {{ __('How It Works') }}</div>
        <div class="timeline">
          <div class="timeline-step"><div class="timeline-dot">1</div><div class="timeline-content"><h4>{{ __('Sign Up') }}</h4><p>{{ __('Create your affiliate account in 2 minutes.') }}</p></div></div>
          <div class="timeline-step"><div class="timeline-dot">2</div><div class="timeline-content"><h4>{{ __('Share Your Link') }}</h4><p>{{ __('Promote COOCA with your unique referral code.') }}</p></div></div>
          <div class="timeline-step"><div class="timeline-dot">3</div><div class="timeline-content"><h4>{{ __('Earn Commission') }}</h4><p>{{ __('Get paid for every business that purchases through you.') }}</p></div></div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section section-alt text-center">
  <div class="container">
    <div class="reveal">
      <div class="section-label"><i class="bi bi-gift"></i> {{ __('Benefits') }}</div>
      <h2 class="section-title">{{ __('Why Join <span class="text-gradient">COOCA Affiliate?</span>') }}</h2>
    </div>
    <div class="row g-4 mt-3">
      <div class="col-md-4 reveal"><div class="card why-card"><div class="why-icon"><i class="bi bi-percent"></i></div><h4>{{ __('High Commission') }}</h4><p style="margin:0;">{{ __('Earn up to 20% per sale with no cap on earnings.') }}</p></div></div>
      <div class="col-md-4 reveal rv-delay-1"><div class="card why-card"><div class="why-icon"><i class="bi bi-graph-up-arrow"></i></div><h4>{{ __('Real-Time Dashboard') }}</h4><p style="margin:0;">{{ __('Track clicks, conversions, and earnings in real-time.') }}</p></div></div>
      <div class="col-md-4 reveal rv-delay-2"><div class="card why-card"><div class="why-icon"><i class="bi bi-wallet2"></i></div><h4>{{ __('Monthly Payouts') }}</h4><p style="margin:0;">{{ __('Reliable monthly payouts directly to your bank account.') }}</p></div></div>
    </div>
    <div class="mt-5 reveal">
      <a href="{{ route('affiliator.register') }}" class="btn btn-primary btn-lg">{{ __('Join Now') }} <i class="bi bi-arrow-right"></i></a>
    </div>
  </div>
</section>
@endsection