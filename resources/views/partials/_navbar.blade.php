{{-- Navigation - Dynamic from Database --}}
@php
    $siteSettings = setting()->all();
    $mainMenu = \App\Models\Menu::where('location', 'main')->where('active', true)->with('children')->orderBy('order')->get();
    $logo = setting('branding.logo', null);
    $logoText = setting('branding.name', config('app.name', 'COOCA'));
@endphp

<nav class="navbar-cooca" id="mainNav">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            {{-- Brand --}}
            <a href="{{ url('/') }}" class="navbar-brand-cooca">
                @if($logo)
                    <img src="{{ asset('storage/' . $logo) }}" alt="{{ $logoText }}" style="height: 36px;">
                @else
                    <div class="logo-icon">{{ substr($logoText, 0, 1) }}</div>
                @endif
                {{ $logoText }}
            </a>
            
            {{-- Desktop Menu --}}
            <div class="d-none d-lg-flex align-items-center gap-1">
                @forelse($mainMenu as $menu)
                    @if($menu->children->count() > 0)
                        <div class="dropdown">
                            <a class="nav-link-cooca dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                {{ $menu->title }}
                            </a>
                            <ul class="dropdown-menu">
                                @foreach($menu->children as $child)
                                    <li><a class="dropdown-item" href="{{ $child->url }}">{{ $child->title }}</a></li>
                                @endforeach
                            </ul>
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
            
            {{-- Right Side Actions --}}
            <div class="d-flex align-items-center gap-3">
                {{-- Theme Toggle --}}
                <button class="theme-toggle d-none d-lg-flex" id="themeToggle" aria-label="Toggle theme">
                    <i class="bi bi-moon-fill" id="themeIcon"></i>
                </button>
                
                {{-- Login Dropdown --}}
                <div class="dropdown d-none d-md-inline-block">
                    <button class="btn dropdown-toggle dropdown-cooca" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Login <i class="bi bi-chevron-down small"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('customer.login') }}">Client Login</a></li>
                        <li><a class="dropdown-item" href="{{ route('affiliate.login') }}">Affiliate Login</a></li>
                    </ul>
                </div>
                
                {{-- CTA Button --}}
                @php
                    $ctaText = setting('cta.button_text', 'Start Free Trial');
                    $ctaUrl = setting('cta.button_url', route('pricing'));
                @endphp
                <a href="{{ $ctaUrl }}" class="btn-cooca btn-cooca-primary btn-cooca-sm d-none d-md-inline-flex">
                    {{ $ctaText }}
                </a>
                
                {{-- Mobile Menu Toggle --}}
                <button class="btn btn-outline-secondary d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" style="border-color: var(--border); color: var(--text); border-radius: 10px; padding: 8px 12px;">
                    <i class="bi bi-list" style="font-size: 1.3rem"></i>
                </button>
            </div>
        </div>
    </div>
</nav>

<style>
/* Navbar Styles */
.navbar-cooca {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1050;
    padding: 16px 0;
    transition: all var(--transition);
    background: transparent;
}

.navbar-cooca.scrolled {
    padding: 10px 0;
    background: var(--glass);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-bottom: 1px solid var(--glass-border);
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
}

.navbar-brand-cooca {
    font-size: 1.6rem;
    font-weight: 800;
    letter-spacing: -0.03em;
    color: var(--text) !important;
    display: flex;
    align-items: center;
    gap: 10px;
}

.navbar-brand-cooca .logo-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: linear-gradient(135deg, var(--primary), var(--accent));
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.1rem;
    font-weight: 800;
}

.nav-link-cooca {
    color: var(--text-muted) !important;
    font-weight: 500;
    font-size: 0.9rem;
    padding: 8px 16px !important;
    transition: color var(--transition);
    position: relative;
}

.nav-link-cooca:hover,
.nav-link-cooca.active {
    color: var(--accent) !important;
}

.nav-link-cooca::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 2px;
    background: var(--accent);
    transition: width var(--transition);
    border-radius: 1px;
}

.nav-link-cooca:hover::after,
.nav-link-cooca.active::after {
    width: 60%;
}

.theme-toggle {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    border: 1px solid var(--border);
    background: var(--card);
    color: var(--text);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all var(--transition);
    font-size: 1.1rem;
}

.theme-toggle:hover {
    border-color: var(--accent);
    color: var(--accent);
    transform: rotate(20deg);
}

.dropdown-cooca .dropdown-toggle {
    background: transparent;
    border: none;
    color: var(--text-muted);
    font-weight: 500;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 4px;
}

.dropdown-cooca .dropdown-menu {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    box-shadow: var(--shadow);
    padding: 8px 0;
}

.dropdown-cooca .dropdown-item {
    color: var(--text);
    padding: 10px 20px;
    transition: background 0.2s;
    font-size: 0.9rem;
}

.dropdown-cooca .dropdown-item:hover {
    background: rgba(56, 189, 248, 0.1);
    color: var(--accent);
}
</style>

<script>
// Navbar scroll effect
window.addEventListener('scroll', function() {
    const navbar = document.getElementById('mainNav');
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});

// Theme toggle
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    const html = document.documentElement;
    
    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            if (themeIcon) {
                themeIcon.className = newTheme === 'dark' ? 'bi bi-moon-fill' : 'bi bi-sun-fill';
            }
        });
        
        // Load saved theme
        const savedTheme = localStorage.getItem('theme') || 'dark';
        html.setAttribute('data-theme', savedTheme);
        if (themeIcon) {
            themeIcon.className = savedTheme === 'dark' ? 'bi bi-moon-fill' : 'bi bi-sun-fill';
        }
    }
});
</script>
