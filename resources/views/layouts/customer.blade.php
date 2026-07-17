<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Customer Dashboard') - {{ config('app.name', 'Cooca ID') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <!-- AOS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Custom CSS Variables and Base Styles -->
    <style>
        :root {
            --bs-body-font-family: 'Inter', system-ui, -apple-system, sans-serif;
            --color-bg: #f8f9fa;
            --color-surface: #ffffff;
            --color-border: rgba(0, 0, 0, 0.08);
            --color-text-primary: #111827;
            --color-text-secondary: #6b7280;
            --color-primary: #0f172a;
            --color-primary-rgb: 15, 23, 42;
            --color-accent: #3b82f6;
            --color-success: #10b981;
            --color-warning: #f59e0b;
            --color-danger: #ef4444;
            --color-info: #0ea5e9;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --radius-md: 12px;
            --radius-lg: 16px;
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 80px;
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        [data-theme="dark"] {
            --color-bg: #0f172a;
            --color-surface: #1e293b;
            --color-border: rgba(255, 255, 255, 0.1);
            --color-text-primary: #f8fafc;
            --color-text-secondary: #94a3b8;
            --bs-body-bg: var(--color-bg);
            --bs-body-color: var(--color-text-primary);
        }

        body {
            background-color: var(--color-bg);
            color: var(--color-text-primary);
            font-family: var(--bs-body-font-family);
            transition: var(--transition-smooth);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        #app-wrapper { display: flex; min-height: 100vh; }
        #main-content {
            flex-grow: 1;
            margin-left: var(--sidebar-width);
            transition: var(--transition-smooth);
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        .sidebar-collapsed #main-content { margin-left: var(--sidebar-collapsed-width); }
        @media (max-width: 991.98px) {
            #main-content { margin-left: 0; }
            .sidebar-collapsed #main-content { margin-left: 0; }
        }

        h1, h2, h3, h4, h5, h6 { font-weight: 600; letter-spacing: -0.025em; }
        
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--color-border); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--color-text-secondary); }

        .skeleton {
            background: linear-gradient(90deg, var(--color-border) 25%, var(--color-bg) 50%, var(--color-border) 75%);
            background-size: 200% 100%;
            animation: skeleton-loading 1.5s infinite;
            border-radius: 4px;
        }
        @keyframes skeleton-loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
    </style>
    @stack('styles')
</head>

<body>
    <div id="app-wrapper">
        <x-customer.sidebar />
        <main id="main-content">
            <x-customer.topbar />
            <div class="container-fluid p-0 animate__animated animate__fadeIn">
                @yield('content')
            </div>
        </main>
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastPlacement"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        AOS.init({ once: true, duration: 600 });

        const themeToggle = document.getElementById('theme-toggle');
        const root = document.documentElement;
        const storedTheme = localStorage.getItem('theme');
        if (storedTheme) {
            root.setAttribute('data-theme', storedTheme);
        } else if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            root.setAttribute('data-theme', 'dark');
        }
        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                const newTheme = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                root.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
            });
        }

        const sidebarToggle = document.getElementById('sidebar-toggle');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                document.body.classList.toggle('sidebar-collapsed');
            });
        }

        window.showToast = (title, message, type = 'info') => {
            const container = document.getElementById('toastPlacement');
            const icons = { success: 'check-circle', danger: 'exclamation-circle', warning: 'exclamation-triangle', info: 'info-circle' };
            const colors = { success: 'text-success', danger: 'text-danger', warning: 'text-warning', info: 'text-info' };
            const toastEl = document.createElement('div');
            toastEl.className = 'toast animate__animated animate__fadeInUp';
            toastEl.innerHTML = `
                <div class="toast-header border-0 pb-0">
                    <i class="bi bi-${icons[type]} ${colors[type]} me-2 fs-5"></i>
                    <strong class="me-auto">${title}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body text-secondary">${message}</div>
            `;
            container.appendChild(toastEl);
            const toast = new bootstrap.Toast(toastEl, { autohide: true, delay: 5000 });
            toast.show();
            toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
        };

        document.addEventListener('DOMContentLoaded', () => {
            @if (session('success')) showToast('Success', '{{ session('success') }}', 'success'); @endif
            @if (session('error')) showToast('Error', '{{ session('error') }}', 'danger'); @endif
            @if (session('warning')) showToast('Warning', '{{ session('warning') }}', 'warning'); @endif
            @if (session('info')) showToast('Info', '{{ session('info') }}', 'info'); @endif
        });
    </script>

    <script src="{{ asset('js/admin-table-tools.js') }}"></script>
    @stack('scripts')
</body>
</html>
