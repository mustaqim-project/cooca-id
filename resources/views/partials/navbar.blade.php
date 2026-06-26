<nav class="navbar-cooca dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700" id="mainNav">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            <a href="{{ route('home') }}" class="navbar-brand-cooca">
                @if(setting('site.logo'))
                    <img src="{{ asset(setting('site.logo')) }}" alt="Logo" class="h-8 object-contain" />
                @else
                    <div class="logo-icon">C</div>
                    {{ setting('site.preloader_text', 'COOCA') }}
                @endif
            </a>
            <div class="d-none d-lg-flex align-items-center gap-1">
                <a href="{{ route('solutions') }}" class="nav-link-cooca">Solutions</a>
                <a href="{{ route('pricing') }}" class="nav-link-cooca">Pricing</a>
                <a href="{{ route('affiliate') }}" class="nav-link-cooca">Affiliate</a>
                <a href="{{ route('blog.index') }}" class="nav-link-cooca">Blog</a>
                <a href="{{ route('about') }}" class="nav-link-cooca">About</a>
            </div>
            <div class="d-flex align-items-center gap-3">
                <button class="theme-toggle d-none d-lg-flex" id="themeToggle" aria-label="Toggle theme">
                    <i class="bi bi-moon-fill" id="themeIcon"></i>
                </button>
                <!-- Login dropdown -->
                <div class="dropdown d-none d-md-inline-block">
                    <button class="btn btn-cooca btn-cooca-primary btn-cooca-sm dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Login <i class="bi bi-chevron-down small"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="{{ route('customer.login') }}">Client Login</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('affiliator.login') }}">Affiliate Login</a>
                        </li>
                    </ul>
                </div>
                <a href="{{ route('customer.register') }}"
                    class="btn-cooca btn-cooca-primary btn-cooca-sm d-none d-md-inline-flex">Start Free Trial</a>
                <button class="btn btn-outline-secondary d-lg-none" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#mobileMenu"
                    style="
            border-color: var(--border);
            color: var(--text);
            border-radius: 10px;
            padding: 8px 12px;
          ">
                    <i class="bi bi-list" style="font-size: 1.3rem"></i>
                </button>
            </div>
        </div>
    </div>
</nav>
