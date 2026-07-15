@extends('layouts.guest')
@section('content')

<section class="page-hero">
  <div class="page-hero-orb page-hero-orb-1"></div>
  <div class="page-hero-orb page-hero-orb-2"></div>
  <div class="grid-bg"></div>
  <div class="container text-center position-relative" style="z-index:2;">
    <div class="badge-glow reveal mb-4">
      <i class="bi bi-star-fill"></i> {{ __('Solutions') }}
    </div>
    <h1 class="hero-title reveal rv-delay-1">{!! __('Solutions <span class="text-gradient">by Industry.</span>') !!}</h1>
    <p class="hero-subtitle reveal rv-delay-2" style="font-size:1.1rem;max-width:640px;margin:16px auto 0;">
      {{ __('Pre-configured business management systems for 9+ industries. Your industry template is ready in 30 minutes.') }}
    </p>
  </div>
</section>
<section class="section">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4 col-md-6 reveal">
        <div class="card card-3d product-card">
          <div class="card-icon card-icon-accent">🛍️</div>
          <h3 class="card-title">{{ __('Retail') }}</h3>
          <p class="card-desc">{{ __('Multi-outlet POS, inventory across warehouses, CRM with purchase history, automated procurement.') }}</p>
          <a href="{{ route('pricing') }}" class="btn btn-outline btn-sm">{{ __('Learn More') }} <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 reveal rv-delay-1">
        <div class="card card-3d product-card">
          <div class="card-icon card-icon-accent">🍴</div>
          <h3 class="card-title">{{ __('Restaurant & F&B') }}</h3>
          <p class="card-desc">{{ __('Table management, kitchen display, recipe costing, ingredient tracking, online order integration.') }}</p>
          <a href="{{ route('pricing') }}" class="btn btn-outline btn-sm">{{ __('Learn More') }} <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 reveal rv-delay-2">
        <div class="card card-3d product-card">
          <div class="card-icon card-icon-accent">🏨</div>
          <h3 class="card-title">{{ __('Hotel & Hospitality') }}</h3>
          <p class="card-desc">{{ __('Front desk, housekeeping, room service, event management, guest CRM, integrated billing.') }}</p>
          <a href="{{ route('pricing') }}" class="btn btn-outline btn-sm">{{ __('Learn More') }} <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 reveal">
        <div class="card card-3d product-card">
          <div class="card-icon card-icon-accent">🏥</div>
          <h3 class="card-title">{{ __('Clinic & Healthcare') }}</h3>
          <p class="card-desc">{{ __('Patient records, appointment scheduling, pharmacy integration, billing, lab results management.') }}</p>
          <a href="{{ route('pricing') }}" class="btn btn-outline btn-sm">{{ __('Learn More') }} <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 reveal rv-delay-1">
        <div class="card card-3d product-card">
          <div class="card-icon card-icon-accent">🎓</div>
          <h3 class="card-title">{{ __('Education') }}</h3>
          <p class="card-desc">{{ __('Student management, scheduling, fee collection, learning management, parent portal, grading.') }}</p>
          <a href="{{ route('pricing') }}" class="btn btn-outline btn-sm">{{ __('Learn More') }} <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 reveal rv-delay-2">
        <div class="card card-3d product-card">
          <div class="card-icon card-icon-accent">✂️</div>
          <h3 class="card-title">{{ __('Salon & Beauty') }}</h3>
          <p class="card-desc">{{ __('Appointment booking, stylist management, product inventory, customer preferences, loyalty program.') }}</p>
          <a href="{{ route('pricing') }}" class="btn btn-outline btn-sm">{{ __('Learn More') }} <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="cta-section">
  <div class="cta-bg"></div>
  <div class="container cta-content text-center">
    <div class="reveal">
      <h2 class="section-title">{{ __('Don't See <span class="text-gradient">Your Industry?</span>') }}</h2>
      <p class="section-subtitle">{{ __('We build custom solutions too. Tell us what you need and we'll configure COOCA for your specific industry.') }}</p>
      <a href="{{ route('contact') }}" class="btn btn-primary btn-lg">{{ __('Contact Sales') }} <i class="bi bi-chat-dots"></i></a>
    </div>
  </div>
</section>
@endsection