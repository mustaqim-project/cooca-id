const fs = require("fs");
const path = require("path");

const BASE = ".";

// Blade helper — preserves Blade __() calls in generated templates
const __ = (...args) => `{{ __('${args[0].replace(/'/g, "\\'")}') }}`;

function write(filepath, content) {
    const full = path.join(BASE, filepath);
    const dir = path.dirname(full);
    if (!fs.existsSync(dir)) fs.mkdirSync(dir, { recursive: true });
    fs.writeFileSync(full, content, "utf8");
    console.log("  Wrote: " + filepath);
}

// ===================== GUEST LAYOUT =====================
let guest = fs.readFileSync("resources/views/layouts/guest.blade.php", "utf8");

// Replace the inline JS with cleaner version
const newScript = `    <script>
    (function(){"use strict";
      var h=document.documentElement;
      var s=localStorage.getItem("cooca-theme")||(window.matchMedia("(prefers-color-scheme: light)").matches?"light":"dark");
      h.setAttribute("data-theme",s);
      window.addEventListener("load",function(){setTimeout(function(){var l=document.getElementById("pageLoader");if(l)l.classList.add("hidden")},1000)});
      function st(t){h.setAttribute("data-theme",t);localStorage.setItem("cooca-theme",t);
        document.querySelectorAll(".theme-icon-light").forEach(function(e){e.style.display=t==="dark"?"none":""});
        document.querySelectorAll(".theme-icon-dark").forEach(function(e){e.style.display=t==="dark"?"":"none"});
        document.querySelectorAll(".nav-logo-light,.loader-img-light").forEach(function(e){e.style.display=t==="dark"?"none":""});
        document.querySelectorAll(".nav-logo-dark,.loader-img-dark").forEach(function(e){e.style.display=t==="dark"?"":"none"});
      }
      st(s);
      document.querySelectorAll("[data-toggle-theme]").forEach(function(b){b.addEventListener("click",function(){st(h.getAttribute("data-theme")==="dark"?"light":"dark")})});
      var nav=document.querySelector(".navbar");if(nav){window.addEventListener("scroll",function(){nav.classList.toggle("scrolled",window.pageYOffset>40)},{passive:true});if(window.pageYOffset>40)nav.classList.add("scrolled")}
      var r=document.querySelectorAll(".reveal");if(r.length&&"IntersectionObserver"in window){var o=new IntersectionObserver(function(e){e.forEach(function(e){if(e.isIntersecting){e.target.classList.add("revealed");o.unobserve(e.target)}})},{threshold:0.1,rootMargin:"0px 0px -40px 0px"});r.forEach(function(e){o.observe(e)})}
      document.addEventListener("click",function(e){var b=e.target.closest(".btn");if(!b)return;var rp=document.createElement("span");rp.classList.add("ripple");var rc=b.getBoundingClientRect();var sz=Math.max(rc.width,rc.height);rp.style.width=rp.style.height=sz+"px";rp.style.left=(e.clientX-rc.left-sz/2)+"px";rp.style.top=(e.clientY-rc.top-sz/2)+"px";b.appendChild(rp);setTimeout(function(){rp.remove()},600)});
      var cs=document.getElementById("counters");if(cs&&"IntersectionObserver"in window){var an=false;var co=new IntersectionObserver(function(e){if(e[0].isIntersecting&&!an){an=true;document.querySelectorAll(".counter").forEach(function(c){var t=parseFloat(c.getAttribute("data-target"));var d=c.getAttribute("data-decimal")==="true";var dur=2000;var st2=performance.now();function up(n){var p=Math.min((n-st2)/dur,1);var e=1-Math.pow(1-p,3);var v=e*t;c.textContent=d?v.toFixed(1):Math.floor(v).toLocaleString();if(p<1)requestAnimationFrame(up);else c.textContent=d?t.toFixed(1):t.toLocaleString()}requestAnimationFrame(up)});co.unobserve(cs)}}},{threshold:0.3});co.observe(cs)}
      document.querySelectorAll(".card-hover-glow").forEach(function(c){c.addEventListener("mousemove",function(e){var r2=c.getBoundingClientRect();c.style.setProperty("--mouse-x",(e.clientX-r2.left)+"px");c.style.setProperty("--mouse-y",(e.clientY-r2.top)+"px")})});
      document.querySelectorAll(".card-3d").forEach(function(c){c.addEventListener("mousemove",function(e){var r3=c.getBoundingClientRect();var cx=r3.width/2,cy=r3.height/2;var rx=((e.clientY-r3.top-cy)/cy)*-4;var ry=((e.clientX-r3.left-cx)/cx)*4;c.style.transform="perspective(1000px) rotateX("+rx+"deg) rotateY("+ry+"deg) translateY(-6px)"});c.addEventListener("mouseleave",function(){c.style.transform=""})});
      document.querySelectorAll(".input-toggle").forEach(function(b){b.addEventListener("click",function(){var i=document.querySelector(this.getAttribute("data-target"));if(!i)return;var p=i.type==="password";i.type=p?"text":"password";var ic=this.querySelector("i");if(ic)ic.className="bi "+(p?"bi-eye-slash":"bi-eye")})});
      var orbs=document.querySelectorAll(".hero-orb,.page-hero-orb");if(orbs.length){window.addEventListener("scroll",function(){var sy=window.pageYOffset;orbs.forEach(function(o,i){o.style.transform="translateY("+(sy*(0.03+i*0.02))+"px)"})},{passive:true})}
    })();
    </script>`;

// Replace the old script block with the new compact version
guest = guest.replace(
    /<script>\s*\/\/\s*=+\s*SHARED SYSTEM[\s\S]*?<\/script>/g,
    newScript,
);

// Remove font-awesome (using bootstrap-icons instead)
guest = guest.replace(
    '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />',
    "",
);

// Remove @stack('head') if present
guest = guest.replace("@stack('head')", "");

write("resources/views/layouts/guest.blade.php", guest);

// ===================== HEADER UPDATE =====================
let header = fs.readFileSync(
    "resources/views/partials/header.blade.php",
    "utf8",
);

// Replace WhatsApp float with pulse ring version
header = header.replace(
    'class="whatsapp-float"',
    'class="whatsapp-float"\n  <span class="pulse-ring"></span>',
);
// Only add pulse-ring if not already present
if (header.indexOf("pulse-ring") === header.indexOf("pulse-ring")) {
    // Already present, fine
}

// Replace the login dropdown to use proper class names
header = header.replace(
    /class="dropdown-menu"/g,
    'class="dropdown-menu dropdown-menu-c"',
);
header = header.replace(/class="dropdown-item"/g, 'class="dropdown-item"');

// Add Home link and Products link to nav
if (header.indexOf("route('home')") === -1) {
    header = header.replace(
        "<a href=\"{{ route('solutions') }}\"",
        "<a href=\"{{ route('home') }}\" class=\"nav-link {{ request()->routeIs('home') ? 'active' : '' }}\">{{ __('Home') }}</a>\n        <a href=\"{{ route('solutions') }}\"",
    );
}
if (header.indexOf("route('products.index')") === -1) {
    header = header.replace(
        '<a href="{{ route(\'solutions\') }}" class="nav-link-cooca',
        "<a href=\"{{ route('products.index') }}\" class=\"nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}\">{{ __('Products') }}</a>\n        <a href=\"{{ route('solutions') }}\" class=\"nav-link-cooca",
    );
}

// Add Docs link
if (header.indexOf("route('docs')") === -1) {
    header = header.replace(
        '<a href="{{ route(\'about\') }}" class="nav-link-cooca',
        "<a href=\"{{ route('docs') }}\" class=\"nav-link {{ request()->routeIs('docs') ? 'active' : '' }}\">{{ __('Docs') }}</a>\n        <a href=\"{{ route('about') }}\" class=\"nav-link-cooca",
    );
}

// Update theme toggle to use data attribute
header = header.replace(
    /id="themeToggle"/g,
    'id="themeToggle" data-toggle-theme',
);
header = header.replace(
    /id="themeToggleMobile"/g,
    'id="themeToggleMobile" data-toggle-theme',
);

write("resources/views/partials/header.blade.php", header);

// ===================== FOOTER UPDATE =====================
let footer = fs.readFileSync(
    "resources/views/partials/footer.blade.php",
    "utf8",
);
// Add YouTube social if missing
if (footer.indexOf("social.youtube") === -1) {
    footer = footer.replace(
        "@if(setting('social.instagram'))",
        "@if(setting('social.youtube'))\n          <a href=\"{{ setting('social.youtube','#') }}\" aria-label=\"YouTube\"><i class=\"bi bi-youtube\"></i></a>\n          @endif\n          @if(setting('social.instagram'))",
    );
}
write("resources/views/partials/footer.blade.php", footer);

// ===================== INNER PAGE HELPER =====================
function innerPageHero(title, subtitle, badge) {
    return `
<section class="page-hero">
  <div class="page-hero-orb page-hero-orb-1"></div>
  <div class="page-hero-orb page-hero-orb-2"></div>
  <div class="grid-bg"></div>
  <div class="container text-center position-relative" style="z-index:2;">
    <div class="badge-glow reveal mb-4">
      <i class="bi bi-star-fill"></i> ${badge}
    </div>
    <h1 class="hero-title reveal rv-delay-1">${title}</h1>
    <p class="hero-subtitle reveal rv-delay-2" style="font-size:1.1rem;max-width:640px;margin:16px auto 0;">
      ${subtitle}
    </p>
  </div>
</section>`;
}

// ===================== ABOUT PAGE =====================
write(
    "resources/views/pages/about/index.blade.php",
    `@extends('layouts.guest')
@section('content')
${innerPageHero(
    "{!! __('Built for <span class=\"text-gradient\">Business Owners</span> Who Think Long-Term.') !!}",
    __(
        "COOCA is an enterprise business infrastructure company. We don't just sell software — we build digital assets that businesses own forever.",
    ),
    __("About Us"),
)}
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
@endsection`,
);

// ===================== PRICING PAGE =====================
write(
    "resources/views/pages/pricing/index.blade.php",
    `@extends('layouts.guest')
@section('content')
${innerPageHero(
    "{!! __('Simple, <span class=\"text-gradient\">Transparent</span> Pricing.') !!}",
    __(
        "One lifetime license. All modules included. No monthly fees. No per-user charges. Enterprise infrastructure at a fraction of SaaS lifetime cost.",
    ),
    __("Pricing"),
)}
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
                <span class="currency">{{ \App\Helpers\setting('currency.symbol','Rp') }}</span>{{ number_format($plan->price,0,',','.') }}
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
@endsection`,
);

// ===================== CONTACT PAGE =====================
write(
    "resources/views/pages/contact/index.blade.php",
    `@extends('layouts.guest')
@section('content')
${innerPageHero(
    "{!! __('Let\'s Talk <span class=\"text-gradient\">Business.</span>') !!}",
    __(
        "Sales questions, technical support, partnership inquiries — our team responds within hours, not days.",
    ),
    __("Contact Us"),
)}
<section class="section">
  <div class="container">
    <div class="row g-4 justify-content-center mb-5">
      <div class="col-lg-3 col-md-6 reveal">
        <div class="channel-card">
          <div class="channel-icon"><i class="bi bi-whatsapp" style="color:#25D366;"></i></div>
          <div class="channel-title">WhatsApp</div>
          <p style="font-size:.85rem;margin-bottom:16px;flex-grow:1;">{{ __('Fastest response during business hours.') }}</p>
          <a href="{{ setting('contact.whatsapp_link', 'https://wa.me/6281234567890') }}" target="_blank" rel="noopener" class="btn btn-outline btn-sm btn-block">{{ __('Open WhatsApp') }}</a>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 reveal rv-delay-1">
        <div class="channel-card">
          <div class="channel-icon"><i class="bi bi-envelope-fill"></i></div>
          <div class="channel-title">Email</div>
          <p style="font-size:.85rem;margin-bottom:16px;flex-grow:1;">{{ __('We respond within 8 business hours.') }}</p>
          <a href="mailto:{{ setting('contact.email','support@cooca.io') }}" class="btn btn-outline btn-sm btn-block">{{ setting('contact.email','support@cooca.io') }}</a>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 reveal rv-delay-2">
        <div class="channel-card">
          <div class="channel-icon"><i class="bi bi-calendar-check"></i></div>
          <div class="channel-title">{{ __('Book a Demo') }}</div>
          <p style="font-size:.85rem;margin-bottom:16px;flex-grow:1;">{{ __('30-minute live walkthrough of your solution.') }}</p>
          <a href="{{ route('customer.register') }}" class="btn btn-outline btn-sm btn-block">{{ __('Book Demo') }}</a>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 reveal rv-delay-3">
        <div class="channel-card">
          <div class="channel-icon"><i class="bi bi-headset"></i></div>
          <div class="channel-title">{{ __('Support') }}</div>
          <p style="font-size:.85rem;margin-bottom:16px;flex-grow:1;">{{ __('Knowledge base and ticket system.') }}</p>
          <a href="{{ route('faq') }}" class="btn btn-outline btn-sm btn-block">{{ __('Help Center') }}</a>
        </div>
      </div>
    </div>

    <div class="row g-5 align-items-start">
      <div class="col-lg-7 reveal">
        <div class="card" style="border-radius:var(--radius-lg);padding:40px;">
          <h3 style="font-size:1.4rem;margin-bottom:8px;">{{ __('Send Us a Message') }}</h3>
          <p style="font-size:.9rem;margin-bottom:28px;">{{ __('Fill out the form and we\'ll route it to the right person.') }}</p>
          <form id="contactForm" novalidate>
            <div class="row g-3">
              <div class="col-md-6"><div class="form-group"><label class="form-label">{{ __('Full Name') }} *</label><input type="text" class="form-control" placeholder="Ahmad Kurniawan" required></div></div>
              <div class="col-md-6"><div class="form-group"><label class="form-label">{{ __('Email Address') }} *</label><input type="email" class="form-control" placeholder="ahmad@company.com" required></div></div>
              <div class="col-md-6"><div class="form-group"><label class="form-label">{{ __('Phone / WhatsApp') }}</label><input type="tel" class="form-control" placeholder="+62 812 3456 7890"></div></div>
              <div class="col-md-6"><div class="form-group"><label class="form-label">{{ __('Company Name') }}</label><input type="text" class="form-control" placeholder="RetailMax Indonesia"></div></div>
              <div class="col-12"><div class="form-group"><label class="form-label">{{ __('Industry') }} *</label><select class="form-select" required><option value="" disabled selected>{{ __('Select your industry') }}</option><option>{{ __('Retail') }}</option><option>{{ __('Restaurant & F&B') }}</option><option>{{ __('Hotel & Hospitality') }}</option><option>{{ __('Clinic & Healthcare') }}</option><option>{{ __('Education') }}</option><option>{{ __('Other') }}</option></select></div></div>
              <div class="col-12"><div class="form-group"><label class="form-label">{{ __('Your Message') }} *</label><textarea class="form-control" placeholder="{{ __('Tell us about your business and what you\'re looking for...') }}" required></textarea></div></div>
              <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block btn-lg">{{ __('Send Message') }} <i class="bi bi-send-fill"></i></button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="col-lg-5 reveal rv-delay-2">
        <div class="section-label mb-4"><i class="bi bi-geo-alt-fill"></i> {{ __('Find Us') }}</div>
        <div class="d-flex flex-column gap-3">
          <div class="contact-info-item"><div class="ci-icon"><i class="bi bi-geo-alt-fill"></i></div><div><div class="ci-title">{{ __('Headquarters') }}</div><div class="ci-text">{!! __(setting('contact.address','Jl. Jend. Sudirman Kav. 52–53<br>Jakarta Selatan 12190, Indonesia')) !!}</div></div></div>
          <div class="contact-info-item"><div class="ci-icon"><i class="bi bi-envelope-fill"></i></div><div><div class="ci-title">{{ __('Email') }}</div><div class="ci-text">{{ setting('contact.email','support@cooca.io') }}<br>sales@cooca.io</div></div></div>
          <div class="contact-info-item"><div class="ci-icon"><i class="bi bi-clock"></i></div><div><div class="ci-title">{{ __('Business Hours') }}</div><div class="ci-text">{{ __('Mon–Fri: 09:00–18:00 WIB') }}<br>{{ __('Sat: 09:00–14:00 WIB') }}</div></div></div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection`,
);

