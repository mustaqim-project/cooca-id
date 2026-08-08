<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $siteName     = setting('site.name', 'COOCA.ID');
        $favicon      = setting('site.favicon') ? asset(setting('site.favicon')) : asset('favicon.svg');
        $affiliator   = auth('affiliator')->user() ?? auth()->user();
        $userName     = $affiliator?->name ?? 'Partner';
        $initials     = strtoupper(substr($userName, 0, 2));
        $referralCode = $affiliator?->referral_code ?? '';
        $unread       = $affiliator ? $affiliator->notifications()->whereNull('read_at')->count() : 0;
        $balance      = number_format((float) ($affiliator?->balance ?? 0), 0, ',', '.');
    @endphp

    <title>@yield('title', 'Affiliate Portal') — {{ $siteName }}</title>
    <link rel="icon" href="{{ $favicon }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Customer Design System CSS --}}
    <link rel="stylesheet" href="{{ asset('css/customer.css') }}">
    @stack('styles')
</head>
<body>
<div class="portal-wrap">

    {{-- ════════════ SIDEBAR ════════════ --}}
    <aside class="portal-sidebar" id="portalSidebar">
        {{-- Header / Logo --}}
        <div class="sidebar-header">
            <div class="sidebar-logo-icon">
                <i class="fa-solid fa-handshake"></i>
            </div>
            <div class="sidebar-brand">
                <div class="brand-name">{{ $siteName }}</div>
                <div class="brand-sub">Affiliate Portal</div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="sidebar-nav">

            {{-- OVERVIEW --}}
            <div class="sidebar-section">
                <div class="sidebar-section-label">Overview</div>

                <a href="{{ route('affiliator.dashboard') }}"
                   class="sidebar-item {{ request()->routeIs('affiliator.dashboard') ? 'active' : '' }}"
                   data-tooltip="Dashboard">
                    <span class="s-icon"><i class="fa-solid fa-house"></i></span>
                    <span class="s-label">Dashboard</span>
                </a>
            </div>

            {{-- NETWORK & EARNINGS --}}
            <div class="sidebar-section">
                <div class="sidebar-section-label">Network & Earnings</div>

                <a href="{{ route('affiliator.referrals.index') }}"
                   class="sidebar-item {{ request()->routeIs('affiliator.referrals.*') ? 'active' : '' }}"
                   data-tooltip="My Referrals">
                    <span class="s-icon"><i class="fa-solid fa-users"></i></span>
                    <span class="s-label">My Referrals</span>
                </a>

                <a href="{{ route('affiliator.downlines.index') }}"
                   class="sidebar-item {{ request()->routeIs('affiliator.downlines.*') ? 'active' : '' }}"
                   data-tooltip="Downlines">
                    <span class="s-icon"><i class="fa-solid fa-sitemap"></i></span>
                    <span class="s-label">Downlines</span>
                </a>

                <a href="{{ route('affiliator.commissions.index') }}"
                   class="sidebar-item {{ request()->routeIs('affiliator.commissions.*') ? 'active' : '' }}"
                   data-tooltip="Commissions">
                    <span class="s-icon"><i class="fa-solid fa-wallet"></i></span>
                    <span class="s-label">Commissions</span>
                </a>

                <a href="{{ route('affiliator.withdrawals.index') }}"
                   class="sidebar-item {{ request()->routeIs('affiliator.withdrawals.*') ? 'active' : '' }}"
                   data-tooltip="Withdrawals">
                    <span class="s-icon"><i class="fa-solid fa-building-columns"></i></span>
                    <span class="s-label">Withdrawals</span>
                </a>
            </div>

            {{-- MARKETING & TOOLS --}}
            <div class="sidebar-section">
                <div class="sidebar-section-label">Marketing & Tools</div>

                <a href="{{ route('affiliator.marketing_materials.index') }}"
                   class="sidebar-item {{ request()->routeIs('affiliator.marketing.*', 'affiliator.marketing_materials.*') ? 'active' : '' }}"
                   data-tooltip="Marketing Assets">
                    <span class="s-icon"><i class="fa-solid fa-bullhorn"></i></span>
                    <span class="s-label">Marketing Assets</span>
                </a>

                <a href="{{ route('affiliator.reviews.index') }}"
                   class="sidebar-item {{ request()->routeIs('affiliator.reviews.*') ? 'active' : '' }}"
                   data-tooltip="My Reviews">
                    <span class="s-icon"><i class="fa-solid fa-star"></i></span>
                    <span class="s-label">My Reviews</span>
                </a>
            </div>

            {{-- ACCOUNT --}}
            <div class="sidebar-section">
                <div class="sidebar-section-label">Account</div>

                <a href="{{ route('affiliator.profile.edit') }}"
                   class="sidebar-item {{ request()->routeIs('affiliator.profile.*') ? 'active' : '' }}"
                   data-tooltip="Profile & Bank">
                    <span class="s-icon"><i class="fa-solid fa-user-gear"></i></span>
                    <span class="s-label">Profile & Bank</span>
                </a>
            </div>

        </nav>

        {{-- Footer / User --}}
        <div class="sidebar-footer">
            <div class="sidebar-user" id="sidebarUserMenu">
                <div class="user-avatar">
                    {{ $initials }}
                </div>
                <div class="user-info">
                    <div class="user-name">{{ $userName }}</div>
                    <div class="user-plan">Balance: Rp {{ $balance }}</div>
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
                <a href="{{ route('affiliator.dashboard') }}" class="crumb-link">
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
                <input type="text" placeholder="Search referrals, commissions…" id="globalSearch">
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
                        {{ $initials }}
                    </div>
                    <div class="prof-info">
                        <div class="prof-name">{{ $userName }}</div>
                        <div class="prof-plan">Rp {{ $balance }}</div>
                    </div>
                    <i class="fa-solid fa-chevron-down prof-chevron" style="font-size:10px;color:var(--text-faint);"></i>

                    <div class="topbar-dropdown" id="profileDropdown">
                        <a href="{{ route('affiliator.dashboard') }}" class="dropdown-item">
                            <i class="fa-solid fa-house"></i> Dashboard
                        </a>
                        <a href="{{ route('affiliator.profile.edit') }}" class="dropdown-item">
                            <i class="fa-solid fa-user-gear"></i> Profile & Bank
                        </a>
                        <a href="{{ route('affiliator.marketing_materials.index') }}" class="dropdown-item">
                            <i class="fa-solid fa-bullhorn"></i> Marketing Materials
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('affiliator.logout') }}" style="display:contents;">
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
        <a href="{{ route('affiliator.dashboard') }}" class="mobile-nav-item {{ request()->routeIs('affiliator.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-house"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('affiliator.referrals.index') }}" class="mobile-nav-item {{ request()->routeIs('affiliator.referrals.*') ? 'active' : '' }}">
            <i class="fa-solid fa-users"></i>
            <span>Referrals</span>
        </a>
        <a href="{{ route('affiliator.commissions.index') }}" class="mobile-nav-item {{ request()->routeIs('affiliator.commissions.*') ? 'active' : '' }}">
            <i class="fa-solid fa-wallet"></i>
            <span>Earnings</span>
        </a>
        <a href="{{ route('affiliator.withdrawals.index') }}" class="mobile-nav-item {{ request()->routeIs('affiliator.withdrawals.*') ? 'active' : '' }}">
            <i class="fa-solid fa-building-columns"></i>
            <span>Payouts</span>
        </a>
        <a href="{{ route('affiliator.profile.edit') }}" class="mobile-nav-item {{ request()->routeIs('affiliator.profile.*') ? 'active' : '' }}">
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
    const savedTheme = localStorage.getItem('cooca-affiliator-theme') || 'light';
    html.setAttribute('data-theme', savedTheme);
    themeIcon.className = savedTheme === 'dark' ? 'fa-solid fa-sun' : 'fa-solid fa-moon';

    themeBtn?.addEventListener('click', () => {
        const current = html.getAttribute('data-theme');
        const next    = current === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-theme', next);
        localStorage.setItem('cooca-affiliator-theme', next);
        themeIcon.className = next === 'dark' ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
    });

    // ── Sidebar Collapse ──
    const sidebar     = document.getElementById('portalSidebar');
    const main        = document.getElementById('portalMain');
    const toggleBtn   = document.getElementById('sidebarToggleBtn');
    const toggleIcon  = document.getElementById('sidebarToggleIcon');

    if (localStorage.getItem('cooca-affiliator-sidebar') === 'collapsed') {
        sidebar?.classList.add('collapsed');
        main?.classList.add('sidebar-collapsed');
        if (toggleIcon) toggleIcon.className = 'fa-solid fa-chevrons-right';
    }

    toggleBtn?.addEventListener('click', () => {
        sidebar?.classList.toggle('collapsed');
        main?.classList.toggle('sidebar-collapsed');
        const isCollapsed = sidebar?.classList.contains('collapsed');
        localStorage.setItem('cooca-affiliator-sidebar', isCollapsed ? 'collapsed' : '');
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

    // ── Copy text utility ──
    window.copyToClipboard = function(text, label = 'Referral Link') {
        navigator.clipboard.writeText(text).then(() => {
            alert(label + ' copied to clipboard!');
        }).catch(err => {
            console.error('Failed to copy: ', err);
        });
    };
</script>
@stack('scripts')
</body>
</html>
