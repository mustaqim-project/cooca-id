@extends('layouts.guest')
@section('content')

<section class="page-hero">
  <div class="page-hero-orb page-hero-orb-1"></div>
  <div class="page-hero-orb page-hero-orb-2"></div>
  <div class="grid-bg"></div>
  <div class="container text-center position-relative" style="z-index:2;">
    <div class="badge-glow reveal mb-4">
      <i class="bi bi-star-fill"></i> {{ __('About Us') }}
    </div>
    <h1 class="hero-title reveal rv-delay-1">{!! __('Built for <span class="text-gradient">Business Owners</span> Who Think Long-Term.') !!}</h1>
    <p class="hero-subtitle reveal rv-delay-2" style="font-size:1.1rem;max-width:640px;margin:16px auto 0;">
      {{ __('COOCA is an enterprise business infrastructure company. We don\'t just sell software — we build digital assets that businesses own forever.') }}
    </p>
  </div>
</section>
<section class="section">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-6 reveal">
        <div class="section-label"><i class="bi bi-building"></i> {{ __('Our Story') }}</div>
        <h2>{{ __('We Believe Businesses <span class="text-gradient">Should Own Their Tools.</span>') }}</h2>
        <p class="mt-3">{{ __('The SaaS industry has conditioned businesses to rent their software forever. Monthly fees that add up to millions over years. Data locked in platforms you can never leave. Infrastructures shared across thousands of tenants — one breach affects everyone.') }}</p>
        <p>{{ __('COOCA was founded to change this. We believe every serious business deserves its own isolated infrastructure, its own database, and a lifetime license to the software that runs its operations.') }}</p>
        <div class="row g-3 mt-4">
          <div class="col-6"><div class="counter-value">{{ setting('about.stat1','2020') }}</div><div class="counter-label">{{ __('Founded') }}</div></div>
          <div class="col-6"><div class="counter-value">{{ setting('about.stat2','10') }}+</div><div class="counter-label">{{ __('Industry Solutions') }}</div></div>
        </div>
      </div>
      <div class="col-lg-6 reveal rv-delay-2">
        <div class="card" style="padding:0;overflow:hidden;border-radius:var(--radius-lg);">
          <div style="padding:32px;background:var(--surface-alt);border-bottom:1px solid var(--border);">
            <div class="feature-item mb-3">
              <div class="feature-icon"><i class="bi bi-check-circle-fill"></i></div>
              <div><div class="feature-title">{{ __('Lifetime License') }}</div><p class="feature-desc">{{ __('Pay once. Use forever. No hidden recurring fees.') }}</p></div>
            </div>
            <div class="feature-item mb-3">
              <div class="feature-icon"><i class="bi bi-shield-check"></i></div>
              <div><div class="feature-title">{{ __('Isolated Infrastructure') }}</div><p class="feature-desc">{{ __('Your own container, your own database. Enterprise-grade isolation.') }}</p></div>
            </div>
            <div class="feature-item">
              <div class="feature-icon"><i class="bi bi-rocket-takeoff"></i></div>
              <div><div class="feature-title">{{ __('30-Minute Provisioning') }}</div><p class="feature-desc">{{ __('From sign-up to fully operational in 30 minutes.') }}</p></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section section-alt">
  <div class="container text-center">
    <div class="reveal">
      <div class="section-label"><i class="bi bi-flag"></i> {{ __('Our Mission') }}</div>
      <h2 class="section-title">{{ __('To Make <span class="text-gradient">Enterprise-Grade</span> Business Infrastructure Accessible to Every Serious Business in Indonesia.') }}</h2>
      <p class="section-subtitle">{{ __('Not through cheap SaaS subscriptions, but through real ownership. Real isolation. Real long-term value.') }}</p>
    </div>
  </div>
</section>

<section class="cta-section">
  <div class="cta-bg"></div>
  <div class="container cta-content text-center">
    <div class="reveal">
      <h2 class="section-title">{{ __('Ready to <span class="text-gradient">Own Your System?</span>') }}</h2>
      <p class="section-subtitle">{{ __('Join 1,200+ businesses that have stopped renting their software.') }}</p>
      <a href="{{ route('customer.register') }}" class="btn btn-primary btn-lg">{{ __('Start Free Trial') }} <i class="bi bi-arrow-right"></i></a>
    </div>
  </div>
</section>
@endsection