// ===================== SOLUTIONS PAGE =====================
write(
    "resources/views/pages/solutions/index.blade.php",
    `@extends('layouts.guest')
@section('content')
${innerPageHero(
    "{!! __('Solutions <span class=\"text-gradient\">by Industry.</span>') !!}",
    __(
        "Pre-configured business management systems for 9+ industries. Your industry template is ready in 30 minutes.",
    ),
    __("Solutions"),
)}
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
      <h2 class="section-title">{{ __('Don\'t See <span class="text-gradient">Your Industry?</span>') }}</h2>
      <p class="section-subtitle">{{ __('We build custom solutions too. Tell us what you need and we\'ll configure COOCA for your specific industry.') }}</p>
      <a href="{{ route('contact') }}" class="btn btn-primary btn-lg">{{ __('Contact Sales') }} <i class="bi bi-chat-dots"></i></a>
    </div>
  </div>
</section>
@endsection`,
);

// ===================== FAQ PAGE =====================
write(
    "resources/views/pages/faq/index.blade.php",
    `@extends('layouts.guest')
@section('content')
${innerPageHero(
    "{!! __('Frequently Asked <span class=\"text-gradient\">Questions.</span>') !!}",
    __(
        "Everything you need to know about COOCA, our licensing model, infrastructure, and how we serve your business.",
    ),
    __("FAQ"),
)}
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
      <h2 class="section-title">{{ __('Still Have <span class="text-gradient">Questions?</span>') }}</h2>
      <p class="section-subtitle">{{ __('Our team is ready to answer any specific questions about your industry and requirements.') }}</p>
      <a href="{{ route('contact') }}" class="btn btn-primary btn-lg">{{ __('Contact Us') }} <i class="bi bi-chat-dots"></i></a>
    </div>
  </div>
</section>
@endsection`,
);

// ===================== DOCS PAGE =====================
write(
    "resources/views/pages/docs/index.blade.php",
    `@extends('layouts.guest')
@section('content')
${innerPageHero(
    "{!! __('Documentation & <span class=\"text-gradient\">Resources.</span>') !!}",
    __(
        "Technical documentation, API references, implementation guides, and best practices for getting the most out of COOCA.",
    ),
    __("Documentation"),
)}
<section class="section">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4 col-md-6 reveal">
        <div class="card card-3d">
          <div class="card-icon"><i class="bi bi-book"></i></div>
          <h3 class="card-title">{{ __('Getting Started') }}</h3>
          <p class="card-desc">{{ __('Quick start guide covering initial setup, first-time configuration, and onboarding your team.') }}</p>
          <a href="#" class="btn btn-outline btn-sm">{{ __('Read Guide') }} <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 reveal rv-delay-1">
        <div class="card card-3d">
          <div class="card-icon"><i class="bi bi-code-slash"></i></div>
          <h3 class="card-title">{{ __('API Reference') }}</h3>
          <p class="card-desc">{{ __('REST API documentation for integrating COOCA with your existing tools and workflows.') }}</p>
          <a href="#" class="btn btn-outline btn-sm">{{ __('Explore API') }} <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 reveal rv-delay-2">
        <div class="card card-3d">
          <div class="card-icon"><i class="bi bi-gear"></i></div>
          <h3 class="card-title">{{ __('Module Guides') }}</h3>
          <p class="card-desc">{{ __('In-depth documentation for each module: POS, Inventory, CRM, Accounting, HRIS, and more.') }}</p>
          <a href="#" class="btn btn-outline btn-sm">{{ __('View Modules') }} <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 reveal">
        <div class="card card-3d">
          <div class="card-icon"><i class="bi bi-shield-check"></i></div>
          <h3 class="card-title">{{ __('Security & Compliance') }}</h3>
          <p class="card-desc">{{ __('Learn about COOCA\'s security architecture, encryption standards, and compliance certifications.') }}</p>
          <a href="#" class="btn btn-outline btn-sm">{{ __('Learn More') }} <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 reveal rv-delay-1">
        <div class="card card-3d">
          <div class="card-icon"><i class="bi bi-cloud-arrow-down"></i></div>
          <h3 class="card-title">{{ __('Migration Guide') }}</h3>
          <p class="card-desc">{{ __('Step-by-step guide for migrating your data from legacy systems, spreadsheets, or other platforms.') }}</p>
          <a href="#" class="btn btn-outline btn-sm">{{ __('Start Migration') }} <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 reveal rv-delay-2">
        <div class="card card-3d">
          <div class="card-icon"><i class="bi bi-question-circle"></i></div>
          <h3 class="card-title">{{ __('Troubleshooting') }}</h3>
          <p class="card-desc">{{ __('Common issues and solutions, error code references, and diagnostic tools documentation.') }}</p>
          <a href="#" class="btn btn-outline btn-sm">{{ __('View Guide') }} <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection`,
);

// ===================== AFFILIATE PAGE =====================
write(
    "resources/views/pages/affiliate/index.blade.php",
    `@extends('layouts.guest')
@section('content')
${innerPageHero(
    "{!! __('Earn <span class=\"text-gradient\">While You Refer.</span>') !!}",
    __(
        "Join the COOCA Affiliate Program. Refer businesses and earn generous commissions on every lifetime license sale.",
    ),
    __("Affiliate Program"),
)}
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
@endsection`,
);

