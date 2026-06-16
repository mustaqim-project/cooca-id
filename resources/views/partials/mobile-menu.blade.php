<div
  class="offcanvas offcanvas-end offcanvas-cooca"
  tabindex="-1"
  id="mobileMenu"
>
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">{{ $setting->company_name ?? 'COOCA' }}</h5>
    <button
      type="button"
      class="btn-close"
      data-bs-dismiss="offcanvas"
    ></button>
  </div>
  <div class="offcanvas-body">
    <div class="d-flex flex-column gap-0">
      <a href="{{ route('solutions') }}" class="nav-link-cooca">Solutions</a>
      <a href="{{ route('pricing') }}" class="nav-link-cooca">Pricing</a>
      <a href="{{ route('affiliate') }}" class="nav-link-cooca">Affiliate</a>
      <a href="{{ route('blog.index') }}" class="nav-link-cooca">Blog</a>
      <a href="{{ route('about') }}" class="nav-link-cooca">About</a>
    </div>
    <hr style="border-color: var(--border)" />
    <div class="d-flex flex-column gap-2">
      <a
        href="{{ route('customer.login') }}"
        class="btn-cooca btn-cooca-outline"
        style="width: 100%; justify-content: center"
        >Client Login</a
      >
      <a
        href="{{ route('affiliator.login') }}"
        class="btn-cooca btn-cooca-outline"
        style="width: 100%; justify-content: center"
        >Affiliate Login</a
      >
      <a
        href="{{ route('pricing') }}"
        class="btn-cooca btn-cooca-primary"
        style="width: 100%; justify-content: center"
        >Start Free Trial</a
      >
    </div>
    <div class="mt-3">
      <button
        class="theme-toggle"
        id="themeToggleMobile"
        aria-label="Toggle theme"
      >
        <i class="bi bi-moon-fill" id="themeIconMobile"></i>
        <span>Toggle Theme</span>
      </button>
    </div>
  </div>
</div>
