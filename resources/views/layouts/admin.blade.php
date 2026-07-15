<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Admin Panel') - Cooca.id</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />

    <!-- App CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <!-- Mobile sidebar backdrop -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- Sidebar -->
    <aside class="app-sidebar" id="appSidebar">
        <!-- Brand -->
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand-link">
                Cooca<span class="sidebar-brand-accent">.id</span>
            </a>
        </div>

        <!-- Navigation -->
        <div class="sidebar-nav-wrap">
            @include('layouts.partials.admin-nav')
        </div>

        <!-- Logout -->
        <div class="sidebar-footer">
            <form class="form-confirm-submit" method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="sidebar-logout-btn">
                    <i class="bi bi-box-arrow-right sidebar-logout-icon"></i>
                    <span>Sign out</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main -->
    <div class="app-main" id="appMain">

        <!-- Top Header -->
        <header class="app-header">
            <div class="header-left">
                <!-- Hamburger -->
                <button class="header-hamburger" id="sidebarToggle" aria-label="Toggle sidebar">
                    <i class="bi bi-list"></i>
                </button>

                <!-- Breadcrumb / page title -->
                <div class="header-breadcrumb">
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    <span class="separator">/</span>
                    <span class="current">@yield('title', 'Dashboard')</span>
                </div>
            </div>

            <div class="header-right">
                <!-- Dark mode toggle -->
                <button class="header-icon-btn" id="darkModeToggle" title="Toggle dark mode">
                    <i class="bi bi-moon-stars" id="darkModeIcon"></i>
                </button>

                <!-- Notifications -->
                <button class="header-icon-btn" title="Notifications">
                    <i class="bi bi-bell"></i>
                    <span class="badge-dot"></span>
                </button>

                <!-- Divider -->
                <div class="vr opacity-25" style="height:24px; margin:0 4px;"></div>

                <!-- Profile dropdown -->
                <div class="dropdown">
                    <button class="header-user-btn" data-bs-toggle="dropdown" id="profileDropdown">
                        <div class="header-user-avatar">
                            {{ strtoupper(substr(auth('admin')->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <span class="header-user-name">
                            {{ auth('admin')->user()->name ?? 'Admin' }}
                        </span>
                        <i class="bi bi-chevron-down header-user-chevron"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm mt-1">
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2"
                                href="{{ route('admin.settings.index') }}">
                                <i class="bi bi-person text-secondary"></i> Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2"
                                href="{{ route('admin.settings.index') }}">
                                <i class="bi bi-gear text-secondary"></i> Settings
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
        <main class="app-content">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ── Dark Mode ──────────────────────────────────────────
        (function() {
            if (localStorage.getItem('adminDarkMode') === 'true') {
                document.documentElement.setAttribute('data-bs-theme', 'dark');
                document.getElementById('darkModeIcon')?.classList.replace('bi-moon-stars', 'bi-sun');
            }
        })();

        document.getElementById('darkModeToggle')?.addEventListener('click', function() {
            const html = document.documentElement;
            const isDark = html.getAttribute('data-bs-theme') === 'dark';
            html.setAttribute('data-bs-theme', isDark ? 'light' : 'dark');
            localStorage.setItem('adminDarkMode', String(!isDark));
            const icon = document.getElementById('darkModeIcon');
            icon?.classList.replace(isDark ? 'bi-sun' : 'bi-moon-stars', isDark ? 'bi-moon-stars' : 'bi-sun');
        });

        // ── Sidebar Toggle ─────────────────────────────────────
        const sidebar = document.getElementById('appSidebar');
        const main = document.getElementById('appMain');
        const backdrop = document.getElementById('sidebarBackdrop');
        const toggle = document.getElementById('sidebarToggle');

        function openSidebar() {
            sidebar.classList.add('open');
            backdrop.classList.add('show');
        }

        function closeSidebar() {
            sidebar.classList.remove('open');
            backdrop.classList.remove('show');
        }

        function toggleCollapse() {
            // Desktop: collapse/expand
            if (window.innerWidth >= 992) {
                sidebar.classList.toggle('collapsed');
                main.classList.toggle('sidebar-collapsed');
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            } else {
                sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
            }
        }

        toggle?.addEventListener('click', toggleCollapse);
        backdrop?.addEventListener('click', closeSidebar);

        // Restore collapsed state on desktop
        if (window.innerWidth >= 992 && localStorage.getItem('sidebarCollapsed') === 'true') {
            sidebar.classList.add('collapsed');
            main.classList.add('sidebar-collapsed');
        }

        // ── Confirm Delete ─────────────────────────────────────
        document.addEventListener('submit', function(e) {
            if (e.target.classList.contains('form-confirm-delete')) {
                e.preventDefault();
                if (confirm('Yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.')) {
                    e.target.submit();
                }
            }
            if (e.target.classList.contains('form-confirm-submit')) {
                e.preventDefault();
                if (confirm('Yakin ingin melanjutkan tindakan ini?')) {
                    e.target.submit();
                }
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