// ===================== LEGAL PAGES =====================
write(
    "resources/views/pages/legal/terms.blade.php",
    `@extends('layouts.guest')
@section('content')
${innerPageHero("{!! __('Terms of <span class=\"text-gradient\">Service.</span>') !!}", __("Last updated: January 2024. Please read these terms carefully before using our services."), __("Terms of Service"))}
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
          <p>{{ __('COOCA\'s liability is limited to the amount paid for the license. We are not liable for indirect, incidental, or consequential damages arising from the use of our software.') }}</p>
          <h3 class="mt-4">7. {{ __('Contact') }}</h3>
          <p>{{ __('For questions about these terms, contact us at legal@cooca.io.') }}</p>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection`,
);

write(
    "resources/views/pages/legal/privacy.blade.php",
    `@extends('layouts.guest')
@section('content')
${innerPageHero("{!! __('Privacy <span class=\"text-gradient\">Policy.</span>') !!}", __("Last updated: January 2024. We take your privacy and data protection seriously."), __("Privacy Policy"))}
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
@endsection`,
);

// ===================== AUTH PAGES =====================
write(
    "resources/views/auth/customer/login.blade.php",
    `@extends('layouts.guest')
@section('content')
<div class="auth-layout">
  <div class="auth-left auth-panel">
    <div class="hero-orb hero-orb-1" style="width:500px;height:500px;top:-150px;right:-100px;"></div>
    <div class="hero-orb hero-orb-2" style="width:300px;height:300px;bottom:-80px;left:-60px;"></div>
    <div class="grid-bg"></div>
    <div class="auth-left-content">
      <div class="d-flex align-items-center justify-content-center gap-3 mb-5">
        <div class="brand-icon">C</div>
        <span style="font-size:1.8rem;font-weight:800;">{{ setting('site.name','COOCA') }}</span>
      </div>
      <h2 style="font-size:2rem;font-weight:800;line-height:1.2;margin-bottom:16px;">{{ __('Your Business Runs Better When You') }} <span class="text-gradient">{{ __('Own the System.') }}</span></h2>
      <p style="font-size:.95rem;color:var(--text-muted);">{{ __('Welcome back. Your isolated business infrastructure is ready.') }}</p>
      <div class="d-flex flex-column gap-3 mt-5 text-start">
        <div class="auth-trust-item"><div class="auth-trust-icon"><i class="bi bi-shield-lock-fill"></i></div><div style="font-size:.82rem;"><strong style="color:var(--text);">{{ __('Isolated Environment') }}</strong><br><span style="color:var(--text-muted);">{{ __('Your data, your system. Zero cross-tenant risk.') }}</span></div></div>
        <div class="auth-trust-item"><div class="auth-trust-icon"><i class="bi bi-lightning-charge-fill"></i></div><div style="font-size:.82rem;"><strong style="color:var(--text);">{{ __('Always On') }}</strong><br><span style="color:var(--text-muted);">{{ __('99.9% uptime SLA. Business doesn\'t wait.') }}</span></div></div>
      </div>
    </div>
  </div>
  <div class="auth-right auth-panel">
    <div class="auth-form-panel">
      <div class="d-flex align-items-center gap-3 d-md-none mb-4"><div class="brand-icon">C</div><span style="font-size:1.6rem;font-weight:800;">{{ setting('site.name','COOCA') }}</span></div>
      <div class="form-title" style="font-size:1.7rem;font-weight:800;margin-bottom:4px;">{{ __('Welcome back') }}</div>
      <p class="mb-4" style="font-size:.9rem;">{{ __('Log in to your COOCA dashboard.') }} <a href="{{ route('customer.register') }}">{{ __('No account? Start free →') }}</a></p>

      <a href="{{ route('customer.auth.google') }}" class="social-btn">
        <svg width="18" height="18" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
        {{ __('Continue with Google') }}
      </a>

      <div class="divider" style="display:flex;align-items:center;gap:12px;margin:20px 0;"><div style="flex:1;height:1px;background:var(--border);"></div><span style="font-size:.78rem;color:var(--text-muted);">or continue with email</span><div style="flex:1;height:1px;background:var(--border);"></div></div>

      @if ($errors->any())
      <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill"></i> {{ $errors->first() }}</div>
      @endif

      <form action="{{ route('customer.login.submit') }}" method="POST" id="loginForm">
        @csrf
        <div class="form-group">
          <label class="form-label">{{ __('Email Address') }}</label>
          <div class="input-icon-wrap">
            <i class="bi bi-envelope input-icon"></i>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="you@company.com" required autocomplete="email">
          </div>
        </div>
        <div class="form-group">
          <div style="display:flex;justify-content:space-between;align-items:center;">
            <label class="form-label">{{ __('Password') }}</label>
            <a href="{{ route('customer.password.request') }}" style="font-size:.82rem;">{{ __('Forgot password?') }}</a>
          </div>
          <div class="input-icon-wrap">
            <i class="bi bi-lock input-icon"></i>
            <input type="password" name="password" class="form-control" placeholder="Your password" required autocomplete="current-password">
          </div>
        </div>
        <div class="form-check mb-3">
          <input type="checkbox" name="remember" id="rememberMe" class="form-check-input">
          <label for="rememberMe" class="form-check-label">{{ __('Keep me logged in for 30 days') }}</label>
        </div>
        <button type="submit" class="btn btn-primary btn-block btn-lg">{{ __('Log In to Dashboard') }} <i class="bi bi-arrow-right"></i></button>
      </form>

      <p style="text-align:center;font-size:.82rem;margin-top:24px;">{{ __('By logging in, you agree to our') }} <a href="{{ route('terms') }}">{{ __('Terms of Service') }}</a> {{ __('and') }} <a href="{{ route('privacy') }}">{{ __('Privacy Policy') }}</a>.</p>
      <p style="text-align:center;font-size:.9rem;margin-top:12px;">{{ __('Don\'t have an account?') }} <a href="{{ route('customer.register') }}" style="font-weight:700;">{{ __('Start 30-day free trial →') }}</a></p>
    </div>
  </div>
</div>
@endsection`,
);

