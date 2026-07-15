@extends('layouts.guest')
@section('content')

<section class="page-hero">
  <div class="page-hero-orb page-hero-orb-1"></div>
  <div class="page-hero-orb page-hero-orb-2"></div>
  <div class="grid-bg"></div>
  <div class="container text-center position-relative" style="z-index:2;">
    <div class="badge-glow reveal mb-4">
      <i class="bi bi-star-fill"></i> {{ __('Terms of Service') }}
    </div>
    <h1 class="hero-title reveal rv-delay-1">{!! __('Terms of <span class="text-gradient">Service.</span>') !!}</h1>
    <p class="hero-subtitle reveal rv-delay-2" style="font-size:1.1rem;max-width:640px;margin:16px auto 0;">
      {{ __('Last updated: January 2024. Please read these terms carefully before using our services.') }}
    </p>
  </div>
</section>
<section class="section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 reveal">
        <div class="card" style="border-radius:var(--radius-lg);padding:40px;">
          <h3>1. {{ __('Acceptance of Terms') }}</h3>
          <p>{{ __('By accessing or using COOCA services, you agree to be bound by these Terms of Service. If you do not agree, please do not use our services.') }}</p>
          <h3 class="mt-4">2. {{ __('License Grant') }}</h3>
          <p>{{ __('COOCA grants you a non-exclusive, non-transferable, perpetual license to use the software for your business operations. Each license covers one business entity and its locations.') }}</p>
          <h3 class="mt-4">3. {{ __('User Responsibilities') }}</h3>
          <p>{{ __('You are responsible for maintaining the confidentiality of your login credentials, all activities under your account, and compliance with applicable laws.') }}</p>
          <h3 class="mt-4">4. {{ __('Data Ownership') }}</h3>
          <p>{{ __('You retain full ownership of your data. COOCA does not access, share, or monetize your business data. Upon termination, you may request a complete data export.') }}</p>
          <h3 class="mt-4">5. {{ __('Service Level Agreement') }}</h3>
          <p>{{ __('COOCA guarantees 99.9% uptime SLA. In the event of service interruption exceeding the SLA, service credits will be applied according to our SLA policy.') }}</p>
          <h3 class="mt-4">6. {{ __('Limitation of Liability') }}</h3>
          <p>{{ __('COOCA's liability is limited to the amount paid for the license. We are not liable for indirect, incidental, or consequential damages arising from the use of our software.') }}</p>
          <h3 class="mt-4">7. {{ __('Contact') }}</h3>
          <p>{{ __('For questions about these terms, contact us at legal@cooca.io.') }}</p>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection