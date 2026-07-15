<!-- PAGE LOADER -->
<div class="page-loader" id="pageLoader">
    <div class="loader-logo">
        @if (setting('site.preloader_image_light') || setting('site.preloader_image_dark'))
            @if (setting('site.preloader_image_light'))
                <img src="{{ asset(setting('site.preloader_image_light')) }}" alt="{{ setting('site.name', 'COOCA') }}"
                    class="loader-img-light" style="max-height:200px;" />
            @endif
            @if (setting('site.preloader_image_dark'))
                <img src="{{ asset(setting('site.preloader_image_dark')) }}" alt="{{ setting('site.name', 'COOCA') }}"
                    class="loader-img-dark" style="max-height:200px; display:none;" />
            @endif
        @elseif(setting('site.logo'))
            <img src="{{ asset(setting('site.logo')) }}" alt="{{ setting('site.name', 'COOCA') }}"
                style="max-height:56px;" />
        @else
            <div class="logo-icon-large">C</div>
            <div class="logo-text">{{ setting('site.preloader_text', 'COOCA') }}</div>
        @endif
    </div>
</div>

<!-- FLOATING WHATSAPP -->
<a href="{{ setting('contact.whatsapp_link', 'https://wa.me/6281234567890') }}" class="whatsapp-float" target="_blank"
    rel="noopener" aria-label="Chat on WhatsApp">
    <span class="pulse-ring"></span>
    <i class="bi bi-whatsapp"></i>
</a>

<!-- NAVBAR -->
<nav class="navbar" id="mainNav">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between w-100">
            <a href="{{ route('home') }}" class="navbar-brand">
                @if (setting('site.logo_light') || setting('site.logo_dark'))
                    @if (setting('site.logo_light'))
                        <img src="{{ asset(setting('site.logo_light')) }}" alt="{{ setting('site.name', 'COOCA') }}"
                            class="nav-logo-light" style="height:32px;object-fit:contain;" />
                    @endif
                    @if (setting('site.logo_dark'))
                        <img src="{{ asset(setting('site.logo_dark')) }}" alt="{{ setting('site.name', 'COOCA') }}"
                            class="nav-logo-dark" style="height:32px;object-fit:contain; display:none;" />
                    @endif
                @elseif(setting('site.logo'))
                    <img src="{{ asset(setting('site.logo')) }}" alt="{{ setting('site.name', 'COOCA') }}"
                        style="height:32px;object-fit:contain;" />
                @else
                    <div class="brand-icon">C</div>
                    <span>{{ setting('site.name', 'COOCA') }}</span>
                @endif
            </a>
            <div class="d-none d-lg-flex align-items-center gap-1">
                <a href="{{ route('home') }}"
                    class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">{{ __('Home') }}</a>
                <a href="{{ route('products.index') }}"
                    class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">{{ __('Products') }}</a>
                <a href="{{ route('solutions') }}"
                    class="nav-link {{ request()->routeIs('solutions') ? 'active' : '' }}">{{ __('Solutions') }}</a>
                <a href="{{ route('pricing') }}"
                    class="nav-link {{ request()->routeIs('pricing') ? 'active' : '' }}">{{ __('Pricing') }}</a>
                <a href="{{ route('affiliate') }}"
                    class="nav-link {{ request()->routeIs('affiliate') ? 'active' : '' }}">{{ __('Affiliate') }}</a>
                <a href="{{ route('blog.index') }}"
                    class="nav-link {{ request()->routeIs('blog.*') ? 'active' : '' }}">{{ __('Blog') }}</a>
                <a href="{{ route('docs') }}"
                    class="nav-link {{ request()->routeIs('docs') ? 'active' : '' }}">{{ __('Docs') }}</a>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="lang-switcher d-none d-lg-flex">
                    <a href="{{ route('lang.switch', 'id') }}"
                        class="{{ app()->getLocale() == 'id' ? 'active' : '' }}">ID</a>
                    <a href="{{ route('lang.switch', 'en') }}"
                        class="{{ app()->getLocale() == 'en' ? 'active' : '' }}">EN</a>
                </div>
                <button class="theme-toggle d-none d-lg-flex" id="themeToggle" aria-label="Toggle theme"
                    data-toggle-theme>
                    <i class="bi bi-moon-fill theme-icon-dark"></i>
                    <i class="bi bi-sun-fill theme-icon-light" style="display:none;"></i>
                </button>
                <div class="dropdown d-none d-md-inline-block">
                    <button class="btn btn-outline btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        {{ __('Login') }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-c">
                        <li><a class="dropdown-item" href="{{ route('customer.login') }}"><i
                                    class="bi bi-person-circle"></i> {{ __('Client Login') }}</a></li>
                        <li><a class="dropdown-item" href="{{ route('affiliator.login') }}"><i
                                    class="bi bi-people"></i> {{ __('Affiliate Login') }}</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="{{ route('admin.login') }}"><i
                                    class="bi bi-shield-lock"></i> {{ __('Admin Panel') }}</a></li>
                    </ul>
                </div>
                <a href="{{ route('customer.register') }}" class="btn btn-primary btn-sm d-none d-md-inline-flex">
                    {{ __(setting('nav.cta_text', 'Start Free Trial')) }}
                </a>
                <button class="btn btn-outline btn-sm d-lg-none px-2" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#mobileMenu" aria-label="Menu">
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
            @if (setting('site.logo_light') || setting('site.logo_dark'))
                @if (setting('site.logo_light'))
                    <img src="{{ asset(setting('site.logo_light')) }}" alt="{{ setting('site.name', 'COOCA') }}"
                        class="nav-logo-light" style="height:28px;" />
                @endif
                @if (setting('site.logo_dark'))
                    <img src="{{ asset(setting('site.logo_dark')) }}" alt="{{ setting('site.name', 'COOCA') }}"
                        class="nav-logo-dark" style="height:28px; display:none;" />
                @endif
            @elseif(setting('site.logo'))
                <img src="{{ asset(setting('site.logo')) }}" alt="{{ setting('site.name', 'COOCA') }}"
                    style="height:28px;" />
            @else
                <div class="brand-icon" style="width:28px;height:28px;font-size:0.8rem;">C</div>
                {{ setting('site.name', 'COOCA') }}
            @endif
        </span>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column gap-2 pt-3">
        <a href="{{ route('home') }}" class="nav-link">{{ __('Home') }}</a>
        <a href="{{ route('products.index') }}" class="nav-link">{{ __('Products') }}</a>
        <a href="{{ route('solutions') }}" class="nav-link">{{ __('Solutions') }}</a>
        <a href="{{ route('pricing') }}" class="nav-link">{{ __('Pricing') }}</a>
        <a href="{{ route('affiliate') }}" class="nav-link">{{ __('Affiliate') }}</a>
        <a href="{{ route('blog.index') }}" class="nav-link">{{ __('Blog') }}</a>
        <a href="{{ route('docs') }}" class="nav-link">{{ __('Documentation') }}</a>
        <a href="{{ route('about') }}" class="nav-link">{{ __('About') }}</a>
        <hr style="border-color:var(--border);">
        <a href="{{ route('customer.login') }}" class="nav-link"><i
                class="bi bi-person-circle me-2"></i>{{ __('Client Login') }}</a>
        <a href="{{ route('affiliator.login') }}" class="nav-link"><i
                class="bi bi-people me-2"></i>{{ __('Affiliate Login') }}</a>
        <a href="{{ route('customer.register') }}"
            class="btn btn-primary btn-block mt-2">{{ __(setting('nav.cta_text', 'Start Free Trial')) }}</a>

        <div class="d-flex align-items-center justify-content-between mt-3 p-2"
            style="background:var(--surface-alt);border:1px solid var(--border);border-radius:var(--radius-sm);">
            <span style="font-size:0.85rem;color:var(--text-muted);">{{ __('Language') }}</span>
            <div class="lang-switcher">
                <a href="{{ route('lang.switch', 'id') }}"
                    class="{{ app()->getLocale() == 'id' ? 'active' : '' }}">ID</a>
                <a href="{{ route('lang.switch', 'en') }}"
                    class="{{ app()->getLocale() == 'en' ? 'active' : '' }}">EN</a>
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
</div>
