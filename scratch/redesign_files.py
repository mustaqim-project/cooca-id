#!/usr/bin/env python3
"""Generate all redesigned blade files for COOCA public website."""
import os

BASE = r"c:\laragon\www\cooca-id"

def write(path, content):
    full = os.path.join(BASE, path)
    os.makedirs(os.path.dirname(full), exist_ok=True)
    with open(full, "w", encoding="utf-8") as f:
        f.write(content)
    print(f"  Wrote: {path}")

# ==================== GUEST LAYOUT ====================
guest = r'''<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @if(setting('site.favicon'))
        <link rel="icon" type="image/x-icon" href="{{ asset(setting('site.favicon')) }}">
    @endif
    @include('partials.seo')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="{{ asset('css/premium.css') }}" rel="stylesheet" />
    @stack('styles')
</head>
<body>
    @include('partials.header')
    <main>
        @yield('content')
    </main>
    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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
    </script>
    @stack('scripts')
</body>
</html>'''

# ==================== HEADER ====================
header = '''<!-- PAGE LOADER -->
<div class="page-loader" id="pageLoader">
  <div class="loader-logo">
    @if(setting('site.preloader_image_light') || setting('site.preloader_image_dark'))
        @if(setting('site.preloader_image_light'))
            <img src="{{ asset(setting('site.preloader_image_light')) }}" alt="{{ setting('site.name','COOCA') }}" class="loader-img-light" style="max-height:200px;" />
        @endif
        @if(setting('site.preloader_image_dark'))
            <img src="{{ asset(setting('site.preloader_image_dark')) }}" alt="{{ setting('site.name','COOCA') }}" class="loader-img-dark" style="max-height:200px; display:none;" />
        @endif
    @elseif(setting('site.logo'))
        <img src="{{ asset(setting('site.logo')) }}" alt="{{ setting('site.name','COOCA') }}" class="loader-img-default" style="max-height:56px;" />
    @else
        <div class="logo-icon-large">C</div>
        <div class="logo-text">{{ setting('site.preloader_text', 'COOCA') }}</div>
    @endif
  </div>
</div>

<!-- FLOATING WHATSAPP -->
<a href="{{ setting('contact.whatsapp_link', 'https://wa.me/6281234567890') }}" class="whatsapp-float" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
  <span class="pulse-ring"></span>
  <i class="bi bi-whatsapp"></i>
</a>

<!-- NAVBAR -->
<nav class="navbar" id="mainNav">
  <div class="container">
    <div class="d-flex align-items-center justify-content-between w-100">
      <a href="{{ route('home') }}" class="navbar-brand">
        @if(setting('site.logo_light') || setting('site.logo_dark'))
            @if(setting('site.logo_light'))
                <img src="{{ asset(setting('site.logo_light')) }}" alt="{{ setting('site.name','COOCA') }}" class="nav-logo-light" style="height:32px;object-fit:contain;" />
            @endif
            @if(setting('site.logo_dark'))
                <img src="{{ asset(setting('site.logo_dark')) }}" alt="{{ setting('site.name','COOCA') }}" class="nav-logo-dark" style="height:32px;object-fit:contain; display:none;" />
            @endif
        @elseif(setting('site.logo'))
            <img src="{{ asset(setting('site.logo')) }}" alt="{{ setting('site.name','COOCA') }}" class="nav-logo-default" style="height:32px;object-fit:contain;" />
        @else
            <div class="brand-icon">C</div>
            <span>{{ setting('site.name', 'COOCA') }}</span>
        @endif
      </a>
      <div class="d-none d-lg-flex align-items-center gap-1">
        <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">{{ __('Home') }}</a>
        <a href="{{ route('solutions') }}" class="nav-link {{ request()->routeIs('solutions') ? 'active' : '' }}">{{ __('Solutions') }}</a>
        <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">{{ __('Products') }}</a>
        <a href="{{ route('pricing') }}" class="nav-link {{ request()->routeIs('pricing') ? 'active' : '' }}">{{ __('Pricing') }}</a>
        <a href="{{ route('affiliate') }}" class="nav-link {{ request()->routeIs('affiliate') ? 'active' : '' }}">{{ __('Affiliate') }}</a>
        <a href="{{ route('blog.index') }}" class="nav-link {{ request()->routeIs('blog.*') ? 'active' : '' }}">{{ __('Blog') }}</a>
        <a href="{{ route('docs') }}" class="nav-link {{ request()->routeIs('docs') ? 'active' : '' }}">{{ __('Docs') }}</a>
      </div>
      <div class="d-flex align-items-center gap-2">
        <div class="lang-switcher d-none d-lg-flex">
          <a href="{{ route('lang.switch', 'id') }}" class="{{ app()->getLocale() == 'id' ? 'active' : '' }}">ID</a>
          <a href="{{ route('lang.switch', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'active' : '' }}">EN</a>
        </div>
        <button class="theme-toggle d-none d-lg-flex" id="themeToggle" aria-label="Toggle theme" data-toggle-theme>
          <i class="bi bi-moon-fill theme-icon-dark"></i>
          <i class="bi bi-sun-fill theme-icon-light" style="display:none;"></i>
        </button>
        <div class="dropdown d-none d-md-inline-block">
          <button class="btn btn-outline btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{ __('Login') }}
          </button>
          <ul class="dropdown-menu dropdown-menu-c">
            <li><a class="dropdown-item" href="{{ route('customer.login') }}"><i class="bi bi-person-circle"></i>{{ __('Client Login') }}</a></li>
            <li><a class="dropdown-item" href="{{ route('affiliator.login') }}"><i class="bi bi-people"></i>{{ __('Affiliate Login') }}</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="{{ route('admin.login') }}"><i class="bi bi-shield-lock"></i>{{ __('Admin Panel') }}</a></li>
          </ul>
        </div>
        <a href="{{ route('customer.register') }}" class="btn btn-primary btn-sm d-none d-md-inline-flex">
          {{ __(setting('nav.cta_text', 'Start Free Trial')) }}
        </a>
        <button class="btn btn-outline btn-sm d-lg-none px-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-label="Menu">
          <i class="bi bi-list" style="font-size:1.3rem;"></i>
        </button>
      </div>
    </div>
  </div>
</nav>

<!-- MOBILE OFFCANVAS -->
<div class="offcanvas offcanvas-end offcanvas-c" tabindex="-1" id="mobileMenu" style="max-width:300px;">
  <div class="offcanvas-header">
    <span class="offcanvas-title">
      @if(setting('site.logo_light') || setting('site.logo_dark'))
          @if(setting('site.logo_light'))
              <img src="{{ asset(setting('site.logo_light')) }}" alt="{{ setting('site.name','COOCA') }}" class="nav-logo-light" style="height:28px;" />
          @endif
          @if(setting('site.logo_dark'))
              <img src="{{ asset(setting('site.logo_dark')) }}" alt="{{ setting('site.name','COOCA') }}" class="nav-logo-dark" style="height:28px; display:none;" />
          @endif
      @elseif(setting('site.logo'))
          <img src="{{ asset(setting('site.logo')) }}" alt="{{ setting('site.name','COOCA') }}" class="nav-logo-default" style="height:28px;" />
      @else
          <div class="brand-icon" style="width:28px;height:28px;font-size:0.8rem;">C</div>
          {{ setting('site.name','COOCA') }}
      @endif
    </span>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body d-flex flex-column gap-2 pt-3">
    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">{{ __('Home') }}</a>
    <a href="{{ route('solutions') }}" class="nav-link">{{ __('Solutions') }}</a>
    <a href="{{ route('products.index') }}" class="nav-link">{{ __('Products') }}</a>
    <a href="{{ route('pricing') }}" class="nav-link">{{ __('Pricing') }}</a>
    <a href="{{ route('affiliate') }}" class="nav-link">{{ __('Affiliate') }}</a>
    <a href="{{ route('blog.index') }}" class="nav-link">{{ __('Blog') }}</a>
    <a href="{{ route('docs') }}" class="nav-link">{{ __('Documentation') }}</a>
    <a href="{{ route('about') }}" class="nav-link">{{ __('About') }}</a>
    <hr style="border-color:var(--border);">
    <a href="{{ route('customer.login') }}" class="nav-link"><i class="bi bi-person-circle me-2"></i>{{ __('Client Login') }}</a>
    <a href="{{ route('affiliator.login') }}" class="nav-link"><i class="bi bi-people me-2"></i>{{ __('Affiliate Login') }}</a>
    <a href="{{ route('customer.register') }}" class="btn btn-primary btn-block mt-2">{{ __(setting('nav.cta_text', 'Start Free Trial')) }}</a>

    <div class="d-flex align-items-center justify-content-between mt-3 p-2" style="background:var(--surface-alt);border:1px solid var(--border);border-radius:var(--radius-sm);">
      <span style="font-size:0.85rem;color:var(--text-muted);">{{ __('Language') }}</span>
      <div class="lang-switcher">
        <a href="{{ route('lang.switch', 'id') }}" class="{{ app()->getLocale() == 'id' ? 'active' : '' }}">ID</a>
        <a href="{{ route('lang.switch', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'active' : '' }}">EN</a>
      </div>
    </div>
    <div class="d-flex align-items-center gap-2 mt-2">
      <button class="theme-toggle" id="themeToggleMobile" aria-label="Toggle theme" data-toggle-theme>
        <i class="bi bi-moon-fill theme-icon-dark"></i>
        <i class="bi bi-sun-fill theme-icon-light" style="display:none;"></i>
      </button>
      <span style="font-size:0.85rem;color:var(--text-muted);">{{ __('Toggle Theme') }}</span>
    </div>
  </div>
</div>'''

