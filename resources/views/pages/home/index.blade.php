@extends('layouts.guest')
@section('content')
<section class="hero">
  <div class="hero-orb hero-orb-1"></div>
  <div class="hero-orb hero-orb-2"></div>
  <div class="hero-orb hero-orb-3"></div>
  <div class="grid-bg"></div>
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6 hero-content">
        <div class="badge-saas reveal mb-4">
          <span class="badge-dot online"></span> {{ __(setting('home.badge', "Indonesia's #1 Business Infrastructure Platform")) }}
        </div>
        <h1 class="hero-title reveal rv-delay-1">
          {!! __(setting('home.headline', 'Own Your Business System. <br><span class="hero-highlight text-gradient">Stop Renting, Start Building.</span>')) !!}
        </h1>
        <p class="hero-subtitle reveal rv-delay-2">
          {{ __(setting('home.subtitle', 'One lifetime license. Your complete ERP, CRM, POS, HRIS. Isolated infrastructure. No recurring fees.')) }}
        </p>
        <div class="hero-cta reveal rv-delay-3">
          <a href="{{ route('customer.register') }}" class="btn btn-primary btn-lg">{{ __('Start Free Trial') }} <i class="bi bi-arrow-right"></i></a>
          <a href="{{ route('contact') }}" class="btn btn-outline btn-lg">{{ __('Request Demo') }} <i class="bi bi-play-circle"></i></a>
        </div>
        <div class="hero-stats reveal rv-delay-4">
          <div><div class="hero-stat-value">{{ setting('home.stat1_value', '1,200+') }}</div><div class="hero-stat-label">{{ __('Active Businesses') }}</div></div>
          <div><div class="hero-stat-value">{{ setting('home.stat2_value', '99.9%') }}</div><div class="hero-stat-label">{{ __('Uptime SLA') }}</div></div>
          <div><div class="hero-stat-value">{{ setting('home.stat3_value', '30min') }}</div><div class="hero-stat-label">{{ __('Provisioning') }}</div></div>
        </div>
      </div>
      <div class="col-lg-6 hero-visual">
        <div class="hero-dashboard reveal rv-delay-3">
          <div class="dashboard-header">
            <div class="dashboard-dot red"></div><div class="dashboard-dot yellow"></div><div class="dashboard-dot green"></div>
            <span style="margin-left:8px;font-size:0.75rem;color:var(--text-muted);">dashboard.cooca.id</span>
          </div>
          <div class="dashboard-body">
            <div class="dashboard-grid">
              <div class="dash-widget"><div class="dash-widget-title">{{ __('Revenue') }}</div><div class="dash-widget-value">Rp 12.4M</div><div class="dash-widget-change">↑ 12.5%</div></div>
              <div class="dash-widget"><div class="dash-widget-title">{{ __('Orders') }}</div><div class="dash-widget-value">147</div><div class="dash-widget-change">↑ 8.2%</div></div>
              <div class="dash-widget"><div class="dash-widget-title">{{ __('Customers') }}</div><div class="dash-widget-value">3,842</div><div class="dash-widget-change">↑ 5.1%</div></div>
              <div class="dash-widget"><div class="dash-widget-title">{{ __('Inventory') }}</div><div class="dash-widget-value">Rp 892M</div><div class="dash-widget-change">→ Stable</div></div>
              <div class="dash-chart">
                <div class="dash-chart-bar" style="height:60%"></div><div class="dash-chart-bar" style="height:80%"></div><div class="dash-chart-bar" style="height:45%"></div><div class="dash-chart-bar" style="height:90%"></div><div class="dash-chart-bar" style="height:55%"></div><div class="dash-chart-bar" style="height:70%"></div><div class="dash-chart-bar" style="height:85%"></div><div class="dash-chart-bar" style="height:50%"></div><div class="dash-chart-bar" style="height:65%"></div><div class="dash-chart-bar" style="height:75%"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="floating-card floating-card-1">
          <div class="fc-icon green"><i class="bi bi-check-circle-fill"></i></div>
          <div class="fc-label">{{ __('License') }}</div>
          <div class="fc-value">{{ __('Active') }}</div>
        </div>
        <div class="floating-card floating-card-2">
          <div class="fc-icon blue"><i class="bi bi-people-fill"></i></div>
          <div class="fc-label">{{ __('Team') }}</div>
          <div class="fc-value">24 {{ __('users') }}</div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="counter-section" id="counters">
  <div class="container">
    <div class="row g-4">
      <div class="col-6 col-md-3 counter-item reveal">
        <div class="counter-value"><span class="counter" data-target="1250">0</span>+</div>
        <div class="counter-label">{{ __('Active Businesses') }}</div>
      </div>
      <div class="col-6 col-md-3 counter-item reveal rv-delay-1">
        <div class="counter-value"><span class="counter" data-target="99.9" data-decimal="true">0</span>%</div>
        <div class="counter-label">{{ __('Uptime SLA') }}</div>
      </div>
      <div class="col-6 col-md-3 counter-item reveal rv-delay-2">
        <div class="counter-value"><span class="counter" data-target="50000">0</span>+</div>
        <div class="counter-label">{{ __('Daily Transactions') }}</div>
      </div>
      <div class="col-6 col-md-3 counter-item reveal rv-delay-3">
        <div class="counter-value"><span class="counter" data-target="9">0</span></div>
        <div class="counter-label">{{ __('Industry Solutions') }}</div>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="text-center reveal">
      <div class="section-label"><i class="bi bi-grid-fill"></i> {{ __('Our Products') }}</div>
      <h2 class="section-title">{{ __('Everything Your Business Needs,') }}<br><span class="text-gradient">{{ __('In One Platform.') }}</span></h2>
      <p class="section-subtitle">{{ __('From point of sale to HR management — all modules included. No hidden fees.') }}</p>
    </div>
    <div class="row g-4">
      @if(isset($products) && count($products))
          @foreach($products->take(6) as $product)
          <div class="col-lg-4 col-md-6 reveal">
            <div class="card card-3d product-card card-hover-glow">
              <div class="card-icon"><i class="bi bi-box"></i></div>
              <h3 class="card-title">{{ $product->name }}</h3>
              <p class="card-desc">{{ Str::limit($product->description ?? $product->short_description, 100) }}</p>
              <div class="card-actions">
                <a href="{{ route('products.show', $product->slug) }}" class="btn btn-outline btn-sm">{{ __('Details') }} <i class="bi bi-arrow-right"></i></a>
              </div>
            </div>
          </div>
          @endforeach
      @endif
    </div>
    <div class="text-center mt-5 reveal">
      <a href="{{ route('products.index') }}" class="btn btn-outline btn-lg">{{ __('View All Products') }} <i class="bi bi-arrow-right"></i></a>
    </div>
  </div>
