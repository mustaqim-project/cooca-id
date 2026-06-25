<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Affiliator Dashboard') - Cooca.id</title>
    
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
        
        .balance-badge {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
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
        
        .user-balance {
            font-size: 0.75rem;
            color: #10b981;
            font-weight: 600;
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
        
        /* Level Cards */
        .level-card {
            border-radius: 0.75rem;
            padding: 1.5rem;
            color: white;
        }
        
        .level-card.level-1 {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }
        
        .level-card.level-2 {
            background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%);
        }
        
        /* Tables */
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
        .badge-blue { background: #dbeafe; color: #1d4ed8; }
        .badge-purple { background: #f3e8ff; color: #7e22ce; }
        
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
        
        /* Tabs */
        .nav-tabs .nav-link {
            color: #6b7280;
            border: none;
            border-bottom: 2px solid transparent;
            padding: 0.75rem 1rem;
            font-weight: 500;
        }
        
        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
            background: transparent;
        }
        
        .nav-tabs .nav-link:hover {
            border-color: #e5e7eb;
            color: #111827;
        }
        
        /* Referral Code Box */
        .referral-box {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            border-radius: 0.75rem;
            padding: 1.5rem;
            color: white;
        }
        
        .referral-code {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-family: monospace;
            font-size: 1.25rem;
            font-weight: 600;
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
            <a href="{{ route('affiliator.dashboard') }}">Cooca.id</a>
        </div>
        
        <nav class="sidebar-nav">
            @php
                $navigation = [
                    ['name' => 'Dashboard', 'href' => route('affiliator.dashboard'), 'icon' => 'bi-house-door'],
                    ['name' => 'Referrals', 'href' => route('affiliator.referrals.index'), 'icon' => 'bi-person-plus'],
                    ['name' => 'Commissions', 'href' => route('affiliator.commissions.index'), 'icon' => 'bi-currency-dollar'],
                    ['name' => 'Downlines', 'href' => route('affiliator.downlines.index'), 'icon' => 'bi-people'],
                    ['name' => 'Withdrawals', 'href' => route('affiliator.withdrawals.index'), 'icon' => 'bi-cash-stack'],
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
                        <p class="user-name">{{ auth()->guard('affiliator')->user()->name ?? 'Affiliator' }}</p>
                        <p class="user-balance">
                            Balance: Rp {{ number_format(auth()->guard('affiliator')->user()->balance ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="user-avatar">
                        {{ substr(auth()->guard('affiliator')->user()->name ?? 'A', 0, 1) }}
                    </div>
                    <form action="{{ route('affiliator.logout') }}" method="POST" style="display: inline;">
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
        
        function copyReferralCode(code) {
            navigator.clipboard.writeText(code).then(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Copied!',
                    text: 'Referral code copied to clipboard',
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