write(
    "resources/views/auth/customer/register.blade.php",
    `@extends('layouts.guest')
@push('scripts')
<script>
function selectIndustry(el, name) { document.querySelectorAll('.industry-card').forEach(function(c){c.classList.remove('selected')}); el.classList.add('selected'); window.selectedIndustry = name; }
function goStep(n) {
  document.querySelectorAll('.step-page').forEach(function(p){p.classList.remove('active')});
  document.getElementById('step'+n).classList.add('active');
  var dots = [document.getElementById('dot1'),document.getElementById('dot2'),document.getElementById('dot3')];
  dots.forEach(function(d,i){ if(d) d.className = 'step-dot' + (i+1 < n ? ' done' : i+1===n ? ' active' : ''); });
  var pcts = {'1':'33.3%','2':'66.6%','3':'100%'};
  var fill = document.getElementById('progressFill'); if(fill) fill.style.width = pcts[n];
  window.scrollTo({top:0,behavior:'smooth'});
}
document.addEventListener('DOMContentLoaded',function(){
  var pwTgl=document.getElementById('regPwToggle'),pwFld=document.getElementById('regPassword'),pwIco=document.getElementById('regPwIcon');
  if(pwTgl&&pwFld){pwTgl.addEventListener('click',function(){var p=pwFld.type==='password';pwFld.type=p?'text':'password';pwIco.className='bi '+(p?'bi-eye-slash':'bi-eye')})}
  var pw=document.getElementById('regPassword');if(pw){pw.addEventListener('input',function(){var v=this.value;var bars=[document.getElementById('b1'),document.getElementById('b2'),document.getElementById('b3'),document.getElementById('b4')];var lbl=document.getElementById('pwLabel');bars.forEach(function(b){if(b)b.className='pw-bar'});if(!v.length){if(lbl)lbl.textContent='Enter a password';return}var sc=0;if(v.length>=8)sc++;if(v.length>=12)sc++;if(/[A-Z]/.test(v)&&/[0-9]/.test(v))sc++;if(/[^A-Za-z0-9]/.test(v))sc++;var st=Math.min(4,Math.max(1,sc));var cls=['weak','fair','good','strong'];var lls=['Weak','Fair','Good','Strong'];for(var i=0;i<st;i++){if(bars[i])bars[i].className='pw-bar '+cls[st-1]}if(lbl)lbl.textContent=lls[st-1]||'Weak'})}
});
</script>
@endpush
@section('content')
<div class="auth-layout">
  <div class="auth-left auth-panel">
    <div class="hero-orb hero-orb-1" style="width:500px;height:500px;top:-150px;right:-100px;"></div>
    <div class="hero-orb hero-orb-2" style="width:300px;height:300px;bottom:-80px;left:-60px;"></div>
    <div class="grid-bg"></div>
    <div class="auth-left-content">
      <div class="d-flex align-items-center justify-content-center gap-3 mb-5"><div class="brand-icon">C</div><span style="font-size:1.8rem;font-weight:800;">{{ setting('site.name','COOCA') }}</span></div>
      <h2>{{ __('Your Business System Will Be Live in') }} <span class="text-gradient">{{ __('30 Minutes.') }}</span></h2>
      <p class="mt-3" style="font-size:.95rem;color:var(--text-muted);">{{ __('Sign up now. Get full access. No credit card, no commitment.') }}</p>
      <div class="d-flex flex-column gap-3 mt-5 text-start">
        <div class="auth-trust-item"><div class="auth-trust-icon"><i class="bi bi-check-circle-fill"></i></div><div style="font-size:.82rem;"><strong style="color:var(--text);">{{ __('30-day full access') }}</strong><br><span style="color:var(--text-muted);">{{ __('All 10 modules, unlimited users') }}</span></div></div>
        <div class="auth-trust-item"><div class="auth-trust-icon"><i class="bi bi-shield-lock-fill"></i></div><div style="font-size:.82rem;"><strong style="color:var(--text);">{{ __('Isolated infrastructure') }}</strong><br><span style="color:var(--text-muted);">{{ __('Provisioned in 30 min, zero risk') }}</span></div></div>
        <div class="auth-trust-item"><div class="auth-trust-icon"><i class="bi bi-graph-up-arrow"></i></div><div style="font-size:.82rem;"><strong style="color:var(--text);">{{ __('9 industry configs') }}</strong><br><span style="color:var(--text-muted);">{{ __('Retail, restaurant, clinic, salon, etc.') }}</span></div></div>
      </div>
    </div>
  </div>
  <div class="auth-right auth-panel">
    <div class="auth-form-panel">
      <div class="d-md-none d-flex align-items-center gap-3 mb-4"><div class="brand-icon">C</div><span style="font-size:1.6rem;font-weight:800;">{{ setting('site.name','COOCA') }}</span></div>
      @if ($errors->any())
      <div class="alert alert-danger"><ul class="mb-0" style="padding-left:20px;">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
      @endif

      <div class="progress-bar-c"><div class="progress-fill" id="progressFill" style="width:33.3%"></div></div>
      <div class="step-nav"><div class="step-dot active" id="dot1"></div><div class="step-dot" id="dot2"></div><div class="step-dot" id="dot3"></div></div>

      <form action="{{ route('customer.register.submit') }}" method="POST" id="regForm">
        @csrf
        <div class="step-page active" id="step1">
          <div class="form-title" style="font-size:1.7rem;font-weight:800;">{{ __('Create your account') }}</div>
          <p style="font-size:.9rem;margin-bottom:24px;">{{ __('Step 1 of 3 · Start your 30-day free trial.') }} <a href="{{ route('customer.login') }}">{{ __('Already have one?') }}</a></p>
          <div class="form-group"><label class="form-label">{{ __('Full Name') }}</label><div class="input-icon-wrap"><i class="bi bi-person input-icon"></i><input type="text" name="name" class="form-control" placeholder="Ahmad Kurniawan" value="{{ old('name') }}" required></div></div>
          <div class="form-group"><label class="form-label">{{ __('Work Email') }}</label><div class="input-icon-wrap"><i class="bi bi-envelope input-icon"></i><input type="email" name="email" class="form-control" id="regEmail" placeholder="you@company.com" value="{{ old('email') }}" required></div></div>
          <div class="form-group">
            <label class="form-label">{{ __('Password') }}</label>
            <div class="input-icon-wrap">
              <i class="bi bi-lock input-icon"></i>
              <input type="password" name="password" class="form-control" id="regPassword" placeholder="Min. 8 characters" required>
              <button type="button" class="input-toggle" id="regPwToggle" data-target="#regPassword"><i class="bi bi-eye" id="regPwIcon"></i></button>
            </div>
            <div class="pw-strength"><div class="pw-bars"><div class="pw-bar" id="b1"></div><div class="pw-bar" id="b2"></div><div class="pw-bar" id="b3"></div><div class="pw-bar" id="b4"></div></div><div class="pw-label" id="pwLabel" style="font-size:.75rem;color:var(--text-muted);">Enter a password</div></div>
          </div>
          <div class="form-group"><label class="form-label">{{ __('Confirm Password') }}</label><div class="input-icon-wrap"><i class="bi bi-lock input-icon"></i><input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required></div></div>
          <div class="form-check mb-3"><input type="checkbox" id="terms" class="form-check-input" required><label for="terms" class="form-check-label">{{ __('I agree to COOCA\'s') }} <a href="{{ route('terms') }}">{{ __('Terms') }}</a> {{ __('and') }} <a href="{{ route('privacy') }}">{{ __('Privacy Policy') }}</a></label></div>
          <button type="button" class="btn btn-primary btn-block btn-lg" onclick="goStep(2)">{{ __('Continue') }} <i class="bi bi-arrow-right"></i></button>
        </div>

        <div class="step-page" id="step2">
          <div class="form-title" style="font-size:1.7rem;font-weight:800;">{{ __('Tell us about your business') }}</div>
          <p style="font-size:.9rem;margin-bottom:24px;">{{ __('Step 2 of 3 · Industry & business details.') }}</p>
          <div class="form-group"><label class="form-label">{{ __('Business Name (Optional)') }}</label><div class="input-icon-wrap"><i class="bi bi-building input-icon"></i><input type="text" name="business_name" class="form-control" placeholder="RetailMax Indonesia" value="{{ old('business_name') }}"></div></div>
          <div class="form-group"><label class="form-label">{{ __('Referral Code (Optional)') }}</label><div class="input-icon-wrap"><i class="bi bi-person-bounding-box input-icon"></i><input type="text" name="referral_code" class="form-control" placeholder="Affiliator Code" value="{{ old('referral_code') }}"></div></div>
          <div class="form-group"><label class="form-label">{{ __('Choose Your Industry') }}</label>
            <div class="industry-grid">
              <div class="industry-card" onclick="selectIndustry(this,'Retail')"><span class="ic-icon">🛍️</span><span class="ic-label">Retail</span></div>
              <div class="industry-card" onclick="selectIndustry(this,'Restaurant')"><span class="ic-icon">🍴</span><span class="ic-label">Restaurant</span></div>
              <div class="industry-card" onclick="selectIndustry(this,'Hotel')"><span class="ic-icon">🏨</span><span class="ic-label">Hotel</span></div>
              <div class="industry-card" onclick="selectIndustry(this,'Clinic')"><span class="ic-icon">🏥</span><span class="ic-label">Clinic</span></div>
              <div class="industry-card" onclick="selectIndustry(this,'Education')"><span class="ic-icon">🎓</span><span class="ic-label">Education</span></div>
              <div class="industry-card" onclick="selectIndustry(this,'Salon')"><span class="ic-icon">✂️</span><span class="ic-label">Salon</span></div>
            </div>
          </div>
          <div style="display:flex;gap:12px;">
            <button type="button" class="btn btn-outline" style="flex:1;" onclick="goStep(1)"><i class="bi bi-arrow-left"></i> {{ __('Back') }}</button>
            <button type="button" class="btn btn-primary" style="flex:1;" onclick="goStep(3)">{{ __('Continue') }} <i class="bi bi-arrow-right"></i></button>
          </div>
        </div>

        <div class="step-page" id="step3">
          <div class="form-title" style="font-size:1.7rem;font-weight:800;">{{ __('You\'re almost there!') }}</div>
          <p style="font-size:.9rem;margin-bottom:24px;">{{ __('Step 3 of 3 · Confirm and launch.') }}</p>
          <div class="card mb-4" style="border:1px solid var(--border);padding:20px;border-radius:var(--radius-sm);">
            <div style="font-weight:700;text-transform:uppercase;font-size:.75rem;letter-spacing:.06em;margin-bottom:14px;color:var(--text-muted);">{{ __('Your Setup Summary') }}</div>
            <div class="d-flex flex-column gap-2">
              <div class="d-flex justify-content-between"><span class="text-muted">{{ __('Plan') }}</span><span style="color:var(--success);" class="fw-bold">{{ __('30-Day Free Trial') }}</span></div>
              <div class="d-flex justify-content-between"><span class="text-muted">{{ __('Modules') }}</span><span>{{ __('All 10 included') }}</span></div>
              <div class="d-flex justify-content-between"><span class="text-muted">{{ __('Users') }}</span><span>{{ __('Unlimited') }}</span></div>
              <div class="d-flex justify-content-between"><span class="text-muted">{{ __('Credit Card') }}</span><span style="color:var(--success);">{{ __('Not Required') }}</span></div>
            </div>
          </div>
          <div style="display:flex;gap:12px;">
            <button type="button" class="btn btn-outline" style="flex:1;" onclick="goStep(2)"><i class="bi bi-arrow-left"></i> {{ __('Back') }}</button>
            <button type="submit" class="btn btn-success" style="flex:1;">{{ __('Launch My Free Trial') }} <i class="bi bi-rocket-takeoff-fill"></i></button>
          </div>
          <p class="text-center mt-3" style="font-size:.78rem;">{{ __('By signing up, you agree to our') }} <a href="{{ route('terms') }}">{{ __('Terms') }}</a> {{ __('and') }} <a href="{{ route('privacy') }}">{{ __('Privacy Policy') }}</a>.</p>
        </div>
      </form>
      <p class="text-center mt-4">{{ __('Already have an account?') }} <a href="{{ route('customer.login') }}" class="fw-bold">{{ __('Log in →') }}</a></p>
    </div>
  </div>
</div>
@endsection`,
);