</section>

<section class="section section-alt">
  <div class="container">
    <div class="text-center reveal">
      <div class="section-label"><i class="bi bi-stars"></i> {{ __('Core Features') }}</div>
      <h2 class="section-title">{!! __('Powered by <span class="text-gradient">10 Integrated Modules</span>') !!}</h2>
    </div>
    <div class="row g-4">
      <div class="col-md-4 col-sm-6 reveal"><div class="card module-card"><div class="module-icon"><i class="bi bi-cart-check"></i></div><div class="module-title">{{ __('Point of Sale') }}</div><p class="module-desc">{{ __('Multi-outlet POS with real-time inventory sync.') }}</p></div></div>
      <div class="col-md-4 col-sm-6 reveal rv-delay-1"><div class="card module-card"><div class="module-icon"><i class="bi bi-box-seam"></i></div><div class="module-title">{{ __('Inventory') }}</div><p class="module-desc">{{ __('Multi-warehouse tracking with automated reorder.') }}</p></div></div>
      <div class="col-md-4 col-sm-6 reveal rv-delay-2"><div class="card module-card"><div class="module-icon"><i class="bi bi-people"></i></div><div class="module-title">{{ __('CRM') }}</div><p class="module-desc">{{ __('360-degree customer view with automation.') }}</p></div></div>
      <div class="col-md-4 col-sm-6 reveal"><div class="card module-card"><div class="module-icon"><i class="bi bi-cash-stack"></i></div><div class="module-title">{{ __('Accounting') }}</div><p class="module-desc">{{ __('Double-entry accounting with auto-reconciliation.') }}</p></div></div>
      <div class="col-md-4 col-sm-6 reveal rv-delay-1"><div class="card module-card"><div class="module-icon"><i class="bi bi-person-badge"></i></div><div class="module-title">{{ __('HRIS') }}</div><p class="module-desc">{{ __('Attendance, payroll, leave management.') }}</p></div></div>
      <div class="col-md-4 col-sm-6 reveal rv-delay-2"><div class="card module-card"><div class="module-icon"><i class="bi bi-graph-up"></i></div><div class="module-title">{{ __('BI') }}</div><p class="module-desc">{{ __('Custom dashboards and predictive analytics.') }}</p></div></div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="text-center reveal">
      <div class="section-label"><i class="bi bi-shield-check"></i> {{ __('Why COOCA') }}</div>
      <h2 class="section-title">{!! __('You Own It. <span class="text-gradient">Forever.</span>') !!}</h2>
    </div>
    <div class="row g-4 mt-3">
      <div class="col-lg-4 col-md-6 reveal"><div class="card why-card"><div class="why-icon"><i class="bi bi-infinity"></i></div><h4>{{ __('Lifetime License') }}</h4><p style="margin:0;">{{ __('Pay once. Use forever. No recurring fees.') }}</p></div></div>
      <div class="col-lg-4 col-md-6 reveal rv-delay-1"><div class="card why-card"><div class="why-icon"><i class="bi bi-shield-lock"></i></div><h4>{{ __('Isolated Infrastructure') }}</h4><p style="margin:0;">{{ __('Your own container. Zero cross-tenant risk.') }}</p></div></div>
      <div class="col-lg-4 col-md-6 reveal rv-delay-2"><div class="card why-card"><div class="why-icon"><i class="bi bi-rocket-takeoff"></i></div><h4>{{ __('30-Minute Setup') }}</h4><p style="margin:0;">{{ __('Pre-configured for your industry.') }}</p></div></div>
    </div>
  </div>
