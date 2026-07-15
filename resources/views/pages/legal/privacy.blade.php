@extends('layouts.guest')
@section('content')

<section class="page-hero">
  <div class="page-hero-orb page-hero-orb-1"></div>
  <div class="page-hero-orb page-hero-orb-2"></div>
  <div class="grid-bg"></div>
  <div class="container text-center position-relative" style="z-index:2;">
    <div class="badge-glow reveal mb-4">
      <i class="bi bi-star-fill"></i> {{ __('Privacy Policy') }}
    </div>
    <h1 class="hero-title reveal rv-delay-1">{!! __('Privacy <span class="text-gradient">Policy.</span>') !!}</h1>
    <p class="hero-subtitle reveal rv-delay-2" style="font-size:1.1rem;max-width:640px;margin:16px auto 0;">
      {{ __('Last updated: January 2024. We take your privacy and data protection seriously.') }}
    </p>
  </div>
</section>
<section class="section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 reveal">
        <div class="card" style="border-radius:var(--radius-lg);padding:40px;">
          <h3>1. {{ __('Information We Collect') }}</h3>
          <p>{{ __('We collect information you provide directly: name, email address, business information, and payment details. We also collect usage data to improve our services.') }}</p>
          <h3 class="mt-4">2. {{ __('How We Use Your Data') }}</h3>
          <p>{{ __('Your data is used solely to provide and improve COOCA services. We do not sell, rent, or share your data with third parties for marketing purposes.') }}</p>
          <h3 class="mt-4">3. {{ __('Data Storage & Security') }}</h3>
          <p>{{ __('All data is stored in isolated containers with AES-256 encryption at rest and TLS 1.3 in transit. Each customer has a dedicated database with no cross-tenant access.') }}</p>
          <h3 class="mt-4">4. {{ __('Your Rights') }}</h3>
          <p>{{ __('You have the right to access, correct, export, or delete your data at any time. Contact our support team to exercise these rights.') }}</p>
          <h3 class="mt-4">5. {{ __('Cookies') }}</h3>
          <p>{{ __('We use essential cookies for authentication and session management. We do not use tracking cookies for advertising purposes.') }}</p>
          <h3 class="mt-4">6. {{ __('Contact') }}</h3>
          <p>{{ __('For privacy-related inquiries, contact us at privacy@cooca.io.') }}</p>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection