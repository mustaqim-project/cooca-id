<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Admin Panel') - Cooca.id</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Custom Styles -->
    <style>
        :root {
            --sidebar-width: 260px;
            --header-height: 64px;
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
        }
        
        /* Sidebar Styles */
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
            border-bottom: 1px solid #f3f4f6;
            padding: 0 1.5rem;
        }
        
        .sidebar-brand a {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            text-decoration: none;
        }
        
        .sidebar-brand span {
            color: var(--primary-color);
        }
        
        .sidebar-nav {
            padding: 1.5rem 1rem;
        }
        
        .nav-group-title {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            margin-bottom: 0.75rem;
            padding-left: 0.75rem;
        }
        
        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.625rem 0.75rem;
            margin-bottom: 0.25rem;
            border-radius: 0.5rem;
            color: #374151;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .nav-item:hover {
            background-color: #f3f4f6;
            color: #111827;
        }
        
        .nav-item.active {
            background-color: #111827;
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
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
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
        
        .user-info {
            display: none;
        }
        
        @media (min-width: 768px) {
            .user-info {
                display: block;
            }
        }
        
        .user-name {
            font-size: 0.875rem;
            font-weight: 500;
            color: #111827;
            margin: 0;
        }
        
        .user-email {
            font-size: 0.75rem;
            color: #6b7280;
            margin: 0;
        }
        
        /* Page Content */
        .page-content {
            flex: 1;
            padding: 2rem;
        }
        
        .page-header {
            margin-bottom: 2rem;
        }
        
        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            margin: 0 0 0.25rem 0;
        }
        
        .page-subtitle {
            color: #6b7280;
            margin: 0;
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
            transition: transform 0.2s, box-shadow 0.2s;
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
        
        .stat-icon.indigo { background: #e0e7ff; color: #4f46e5; }
        .stat-icon.green { background: #dcfce7; color: #16a34a; }
        .stat-icon.blue { background: #dbeafe; color: #2563eb; }
        .stat-icon.yellow { background: #fef3c7; color: #d97706; }
        
        .stat-value {
            font-size: 1.875rem;
            font-weight: 700;
            color: #111827;
            margin: 0.5rem 0 0 0;
        }
        
        .stat-label {
            font-size: 0.875rem;
            color: #6b7280;
            margin: 0;
        }
        
        .stat-change {
            font-size: 0.75rem;
            font-weight: 500;
            margin-top: 0.25rem;
        }
        
        .stat-change.positive { color: #16a34a; }
        .stat-change.negative { color: #dc2626; }
        
        /* Tables */
        .table-container {
            background: white;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }
        
        .table {
            margin: 0;
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
            transition: all 0.2s;
        }
        
        .btn-primary:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
        }
        
        .btn-outline-danger {
            background: transparent;
            border-color: #dc2626;
            color: #dc2626;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
        }
        
        .btn-outline-danger:hover {
            background: #dc2626;
            color: white;
        }
        
        /* Mobile Toggle */
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #6b7280;
            cursor: pointer;
        }
        
        @media (max-width: 768px) {
            .mobile-toggle {
                display: block;
            }
            
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }
        
        /* Search Input */
        .search-input {
            background: #f3f4f6;
            border: none;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            font-size: 0.875rem;
            width: 100%;
            max-width: 320px;
        }
        
        .search-input:focus {
            outline: none;
            background: white;
            box-shadow: 0 0 0 2px var(--primary-color);
        }
        
        .search-wrapper {
            position: relative;
        }
        
        .search-wrapper i {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}">Cooca<span>.id</span></a>
        </div>
        
        <nav class="sidebar-nav">
            @php
                $navigationGroups = [
                    [
                        'title' => 'Main',
                        'items' => [
                            ['name' => 'Dashboard', 'href' => route('admin.dashboard'), 'icon' => 'bi-house-door'],
                        ]
                    ],
                    [
                        'title' => 'Users',
                        'items' => [
                            ['name' => 'Customers', 'href' => route('admin.customers.index'), 'icon' => 'bi-people'],
                            ['name' => 'Affiliators', 'href' => route('admin.affiliators.index'), 'icon' => 'bi-person-badge'],
                        ]
                    ],
                    [
                        'title' => 'Catalog',
                        'items' => [
                            ['name' => 'Products', 'href' => route('admin.products.index'), 'icon' => 'bi-box'],
                            ['name' => 'Categories', 'href' => route('admin.product-categories.index'), 'icon' => 'bi-tag'],
                            ['name' => 'Subscriptions', 'href' => route('admin.subscriptions.index'), 'icon' => 'bi-calendar'],
                            ['name' => 'Licenses', 'href' => route('admin.licenses.index'), 'icon' => 'bi-key'],
                        ]
                    ],
                    [
                        'title' => 'Sales & Finance',
                        'items' => [
                            ['name' => 'Transactions', 'href' => route('admin.transactions.index'), 'icon' => 'bi-currency-dollar'],
                            ['name' => 'Settlements', 'href' => route('admin.settlements.index'), 'icon' => 'bi-cash-stack'],
                            ['name' => 'Vouchers', 'href' => route('admin.vouchers.index'), 'icon' => 'bi-ticket'],
                            ['name' => 'ERP Requests', 'href' => route('admin.erp-requests.index'), 'icon' => 'bi-server'],
                        ]
                    ],
                    [
                        'title' => 'Content',
                        'items' => [
                            ['name' => 'CMS Pages', 'href' => route('admin.cms.pages.index'), 'icon' => 'bi-file-text'],
                            ['name' => 'Blog', 'href' => route('admin.blog.index'), 'icon' => 'bi-newspaper'],
                            ['name' => 'FAQs', 'href' => route('admin.faqs.index'), 'icon' => 'bi-question-circle'],
                            ['name' => 'Testimonials', 'href' => route('admin.testimonials.index'), 'icon' => 'bi-chat-quote'],
                            ['name' => 'Reviews', 'href' => route('admin.reviews.index'), 'icon' => 'bi-star'],
                        ]
                    ],
                    [
                        'title' => 'Communication',
                        'items' => [
                            ['name' => 'Email Campaigns', 'href' => route('admin.email-campaigns.index'), 'icon' => 'bi-envelope'],
                            ['name' => 'Email Templates', 'href' => route('admin.email-templates.index'), 'icon' => 'bi-files'],
                            ['name' => 'Tickets', 'href' => route('admin.tickets.index'), 'icon' => 'bi-inbox'],
                        ]
                    ],
                    [
                        'title' => 'System',
                        'items' => [
                            ['name' => 'Settings', 'href' => route('admin.settings.index'), 'icon' => 'bi-gear'],
                        ]
                    ],
                ];
            @endphp
            
            @foreach($navigationGroups as $group)
                <div class="mb-4">
                    <h6 class="nav-group-title">{{ $group['title'] }}</h6>
                    @foreach($group['items'] as $item)
                        @php
                            $isActive = request()->is(ltrim(parse_url($item['href'], PHP_URL_PATH), '/'));
                        @endphp
                        <a href="{{ $item['href'] }}" class="nav-item {{ $isActive ? 'active' : '' }}">
                            <i class="bi {{ $item['icon'] }}"></i>
                            {{ $item['name'] }}
                        </a>
                    @endforeach
                </div>
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
                
                <div class="search-wrapper d-none d-sm-block">
                    <i class="bi bi-search"></i>
                    <input type="text" class="search-input" placeholder="Search anywhere...">
                </div>
                
                <div class="user-menu">
                    <div class="user-info text-end">
                        <p class="user-name">{{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="user-email">{{ auth()->user()->email ?? 'admin@cooca.id' }}</p>
                    </div>
                    <div class="user-avatar">
                        {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                    </div>
                    <form action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
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
        
        // Close sidebar when clicking outside on mobile
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