# ==================== FOOTER ====================
footer = '''<footer class="footer">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4 col-md-6">
        <div class="footer-brand">
          @if(setting('site.logo_light') || setting('site.logo_dark'))
              @if(setting('site.logo_light'))
                  <img src="{{ asset(setting('site.logo_light')) }}" alt="{{ setting('site.name','COOCA') }}" class="nav-logo-light" style="height:28px;object-fit:contain;" />
              @endif
              @if(setting('site.logo_dark'))
                  <img src="{{ asset(setting('site.logo_dark')) }}" alt="{{ setting('site.name','COOCA') }}" class="nav-logo-dark" style="height:28px;object-fit:contain; display:none;" />
              @endif
          @elseif(setting('site.logo'))
              <img src="{{ asset(setting('site.logo')) }}" alt="{{ setting('site.name','COOCA') }}" class="nav-logo-default" style="height:28px;object-fit:contain;" />
          @else
              <div class="brand-icon">C</div>
              {{ setting('site.name', 'COOCA') }}
          @endif
        </div>
        <p class="footer-desc">{{ __(setting('footer.description', 'The business system that works like an asset. Lifetime license, modular ERP, and complete digital infrastructure for serious businesses.')) }}</p>
        <div class="footer-socials">
          @if(setting('social.twitter'))
          <a href="{{ setting('social.twitter','#') }}" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
          @endif
          @if(setting('social.linkedin'))
          <a href="{{ setting('social.linkedin','#') }}" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
          @endif
          @if(setting('social.github'))
          <a href="{{ setting('social.github','#') }}" aria-label="GitHub"><i class="bi bi-github"></i></a>
          @endif
          @if(setting('social.instagram'))
          <a href="{{ setting('social.instagram','#') }}" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
          @endif
          @if(setting('social.youtube'))
          <a href="{{ setting('social.youtube','#') }}" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
          @endif
        </div>
      </div>
      <div class="col-lg-2 col-md-6 col-6">
        <div class="footer-title">{{ __('Products') }}</div>
        <ul class="footer-links">
          <li><a href="{{ route('products.index') }}">{{ __('Product Catalog') }}</a></li>
          <li><a href="{{ route('solutions') }}">{{ __('ERP Solutions') }}</a></li>
          <li><a href="{{ route('solutions') }}">{{ __('CRM Solutions') }}</a></li>
          <li><a href="{{ route('solutions') }}">{{ __('POS System') }}</a></li>
          <li><a href="{{ route('solutions') }}">{{ __('HRIS') }}</a></li>
        </ul>
      </div>
      <div class="col-lg-2 col-md-6 col-6">
        <div class="footer-title">{{ __('Company') }}</div>
        <ul class="footer-links">
          <li><a href="{{ route('about') }}">{{ __('About Us') }}</a></li>
          <li><a href="{{ route('blog.index') }}">{{ __('Blog') }}</a></li>
          <li><a href="{{ route('affiliate') }}">{{ __('Partners') }}</a></li>
          <li><a href="{{ route('contact') }}">{{ __('Contact') }}</a></li>
        </ul>
      </div>
      <div class="col-lg-2 col-md-6 col-6">
        <div class="footer-title">{{ __('Resources') }}</div>
        <ul class="footer-links">
          <li><a href="{{ route('docs') }}">{{ __('Documentation') }}</a></li>
          <li><a href="{{ route('faq') }}">{{ __('FAQ') }}</a></li>
          <li><a href="{{ route('pricing') }}">{{ __('Pricing') }}</a></li>
          <li><a href="{{ route('blog.index') }}">{{ __('Resources') }}</a></li>
        </ul>
      </div>
      <div class="col-lg-2 col-md-6 col-6">
        <div class="footer-title">{{ __('Legal') }}</div>
        <ul class="footer-links">
          <li><a href="{{ route('terms') }}">{{ __('Terms of Service') }}</a></li>
          <li><a href="{{ route('privacy') }}">{{ __('Privacy Policy') }}</a></li>
          <li><a href="{{ route('contact') }}">{{ __('Help Center') }}</a></li>
        </ul>
        <div class="mt-3">
          <div class="footer-title" style="font-size:0.7rem;">{{ __('Newsletter') }}</div>
          <form action="{{ route('newsletter.subscribe') }}" method="POST" class="d-flex gap-1 mt-1">
            @csrf
            <input type="email" name="email" class="form-control form-control-sm" placeholder="your@email.com"
              style="background:var(--surface-alt);border-color:var(--border);color:var(--text);border-radius:var(--radius-xs);font-size:0.78rem;" required>
            <button type="submit" class="btn btn-primary btn-sm" style="white-space:nowrap;border-radius:var(--radius-xs);padding:6px 10px;font-size:0.75rem;">
              <i class="bi bi-send-fill"></i>
            </button>
          </form>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; {{ date('Y') }} {{ setting('site.name','COOCA') }}. {{ __(setting('footer.copyright_text','All rights reserved.')) }}</p>
      <p>{{ __(setting('footer.tagline', 'Enterprise Business Infrastructure — Built for Ownership.')) }}</p>
    </div>
  </div>
</footer>'''

