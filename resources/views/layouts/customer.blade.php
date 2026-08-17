<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $siteName    = setting('site.name', 'COOCA.ID');
        $favicon     = setting('site.favicon') ? asset(setting('site.favicon')) : asset('favicon.svg');
        $customer    = auth('customer')->user();
        $companyName = $customer?->business_name ?? $customer?->name ?? 'Customer';
        $initials    = strtoupper(substr($companyName, 0, 2));
        $logoUrl     = $customer?->logo_url;
        $unread      = $customer ? $customer->notifications()->whereNull('read_at')->count() : 0;
        $planName    = $customer?->subscriptions()->active()->first()?->subscriptionPlan?->name ?? 'Free';
    @endphp

    <title>@yield('title', 'Customer Portal') — {{ $siteName }}</title>
    <link rel="icon" href="{{ $favicon }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Customer Portal CSS --}}
    <link rel="stylesheet" href="{{ asset('css/customer.css') }}">
    @stack('styles')
    @yield('styles')
</head>
<body>
<div class="portal-wrap">

    {{-- ════════════ SIDEBAR ════════════ --}}
    <aside class="portal-sidebar" id="portalSidebar">
        {{-- Header / Logo --}}
        <div class="sidebar-header">
            <div class="sidebar-logo-icon">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <div class="sidebar-brand">
                <div class="brand-name">{{ $siteName }}</div>
                <div class="brand-sub">Customer Portal</div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="sidebar-nav">

            {{-- OVERVIEW --}}
            <div class="sidebar-section">
                <div class="sidebar-section-label">Overview</div>

                <a href="{{ route('customer.dashboard') }}"
                   class="sidebar-item {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}"
                   data-tooltip="Dashboard">
                    <span class="s-icon"><i class="fa-solid fa-house"></i></span>
                    <span class="s-label">Dashboard</span>
                </a>
            </div>

            {{-- PRODUCTS & SERVICES --}}
            <div class="sidebar-section">
                <div class="sidebar-section-label">Products & Services</div>

                <a href="{{ route('customer.products.index') }}"
                   class="sidebar-item {{ request()->routeIs('customer.products.*', 'customer.subscriptions.*', 'customer.trials.*', 'customer.licenses.*') ? 'active' : '' }}"
                   data-tooltip="My Services">
                    <span class="s-icon"><i class="fa-solid fa-cube"></i></span>
                    <span class="s-label">My Services</span>
                </a>

                <a href="{{ route('customer.domains.index') }}"
                   class="sidebar-item {{ request()->routeIs('customer.domains.*') ? 'active' : '' }}"
                   data-tooltip="Domains">
                    <span class="s-icon"><i class="fa-solid fa-globe"></i></span>
                    <span class="s-label">Domains</span>
                </a>

                <a href="{{ route('customer.whatsapp-devices.index') }}"
                   class="sidebar-item {{ request()->routeIs('customer.whatsapp-devices.*') ? 'active' : '' }}"
                   data-tooltip="WhatsApp API">
                    <span class="s-icon"><i class="fa-brands fa-whatsapp"></i></span>
                    <span class="s-label">WhatsApp API Generator</span>
                </a>

                <a href="{{ route('customer.ai-usage.index') }}"
                   class="sidebar-item {{ request()->routeIs('customer.ai-usage.*') ? 'active' : '' }}"
                   data-tooltip="AI Platform & Keys">
                    <span class="s-icon"><i class="fa-solid fa-brain"></i></span>
                    <span class="s-label">AI Gateway & Keys</span>
                </a>
            </div>


            {{-- BILLING & PAYMENTS --}}
            <div class="sidebar-section">
                <div class="sidebar-section-label">Billing & Payments</div>

                <a href="{{ route('customer.invoices.index') }}"
                   class="sidebar-item {{ request()->routeIs('customer.invoices.*') ? 'active' : '' }}"
                   data-tooltip="Invoices">
                    <span class="s-icon"><i class="fa-solid fa-file-invoice"></i></span>
                    <span class="s-label">Invoices</span>
                </a>

                <a href="{{ route('customer.payments.index') }}"
                   class="sidebar-item {{ request()->routeIs('customer.payments.*') ? 'active' : '' }}"
                   data-tooltip="Payments">
                    <span class="s-icon"><i class="fa-solid fa-credit-card"></i></span>
                    <span class="s-label">Payments</span>
                </a>
            </div>

            {{-- SUPPORT --}}
            <div class="sidebar-section">
                <div class="sidebar-section-label">Support</div>

                <a href="{{ route('customer.tickets.index') }}"
                   class="sidebar-item {{ request()->routeIs('customer.tickets.*') ? 'active' : '' }}"
                   data-tooltip="Support Tickets">
                    <span class="s-icon"><i class="fa-solid fa-headset"></i></span>
                    <span class="s-label">Support Tickets</span>
                </a>

                <a href="{{ route('customer.reviews.index') }}"
                   class="sidebar-item {{ request()->routeIs('customer.reviews.*') ? 'active' : '' }}"
                   data-tooltip="My Reviews">
                    <span class="s-icon"><i class="fa-solid fa-star"></i></span>
                    <span class="s-label">My Reviews</span>
                </a>
            </div>

            {{-- PROJECT (if active) --}}
            <div class="sidebar-section">
                <div class="sidebar-section-label">Projects</div>

                <a href="{{ route('customer.projects.index') }}"
                   class="sidebar-item {{ request()->routeIs('customer.projects.*') ? 'active' : '' }}"
                   data-tooltip="My Projects">
                    <span class="s-icon"><i class="fa-solid fa-diagram-project"></i></span>
                    <span class="s-label">My Projects</span>
                </a>
            </div>

            {{-- ACCOUNT --}}
            <div class="sidebar-section">
                <div class="sidebar-section-label">Account</div>

                <a href="{{ route('customer.profile.edit') }}"
                   class="sidebar-item {{ request()->routeIs('customer.profile.*') ? 'active' : '' }}"
                   data-tooltip="Profile">
                    <span class="s-icon"><i class="fa-solid fa-user"></i></span>
                    <span class="s-label">Profile</span>
                </a>

                <a href="{{ route('customer.company-profile.edit') }}"
                   class="sidebar-item {{ request()->routeIs('customer.company-profile.*') ? 'active' : '' }}"
                   data-tooltip="Company Profile">
                    <span class="s-icon"><i class="fa-solid fa-building"></i></span>
                    <span class="s-label">Company Profile</span>
                </a>
            </div>

        </nav>

        {{-- Footer / User --}}
        <div class="sidebar-footer">
            <div class="sidebar-user" id="sidebarUserMenu">
                <div class="user-avatar">
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ $companyName }}">
                    @else
                        {{ $initials }}
                    @endif
                </div>
                <div class="user-info">
                    <div class="user-name">{{ $companyName }}</div>
                    <div class="user-plan">{{ $planName }} Plan</div>
                </div>
            </div>
            <button class="sidebar-toggle" id="sidebarToggleBtn">
                <i id="sidebarToggleIcon" class="fa-solid fa-chevrons-left"></i>
            </button>
        </div>
    </aside>

    {{-- ════════════ MAIN ════════════ --}}
    <main class="portal-main" id="portalMain">

        {{-- ── TOPBAR ── --}}
        <header class="portal-topbar">
            {{-- Breadcrumb --}}
            <div class="topbar-breadcrumb">
                <a href="{{ route('customer.dashboard') }}" class="crumb-link">
                    <i class="fa-solid fa-house" style="font-size:12px;"></i>
                </a>
                @hasSection('breadcrumb')
                    <span class="crumb-sep"><i class="fa-solid fa-chevron-right" style="font-size:9px;"></i></span>
                    @yield('breadcrumb')
                @endif
            </div>

            {{-- Search --}}
            <div class="topbar-search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Search products, invoices…" id="globalSearch">
                <span style="font-size:11px;color:var(--text-faint);background:var(--border);padding:2px 6px;border-radius:4px;">⌘K</span>
            </div>

            <div class="topbar-actions">
                {{-- Theme Toggle --}}
                <button class="topbar-btn" id="themeToggle" title="Toggle theme">
                    <i id="themeIcon" class="fa-solid fa-moon"></i>
                </button>

                {{-- Notifications --}}
                <button class="topbar-btn" title="Notifications">
                    <i class="fa-solid fa-bell"></i>
                    @if($unread > 0)
                        <span class="notif-badge">{{ $unread > 9 ? '9+' : $unread }}</span>
                    @endif
                </button>

                {{-- Profile Dropdown --}}
                <div class="topbar-profile" id="profileDropdownBtn">
                    <div class="user-avatar" style="width:30px;height:30px;font-size:12px;">
                        @if($logoUrl)
                            <img src="{{ $logoUrl }}" alt="{{ $companyName }}">
                        @else
                            {{ $initials }}
                        @endif
                    </div>
                    <div class="prof-info">
                        <div class="prof-name">{{ $companyName }}</div>
                        <div class="prof-plan">{{ $planName }}</div>
                    </div>
                    <i class="fa-solid fa-chevron-down prof-chevron" style="font-size:10px;color:var(--text-faint);"></i>

                    <div class="topbar-dropdown" id="profileDropdown">
                        <a href="{{ route('customer.dashboard') }}" class="dropdown-item">
                            <i class="fa-solid fa-house"></i> Dashboard
                        </a>
                        <a href="{{ route('customer.profile.edit') }}" class="dropdown-item">
                            <i class="fa-solid fa-user"></i> Profile
                        </a>
                        <a href="{{ route('customer.company-profile.edit') }}" class="dropdown-item">
                            <i class="fa-solid fa-building"></i> Company
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('customer.logout') }}" style="display:contents;">
                            @csrf
                            <button type="submit" class="dropdown-item danger" style="width:100%;border:none;background:none;cursor:pointer;text-align:left;">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i> Sign out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- ── SESSION FLASH ── --}}
        @if(session('success'))
            <div class="toast-wrap" id="toastWrap">
                <div class="toast toast-success">
                    <span class="toast-icon"><i class="fa-solid fa-circle-check" style="color:var(--success);"></i></span>
                    <div>
                        <div class="toast-title">Success</div>
                        <div class="toast-msg">{{ session('success') }}</div>
                    </div>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="toast-wrap" id="toastWrap">
                <div class="toast toast-error">
                    <span class="toast-icon"><i class="fa-solid fa-circle-xmark" style="color:var(--danger);"></i></span>
                    <div>
                        <div class="toast-title">Error</div>
                        <div class="toast-msg">{{ session('error') }}</div>
                    </div>
                </div>
            </div>
        @endif

        {{-- ── PAGE CONTENT ── --}}
        <div class="portal-content">
            @yield('content')
        </div>

    </main>