</section>

<section class="section section-alt">
  <div class="container text-center">
    <div class="reveal">
      <div class="section-label"><i class="bi bi-chat-quote"></i> {{ __('Testimonials') }}</div>
      <h2 class="section-title">{!! __('Trusted by <span class="text-gradient">Business Leaders</span>') !!}</h2>
    </div>
    <div class="row g-4 mt-4">
      <div class="col-lg-4 col-md-6 reveal"><div class="card testimonial-card"><div class="d-flex align-items-center gap-3 mb-3"><div class="brand-icon" style="width:48px;height:48px;font-size:1.2rem;">BS</div><div><div class="testimonial-name">Budi Santoso</div><div class="testimonial-role">{{ __('CEO, RetailMax Group') }}</div></div></div><div class="testimonial-stars">★★★★★</div><p class="testimonial-text">{{ __('"Switched from annual SaaS to COOCA lifetime. ROI in month 3."') }}</p></div></div>
      <div class="col-lg-4 col-md-6 reveal rv-delay-1"><div class="card testimonial-card"><div class="d-flex align-items-center gap-3 mb-3"><div class="brand-icon" style="width:48px;height:48px;font-size:1.2rem;">SR</div><div><div class="testimonial-name">Siti Rahma</div><div class="testimonial-role">{{ __('Owner, Sehati Clinic') }}</div></div></div><div class="testimonial-stars">★★★★★</div><p class="testimonial-text">{{ __('"Isolated infrastructure was the key factor. Patient data stays safe."') }}</p></div></div>
      <div class="col-lg-4 col-md-6 reveal rv-delay-2"><div class="card testimonial-card"><div class="d-flex align-items-center gap-3 mb-3"><div class="brand-icon" style="width:48px;height:48px;font-size:1.2rem;">AW</div><div><div class="testimonial-name">Andi Wijaya</div><div class="testimonial-role">{{ __('COO, EduPrime') }}</div></div></div><div class="testimonial-stars">★★★★★</div><p class="testimonial-text">{{ __('"HRIS module saved us 40 hours/month on payroll alone."') }}</p></div></div>
    </div>
  </div>