# ==================== HOME PAGE ====================
home = '''@extends('layouts.guest')
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    .swiper-pagination-bullet { background: var(--text-muted); }
    .swiper-pagination-bullet-active { background: var(--accent); width: 28px; border-radius: 5px; }
    .swiper-button-next, .swiper-button-prev { width: 48px; height: 48px; border-radius: 50%; background: var(--surface); border: 1px solid var(--border); color: var(--accent); }
    .swiper-button-next:hover, .swiper-button-prev:hover { background: var(--btn-primary-gradient); color: #fff; }
    .hero-highlight { position: relative; display: inline-block; }
    .hero-highlight::after { content: ''; position: absolute; bottom: 4px; left: 0; right: 0; height: 6px; background: rgba(99,102,241,0.25); border-radius: 3px; }
</style>
@endpush
@section('content')
<!-- HERO SECTION -->
<section class="hero">
  <div class="hero-orb hero-orb-1"></div>
  <div class="hero-orb hero-orb-2"></div>
  <div class="hero-orb hero-orb-3"></div>
  <div class="grid-bg"></div>
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6 hero-content">
        <div class="badge-saas reveal mb-4">
          <span class="badge-dot online"></span> {{ __(setting('home.badge', 'Indonesia\'s #1 Business Infrastructure Platform')) }}
        </div>
        <h1 class="hero-title reveal rv-delay-1">
          {!! __(setting('home.headline', 'Own Your Business System. <br><span class="hero-highlight text-gradient">Stop Renting, Start Building.</span>')) !!}
        </h1>
        <p class="hero-subtitle reveal rv-delay-2">
          {{ __(setting('home.subtitle', 'One lifetime license. Your complete ERP, CRM, POS, HRIS. Isolated infrastructure. No recurring fees. Enterprise-grade ownership for businesses that think decade-level.')) }}
        </p>
        <div class="hero-cta reveal rv-delay-3">
          <a href="{{ route('customer.register') }}" class="btn btn-primary btn-lg">{{ __('Start Free Trial') }} <i class="bi bi-arrow-right"></i></a>
          <a href="{{ route('contact') }}" class="btn btn-outline btn-lg">{{ __('Request Demo') }} <i class="bi bi-play-circle"></i></a>
        </div>
        <div class="hero-stats reveal rv-delay-4">
          <div><div class="hero-stat-value">{{ setting('home.stat1_value', '1,200+') }}</div><div class="hero-stat-label">{{ __('Active Businesses') }}</div></div>
          <div><div class="hero-stat-value">{{ setting('home.stat2_value', '99.9%') }}</div><div class="hero-stat-label">{{ __('Uptime SLA') }}</div></div>
          <div><div class="hero-stat-value">{{ setting('home.stat3_value', '30min') }}</div><div class="hero-stat-label">{{ __('Provisioning Time') }}</div></div>
        </div>
      </div>
      <div class="col-lg-6 hero-visual">
        <div class="hero-dashboard reveal rv-delay-3">
          <div class="dashboard-header">
            <div class="dashboard-dot red"></div>
            <div class="dashboard-dot yellow"></div>
            <div class="dashboard-dot green"></div>
            <span style="margin-left:8px;font-size:0.75rem;color:var(--text-muted);">{{ __('dashboard.cooca.id') }}</span>
          </div>
          <div class="dashboard-body">
            <div class="dashboard-grid">
              <div class="dash-widget">
                <div class="dash-widget-title">{{ __('Revenue Today') }}</div>
                <div class="dash-widget-value">{{ __('Rp 12.4M') }}</div>
                <div class="dash-widget-change">↑ 12.5%</div>
              </div>
              <div class="dash-widget">
                <div class="dash-widget-title">{{ __('Active Orders') }}</div>
                <div class="dash-widget-value">147</div>
                <div class="dash-widget-change">↑ 8.2%</div>
              </div>
              <div class="dash-widget">
                <div class="dash-widget-title">{{ __('Customers') }}</div>
                <div class="dash-widget-value">3,842</div>
                <div class="dash-widget-change">↑ 5.1%</div>
              </div>
              <div class="dash-widget">
                <div class="dash-widget-title">{{ __('Inventory Value') }}</div>
                <div class="dash-widget-value">{{ __('Rp 892M') }}</div>
                <div class="dash-widget-change">→ Stable</div>
              </div>
              <div class="dash-chart">
                <div class="dash-chart-bar" style="height:60%"></div>
                <div class="dash-chart-bar" style="height:80%"></div>
                <div class="dash-chart-bar" style="height:45%"></div>
                <div class="dash-chart-bar" style="height:90%"></div>
                <div class="dash-chart-bar" style="height:55%"></div>
                <div class="dash-chart-bar" style="height:70%"></div>
                <div class="dash-chart-bar" style="height:85%"></div>
                <div class="dash-chart-bar" style="height:50%"></div>
                <div class="dash-chart-bar" style="height:65%"></div>
                <div class="dash-chart-bar" style="height:75%"></div>
                <div class="dash-chart-bar" style="height:40%"></div>
                <div class="dash-chart-bar" style="height:95%"></div>
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

<!-- COUNTER SECTION -->
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

<!-- PRODUCT SHOWCASE -->
<section class="section">
  <div class="container">
    <div class="text-center reveal">
      <div class="section-label"><i class="bi bi-grid-fill"></i> {{ __('Our Products') }}</div>
      <h2 class="section-title">{{ __('Everything Your Business Needs,') }}<br><span class="text-gradient">{{ __('In One Platform.') }}</span></h2>
      <p class="section-subtitle">{{ __('From point of sale to HR management, from inventory to customer relationships — all modules included. No hidden fees, no per-module pricing.') }}</p>
    </div>
    <div class="row g-4">
      @if(isset($products) && count($products))
          @foreach($products->take(6) as $product)
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
                <a href="{{ route('products.show', $product->slug) }}" class="btn btn-outline btn-sm">{{ __('Learn More') }} <i class="bi bi-arrow-right"></i></a>
                @if($product->subscriptionPlans && $product->subscriptionPlans->count())
                    {{ __('From') }} {{ \App\Helpers\setting('currency.symbol','Rp') }} {{ number_format($product->subscriptionPlans->min('price'),0,',','.') }}
                  </span>
                @endif
              </div>
            </div>
          </div>
          @endforeach
      @else
          <div class="col-12 text-center reveal p-5">
            <div class="empty-state">
              <div class="empty-state-icon">📦</div>
              <h4>{{ __('Products Coming Soon') }}</h4>
              <p>{{ __('Our product catalog is being prepared. Check back soon or contact sales for early access.') }}</p>
            </div>
          </div>
      @endif
    </div>
    @if(isset($products) && count($products) > 6)
    <div class="text-center mt-5 reveal">
      <a href="{{ route('products.index') }}" class="btn btn-outline btn-lg">{{ __('View All Products') }} <i class="bi bi-arrow-right"></i></a>
    </div>
    @endif
  </div>
</section>

<!-- CORE FEATURES -->
<section class="section section-alt">
  <div class="container">
    <div class="text-center reveal">
      <div class="section-label"><i class="bi bi-stars"></i> {{ __('Core Features') }}</div>
      <h2 class="section-title">{{ __('Powered by <span class="text-gradient">10 Integrated Modules</span>') }}</h2>
      <p class="section-subtitle">{{ __('Every module talks to every other module. Real-time synchronization. Zero data silos.') }}</p>
    </div>
    <div class="row g-4">
      <div class="col-md-4 col-sm-6 reveal">
        <div class="card module-card">
          <div class="module-icon"><i class="bi bi-cart-check"></i></div>
          <div class="module-title">{{ __('Point of Sale') }}</div>
          <p class="module-desc">{{ __('Multi-outlet POS with real-time inventory sync and offline mode.') }}</p>
        </div>
      </div>
      <div class="col-md-4 col-sm-6 reveal rv-delay-1">
        <div class="card module-card">
          <div class="module-icon"><i class="bi bi-box-seam"></i></div>
          <div class="module-title">{{ __('Inventory Management') }}</div>
          <p class="module-desc">{{ __('Multi-warehouse tracking with automated reorder points and batch management.') }}</p>
        </div>
      </div>
      <div class="col-md-4 col-sm-6 reveal rv-delay-2">
        <div class="card module-card">
          <div class="module-icon"><i class="bi bi-people"></i></div>
          <div class="module-title">{{ __('CRM') }}</div>
          <p class="module-desc">{{ __('360-degree customer view with automated engagement workflows.') }}</p>
        </div>
      </div>
      <div class="col-md-4 col-sm-6 reveal">
        <div class="card module-card">
          <div class="module-icon"><i class="bi bi-cash-stack"></i></div>
          <div class="module-title">{{ __('Accounting') }}</div>
          <p class="module-desc">{{ __('Double-entry accounting with automated reconciliation and tax reporting.') }}</p>
        </div>
      </div>
      <div class="col-md-4 col-sm-6 reveal rv-delay-1">
        <div class="card module-card">
          <div class="module-icon"><i class="bi bi-person-badge"></i></div>
          <div class="module-title">{{ __('HRIS') }}</div>
          <p class="module-desc">{{ __('Attendance, payroll, leave management, and performance tracking.') }}</p>
        </div>
      </div>
      <div class="col-md-4 col-sm-6 reveal rv-delay-2">
        <div class="card module-card">
          <div class="module-icon"><i class="bi bi-graph-up"></i></div>
          <div class="module-title">{{ __('Business Intelligence') }}</div>
          <p class="module-desc">{{ __('Custom dashboards, automated reports, and predictive analytics.') }}</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- WHY CHOOSE US -->
<section class="section">
  <div class="container">
    <div class="text-center reveal">
      <div class="section-label"><i class="bi bi-shield-check"></i> {{ __('Why Choose COOCA') }}</div>
      <h2 class="section-title">{{ __('You Own It. <span class="text-gradient">Forever.</span>') }}</h2>
      <p class="section-subtitle">{{ __('Unlike SaaS subscriptions that lock your data and bleed your budget, COOCA gives you lifetime ownership with isolated infrastructure.') }}</p>
    </div>
    <div class="row g-4 mt-3">
      <div class="col-lg-4 col-md-6 reveal">
        <div class="card why-card">
          <div class="why-icon"><i class="bi bi-infinity"></i></div>
          <h4>{{ __('Lifetime License') }}</h4>
          <p style="margin:0;">{{ __('Pay once. Use forever. No recurring subscription fees. Your software. Your rules.') }}</p>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 reveal rv-delay-1">
        <div class="card why-card">
          <div class="why-icon"><i class="bi bi-shield-lock"></i></div>
          <h4>{{ __('Isolated Infrastructure') }}</h4>
          <p style="margin:0;">{{ __('Your own container. Your own database. Zero cross-tenant risk. Enterprise-grade isolation.') }}</p>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 reveal rv-delay-2">
        <div class="card why-card">
          <div class="why-icon"><i class="bi bi-rocket-takeoff"></i></div>
          <h4>{{ __('30-Minute Setup') }}</h4>
          <p style="margin:0;">{{ __('Fully configured for your industry in 30 minutes. Pre-configured industry templates.') }}</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- TESTIMONIALS -->
<section class="section section-alt">
  <div class="container">
    <div class="text-center reveal">
      <div class="section-label"><i class="bi bi-chat-quote"></i> {{ __('Testimonials') }}</div>
      <h2 class="section-title">{{ __('Trusted by <span class="text-gradient">Business Leaders</span>') }}</h2>
      <p class="section-subtitle">{{ __('Hear from business owners who switched from renting software to owning their infrastructure.') }}</p>
    </div>
    <div class="row g-4">
      <div class="col-lg-4 col-md-6 reveal">
        <div class="card testimonial-card">
          <div class="d-flex align-items-center gap-3 mb-3">
            <img src="https://ui-avatars.com/api/?name=Budi+Santoso&background=4f46e5&color=fff&size=48" alt="" class="testimonial-avatar">
            <div>
              <div class="testimonial-name">Budi Santoso</div>
              <div class="testimonial-role">{{ __('CEO, RetailMax Group') }}</div>
            </div>
          </div>
          <div class="testimonial-stars">★★★★★</div>
          <p class="testimonial-text">{{ __('"Switched from annual SaaS to COOCA lifetime. ROI in month 3. Our 12 outlets now run on one system with zero latency."') }}</p>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 reveal rv-delay-1">
        <div class="card testimonial-card">
          <div class="d-flex align-items-center gap-3 mb-3">
            <img src="https://ui-avatars.com/api/?name=Siti+Rahma&background=7c3aed&color=fff&size=48" alt="" class="testimonial-avatar">
            <div>
              <div class="testimonial-name">Siti Rahma</div>
              <div class="testimonial-role">{{ __('Owner, Sehati Clinic Network') }}</div>
            </div>
          </div>
          <div class="testimonial-stars">★★★★★</div>
          <p class="testimonial-text">{{ __('"Isolated infrastructure was the key decision factor. Patient data stays in our container. HIPAA-compliant and peace of mind."') }}</p>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 reveal rv-delay-2">
        <div class="card testimonial-card">
          <div class="d-flex align-items-center gap-3 mb-3">
            <img src="https://ui-avatars.com/api/?name=Andi+Wijaya&background=38bdf8&color=fff&size=48" alt="" class="testimonial-avatar">
            <div>
              <div class="testimonial-name">Andi Wijaya</div>
              <div class="testimonial-role">{{ __('COO, EduPrime Academy') }}</div>
            </div>
          </div>
          <div class="testimonial-stars">★★★★★</div>
          <p class="testimonial-text">{{ __('"The HRIS module alone saved us 40 hours/month. Payroll, attendance, and leave management are now fully automated."') }}</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA SECTION -->
<section class="cta-section">
  <div class="cta-bg"></div>
  <div class="container cta-content text-center">
    <div class="reveal">
      <div class="section-label"><i class="bi bi-rocket-takeoff-fill"></i> {{ __('Get Started') }}</div>
      <h2 class="section-title">{{ __('Ready to <span class="text-gradient">Own Your System?</span>') }}</h2>
      <p class="section-subtitle">{{ __('Start your 30-day free trial. No credit card. Full access to all 10 modules. Provisioned in 30 minutes.') }}</p>
      <div class="d-flex justify-content-center gap-3 flex-wrap">
        <a href="{{ route('customer.register') }}" class="btn btn-primary btn-lg">{{ __('Start Free Trial') }} <i class="bi bi-arrow-right"></i></a>
        <a href="{{ route('contact') }}" class="btn btn-outline btn-lg">{{ __('Talk to Sales') }} <i class="bi bi-chat-dots"></i></a>
      </div>
    </div>
  </div>
</section>

<!-- FAQ -->
<section class="section section-alt">
  <div class="container">
    <div class="text-center reveal">
      <div class="section-label"><i class="bi bi-question-circle"></i> {{ __('FAQ') }}</div>
      <h2 class="section-title">{{ __('Questions <span class="text-gradient">You Might Have</span>') }}</h2>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-8 reveal">
        <div class="accordion accordion-c" id="faqAccordion">
          <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">{{ __('What does "lifetime license" mean?') }}</button></h2>
            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion"><div class="accordion-body">{{ __('You pay once and own the software forever. No annual renewal fees. No forced upgrades. Your license does not expire.') }}</div></div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">{{ __('How is my data secured?') }}</button></h2>
            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">{{ __('Each customer gets isolated infrastructure — separate container, separate database. Your data never touches another customer. End-to-end encryption and regular backups included.') }}</div></div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">{{ __('Can I migrate my existing data?') }}</button></h2>
            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">{{ __('Yes. We provide migration tools and dedicated support to move your data from legacy systems, spreadsheets, or other platforms. Most migrations complete within 24 hours.') }}</div></div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">{{ __('Is there a free trial?') }}</button></h2>
            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">{{ __('Absolutely. Start a 30-day full-access trial with all 10 modules. No credit card required. Your instance is provisioned in 30 minutes.') }}</div></div>
          </div>
        </div>
        <div class="text-center mt-4">
          <a href="{{ route('faq') }}" class="btn btn-outline">{{ __('View All FAQs') }} <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- TRUST SECTION (Final CTA) -->
<section class="section text-center">
  <div class="container">
    <div class="reveal">
      <div class="section-label"><i class="bi bi-building-check"></i> {{ __('Enterprise-Ready') }}</div>
      <h2 class="section-title" style="max-width:700px;margin:0 auto 16px;">{{ __('Built with <span class="text-gradient">the same infrastructure standards</span> as Fortune 500 companies.') }}</h2>
      <div class="trust-pills mt-4">
        <span class="trust-pill"><i class="bi bi-shield-check"></i> ISO 27001</span>
        <span class="trust-pill"><i class="bi bi-lock-fill"></i> AES-256</span>
        <span class="trust-pill"><i class="bi bi-cloud-check"></i> 99.9% SLA</span>
        <span class="trust-pill"><i class="bi bi-database-check"></i> Daily Backups</span>
        <span class="trust-pill"><i class="bi bi-globe2"></i> GDPR Ready</span>
      </div>
    </div>
  </div>
</section>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
@endpush'''