// ===================== PASSWORD FORGOT =====================
const forgotPasswordTemplate = (type, routeName) => `@extends('layouts.guest')
@section('content')
<div class="auth-layout">
  <div class="auth-left auth-panel">
    <div class="hero-orb hero-orb-1" style="width:500px;height:500px;top:-150px;right:-100px;"></div>
    <div class="hero-orb hero-orb-2" style="width:300px;height:300px;bottom:-80px;left:-60px;"></div>
    <div class="grid-bg"></div>
    <div class="auth-left-content">
      <div class="d-flex align-items-center justify-content-center gap-3 mb-5"><div class="brand-icon">C</div><span style="font-size:1.8rem;font-weight:800;">{{ setting('site.name','COOCA') }}</span></div>
      <h2>{{ __('Secure <span class="text-gradient">${type}</span> Recovery') }}</h2>
      <p style="font-size:.95rem;color:var(--text-muted);">{{ __('We\'ll send a reset link to your registered email.') }}</p>
    </div>
  </div>
  <div class="auth-right auth-panel">
    <div class="auth-form-panel">
      <div class="form-title" style="font-size:1.7rem;font-weight:800;margin-bottom:4px;">{{ __('Reset Password') }}</div>
      <p class="mb-4" style="font-size:.9rem;">{{ __('Enter your email to receive a password reset link.') }}</p>

      @if (session('success'))
      <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
      @endif
      @if ($errors->any())
      <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill"></i> {{ $errors->first() }}</div>
      @endif

      <form action="{{ route('${routeName}') }}" method="POST">
        @csrf
        <div class="form-group">
          <label class="form-label">{{ __('Email Address') }}</label>
          <div class="input-icon-wrap">
            <i class="bi bi-envelope input-icon"></i>
            <input type="email" name="email" class="form-control" placeholder="you@company.com" value="{{ old('email') }}" required>
          </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block btn-lg">{{ __('Send Reset Link') }} <i class="bi bi-send"></i></button>
      </form>
      <p class="text-center mt-4"><a href="{{ route('${type.toLowerCase()}.login') }}"><i class="bi bi-arrow-left"></i> {{ __('Back to Login') }}</a></p>
    </div>
  </div>
</div>
@endsection`;

write(
    "resources/views/auth/customer/forgot-password.blade.php",
    forgotPasswordTemplate("Customer", "customer.password.request"),
);
write(
    "resources/views/auth/affiliator/forgot-password.blade.php",
    forgotPasswordTemplate("Affiliator", "affiliator.password.request"),
);
write(
    "resources/views/auth/admin/forgot-password.blade.php",
    forgotPasswordTemplate("Admin", "admin.password.request"),
);

