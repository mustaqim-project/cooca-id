<!-- PAGE LOADER -->
<div class="page-loader" id="pageLoader">
  <div class="loader-logo">
    @if(setting('site.logo'))
        <img src="{{ setting('site.logo') }}" alt="{{ setting('site.name','COOCA') }}" class="h-12 object-contain" style="max-height:56px;" />
    @else
        <div class="logo-icon-large">C</div>
        <div class="logo-text">{{ setting('site.preloader_text', 'COOCA') }}</div>
    @endif
  </div>
</div>

<!-- FLOATING WHATSAPP -->
<a
  href="{{ setting('contact.whatsapp_link', 'https://wa.me/6281234567890') }}"
  class="whatsapp-float"
  target="_blank"
  rel="noopener"
  aria-label="Chat on WhatsApp"
>
  <i class="bi bi-whatsapp"></i>
</a>

<!-- NAVBAR -->
<nav class="navbar-cooca" id="mainNav">
  <div class="container">
    <div class="d-flex align-items-center justify-content-between">
      <a href="{{ route('home') }}" class="navbar-brand-cooca">
        @if(setting('site.logo'))
            <img src="{{ setting('site.logo') }}" alt="{{ setting('site.name','COOCA') }}" style="height:32px;object-fit:contain;" />
        @else
            <div class="logo-icon">C</div>
            {{ setting('site.name', 'COOCA') }}
        @endif
      </a>
      <div class="d-none d-lg-flex align-items-center gap-1">
        <a href="{{ route('solutions') }}" class="nav-link-cooca {{ request()->routeIs('solutions') ? 'active' : '' }}">{{ __('Solutions') }}</a>
        <a href="{{ route('pricing') }}" class="nav-link-cooca {{ request()->routeIs('pricing') ? 'active' : '' }}">{{ __('Pricing') }}</a>
        <a href="{{ route('affiliate') }}" class="nav-link-cooca {{ request()->routeIs('affiliate') ? 'active' : '' }}">{{ __('Affiliate') }}</a>
        <a href="{{ route('blog.index') }}" class="nav-link-cooca {{ request()->routeIs('blog.*') ? 'active' : '' }}">{{ __('Blog') }}</a>
        <a href="{{ route('about') }}" class="nav-link-cooca {{ request()->routeIs('about') ? 'active' : '' }}">{{ __('About') }}</a>
      </div>
      <div class="d-flex align-items-center gap-3">
        <!-- Language Switcher -->
        <div class="lang-switcher d-flex align-items-center p-1" style="background:var(--card-alt);border:1px solid var(--border);border-radius:12px;">
          <a href="{{ route('lang.switch', 'id') }}" class="px-2 py-1 text-xs rounded" style="border-radius:8px;font-size:0.75rem;font-weight:600;transition:all 0.3s;{{ app()->getLocale() == 'id' ? 'background:var(--primary);color:#fff;' : 'color:var(--text-muted);' }}">ID</a>
          <a href="{{ route('lang.switch', 'en') }}" class="px-2 py-1 text-xs rounded" style="border-radius:8px;font-size:0.75rem;font-weight:600;transition:all 0.3s;{{ app()->getLocale() == 'en' ? 'background:var(--primary);color:#fff;' : 'color:var(--text-muted);' }}">EN</a>
        </div>
        <button class="theme-toggle d-none d-lg-flex" id="themeToggle" aria-label="Toggle theme">
          <i class="bi bi-moon-fill" id="themeIcon"></i>
        </button>
        <!-- Login dropdown -->
        <div class="dropdown d-none d-md-inline-block">
          <button class="btn dropdown-toggle dropdown-cooca" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{ __('Login') }} <i class="bi bi-chevron-down small"></i>
          </button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('customer.login') }}"><i class="bi bi-person-circle me-2"></i>{{ __('Client Login') }}</a></li>
            <li><a class="dropdown-item" href="{{ route('affiliator.login') }}"><i class="bi bi-people me-2"></i>{{ __('Affiliate Login') }}</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="{{ route('admin.login') }}"><i class="bi bi-shield-lock me-2"></i>{{ __('Admin') }}</a></li>
          </ul>
        </div>
        <a href="{{ route('pricing') }}" class="btn-cooca btn-cooca-primary btn-cooca-sm d-none d-md-inline-flex">
          {{ __(setting('nav.cta_text', 'Start Free Trial')) }}
        </a>
        <button class="btn btn-outline-secondary d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu"
          style="border-color:var(--border);color:var(--text);border-radius:10px;padding:8px 12px;">
          <i class="bi bi-list" style="font-size:1.3rem"></i>
        </button>
      </div>
    </div>
  </div>