# Write all files
print("Writing redesign files...")
write("resources/views/layouts/guest.blade.php", guest)
write("resources/views/partials/header.blade.php", header)
write("resources/views/partials/footer.blade.php", footer)
write("resources/views/pages/home/index.blade.php", home)
print("Done!")                    {{ __('From') }} {{ \App\Helpers\setting('currency.symbol','Rp') }} {{ number_format($product->subscriptionPlans->min('price'),0,',','.') }}
                  </span>
                @endif
              </div>
            </div>
          </div>
          @endforeach
      @else
          <div class="col-12 text-center reveal p-5">
            <div class="empty-state">
              <div class="empty-state-icon">📦</div>
              <h4>{{ __('Products Coming Soon') }}</h4>
              <p>{{ __('Our product catalog is being prepared. Check back soon or contact sales for early access.') }}</p>
            </div>
          </div>
      @endif
    </div>
    @if(isset($products) && count($products) > 6)
    <div class="text-center mt-5 reveal">
      <a href="{{ route('products.index') }}" class="btn btn-outline btn-lg">{{ __('View All Products') }} <i class="bi bi-arrow-right"></i></a>
    </div>
    @endif
  </div>
</section>

<!-- CORE FEATURES -->
<section class="section section-alt">
  <div class="container">
    <div class="text-center reveal">
      <div class="section-label"><i class="bi bi-stars"></i> {{ __('Core Features') }}</div>
      <h2 class="section-title">{{ __('Powered by <span class="text-gradient">10 Integrated Modules</span>') }}</h2>
      <p class="section-subtitle">{{ __('Every module talks to every other module. Real-time synchronization. Zero data silos.') }}</p>
    </div>
    <div class="row g-4">
      <div class="col-md-4 col-sm-6 reveal">
        <div class="card module-card">
          <div class="module-icon"><i class="bi bi-cart-check"></i></div>
          <div class="module-title">{{ __('Point of Sale') }}</div>
          <p class="module-desc">{{ __('Multi-outlet POS with real-time inventory sync and offline mode.') }}</p>
        </div>
      </div>
      <div class="col-md-4 col-sm-6 reveal rv-delay-1">
        <div class="card module-card">
          <div class="module-icon"><i class="bi bi-box-seam"></i></div>
          <div class="module-title">{{ __('Inventory Management') }}</div>
          <p class="module-desc">{{ __('Multi-warehouse tracking with automated reorder points and batch management.') }}</p>
        </div>
      </div>
      <div class="col-md-4 col-sm-6 reveal rv-delay-2">
        <div class="card module-card">
          <div class="module-icon"><i class="bi bi-people"></i></div>
          <div class="module-title">{{ __('CRM') }}</div>
          <p class="module-desc">{{ __('360-degree customer view with automated engagement workflows.') }}</p>
        </div>
      </div>
      <div class="col-md-4 col-sm-6 reveal">
        <div class="card module-card">
          <div class="module-icon"><i class="bi bi-cash-stack"></i></div>
          <div class="module-title">{{ __('Accounting') }}</div>
          <p class="module-desc">{{ __('Double-entry accounting with automated reconciliation and tax reporting.') }}</p>
        </div>
      </div>
      <div class="col-md-4 col-sm-6 reveal rv-delay-1">
        <div class="card module-card">
          <div class="module-icon"><i class="bi bi-person-badge"></i></div>
          <div class="module-title">{{ __('HRIS') }}</div>
          <p class="module-desc">{{ __('Attendance, payroll, leave management, and performance tracking.') }}</p>
        </div>
      </div>
      <div class="col-md-4 col-sm-6 reveal rv-delay-2">
        <div class="card module-card">
          <div class="module-icon"><i class="bi bi-graph-up"></i></div>
          <div class="module-title">{{ __('Business Intelligence') }}</div>
          <p class="module-desc">{{ __('Custom dashboards, automated reports, and predictive analytics.') }}</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- WHY CHOOSE US -->