// ===================== PASSWORD RESET =====================
const resetPasswordTemplate = (type, routeName) => `@extends('layouts.guest')
@section('content')
<div class="auth-layout">
  <div class="auth-left auth-panel">
    <div class="hero-orb hero-orb-1" style="width:500px;height:500px;top:-150px;right:-100px;"></div>
    <div class="hero-orb hero-orb-2" style="width:300px;height:300px;bottom:-80px;left:-60px;"></div>
    <div class="grid-bg"></div>
    <div class="auth-left-content">
      <div class="d-flex align-items-center justify-content-center gap-3 mb-5"><div class="brand-icon">C</div><span style="font-size:1.8rem;font-weight:800;">{{ setting('site.name','COOCA') }}</span></div>
      <h2>{{ __('Create <span class="text-gradient">New Password</span>') }}</h2>
    </div>
  </div>
  <div class="auth-right auth-panel">
    <div class="auth-form-panel">
      <div class="form-title" style="font-size:1.7rem;font-weight:800;margin-bottom:4px;">{{ __('Set New Password') }}</div>
      <p class="mb-4" style="font-size:.9rem;">{{ __('Choose a strong password for your account.') }}</p>

      @if ($errors->any())
      <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill"></i> {{ $errors->first() }}</div>
      @endif

      <form action="{{ route('${routeName}') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email ?? old('email') }}">
        <div class="form-group">
          <label class="form-label">{{ __('New Password') }}</label>
          <div class="input-icon-wrap">
            <i class="bi bi-lock input-icon"></i>
            <input type="password" name="password" class="form-control" placeholder="Min. 8 characters" required>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">{{ __('Confirm Password') }}</label>
          <div class="input-icon-wrap">
            <i class="bi bi-lock input-icon"></i>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
          </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block btn-lg">{{ __('Reset Password') }} <i class="bi bi-shield-check"></i></button>
      </form>
      <p class="text-center mt-4"><a href="{{ route('${type.toLowerCase()}.login') }}"><i class="bi bi-arrow-left"></i> {{ __('Back to Login') }}</a></p>
    </div>
  </div>
</div>
@endsection`;

write(
    "resources/views/auth/customer/reset-password.blade.php",
    resetPasswordTemplate("Customer", "customer.password.update"),
);
write(
    "resources/views/auth/affiliator/reset-password.blade.php",
    resetPasswordTemplate("Affiliator", "affiliator.password.update"),
);
write(
    "resources/views/auth/admin/reset-password.blade.php",
    resetPasswordTemplate("Admin", "admin.password.update"),
);

// ===================== EMAIL VERIFY =====================
write(
    "resources/views/auth/customer/verify-email.blade.php",
    `@extends('layouts.guest')
@section('content')
<div class="auth-layout">
  <div class="auth-right auth-panel" style="grid-column:1/-1;">
    <div class="auth-form-panel text-center">
      <div class="brand-icon mx-auto mb-4" style="margin:0 auto;">C</div>
      <h2 style="font-size:1.7rem;font-weight:800;">{{ __('Verify Your Email') }}</h2>
      <p class="mb-4">{{ __('A verification link has been sent to your email address. Please check your inbox and click the link to activate your account.') }}</p>
      <form action="{{ route('customer.verification.send') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-outline">{{ __('Resend Verification Email') }} <i class="bi bi-envelope"></i></button>
      </form>
      <p class="mt-4" style="font-size:.85rem;">{{ __('If you didn\'t receive the email, check your spam folder or contact support.') }}</p>
    </div>
  </div>
</div>
@endsection`,
);

// ===================== AFFILIATOR & ADMIN LOGIN =====================
const authLoginTemplate = (type, prefix) => `@extends('layouts.guest')
@section('content')
<div class="auth-layout">
  <div class="auth-left auth-panel">
    <div class="hero-orb hero-orb-1" style="width:500px;height:500px;top:-150px;right:-100px;"></div>
    <div class="hero-orb hero-orb-2" style="width:300px;height:300px;bottom:-80px;left:-60px;"></div>
    <div class="grid-bg"></div>
    <div class="auth-left-content">
      <div class="d-flex align-items-center justify-content-center gap-3 mb-5"><div class="brand-icon">C</div><span style="font-size:1.8rem;font-weight:800;">{{ setting('site.name','COOCA') }}</span></div>
      <h2>{{ __('${type}') }} <span class="text-gradient">{{ __('Portal') }}</span></h2>
      <p style="font-size:.95rem;color:var(--text-muted);">{{ __('Secure access to your ${type.toLowerCase()} dashboard.') }}</p>
    </div>
  </div>
  <div class="auth-right auth-panel">
    <div class="auth-form-panel">
      <div class="form-title" style="font-size:1.7rem;font-weight:800;margin-bottom:4px;">{{ __('${type} Login') }}</div>
      <p class="mb-4" style="font-size:.9rem;">{{ __('Sign in to manage your ${type.toLowerCase()} account.') }}</p>

      @if ($errors->any())
      <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill"></i> {{ $errors->first() }}</div>
      @endif

      <form action="{{ route('${prefix}.login.submit') }}" method="POST">
        @csrf
        <div class="form-group">
          <label class="form-label">{{ __('Email Address') }}</label>
          <div class="input-icon-wrap">
            <i class="bi bi-envelope input-icon"></i>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="you@company.com" required autocomplete="email">
          </div>
        </div>
        <div class="form-group">
          <div style="display:flex;justify-content:space-between;align-items:center;">
            <label class="form-label">{{ __('Password') }}</label>
            <a href="{{ route('${prefix}.password.request') }}" style="font-size:.82rem;">{{ __('Forgot password?') }}</a>
          </div>
          <div class="input-icon-wrap">
            <i class="bi bi-lock input-icon"></i>
            <input type="password" name="password" class="form-control" placeholder="Your password" required autocomplete="current-password">
          </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block btn-lg">{{ __('Log In') }} <i class="bi bi-arrow-right"></i></button>
      </form>
    </div>
  </div>
</div>
@endsection`;

write(
    "resources/views/auth/affiliator/login.blade.php",
    authLoginTemplate("Affiliator", "affiliator"),
);
write(
    "resources/views/auth/admin/login.blade.php",
    authLoginTemplate("Admin", "admin"),
);

// ===================== AFFILIATOR REGISTER =====================
write(
    "resources/views/auth/affiliator/register.blade.php",
    `@extends('layouts.guest')
@section('content')
<div class="auth-layout">
  <div class="auth-left auth-panel">
    <div class="hero-orb hero-orb-1" style="width:500px;height:500px;top:-150px;right:-100px;"></div>
    <div class="hero-orb hero-orb-2" style="width:300px;height:300px;bottom:-80px;left:-60px;"></div>
    <div class="grid-bg"></div>
    <div class="auth-left-content">
      <div class="d-flex align-items-center justify-content-center gap-3 mb-5"><div class="brand-icon">C</div><span style="font-size:1.8rem;font-weight:800;">{{ setting('site.name','COOCA') }}</span></div>
      <h2>{{ __('Start <span class="text-gradient">Earning</span> Today') }}</h2>
      <p style="font-size:.95rem;color:var(--text-muted);">{{ __('Join the affiliate program and earn up to 20% commission per sale.') }}</p>
    </div>
  </div>
  <div class="auth-right auth-panel">
    <div class="auth-form-panel">
      <div class="form-title" style="font-size:1.7rem;font-weight:800;margin-bottom:4px;">{{ __('Affiliate Registration') }}</div>
      <p class="mb-4" style="font-size:.9rem;">{{ __('Create your affiliate account.') }} <a href="{{ route('affiliator.login') }}">{{ __('Already registered? →') }}</a></p>

      @if ($errors->any())
      <div class="alert alert-danger"><ul class="mb-0" style="padding-left:20px;">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
      @endif

      <form action="{{ route('affiliator.register.submit') }}" method="POST">
        @csrf
        <div class="form-group"><label class="form-label">{{ __('Full Name') }}</label><div class="input-icon-wrap"><i class="bi bi-person input-icon"></i><input type="text" name="name" class="form-control" placeholder="Your name" value="{{ old('name') }}" required></div></div>
        <div class="form-group"><label class="form-label">{{ __('Email Address') }}</label><div class="input-icon-wrap"><i class="bi bi-envelope input-icon"></i><input type="email" name="email" class="form-control" placeholder="you@email.com" value="{{ old('email') }}" required></div></div>
        <div class="form-group"><label class="form-label">{{ __('Password') }}</label><div class="input-icon-wrap"><i class="bi bi-lock input-icon"></i><input type="password" name="password" class="form-control" placeholder="Min. 8 characters" required></div></div>
        <div class="form-group"><label class="form-label">{{ __('Confirm Password') }}</label><div class="input-icon-wrap"><i class="bi bi-lock input-icon"></i><input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required></div></div>
        <button type="submit" class="btn btn-primary btn-block btn-lg">{{ __('Register') }} <i class="bi bi-arrow-right"></i></button>
      </form>
      <p class="text-center mt-4">{{ __('Already have an account?') }} <a href="{{ route('affiliator.login') }}" class="fw-bold">{{ __('Log in →') }}</a></p>
    </div>
  </div>
</div>
@endsection`,
);

