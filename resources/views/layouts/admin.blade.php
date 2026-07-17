<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Admin Panel') - Cooca.id</title>

    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- ApexCharts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.49.0/dist/apexcharts.css" />

    <!-- App CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Enterprise SaaS CSS Variables */
        :root {
            --bs-primary: #0F172A;
            --bs-primary-rgb: 15, 23, 42;
            --bs-secondary: #64748B;
            --bs-success: #10B981;
            --bs-info: #3B82F6;
            --bs-warning: #F59E0B;
            --bs-danger: #EF4444;
            --bs-light: #F8FAFC;
            --bs-dark: #0F172A;

            --bs-body-font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            --bs-body-bg: #F8FAFC;
            --bs-body-color: #334155;

            --admin-sidebar-width: 260px;
            --admin-sidebar-collapsed: 72px;
            --admin-header-height: 64px;

            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.5);
            --glass-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);

            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);

            --radius-md: 0.5rem;
            --radius-lg: 0.75rem;
            --radius-xl: 1rem;
            --radius-2xl: 1.5rem;
        }

        [data-bs-theme="dark"] {
            --bs-primary: #38BDF8;
            --bs-body-bg: #0B1120;
            --bs-body-color: #CBD5E1;

            --glass-bg: rgba(15, 23, 42, 0.7);
            --glass-border: rgba(30, 41, 59, 0.5);
            --glass-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);
        }

        body {
            font-family: var(--bs-body-font-family);
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        [data-bs-theme="dark"] body {
            background: #0f172a;
        }

        .loading-skeleton {
            background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
            background-size: 200% 100%;
            animation: skeleton-loading 1.5s infinite;
            border-radius: 8px;
        }

        @keyframes skeleton-loading {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.4s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    @stack('styles')
</head>

<body class="admin-body">

    <!-- Loading Overlay -->
    <div id="pageLoader" class="page-loader">
        <div class="loader-spinner"></div>
        <p class="loader-text">Loading...</p>
    </div>

    <!-- Mobile sidebar backdrop -->
    <div class="sidebar-backdrop" id="sidebarBackdrop" role="button" tabindex="0" aria-label="Close sidebar"></div>

    <!-- Sidebar -->
    <aside class="app-sidebar shadow-sm" id="appSidebar" role="navigation" aria-label="Main navigation">
        <!-- Brand -->
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand-link" aria-label="Go to dashboard">
                <span class="sidebar-brand-text">
                    Cooca<span class="sidebar-brand-accent">.id</span>
                </span>
            </a>
            <button class="sidebar-collapse-btn d-none d-lg-flex" id="sidebarCollapseBtn" aria-label="Collapse sidebar"
                style="background:transparent;border:none;color:#fff;">
                <i class="bi bi-chevron-left"></i>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="sidebar-nav-wrap" id="sidebarNav" role="menubar">
            @include('layouts.partials.admin-nav')
        </nav>

    </aside>

    <!-- Main Content Wrapper -->
    <div class="app-main" id="appMain">

        <!-- Top Header -->
        <header class="app-header" role="banner">
            <div class="header-left">
                <!-- Hamburger Toggle -->
                <button class="header-hamburger" id="sidebarToggle" aria-label="Toggle sidebar" aria-expanded="false">
                    <i class="bi bi-list"></i>
                </button>

                <!-- Breadcrumb -->
                <nav class="header-breadcrumb" aria-label="Breadcrumb">
                    <ol class="breadcrumb-list">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">
                                <i class="bi bi-house-door"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-separator">/</li>
                        <li class="breadcrumb-item active" aria-current="page">
                            @yield('title', 'Dashboard')
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="header-right">
                <!-- Global Search -->
                <div class="header-search desktop-only">
                    <div class="search-input-wrapper">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" class="header-search-input" placeholder="Quick search... (Ctrl+K)"
                            aria-label="Global search" id="globalSearchInput">
                        <kbd class="search-shortcut">Ctrl+K</kbd>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="header-actions">
                    <!-- Theme Toggle -->
                    <button class="header-action-btn" id="darkModeToggle" title="Toggle dark mode"
                        aria-label="Toggle dark mode">
                        <i class="bi bi-moon-stars-fill theme-icon-light"></i>
                        <i class="bi bi-sun-fill theme-icon-dark"></i>
                    </button>

                    <!-- Fullscreen -->
                    <button class="header-action-btn" id="fullscreenToggle" title="Toggle fullscreen"
                        aria-label="Toggle fullscreen">
                        <i class="bi bi-fullscreen"></i>
                    </button>

                    <!-- Notifications -->
                    <button class="header-action-btn notification-btn" title="Notifications"
                        aria-label="View notifications">
                        <i class="bi bi-bell"></i>
                        <span class="notification-badge">3</span>
                    </button>

                    <!-- Messages -->
                    <button class="header-action-btn" title="Messages" aria-label="View messages">
                        <i class="bi bi-envelope"></i>
                        <span class="notification-badge">1</span>
                    </button>
                </div>

                <!-- Divider -->
                <div class="header-divider"></div>

                <!-- Profile Dropdown -->
                <div class="dropdown profile-dropdown">
                    <button class="profile-btn" data-bs-toggle="dropdown" id="profileDropdown"
                        aria-expanded="false">
                        <div class="profile-avatar">
                            {{ strtoupper(substr(auth('admin')->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="profile-info desktop-only">
                            <span class="profile-name">{{ auth('admin')->user()->name ?? 'Admin' }}</span>
                            <span class="profile-role">Admin</span>
                        </div>
                        <i class="bi bi-chevron-down profile-chevron"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end profile-menu shadow-lg">
                        <li class="dropdown-header">
                            <div class="profile-avatar-sm">
                                {{ strtoupper(substr(auth('admin')->user()->name ?? 'A', 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold">{{ auth('admin')->user()->name ?? 'Admin' }}</div>
                                <div class="text-muted small">Administrator</div>
                            </div>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2"
                                href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2"
                                href="{{ route('admin.settings.index') }}">
                                <i class="bi bi-person"></i> Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2"
                                href="{{ route('admin.settings.index') }}">
                                <i class="bi bi-gear"></i> Settings
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="#">
                                <i class="bi bi-question-circle"></i> Help Center
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form class="form-confirm-submit" method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit"
                                    class="dropdown-item d-flex align-items-center gap-2 text-danger">
                                    <i class="bi bi-box-arrow-right"></i> Sign out
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="app-content" role="main">
            <!-- Toast Container -->
            <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;" id="toastContainer">
            </div>

            <!-- Flash Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('warning'))
                <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('info'))
                <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="app-footer">
            <div class="footer-content">
                <span class="copyright">&copy; {{ date('Y') }} Cooca.id. All rights reserved.</span>
                <div class="footer-links">
                    <a href="#" class="footer-link">Privacy Policy</a>
                    <a href="#" class="footer-link">Terms of Service</a>
                    <a href="#" class="footer-link">Help Center</a>
                </div>
            </div>
        </footer>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.49.0/dist/apexcharts.min.js"></script>

    <script>
        // ── Page Loader ──────────────────────────────────────
        window.addEventListener('load', function() {
            const loader = document.getElementById('pageLoader');
            if (loader) {
                setTimeout(() => {
                    loader.classList.add('loaded');
                    setTimeout(() => loader.remove(), 300);
                }, 500);
            }
        });

        // ── Dark Mode ────────────────────────────────────────
        (function() {
            const html = document.documentElement;
            const savedTheme = localStorage.getItem('adminDarkMode');

            if (savedTheme === 'true') {
                html.setAttribute('data-bs-theme', 'dark');
            }

            updateThemeIcon();
        })();

        document.getElementById('darkModeToggle')?.addEventListener('click', function() {
            const html = document.documentElement;
            const isDark = html.getAttribute('data-bs-theme') === 'dark';
            const newTheme = isDark ? 'light' : 'dark';

            html.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('adminDarkMode', String(!isDark));

            // Animate transition
            document.body.style.transition = 'background-color 0.3s ease, color 0.3s ease';
            updateThemeIcon();

            // Dispatch event for charts to re-render
            window.dispatchEvent(new CustomEvent('themechange', {
                detail: {
                    isDark: !isDark
                }
            }));
        });

        function updateThemeIcon() {
            const html = document.documentElement;
            const isDark = html.getAttribute('data-bs-theme') === 'dark';
            const icon = document.getElementById('darkModeToggle');
            if (icon) {
                icon.querySelector('.theme-icon-light').style.display = isDark ? 'none' : 'block';
                icon.querySelector('.theme-icon-dark').style.display = isDark ? 'block' : 'none';
            }
        }

        // ── Fullscreen Toggle ───────────────────────────────
        document.getElementById('fullscreenToggle')?.addEventListener('click', function() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {
                    console.log('Fullscreen request failed:', err);
                });
                this.querySelector('i').classList.replace('bi-fullscreen', 'bi-fullscreen-exit');
            } else {
                document.exitFullscreen();
                this.querySelector('i').classList.replace('bi-fullscreen-exit', 'bi-fullscreen');
            }
        });

        // ── Sidebar Toggle & Collapse ───────────────────────
        const sidebar = document.getElementById('appSidebar');
        const main = document.getElementById('appMain');
        const backdrop = document.getElementById('sidebarBackdrop');
        const toggle = document.getElementById('sidebarToggle');
        const collapseBtn = document.getElementById('sidebarCollapseBtn');

        function openSidebar() {
            sidebar.classList.add('open');
            backdrop.classList.add('show');
            toggle?.setAttribute('aria-expanded', 'true');
        }

        function closeSidebar() {
            sidebar.classList.remove('open');
            backdrop.classList.remove('show');
            toggle?.setAttribute('aria-expanded', 'false');
        }

        function toggleSidebar() {
            if (window.innerWidth >= 992) {
                sidebar.classList.toggle('collapsed');
                main.classList.toggle('sidebar-collapsed');
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));

                // Update collapse button icon
                const icon = collapseBtn?.querySelector('i');
                if (sidebar.classList.contains('collapsed')) {
                    icon?.classList.replace('bi-chevron-left', 'bi-chevron-right');
                } else {
                    icon?.classList.replace('bi-chevron-right', 'bi-chevron-left');
                }
            } else {
                sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
            }
        }

        toggle?.addEventListener('click', toggleSidebar);
        collapseBtn?.addEventListener('click', toggleSidebar);
        backdrop?.addEventListener('click', closeSidebar);

        // Restore collapsed state on desktop
        if (window.innerWidth >= 992 && localStorage.getItem('sidebarCollapsed') === 'true') {
            sidebar?.classList.add('collapsed');
            main?.classList.add('sidebar-collapsed');
            collapseBtn?.querySelector('i')?.classList.replace('bi-chevron-left', 'bi-chevron-right');
        }

        // ── Keyboard Shortcuts ──────────────────────────────
        document.addEventListener('keydown', function(e) {
            // Ctrl+K for global search
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                document.getElementById('globalSearchInput')?.focus();
            }

            // Escape to close sidebar on mobile
            if (e.key === 'Escape') {
                closeSidebar();
            }
        });

        // ── Menu Search ─────────────────────────────────────
        document.getElementById('menuSearchInput')?.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase();
            const navItems = document.querySelectorAll('.sidebar-nav-item');

            navItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(query) ? '' : 'none';
            });
        });

        // ── Confirm Delete ──────────────────────────────────
        document.addEventListener('submit', function(e) {
            if (e.target.classList.contains('form-confirm-delete')) {
                e.preventDefault();
                if (confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                    e.target.submit();
                }
            }
            if (e.target.classList.contains('form-confirm-submit')) {
                e.preventDefault();
                if (confirm('Are you sure you want to proceed with this action?')) {
                    e.target.submit();
                }
            }
        });

        // ── Toast Notification Helper ───────────────────────
        function showToast(message, type = 'info', duration = 5000) {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-white bg-${type} border-0 show`;
            toast.setAttribute('role', 'alert');
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : type === 'warning' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            `;
            container.appendChild(toast);

            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, duration);
        }

        // ── Animate Elements on Scroll ──────────────────────
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in-up');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.card-saas, .stat-card').forEach(el => {
            el.style.opacity = '0';
            observer.observe(el);
        });
    </script>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 500,
            easing: 'ease-out-cubic',
            once: true
        });
    </script>

    @stack('scripts')
</body>

</html>
