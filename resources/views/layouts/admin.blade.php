<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', sidebarOpen: false }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Admin Panel') - Cooca.id</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface-50 text-surface-900 dark:bg-surface-900 dark:text-surface-100 font-sans antialiased flex h-screen overflow-hidden transition-colors duration-200">
    
    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" style="display: none;" class="fixed inset-0 z-40 bg-surface-900/80 backdrop-blur-sm lg:hidden" @click="sidebarOpen = false" x-transition></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
           class="fixed inset-y-0 left-0 z-50 w-72 corporate-sidebar transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0 lg:static lg:inset-auto flex flex-col shadow-xl lg:shadow-none h-screen shrink-0">
        
        <!-- Brand -->
        <div class="h-16 flex items-center justify-center border-b border-surface-200 dark:border-surface-700 shrink-0">
            <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold tracking-tight text-surface-900 dark:text-white">
                Cooca<span class="text-primary-600 dark:text-primary-400">.id</span>
            </a>
        </div>
        
        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto p-4 space-y-6">
            @php
                $navigationGroups = [
                    [
                        'title' => 'Main',
                        'items' => [
                            ['name' => 'Dashboard', 'href' => route('admin.dashboard'), 'icon' => 'bi-house-door', 'route_name' => 'admin.dashboard'],
                        ]
                    ],
                    [
                        'title' => 'Users',
                        'items' => [
                            ['name' => 'Customers', 'href' => route('admin.customers.index'), 'icon' => 'bi-people', 'route_name' => 'admin.customers.*'],
                            ['name' => 'Affiliators', 'href' => route('admin.affiliators.index'), 'icon' => 'bi-person-badge', 'route_name' => 'admin.affiliators.*'],
                        ]
                    ],
                    [
                        'title' => 'Catalog',
                        'items' => [
                            ['name' => 'Products', 'href' => route('admin.products.index'), 'icon' => 'bi-box', 'route_name' => 'admin.products.*'],
                            ['name' => 'Categories', 'href' => route('admin.product-categories.index'), 'icon' => 'bi-tag', 'route_name' => 'admin.product-categories.*'],
                            ['name' => 'Subscriptions', 'href' => route('admin.subscriptions.index'), 'icon' => 'bi-calendar', 'route_name' => 'admin.subscriptions.*'],
                            ['name' => 'Licenses', 'href' => route('admin.licenses.index'), 'icon' => 'bi-key', 'route_name' => 'admin.licenses.*'],
                        ]
                    ],
                    [
                        'title' => 'Sales & Finance',
                        'items' => [
                            ['name' => 'Transactions', 'href' => route('admin.transactions.index'), 'icon' => 'bi-currency-dollar', 'route_name' => 'admin.transactions.*'],
                            ['name' => 'Settlements', 'href' => route('admin.settlements.index'), 'icon' => 'bi-cash-stack', 'route_name' => 'admin.settlements.*'],
                            ['name' => 'Vouchers', 'href' => route('admin.vouchers.index'), 'icon' => 'bi-ticket', 'route_name' => 'admin.vouchers.*'],
                            ['name' => 'ERP Requests', 'href' => route('admin.erp-requests.index'), 'icon' => 'bi-server', 'route_name' => 'admin.erp-requests.*'],
                        ]
                    ],
                    [
                        'title' => 'Content Management',
                        'items' => [
                            ['name' => 'Landing Page', 'href' => route('admin.cms.landing.index'), 'icon' => 'bi-layout-text-window', 'route_name' => 'admin.cms.landing.*'],
                            ['name' => 'CMS Pages', 'href' => route('admin.cms.pages.index'), 'icon' => 'bi-file-text', 'route_name' => 'admin.cms.pages.*'],
                            ['name' => 'Blog', 'href' => route('admin.blog.index'), 'icon' => 'bi-newspaper', 'route_name' => 'admin.blog.*'],
                            ['name' => 'FAQs', 'href' => route('admin.faqs.index'), 'icon' => 'bi-question-circle', 'route_name' => 'admin.faqs.*'],
                            ['name' => 'Testimonials', 'href' => route('admin.testimonials.index'), 'icon' => 'bi-chat-quote', 'route_name' => 'admin.testimonials.*'],
                            ['name' => 'Reviews', 'href' => route('admin.reviews.index'), 'icon' => 'bi-star', 'route_name' => 'admin.reviews.*'],
                        ]
                    ],
                    [
                        'title' => 'Communication',
                        'items' => [
                            ['name' => 'Email Campaigns', 'href' => route('admin.email-campaigns.index'), 'icon' => 'bi-envelope', 'route_name' => 'admin.email-campaigns.*'],
                            ['name' => 'Email Templates', 'href' => route('admin.email-templates.index'), 'icon' => 'bi-files', 'route_name' => 'admin.email-templates.*'],
                            ['name' => 'Tickets', 'href' => route('admin.tickets.index'), 'icon' => 'bi-inbox', 'route_name' => 'admin.tickets.*'],
                        ]
                    ],
                    [
                        'title' => 'System',
                        'items' => [
                            ['name' => 'Settings', 'href' => route('admin.settings.index'), 'icon' => 'bi-gear', 'route_name' => 'admin.settings.*'],
                        ]
                    ],
                ];
            @endphp
            
            @foreach($navigationGroups as $group)
                <div>
                    <h3 class="px-3 text-xs font-semibold text-surface-500 dark:text-surface-400 uppercase tracking-wider mb-2">
                        {{ $group['title'] }}
                    </h3>
                    <ul class="space-y-1">
                        @foreach($group['items'] as $item)
                            <li>
                                <a href="{{ $item['href'] }}" 
                                   class="{{ request()->routeIs($item['route_name']) ? 'corporate-nav-item-active' : 'corporate-nav-item' }}">
                                    <i class="bi {{ $item['icon'] }} text-lg mr-3 {{ request()->routeIs($item['route_name']) ? 'text-white' : 'text-surface-400 dark:text-surface-500' }}"></i>
                                    {{ $item['name'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </nav>
        
        <!-- Bottom Action -->
        <div class="p-4 border-t border-surface-200 dark:border-surface-700">
            <form class="form-confirm-submit" method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="flex w-full items-center px-3 py-2 text-sm font-medium text-red-600 dark:text-red-400 rounded-md hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                    <i data-lucide="log-out" class="w-5 h-5 icon-3d"></i>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-h-screen overflow-hidden min-w-0">
        
        <!-- Top Header -->
        <header class="h-16 corporate-header sticky top-0 z-30 flex items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-md text-surface-400 hover:text-surface-500 hover:bg-surface-100 dark:hover:bg-surface-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                    <i data-lucide="menu" class="w-5 h-5 icon-3d"></i>
                </button>
            </div>
            
            <div class="flex items-center space-x-4">
                <!-- Dark Mode Toggle -->
                <button @click="darkMode = !darkMode" class="p-2 text-surface-400 hover:text-surface-500 dark:hover:text-surface-300 transition-colors">
                    <i class="bi" :class="darkMode ? 'bi-sun' : 'bi-moon'"></i>
                </button>
                
                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" class="flex items-center space-x-3 focus:outline-none">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold shadow-sm">
                            {{ substr(auth('admin')->user()->name ?? 'A', 0, 1) }}
                        </div>
                        <div class="hidden md:block text-left">
                            <p class="text-sm font-medium text-surface-700 dark:text-surface-200">{{ auth('admin')->user()->name ?? 'Admin' }}</p>
                        </div>
                        <i data-lucide="chevron-down" class="w-5 h-5 text-xs text-surface-500 icon-3d"></i>
                    </button>
                    
                    <div x-show="open" x-transition class="absolute right-0 mt-2 w-48 bg-white dark:bg-surface-800 rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5 focus:outline-none" style="display: none;">
                        <a href="{{ route('admin.settings.index') }}" class="block px-4 py-2 text-sm text-surface-700 dark:text-surface-300 hover:bg-surface-100 dark:hover:bg-surface-700">Profile</a>
                        <a href="{{ route('admin.settings.index') }}" class="block px-4 py-2 text-sm text-surface-700 dark:text-surface-300 hover:bg-surface-100 dark:hover:bg-surface-700">Settings</a>
                        <div class="border-t border-surface-100 dark:border-surface-700"></div>
                        <form class="form-confirm-submit" method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">Sign out</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-surface-50 dark:bg-surface-900 p-4 sm:p-6 lg:p-8">
            <!-- Header section -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-surface-900 dark:text-white">@yield('title')</h1>
                <p class="mt-1 text-sm text-surface-500 dark:text-surface-400">@yield('subtitle')</p>
            </div>
            
            @yield('content')
        </main>
    </div>
    
    @include('components.swal-alert')
    @stack('scripts')
</body>
</html>