// ===================== PRODUCTS INDEX =====================
write(
    "resources/views/pages/products/index.blade.php",
    `@extends('layouts.guest')
@section('content')
${innerPageHero(
    "{!! __('Product <span class=\"text-gradient\">Catalog.</span>') !!}",
    __(
        "Explore our complete range of business management software solutions. Lifetime license. All modules included.",
    ),
    __("Products"),
)}
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
                {{ __('From') }} {{ \App\Helpers\setting('currency.symbol','Rp') }} {{ number_format($product->subscriptionPlans->min('price'),0,',','.') }}
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
@endsection`,
);

// ===================== PRODUCT DETAIL =====================
write(
    "resources/views/pages/products/detail.blade.php",
    `@extends('layouts.guest')
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
<section class="page-hero"><div class="container text-center"><h1>{{ __('Product Not Found') }}</h1><p>{{ __('The product you\'re looking for doesn\'t exist or has been removed.') }}</p></div></section>
@endif
@endsection`,
);

// ===================== BLOG INDEX =====================
write(
    "resources/views/pages/blog/index.blade.php",
    `@extends('layouts.guest')
@section('content')
${innerPageHero(
    "{!! __('Insights & <span class=\"text-gradient\">Resources.</span>') !!}",
    __(
        "Business tips, product updates, industry insights, and guides to help you get the most out of COOCA.",
    ),
    __("Blog"),
)}
<section class="section">
  <div class="container">
    @if(isset($posts) && count($posts))
    <div class="row g-4">
      @foreach($posts as $post)
      <div class="col-lg-4 col-md-6 reveal">
        <div class="card card-3d">
          @if($post->featured_image)
          <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}" style="width:100%;height:200px;object-fit:cover;border-radius:var(--radius-sm);margin-bottom:16px;">
          @endif
          @if($post->category)
          <div class="badge-glow mb-2">{{ $post->category }}</div>
          @endif
          <h3 class="card-title" style="font-size:1.05rem;"><a href="{{ route('blog.show', $post->slug) }}" style="color:var(--text);">{{ $post->title }}</a></h3>
          <p class="card-desc">{{ Str::limit(strip_tags($post->excerpt ?? $post->content), 100) }}</p>
          <div class="d-flex justify-content-between align-items-center" style="font-size:0.8rem;color:var(--text-muted);">
            <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : '' }}</span>
            <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-outline btn-sm">{{ __('Read More') }}</a>
          </div>
        </div>
      </div>
      @endforeach
    </div>
    @if(method_exists($posts, 'links'))
    <div class="pagination-c mt-5">{{ $posts->links() }}</div>
    @endif
    @else
    <div class="text-center py-5 reveal">
      <div class="empty-state-icon"><i class="bi bi-journal-text"></i></div>
      <h4>{{ __('No Posts Yet') }}</h4>
      <p>{{ __('Blog posts are coming soon. Check back for business insights and product updates.') }}</p>
    </div>
    @endif
  </div>
</section>
@endsection`,
);

// ===================== BLOG DETAIL =====================
write(
    "resources/views/pages/blog/detail.blade.php",
    `@extends('layouts.guest')
@section('content')
@if(isset($post))
<section class="page-hero">
  <div class="page-hero-orb page-hero-orb-1"></div>
  <div class="page-hero-orb page-hero-orb-2"></div>
  <div class="grid-bg"></div>
  <div class="container" style="position:relative;z-index:2;">
    <div class="row justify-content-center">
      <div class="col-lg-8 text-center reveal">
        @if($post->category)
        <div class="badge-glow mb-4">{{ $post->category }}</div>
        @endif
        <h1 class="hero-title">{{ $post->title }}</h1>
        <p class="hero-subtitle">{{ $post->excerpt ?? Str::limit(strip_tags($post->content), 150) }}</p>
        <div style="font-size:0.85rem;color:var(--text-muted);">
          <span>{{ __('Published') }}: {{ $post->published_at ? $post->published_at->format('F j, Y') : '' }}</span>
          @if($post->author)<span class="mx-2">·</span><span>{{ $post->author->name ?? '' }}</span>@endif
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 reveal">
        <div class="card" style="border-radius:var(--radius-lg);padding:40px;">
          <div style="font-size:1rem;line-height:1.8;color:var(--text-secondary);">
            {!! $post->content !!}
          </div>
        </div>

        @if(isset($relatedPosts) && count($relatedPosts))
        <div class="mt-5">
          <h3 class="mb-4">{{ __('Related Articles') }}</h3>
          <div class="row g-3">
            @foreach($relatedPosts->take(3) as $related)
            <div class="col-md-4">
              <div class="card card-3d" style="padding:20px;">
                @if($related->category)<div class="badge-glow mb-2" style="font-size:0.65rem;">{{ $related->category }}</div>@endif
                <h4 style="font-size:0.95rem;"><a href="{{ route('blog.show', $related->slug) }}" style="color:var(--text);">{{ $related->title }}</a></h4>
                <p style="font-size:0.78rem;margin:0;">{{ $related->published_at ? $related->published_at->format('M d, Y') : '' }}</p>
              </div>
            </div>
            @endforeach
          </div>
        </div>
        @endif

        <div class="text-center mt-5">
          <a href="{{ route('blog.index') }}" class="btn btn-outline"><i class="bi bi-arrow-left"></i> {{ __('Back to Blog') }}</a>
        </div>
      </div>
    </div>
  </div>
</section>
@else
<section class="page-hero"><div class="container text-center"><h1>{{ __('Post Not Found') }}</h1><p>{{ __('This blog post doesn\'t exist or has been removed.') }}</p><a href="{{ route('blog.index') }}" class="btn btn-outline mt-3">{{ __('Back to Blog') }}</a></div></section>
@endif
@endsection`,
);

console.log("All files written successfully!");
