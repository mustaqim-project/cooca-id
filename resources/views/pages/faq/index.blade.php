@extends('layouts.guest')
@section('content')

<section class="page-hero">
  <div class="page-hero-orb page-hero-orb-1"></div>
  <div class="page-hero-orb page-hero-orb-2"></div>
  <div class="grid-bg"></div>
  <div class="container text-center position-relative" style="z-index:2;">
    <div class="badge-glow reveal mb-4">
      <i class="bi bi-star-fill"></i> {{ __('FAQ') }}
    </div>
    <h1 class="hero-title reveal rv-delay-1">{!! __('Frequently Asked <span class="text-gradient">Questions.</span>') !!}</h1>
    <p class="hero-subtitle reveal rv-delay-2" style="font-size:1.1rem;max-width:640px;margin:16px auto 0;">
      {{ __('Everything you need to know about COOCA, our licensing model, infrastructure, and how we serve your business.') }}
    </p>
  </div>
</section>
<section class="section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 reveal">
        <div class="accordion accordion-c" id="faqMain">
          <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#f1">{{ __('What is COOCA?') }}</button></h2>
            <div id="f1" class="accordion-collapse collapse show" data-bs-parent="#faqMain"><div class="accordion-body">{{ __('COOCA is an enterprise business infrastructure platform that provides lifetime-licensed ERP, CRM, POS, HRIS, and other business management modules on isolated container infrastructure.') }}</div></div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#f2">{{ __('What does lifetime license mean?') }}</button></h2>
            <div id="f2" class="accordion-collapse collapse" data-bs-parent="#faqMain"><div class="accordion-body">{{ __('You pay once and own the software forever. No annual renewal fees. No forced upgrades. Your license does not expire. This is fundamental to our business model.') }}</div></div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#f3">{{ __('How is my data secured?') }}</button></h2>
            <div id="f3" class="accordion-collapse collapse" data-bs-parent="#faqMain"><div class="accordion-body">{{ __('Each customer gets isolated infrastructure: separate container, separate database. Your data never touches another customer. All data is encrypted at rest (AES-256) and in transit (TLS 1.3). We perform daily automated backups.') }}</div></div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#f4">{{ __('How long does setup take?') }}</button></h2>
            <div id="f4" class="accordion-collapse collapse" data-bs-parent="#faqMain"><div class="accordion-body">{{ __('Your isolated instance is provisioned in approximately 30 minutes. Pre-configured industry templates mean you can start using the system immediately.') }}</div></div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#f5">{{ __('Can I migrate my existing data?') }}</button></h2>
            <div id="f5" class="accordion-collapse collapse" data-bs-parent="#faqMain"><div class="accordion-body">{{ __('Yes. We provide migration tools and dedicated support to move your data from legacy systems, spreadsheets, or other platforms. Most migrations are completed within 24 hours.') }}</div></div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#f6">{{ __('Is there a free trial?') }}</button></h2>
            <div id="f6" class="accordion-collapse collapse" data-bs-parent="#faqMain"><div class="accordion-body">{{ __('Absolutely. Start a 30-day full-access trial with all 10 modules. No credit card required. Your isolated instance is provisioned in 30 minutes, and you get full access to evaluate the system.') }}</div></div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#f7">{{ __('Do you offer support?') }}</button></h2>
            <div id="f7" class="accordion-collapse collapse" data-bs-parent="#faqMain"><div class="accordion-body">{{ __('Yes. We provide 30-day setup support included with every license, with extended support plans available. Our support team operates during Indonesian business hours (Mon–Fri, 9AM–6PM WIB) via WhatsApp, email, and ticketing.') }}</div></div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#f8">{{ __('Can I cancel my license?') }}</button></h2>
            <div id="f8" class="accordion-collapse collapse" data-bs-parent="#faqMain"><div class="accordion-body">{{ __('Our refund policy covers the first 30 days after purchase. After that, since you own the license, there is no cancellation — the software is yours. We recommend thoroughly evaluating during the free trial period.') }}</div></div>
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
      <h2 class="section-title">{!! __('Still Have <span class="text-gradient">Questions?</span>') !!}</h2>
      <p class="section-subtitle">{{ __('Our team is ready to answer any specific questions about your industry and requirements.') }}</p>
      <a href="{{ route('contact') }}" class="btn btn-primary btn-lg">{{ __('Contact Us') }} <i class="bi bi-chat-dots"></i></a>
    </div>
  </div>
</section>
@endsection