<section class="section">
  <div class="container">
    <div class="text-center reveal">
      <div class="section-label"><i class="bi bi-shield-check"></i> {{ __('Why Choose COOCA') }}</div>
      <h2 class="section-title">{{ __('You Own It. <span class="text-gradient">Forever.</span>') }}</h2>
      <p class="section-subtitle">{{ __('Unlike SaaS subscriptions that lock your data and bleed your budget, COOCA gives you lifetime ownership with isolated infrastructure.') }}</p>
    </div>
    <div class="row g-4 mt-3">
      <div class="col-lg-4 col-md-6 reveal">
        <div class="card why-card">
          <div class="why-icon"><i class="bi bi-infinity"></i></div>
          <h4>{{ __('Lifetime License') }}</h4>
          <p style="margin:0;">{{ __('Pay once. Use forever. No recurring subscription fees. Your software. Your rules.') }}</p>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 reveal rv-delay-1">
        <div class="card why-card">
          <div class="why-icon"><i class="bi bi-shield-lock"></i></div>
          <h4>{{ __('Isolated Infrastructure') }}</h4>
          <p style="margin:0;">{{ __('Your own container. Your own database. Zero cross-tenant risk. Enterprise-grade isolation.') }}</p>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 reveal rv-delay-2">
        <div class="card why-card">
          <div class="why-icon"><i class="bi bi-rocket-takeoff"></i></div>
          <h4>{{ __('30-Minute Setup') }}</h4>
          <p style="margin:0;">{{ __('Fully configured for your industry in 30 minutes. Pre-configured industry templates.') }}</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- TESTIMONIALS -->
