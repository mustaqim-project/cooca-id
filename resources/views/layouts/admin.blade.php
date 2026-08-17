<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-admin-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $siteName = setting('site.name', 'COOCA.ID');
        $favicon = setting('site.favicon') ? asset(setting('site.favicon')) : asset('favicon.svg');
        $logoLight = setting('site.logo_light') ? asset(setting('site.logo_light')) : (setting('site.logo') ? asset(setting('site.logo')) : null);
        $logoDark = setting('site.logo_dark') ? asset(setting('site.logo_dark')) : (setting('site.logo') ? asset(setting('site.logo')) : null);
    @endphp

    <title>@yield('title', 'Admin Console — ' . $siteName)</title>

    {{-- Favicon --}}
    <link rel="icon" href="{{ $favicon }}" type="image/svg+xml">

    {{-- Fonts & Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    {{-- Font Awesome 6 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Custom Admin CSS --}}
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('styles')
</head>
<body class="admin-body">

    <div class="admin-shell">
        {{-- SIDEBAR --}}
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-logo">
                <a href="{{ route('admin.dashboard') }}" style="display: flex; align-items: center; text-decoration: none; width: 100%; height: 100%;">
                    @if($logoLight)
                        <img src="{{ $logoLight }}" alt="{{ $siteName }}" class="admin-logo-light" style="max-height: 42px; max-width: 190px; width: auto; height: auto; object-fit: contain;">
                    @endif
                    @if($logoDark)
                        <img src="{{ $logoDark }}" alt="{{ $siteName }}" class="admin-logo-dark" style="max-height: 42px; max-width: 190px; width: auto; height: auto; object-fit: contain; {{ $logoLight ? 'display: none;' : '' }}">
                    @endif
                    @if(!$logoLight && !$logoDark)
                        <div class="sidebar-logo-icon">
                            <i class="fa-solid fa-rocket"></i>
                        </div>
                        <div class="sidebar-logo-text">
                            <span class="brand">{{ $siteName }}</span>
                            <span class="tagline">Enterprise Console</span>
                        </div>
                    @endif
                </a>
            </div>

            <nav class="sidebar-nav">
                {{-- Overview --}}
                <div class="sidebar-group">
                    <div class="sidebar-group-label">Overview</div>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" data-tooltip="Dashboard">
                        <span class="s-icon"><i class="fa-solid fa-chart-pie"></i></span>
                        <span class="s-label">Dashboard</span>
                    </a>
                </div>

                {{-- Core SaaS & Products --}}
                <div class="sidebar-group">
                    <div class="sidebar-group-label">Core SaaS & Products</div>
                    <a href="{{ route('admin.erp-requests.index') }}" class="sidebar-item {{ request()->routeIs('admin.erp-requests.*') ? 'active' : '' }}" data-tooltip="Pengajuan ERP">
                        <span class="s-icon"><i class="fa-solid fa-server"></i></span>
                        <span class="s-label">Pengajuan ERP</span>
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="sidebar-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" data-tooltip="Products">
                        <span class="s-icon"><i class="fa-solid fa-box"></i></span>
                        <span class="s-label">Products</span>
                    </a>
                    <a href="{{ route('admin.product-categories.index') }}" class="sidebar-item {{ request()->routeIs('admin.product-categories.*') ? 'active' : '' }}" data-tooltip="Categories">
                        <span class="s-icon"><i class="fa-solid fa-tags"></i></span>
                        <span class="s-label">Categories</span>
                    </a>
                    <a href="{{ route('admin.customers.index') }}" class="sidebar-item {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}" data-tooltip="Customers">
                        <span class="s-icon"><i class="fa-solid fa-users"></i></span>
                        <span class="s-label">Customers</span>
                    </a>
                    <a href="{{ route('admin.licenses.index') }}" class="sidebar-item {{ request()->routeIs('admin.licenses.*') ? 'active' : '' }}" data-tooltip="Licenses">
                        <span class="s-icon"><i class="fa-solid fa-key"></i></span>
                        <span class="s-label">Licenses</span>
                    </a>
                    <a href="{{ route('admin.subscriptions.index') }}" class="sidebar-item {{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}" data-tooltip="Subscriptions">
                        <span class="s-icon"><i class="fa-solid fa-arrows-rotate"></i></span>
                        <span class="s-label">Subscriptions</span>
                    </a>
                    <a href="{{ route('admin.trials.index') }}" class="sidebar-item {{ request()->routeIs('admin.trials.*') ? 'active' : '' }}" data-tooltip="Trials">
                        <span class="s-icon"><i class="fa-solid fa-flask"></i></span>
                        <span class="s-label">Trials</span>
                    </a>
                    <a href="{{ route('admin.ai.dashboard') }}" class="sidebar-item {{ request()->routeIs('admin.ai.*') ? 'active' : '' }}" data-tooltip="AI Gateway & Models">
                        <span class="s-icon"><i class="fa-solid fa-brain"></i></span>
                        <span class="s-label">AI Gateway & LLM</span>
                    </a>
                </div>

                {{-- Finance & Accounting --}}
                <div class="sidebar-group">
                    <div class="sidebar-group-label">Finance & Accounting</div>
                    <a href="{{ route('admin.finance.index') }}" class="sidebar-item {{ request()->routeIs('admin.finance.*') ? 'active' : '' }}" data-tooltip="Ikhtisar Keuangan">
                        <span class="s-icon"><i class="fa-solid fa-chart-line"></i></span>
                        <span class="s-label">Ikhtisar Keuangan</span>
                    </a>
                    <a href="{{ route('admin.transactions.index') }}" class="sidebar-item {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}" data-tooltip="Transactions">
                        <span class="s-icon"><i class="fa-solid fa-credit-card"></i></span>
                        <span class="s-label">Transactions</span>
                    </a>
                    <a href="{{ route('admin.accounting.coa.index') }}" class="sidebar-item {{ request()->routeIs('admin.accounting.coa.*') ? 'active' : '' }}" data-tooltip="Chart of Accounts">
                        <span class="s-icon"><i class="fa-solid fa-list-ul"></i></span>
                        <span class="s-label">Chart of Accounts</span>
                    </a>
                    <a href="{{ route('admin.accounting.journal.index') }}" class="sidebar-item {{ request()->routeIs('admin.accounting.journal.*') ? 'active' : '' }}" data-tooltip="Jurnal Umum">
                        <span class="s-icon"><i class="fa-solid fa-book-journal-whills"></i></span>
                        <span class="s-label">Jurnal Umum</span>
                    </a>
                    <a href="{{ route('admin.accounting.reports.ledger') }}" class="sidebar-item {{ request()->routeIs('admin.accounting.reports.ledger') ? 'active' : '' }}" data-tooltip="Buku Besar">
                        <span class="s-icon"><i class="fa-solid fa-book-open"></i></span>
                        <span class="s-label">Buku Besar</span>
                    </a>
                    <a href="{{ route('admin.accounting.reports.profit-loss') }}" class="sidebar-item {{ request()->routeIs('admin.accounting.reports.profit-loss') ? 'active' : '' }}" data-tooltip="Laba Rugi">
                        <span class="s-icon"><i class="fa-solid fa-file-invoice-dollar"></i></span>
                        <span class="s-label">Laba Rugi</span>
                    </a>
                    <a href="{{ route('admin.reports.payments.index') }}" class="sidebar-item {{ request()->routeIs('admin.reports.payments.*') ? 'active' : '' }}" data-tooltip="Laporan Metode Pembayaran">
                        <span class="s-icon"><i class="fa-solid fa-chart-pie"></i></span>
                        <span class="s-label">Laporan Metode Bayar</span>
                    </a>
                    <a href="{{ route('admin.settlements.index') }}" class="sidebar-item {{ request()->routeIs('admin.settlements.*') ? 'active' : '' }}" data-tooltip="Settlements">
                        <span class="s-icon"><i class="fa-solid fa-money-bill-transfer"></i></span>
                        <span class="s-label">Settlements</span>
                    </a>
                    <a href="{{ route('admin.bank-accounts.index') }}" class="sidebar-item {{ request()->routeIs('admin.bank-accounts.*') ? 'active' : '' }}" data-tooltip="Rekening Perusahaan">
                        <span class="s-icon"><i class="fa-solid fa-building-columns"></i></span>
                        <span class="s-label">Rekening Bank (CMS)</span>
                    </a>
                </div>

                {{-- Growth & Sales --}}
                <div class="sidebar-group">
                    <div class="sidebar-group-label">Growth & Sales</div>
                    <a href="{{ route('admin.affiliators.index') }}" class="sidebar-item {{ request()->routeIs('admin.affiliators.*') ? 'active' : '' }}" data-tooltip="Affiliators">
                        <span class="s-icon"><i class="fa-solid fa-handshake"></i></span>
                        <span class="s-label">Affiliators</span>
                    </a>
                    <a href="{{ route('admin.vouchers.index') }}" class="sidebar-item {{ request()->routeIs('admin.vouchers.*') ? 'active' : '' }}" data-tooltip="Vouchers">
                        <span class="s-icon"><i class="fa-solid fa-ticket"></i></span>
                        <span class="s-label">Vouchers</span>
                    </a>
                </div>

                {{-- Support & Communications --}}
                <div class="sidebar-group">
                    <div class="sidebar-group-label">Support & Communications</div>
                    <a href="{{ route('admin.live-chats.index') }}" class="sidebar-item {{ request()->routeIs('admin.live-chats.*') ? 'active' : '' }}" data-tooltip="Live Chat Support">
                        <span class="s-icon"><i class="fa-solid fa-comments"></i></span>
                        <span class="s-label">Live Chat Support</span>
                    </a>
                    <a href="{{ route('admin.whatsapp-devices.index') }}" class="sidebar-item {{ request()->routeIs('admin.whatsapp-devices.*') ? 'active' : '' }}" data-tooltip="WhatsApp API">
                        <span class="s-icon"><i class="fa-brands fa-whatsapp"></i></span>
                        <span class="s-label">WhatsApp Gateway</span>
                    </a>
                    <a href="{{ route('admin.tickets.index') }}" class="sidebar-item {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}" data-tooltip="Tickets">
                        <span class="s-icon"><i class="fa-solid fa-headset"></i></span>
                        <span class="s-label">Tickets</span>
                    </a>
                    <a href="{{ route('admin.deals.index') }}" class="sidebar-item {{ request()->routeIs('admin.deals.*') || request()->routeIs('admin.pipelines.*') ? 'active' : '' }}" data-tooltip="CRM Pipeline">
                        <span class="s-icon"><i class="fa-solid fa-bullseye"></i></span>
                        <span class="s-label">CRM Pipeline</span>
                    </a>
                    <a href="{{ route('admin.projects.index') }}" class="sidebar-item {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}" data-tooltip="Projects">
                        <span class="s-icon"><i class="fa-solid fa-list-check"></i></span>
                        <span class="s-label">Projects</span>
                    </a>
                </div>

                {{-- Content & Marketing --}}
                <div class="sidebar-group">
                    <div class="sidebar-group-label">Content & Marketing</div>
                    <a href="{{ route('admin.cms.landing.index') }}" class="sidebar-item {{ request()->routeIs('admin.cms.landing.*') ? 'active' : '' }}" data-tooltip="Landing Page CMS">
                        <span class="s-icon"><i class="fa-solid fa-palette"></i></span>
                        <span class="s-label">Landing CMS</span>
                    </a>
                    <a href="{{ route('admin.cms.pages.index') }}" class="sidebar-item {{ request()->routeIs('admin.cms.pages.*') ? 'active' : '' }}" data-tooltip="Pages CMS">
                        <span class="s-icon"><i class="fa-solid fa-file"></i></span>
                        <span class="s-label">Custom Pages</span>
                    </a>
                    <a href="{{ route('admin.blog.index') }}" class="sidebar-item {{ request()->routeIs('admin.blog.*') ? 'active' : '' }}" data-tooltip="Blog Articles">
                        <span class="s-icon"><i class="fa-solid fa-newspaper"></i></span>
                        <span class="s-label">Blog Articles</span>
                    </a>
                    <a href="{{ route('admin.faqs.index') }}" class="sidebar-item {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}" data-tooltip="FAQs">
                        <span class="s-icon"><i class="fa-solid fa-circle-question"></i></span>
                        <span class="s-label">FAQs</span>
                    </a>
                    <a href="{{ route('admin.testimonials.index') }}" class="sidebar-item {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}" data-tooltip="Testimonials">
                        <span class="s-icon"><i class="fa-solid fa-comment-dots"></i></span>
                        <span class="s-label">Testimonials</span>
                    </a>
                    <a href="{{ route('admin.reviews.index') }}" class="sidebar-item {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}" data-tooltip="Reviews">
                        <span class="s-icon"><i class="fa-solid fa-star"></i></span>
                        <span class="s-label">Reviews</span>
                    </a>
                    <a href="{{ route('admin.email-campaigns.index') }}" class="sidebar-item {{ request()->routeIs('admin.email-campaigns.*') ? 'active' : '' }}" data-tooltip="Email Broadcast">
                        <span class="s-icon"><i class="fa-solid fa-envelope"></i></span>
                        <span class="s-label">Email Broadcast</span>
                    </a>
                </div>

                {{-- System & Security --}}
                <div class="sidebar-group">
                    <div class="sidebar-group-label">System & Security</div>
                    <a href="{{ route('admin.api-integrations.index') }}" class="sidebar-item {{ request()->routeIs('admin.api-integrations.*') ? 'active' : '' }}" data-tooltip="API Integrations">
                        <span class="s-icon"><i class="fa-solid fa-plug"></i></span>
                        <span class="s-label">Integrations</span>
                    </a>
                    <a href="{{ route('admin.email-templates.index') }}" class="sidebar-item {{ request()->routeIs('admin.email-templates.*') ? 'active' : '' }}" data-tooltip="Email Templates">
                        <span class="s-icon"><i class="fa-solid fa-envelope-open-text"></i></span>
                        <span class="s-label">Email Templates</span>
                    </a>
                    <a href="{{ route('admin.audit-logs.index') }}" class="sidebar-item {{ request()->routeIs('admin.audit-logs.*') ? 'active' : '' }}" data-tooltip="Audit Logs">
                        <span class="s-icon"><i class="fa-solid fa-shield"></i></span>
                        <span class="s-label">Audit Logs</span>
                    </a>
                    <a href="{{ route('admin.error-logs.index') }}" class="sidebar-item {{ request()->routeIs('admin.error-logs.*') ? 'active' : '' }}" data-tooltip="Error Logs">
                        <span class="s-icon"><i class="fa-solid fa-triangle-exclamation"></i></span>
                        <span class="s-label">Error Logs</span>
                    </a>
                    <a href="{{ route('admin.blocked-ips.index') }}" class="sidebar-item {{ request()->routeIs('admin.blocked-ips.*') ? 'active' : '' }}" data-tooltip="Blocked IPs">
                        <span class="s-icon"><i class="fa-solid fa-ban"></i></span>
                        <span class="s-label">Blocked IPs</span>
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="sidebar-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" data-tooltip="System Settings">
                        <span class="s-icon"><i class="fa-solid fa-gear"></i></span>
                        <span class="s-label">Settings</span>
                    </a>
                </div>
            </nav>

            <div class="sidebar-footer">
                <button class="sidebar-collapse-btn" id="sidebarToggle" title="Toggle Sidebar">
                    <i id="sidebarToggleIcon" class="fa-solid fa-chevron-left"></i>
                </button>
            </div>
        </aside>

        {{-- MAIN --}}
        <main class="admin-main" id="adminMain">
            {{-- TOPBAR --}}
            <header class="admin-topbar">
                <div class="topbar-search">
                    <span class="topbar-search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" placeholder="Search customers, licenses, transactions... (Press '/' to focus)" id="globalSearch">
                    <span class="topbar-search-kbd">⌘K</span>
                </div>

                <div class="topbar-actions">
                    <button class="topbar-btn" id="themeToggle" title="Toggle Light/Dark Theme">
                        <i id="themeIcon" class="fa-solid fa-sun"></i>
                    </button>

                    <div class="topbar-divider"></div>

                    <div class="topbar-profile-container">
                        <div class="topbar-profile" id="profileDropdownBtn">
                            <div class="topbar-avatar">
                                {{ substr(auth('admin')->user()->name ?? 'Admin', 0, 2) }}
                                <span class="online-dot"></span>
                            </div>
                            <div class="topbar-profile-info">
                                <span class="topbar-profile-name">{{ auth('admin')->user()->name ?? 'Administrator' }}</span>
                                <span class="topbar-profile-role">Super Admin</span>
                            </div>
                            <i class="fa-solid fa-chevron-down" style="font-size: 10px; margin-left: 4px; color: var(--text-muted);"></i>
                        </div>

                        <div class="topbar-dropdown-menu" id="profileDropdownMenu">
                            <div class="dropdown-header">
                                <div class="dropdown-user-name">{{ auth('admin')->user()->name ?? 'Administrator' }}</div>
                                <div class="dropdown-user-email">{{ auth('admin')->user()->email ?? 'admin@cooca.id' }}</div>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('admin.profile.edit') }}" class="dropdown-item">
                                <i class="fa-solid fa-user-gear"></i>
                                <span>Profil & Ganti Password</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('admin.logout') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="dropdown-item logout-btn">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            {{-- CONTENT --}}
            <div class="admin-content">
                @if(session('success'))
                    <div class="toast-container">
                        <div class="toast">
                            <span class="toast-icon"><i class="fa-solid fa-circle-check"></i></span>
                            <div>
                                <div class="toast-title">Success</div>
                                <div class="toast-desc">{{ session('success') }}</div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="toast-container">
                        <div class="toast" style="border-left: 3px solid var(--danger);">
                            <span class="toast-icon"><i class="fa-solid fa-circle-xmark"></i></span>
                            <div>
                                <div class="toast-title">Error</div>
                                <div class="toast-desc">{{ session('error') }}</div>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    {{-- Chart.js CDN for Analytics --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- TinyMCE Initialization for Textareas -->
    <script src="https://cdn.tiny.cloud/1/24k0c573h901jvrr06jiqwo75byzannzhejp9zlo610wriru/tinymce/8/tinymce.min.js"
        referrerpolicy="origin" crossorigin="anonymous"></script>
    <script>
        tinymce.init({
            selector: 'textarea.tinymce, textarea.rich-editor, textarea#content, textarea#body',
            plugins: [
                'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'link', 'lists', 'media',
                'searchreplace', 'table', 'visualblocks', 'wordcount'
            ],
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        });
    </script>

    <script>
        // Theme switching (now using Font Awesome icons)
        const themeBtn = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const html = document.documentElement;

        function updateAdminLogo(theme) {
            const lightImg = document.querySelector('.admin-logo-light');
            const darkImg = document.querySelector('.admin-logo-dark');
            if (lightImg && darkImg) {
                if (theme === 'dark') {
                    lightImg.style.display = 'none';
                    darkImg.style.display = 'block';
                } else {
                    lightImg.style.display = 'block';
                    darkImg.style.display = 'none';
                }
            }
        }

        const savedTheme = localStorage.getItem('cooca-admin-theme') || 'light';
        html.setAttribute('data-admin-theme', savedTheme);
        themeIcon.className = savedTheme === 'dark' ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
        updateAdminLogo(savedTheme);

        themeBtn.addEventListener('click', () => {
            const current = html.getAttribute('data-admin-theme');
            const next = current === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-admin-theme', next);
            localStorage.setItem('cooca-admin-theme', next);
            themeIcon.className = next === 'dark' ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
            updateAdminLogo(next);
        });

        // Sidebar collapse (now using FA chevron icons)
        const sidebar = document.getElementById('adminSidebar');
        const main = document.getElementById('adminMain');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarToggleIcon = document.getElementById('sidebarToggleIcon');

        const savedSidebar = localStorage.getItem('cooca-sidebar-collapsed');
        if (savedSidebar === 'true') {
            sidebar.classList.add('collapsed');
            main.classList.add('sidebar-collapsed');
            sidebarToggleIcon.className = 'fa-solid fa-chevron-right';
        }

        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            main.classList.toggle('sidebar-collapsed');
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('cooca-sidebar-collapsed', isCollapsed);
            sidebarToggleIcon.className = isCollapsed ? 'fa-solid fa-chevron-right' : 'fa-solid fa-chevron-left';
        });

        // Quick Search focus shortcut
        document.addEventListener('keydown', (e) => {
            if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                e.preventDefault();
                document.getElementById('globalSearch').focus();
            }
        });

        // Profile Dropdown Toggle
        const profileBtn = document.getElementById('profileDropdownBtn');
        const profileMenu = document.getElementById('profileDropdownMenu');

        if (profileBtn && profileMenu) {
            profileBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                profileMenu.classList.toggle('show');
            });

            document.addEventListener('click', (e) => {
                if (!profileMenu.contains(e.target) && !profileBtn.contains(e.target)) {
                    profileMenu.classList.remove('show');
                }
            });
        }
    </script>
    @stack('scripts')
    @yield('scripts')
</body>
</html>