</div>

{{-- ── MOBILE BOTTOM NAV ── --}}
<nav class="mobile-nav">
    <div class="mobile-nav-inner">
        <a href="{{ route('customer.dashboard') }}" class="mobile-nav-item {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-house"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('customer.products.index') }}" class="mobile-nav-item {{ request()->routeIs('customer.products.*') ? 'active' : '' }}">
            <i class="fa-solid fa-cube"></i>
            <span>Products</span>
        </a>
        <a href="{{ route('customer.invoices.index') }}" class="mobile-nav-item {{ request()->routeIs('customer.invoices.*') ? 'active' : '' }}">
            <i class="fa-solid fa-file-invoice"></i>
            <span>Invoices</span>
        </a>
        <a href="{{ route('customer.tickets.index') }}" class="mobile-nav-item {{ request()->routeIs('customer.tickets.*') ? 'active' : '' }}">
            <i class="fa-solid fa-headset"></i>
            <span>Support</span>
        </a>
        <a href="{{ route('customer.profile.edit') }}" class="mobile-nav-item {{ request()->routeIs('customer.profile.*') ? 'active' : '' }}">
            <i class="fa-solid fa-user"></i>
            <span>Profile</span>
        </a>
    </div>
</nav>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // ── Theme Toggle ──
    const html       = document.documentElement;
    const themeBtn   = document.getElementById('themeToggle');
    const themeIcon  = document.getElementById('themeIcon');
    const savedTheme = localStorage.getItem('cooca-customer-theme') || 'light';
    html.setAttribute('data-theme', savedTheme);
    themeIcon.className = savedTheme === 'dark' ? 'fa-solid fa-sun' : 'fa-solid fa-moon';

    themeBtn?.addEventListener('click', () => {
        const current = html.getAttribute('data-theme');
        const next    = current === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-theme', next);
        localStorage.setItem('cooca-customer-theme', next);
        themeIcon.className = next === 'dark' ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
    });

    // ── Sidebar Collapse ──
    const sidebar     = document.getElementById('portalSidebar');
    const main        = document.getElementById('portalMain');
    const toggleBtn   = document.getElementById('sidebarToggleBtn');
    const toggleIcon  = document.getElementById('sidebarToggleIcon');

    if (localStorage.getItem('cooca-customer-sidebar') === 'collapsed') {
        sidebar?.classList.add('collapsed');
        main?.classList.add('sidebar-collapsed');
        if (toggleIcon) toggleIcon.className = 'fa-solid fa-chevrons-right';
    }

    toggleBtn?.addEventListener('click', () => {
        sidebar?.classList.toggle('collapsed');
        main?.classList.toggle('sidebar-collapsed');
        const isCollapsed = sidebar?.classList.contains('collapsed');
        localStorage.setItem('cooca-customer-sidebar', isCollapsed ? 'collapsed' : '');
        if (toggleIcon) toggleIcon.className = isCollapsed ? 'fa-solid fa-chevrons-right' : 'fa-solid fa-chevrons-left';
    });

    // ── Profile Dropdown ──
    const profileBtn      = document.getElementById('profileDropdownBtn');
    const profileDropdown = document.getElementById('profileDropdown');
    profileBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        profileDropdown?.classList.toggle('open');
    });
    document.addEventListener('click', () => profileDropdown?.classList.remove('open'));

    // ── Toast Auto-hide ──
    setTimeout(() => {
        const toast = document.getElementById('toastWrap');
        if (toast) { toast.style.opacity = '0'; toast.style.transition = 'opacity .5s'; setTimeout(() => toast.remove(), 500); }
    }, 4000);

    // ── ⌘K Search shortcut ──
    document.addEventListener('keydown', (e) => {
        if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
            e.preventDefault();
            document.getElementById('globalSearch')?.focus();
        }
    });
</script>
@stack('scripts')
@yield('scripts')
</body>
</html>