<section class="section section-alt">
  <div class="container">
    <div class="text-center reveal">
      <div class="section-label"><i class="bi bi-chat-quote"></i> {{ __('Testimonials') }}</div>
      <h2 class="section-title">{{ __('Trusted by <span class="text-gradient">Business Leaders</span>') }}</h2>
      <p class="section-subtitle">{{ __('Hear from business owners who switched from renting software to owning their infrastructure.') }}</p>
    </div>
    <div class="row g-4">
      <div class="col-lg-4 col-md-6 reveal">
        <div class="card testimonial-card">
          <div class="d-flex align-items-center gap-3 mb-3">
            <img src="https://ui-avatars.com/api/?name=Budi+Santoso&background=4f46e5&color=fff&size=48" alt="" class="testimonial-avatar">
            <div>
              <div class="testimonial-name">Budi Santoso</div>
              <div class="testimonial-role">{{ __('CEO, RetailMax Group') }}</div>
            </div>
          </div>
          <div class="testimonial-stars">★★★★★</div>
          <p class="testimonial-text">{{ __('"Switched from annual SaaS to COOCA lifetime. ROI in month 3. Our 12 outlets now run on one system with zero latency."') }}</p>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 reveal rv-delay-1">
        <div class="card testimonial-card">
          <div class="d-flex align-items-center gap-3 mb-3">
            <img src="https://ui-avatars.com/api/?name=Siti+Rahma&background=7c3aed&color=fff&size=48" alt="" class="testimonial-avatar">
            <div>
              <div class="testimonial-name">Siti Rahma</div>
              <div class="testimonial-role">{{ __('Owner, Sehati Clinic Network') }}</div>
            </div>
          </div>
          <div class="testimonial-stars">★★★★★</div>
          <p class="testimonial-text">{{ __('"Isolated infrastructure was the key decision factor. Patient data stays in our container. HIPAA-compliant and peace of mind."') }}</p>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 reveal rv-delay-2">
        <div class="card testimonial-card">
          <div class="d-flex align-items-center gap-3 mb-3">
            <img src="https://ui-avatars.com/api/?name=Andi+Wijaya&background=38bdf8&color=fff&size=48" alt="" class="testimonial-avatar">
            <div>
              <div class="testimonial-name">Andi Wijaya</div>
              <div class="testimonial-role">{{ __('COO, EduPrime Academy') }}</div>
            </div>
          </div>
          <div class="testimonial-stars">★★★★★</div>
          <p class="testimonial-text">{{ __('"The HRIS module alone saved us 40 hours/month. Payroll, attendance, and leave management are now fully automated."') }}</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA SECTION -->
