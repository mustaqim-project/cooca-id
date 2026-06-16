{{-- Mobile Offcanvas Menu - Dynamic from Database --}}
@php
    $mainMenu = \App\Models\Menu::where('location', 'main')->where('active', true)->with('children')->orderBy('order')->get();
    $logoText = setting('branding.name', config('app.name', 'COOCA'));
@endphp

<div class="offcanvas offcanvas-end offcanvas-cooca" tabindex="-1" id="mobileMenu">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">{{ $logoText }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        {{-- Menu Links --}}
        <div class="d-flex flex-column gap-0">
            @forelse($mainMenu as $menu)
                @if($menu->children->count() > 0)
                    <div class="accordion-item" style="border: none;">
                        <a href="#" class="nav-link-cooca" data-bs-toggle="collapse" data-bs-target="#mobile-submenu-{{ $menu->id }}">
                            {{ $menu->title }} <i class="bi bi-chevron-down float-end"></i>
                        </a>
                        <div class="collapse" id="mobile-submenu-{{ $menu->id }}">
                            <div class="ps-3 py-2">
                                @foreach($menu->children as $child)
                                    <a href="{{ $child->url }}" class="d-block py-2" style="color: var(--text-muted); font-size: 0.85rem;">
                                        {{ $child->title }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ $menu->url }}" class="nav-link-cooca {{ request()->is(trim($menu->url, '/')) ? 'active' : '' }}">
                        {{ $menu->title }}
                    </a>
                @endif
            @empty
                {{-- Default menu if none configured --}}
                <a href="{{ url('/solutions') }}" class="nav-link-cooca">Solutions</a>
                <a href="{{ url('/pricing') }}" class="nav-link-cooca">Pricing</a>
                <a href="{{ url('/affiliate') }}" class="nav-link-cooca">Affiliate</a>
                <a href="{{ url('/blog') }}" class="nav-link-cooca">Blog</a>
                <a href="{{ url('/about') }}" class="nav-link-cooca">About</a>
            @endforelse
        </div>
        
        <hr style="border-color: var(--border)" />
        
        {{-- Auth Buttons --}}
        <div class="d-flex flex-column gap-2">
            <a href="{{ route('customer.login') }}" class="btn-cooca btn-cooca-outline" style="width: 100%; justify-content: center;">
                Client Login
            </a>
            <a href="{{ route('affiliate.login') }}" class="btn-cooca btn-cooca-outline" style="width: 100%; justify-content: center;">
                Affiliate Login
            </a>
            @php
                $ctaText = setting('cta.button_text', 'Start Free Trial');
                $ctaUrl = setting('cta.button_url', route('pricing'));
            @endphp
            <a href="{{ $ctaUrl }}" class="btn-cooca btn-cooca-primary" style="width: 100%; justify-content: center;">
                {{ $ctaText }}
            </a>
        </div>
        
        {{-- Theme Toggle Mobile --}}
        <div class="mt-3">
            <button class="theme-toggle" id="themeToggleMobile" aria-label="Toggle theme" style="width: 100%; height: auto; padding: 12px 16px; border-radius: 10px; justify-content: flex-start; gap: 10px; font-size: 0.9rem; font-weight: 500; background: var(--card-alt); border: 1px solid var(--border); color: var(--text);">
                <i class="bi bi-moon-fill" id="themeIconMobile"></i>
                <span>Toggle Theme</span>
            </button>
        </div>
    </div>
</div>

<style>
/* Offcanvas Mobile */
.offcanvas-cooca {
    background: var(--glass) !important;
    backdrop-filter: blur(30px);
    -webkit-backdrop-filter: blur(30px);
    border-left: 1px solid var(--glass-border);
}

.offcanvas-cooca .offcanvas-header {
    border-bottom: 1px solid var(--border);
}

.offcanvas-cooca .offcanvas-title {
    font-weight: 800;
}

.offcanvas-cooca .btn-close {
    filter: invert(1);
}

[data-theme="light"] .offcanvas-cooca .btn-close {
    filter: none;
}

.offcanvas-cooca .nav-link-cooca {
    display: block;
    padding: 14px 0 !important;
    font-size: 1rem;
    border-bottom: 1px solid var(--border);
}

.offcanvas-cooca .nav-link-cooca::after {
    display: none;
}

.offcanvas-cooca .theme-toggle {
    width: 100%;
    height: auto;
    padding: 12px 16px;
    border-radius: 10px;
    justify-content: flex-start;
    gap: 10px;
    font-size: 0.9rem;
    font-weight: 500;
    background: var(--card-alt);
    border: 1px solid var(--border);
    color: var(--text);
}

.offcanvas-cooca .theme-toggle:hover {
    border-color: var(--accent);
    color: var(--accent);
    transform: none;
}
</style>

<script>
// Mobile theme toggle
document.addEventListener('DOMContentLoaded', function() {
    const themeToggleMobile = document.getElementById('themeToggleMobile');
    const themeIconMobile = document.getElementById('themeIconMobile');
    const html = document.documentElement;
    
    if (themeToggleMobile) {
        themeToggleMobile.addEventListener('click', function() {
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            if (themeIconMobile) {
                themeIconMobile.className = newTheme === 'dark' ? 'bi bi-moon-fill' : 'bi bi-sun-fill';
            }
            // Sync with desktop toggle
            const desktopIcon = document.getElementById('themeIcon');
            if (desktopIcon) {
                desktopIcon.className = newTheme === 'dark' ? 'bi bi-moon-fill' : 'bi bi-sun-fill';
            }
        });
    }
});
</script>