</nav>

<!-- MOBILE OFFCANVAS -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="mobileMenu" style="background:var(--bg);color:var(--text);max-width:300px;">
  <div class="offcanvas-header" style="border-bottom:1px solid var(--border);">
    <span class="navbar-brand-cooca mb-0">
      @if(setting('site.logo'))
          <img src="{{ setting('site.logo') }}" alt="{{ setting('site.name','COOCA') }}" style="height:28px;" />
      @else
          <div class="logo-icon" style="width:28px;height:28px;font-size:0.8rem;">C</div>
          {{ setting('site.name','COOCA') }}
      @endif
    </span>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" style="filter:invert(1);"></button>
  </div>
  <div class="offcanvas-body d-flex flex-column gap-2 pt-3">
    <a href="{{ route('solutions') }}" class="nav-link-cooca">{{ __('Solutions') }}</a>
    <a href="{{ route('pricing') }}" class="nav-link-cooca">{{ __('Pricing') }}</a>
    <a href="{{ route('affiliate') }}" class="nav-link-cooca">{{ __('Affiliate') }}</a>
    <a href="{{ route('blog.index') }}" class="nav-link-cooca">{{ __('Blog') }}</a>
    <a href="{{ route('about') }}" class="nav-link-cooca">{{ __('About') }}</a>
    <hr style="border-color:var(--border);">
    <a href="{{ route('customer.login') }}" class="nav-link-cooca"><i class="bi bi-person-circle me-2"></i>{{ __('Client Login') }}</a>
    <a href="{{ route('affiliator.login') }}" class="nav-link-cooca"><i class="bi bi-people me-2"></i>{{ __('Affiliate Login') }}</a>
    <a href="{{ route('pricing') }}" class="btn-cooca btn-cooca-primary mt-2">{{ __(setting('nav.cta_text', 'Start Free Trial')) }}</a>
    
    <!-- Mobile Lang Switcher -->
    <div class="d-flex align-items-center justify-content-between mt-3 p-2" style="background:var(--card-alt);border:1px solid var(--border);border-radius:12px;">
      <span style="font-size:0.85rem;color:var(--text-muted);">Language / Bahasa</span>
      <div class="d-flex gap-1">
        <a href="{{ route('lang.switch', 'id') }}" class="px-2 py-1 text-xs rounded" style="border-radius:8px;font-size:0.75rem;font-weight:600;{{ app()->getLocale() == 'id' ? 'background:var(--primary);color:#fff;' : 'color:var(--text-muted);' }}">ID</a>
        <a href="{{ route('lang.switch', 'en') }}" class="px-2 py-1 text-xs rounded" style="border-radius:8px;font-size:0.75rem;font-weight:600;{{ app()->getLocale() == 'en' ? 'background:var(--primary);color:#fff;' : 'color:var(--text-muted);' }}">EN</a>
      </div>
    </div>

    <div class="d-flex align-items-center gap-2 mt-2">
      <button class="theme-toggle" id="themeToggleMobile" aria-label="Toggle theme">
        <i class="bi bi-moon-fill" id="themeIconMobile"></i>
      </button>
      <span style="font-size:0.85rem;color:var(--text-muted);">{{ __('Toggle Theme') }}</span>
    </div>
  </div>
</div>