<section class="cta-section">
  <div class="cta-bg"></div>
  <div class="container cta-content text-center">
    <div class="reveal">
      <div class="section-label"><i class="bi bi-rocket-takeoff-fill"></i> {{ __('Get Started') }}</div>
      <h2 class="section-title">{{ __('Ready to <span class="text-gradient">Own Your System?</span>') }}</h2>
      <p class="section-subtitle">{{ __('Start your 30-day free trial. No credit card. Full access to all 10 modules. Provisioned in 30 minutes.') }}</p>
      <div class="d-flex justify-content-center gap-3 flex-wrap">
        <a href="{{ route('customer.register') }}" class="btn btn-primary btn-lg">{{ __('Start Free Trial') }} <i class="bi bi-arrow-right"></i></a>
        <a href="{{ route('contact') }}" class="btn btn-outline btn-lg">{{ __('Talk to Sales') }} <i class="bi bi-chat-dots"></i></a>
      </div>
    </div>
  </div>
</section>

<!-- FAQ -->
<section class="section section-alt">
  <div class="container">
    <div class="text-center reveal">
      <div class="section-label"><i class="bi bi-question-circle"></i> {{ __('FAQ') }}</div>
      <h2 class="section-title">{{ __('Questions <span class="text-gradient">You Might Have</span>') }}</h2>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-8 reveal">
        <div class="accordion accordion-c" id="faqAccordion">
          <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">{{ __('What does "lifetime license" mean?') }}</button></h2>
            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion"><div class="accordion-body">{{ __('You pay once and own the software forever. No annual renewal fees. No forced upgrades. Your license does not expire.') }}</div></div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">{{ __('How is my data secured?') }}</button></h2>
            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">{{ __('Each customer gets isolated infrastructure — separate container, separate database. Your data never touches another customer. End-to-end encryption and regular backups included.') }}</div></div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">{{ __('Can I migrate my existing data?') }}</button></h2>
            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">{{ __('Yes. We provide migration tools and dedicated support to move your data from legacy systems, spreadsheets, or other platforms. Most migrations complete within 24 hours.') }}</div></div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">{{ __('Is there a free trial?') }}</button></h2>
            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">{{ __('Absolutely. Start a 30-day full-access trial with all 10 modules. No credit card required. Your instance is provisioned in 30 minutes.') }}</div></div>
          </div>
        </div>
        <div class="text-center mt-4">
          <a href="{{ route('faq') }}" class="btn btn-outline">{{ __('View All FAQs') }} <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- TRUST SECTION (Final CTA) -->
<section class="section text-center">
  <div class="container">
    <div class="reveal">
      <div class="section-label"><i class="bi bi-building-check"></i> {{ __('Enterprise-Ready') }}</div>
      <h2 class="section-title" style="max-width:700px;margin:0 auto 16px;">{{ __('Built with <span class="text-gradient">the same infrastructure standards</span> as Fortune 500 companies.') }}</h2>
      <div class="trust-pills mt-4">
        <span class="trust-pill"><i class="bi bi-shield-check"></i> ISO 27001</span>
        <span class="trust-pill"><i class="bi bi-lock-fill"></i> AES-256</span>
        <span class="trust-pill"><i class="bi bi-cloud-check"></i> 99.9% SLA</span>
        <span class="trust-pill"><i class="bi bi-database-check"></i> Daily Backups</span>
        <span class="trust-pill"><i class="bi bi-globe2"></i> GDPR Ready</span>
      </div>
    </div>
  </div>
</section>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
@endpush'''

# Write all files
print("Writing redesign files...")
write("resources/views/layouts/guest.blade.php", guest)
write("resources/views/partials/header.blade.php", header)
write("resources/views/partials/footer.blade.php", footer)
write("resources/views/pages/home/index.blade.php", home)
