<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Customer Dashboard') - Cooca.id</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-default@5/default.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --sidebar-width: 260px;
            --header-height: 64px;
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: #ffffff;
            box-shadow: 2px 0 8px rgba(0,0,0,0.05);
            z-index: 1000;
            overflow-y: auto;
            transition: transform 0.3s ease-in-out;
        }
        
        .sidebar-brand {
            height: var(--header-height);
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .sidebar-brand a {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .sidebar-nav {
            padding: 1.5rem 1rem;
        }
        
        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            margin-bottom: 0.25rem;
            border-radius: 0.5rem;
            color: #374151;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .nav-item:hover {
            background-color: #eef2ff;
            color: var(--primary-color);
        }
        
        .nav-item.active {
            background-color: var(--primary-color);
            color: #ffffff;
        }
        
        .nav-item i {
            margin-right: 0.75rem;
            font-size: 1.125rem;
            width: 20px;
            text-align: center;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Header */
        .top-header {
            height: var(--header-height);
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        .header-content {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
            border: 2px solid #e5e7eb;
        }
        
        .user-info { display: none; }
        @media (min-width: 768px) { .user-info { display: block; } }
        
        .user-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: #111827;
            margin: 0;
        }
        
        .user-business {
            font-size: 0.75rem;
            color: #6b7280;
            margin: 0;
        }
        
        /* Page Content */
        .page-content {
            flex: 1;
            padding: 1.5rem;
        }
        
        @media (min-width: 768px) {
            .page-content {
                padding: 2rem;
            }
        }
        
        /* Cards */
        .card {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        .stat-card {
            background: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            border: 1px solid #e5e7eb;
            transition: transform 0.2s;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .stat-icon.indigo { background: #e0e7ff; }
        .stat-icon.green { background: #dcfce7; }
        .stat-icon.blue { background: #dbeafe; }
        .stat-icon.yellow { background: #fef3c7; }
        
        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            margin: 0.25rem 0 0 0;
        }
        
        .stat-label {
            font-size: 0.875rem;
            color: #6b7280;
            margin: 0;
        }
        
        /* Tables */
        .table-container {
            background: white;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }
        
        .table thead th {
            background: #f9fafb;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f3f4f6;
            font-size: 0.875rem;
        }
        
        .table tbody tr:hover {
            background: #f9fafb;
        }
        
        /* Badges */
        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .badge-success { background: #dcfce7; color: #16a34a; }
        .badge-warning { background: #fef3c7; color: #d97706; }
        .badge-danger { background: #fee2e2; color: #dc2626; }
        .badge-info { background: #dbeafe; color: #2563eb; }
        .badge-secondary { background: #f3f4f6; color: #4b5563; }
        
        /* Buttons */
        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
        }
        
        .btn-primary:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
            color: white;
        }
        
        /* Mobile */
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #6b7280;
        }
        
        @media (max-width: 768px) {
            .mobile-toggle { display: block; }
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <a href="{{ route('customer.dashboard') }}">Cooca.id</a>
        </div>
        
        <nav class="sidebar-nav">
            @php
                $navigation = [
                    ['name' => 'Dashboard', 'href' => route('customer.dashboard'), 'icon' => 'bi-house-door'],
                    ['name' => 'Produk', 'href' => route('customer.products.index'), 'icon' => 'bi-box'],
                    ['name' => 'Subscripsi Saya', 'href' => route('customer.subscriptions.index'), 'icon' => 'bi-arrow-repeat'],
                    ['name' => 'Lisensi', 'href' => route('customer.licenses.index'), 'icon' => 'bi-key'],
                    ['name' => 'Invoice', 'href' => route('customer.invoices.index'), 'icon' => 'bi-file-text'],
                ];
            @endphp
            
            @foreach($navigation as $item)
                @php
                    $isActive = request()->is(ltrim(parse_url($item['href'], PHP_URL_PATH), '/'));
                @endphp
                <a href="{{ $item['href'] }}" class="nav-item {{ $isActive ? 'active' : '' }}">
                    <i class="bi {{ $item['icon'] }}"></i>
                    {{ $item['name'] }}
                </a>
            @endforeach
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div class="header-content">
                <button class="mobile-toggle" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                
                <div class="ms-auto d-flex align-items-center gap-3">
                    <div class="user-info text-end">
                        <p class="user-name">{{ auth()->guard('customer')->user()->name ?? 'Customer' }}</p>
                        <p class="user-business">{{ auth()->guard('customer')->user()->business_name ?? '-' }}</p>
                    </div>
                    <div class="user-avatar">
                        {{ substr(auth()->guard('customer')->user()->name ?? 'C', 0, 1) }}
                    </div>
                    <form action="{{ route('customer.logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger">Logout</button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="page-content">
            @if(session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: '{{ session('success') }}',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    });
                </script>
            @endif
            
            @if(session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: '{{ session('error') }}',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    });
                </script>
            @endif
            
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }
        
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-toggle');
            
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !toggle.contains(event.target) && sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                }
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