</section>

<section class="cta-section">
  <div class="cta-bg"></div>
  <div class="container cta-content text-center">
    <div class="reveal">
      <h2 class="section-title">{!! __('Ready to <span class="text-gradient">Own Your System?</span>') !!}</h2>
      <p class="section-subtitle">{{ __('30-day free trial. No credit card. All modules. 30-minute setup.') }}</p>
      <a href="{{ route('customer.register') }}" class="btn btn-primary btn-lg">{{ __('Start Free Trial') }} <i class="bi bi-arrow-right"></i></a>
    </div>
  </div>
</section>

<section class="section section-alt">
  <div class="container">
    <div class="text-center reveal"><div class="section-label"><i class="bi bi-question-circle"></i> {{ __('FAQ') }}</div><h2 class="section-title">{!! __('Quick <span class="text-gradient">Answers</span>') !!}</h2></div>
    <div class="row justify-content-center"><div class="col-lg-8 reveal">
      <div class="accordion accordion-c" id="homeFaq">
        <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#hq1">{{ __('What does lifetime license mean?') }}</button></h2><div id="hq1" class="accordion-collapse collapse show" data-bs-parent="#homeFaq"><div class="accordion-body">{{ __('Pay once. Own forever. No annual fees. No forced upgrades. Your license never expires.') }}</div></div></div>
        <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#hq2">{{ __('How is my data secured?') }}</button></h2><div id="hq2" class="accordion-collapse collapse" data-bs-parent="#homeFaq"><div class="accordion-body">{{ __('Isolated container + dedicated database. AES-256 encryption. Daily automated backups.') }}</div></div></div>
        <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#hq3">{{ __('Is there a free trial?') }}</button></h2><div id="hq3" class="accordion-collapse collapse" data-bs-parent="#homeFaq"><div class="accordion-body">{{ __('Yes. 30 days, full access, all 10 modules. No credit card required. 30-minute provisioning.') }}</div></div></div>
      </div>
      <div class="text-center mt-4"><a href="{{ route('faq') }}" class="btn btn-outline">{{ __('View All FAQs') }} <i class="bi bi-arrow-right"></i></a></div>
    </div></div>
  </div>
</section>

<section class="section text-center">
  <div class="container">
    <div class="reveal">
      <div class="section-label"><i class="bi bi-building-check"></i> {{ __('Enterprise-Ready') }}</div>
      <h2 class="section-title">{!! __('Built with <span class="text-gradient">Fortune 500</span> infrastructure standards.') !!}</h2>
      <div class="trust-pills mt-4">
        <span class="trust-pill"><i class="bi bi-shield-check"></i> ISO 27001</span>
        <span class="trust-pill"><i class="bi bi-lock-fill"></i> AES-256</span>
        <span class="trust-pill"><i class="bi bi-cloud-check"></i> 99.9% SLA</span>
        <span class="trust-pill"><i class="bi bi-database-check"></i> Daily Backups</span>
      </div>
    </div>
  </div>
</section>
@endsection