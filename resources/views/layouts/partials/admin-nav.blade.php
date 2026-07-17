@php
    $navigationGroups = [
        [
            'title' => 'Main',
            'items' => [
                [
                    'name' => 'Dashboard',
                    'href' => route('admin.dashboard'),
                    'icon' => 'bi-house-door',
                    'route_name' => 'admin.dashboard',
                ],
            ],
        ],
        [
            'title' => 'Users',
            'items' => [
                [
                    'name' => 'Customers',
                    'href' => route('admin.customers.index'),
                    'icon' => 'bi-people',
                    'route_name' => 'admin.customers.*',
                ],
                [
                    'name' => 'Affiliators',
                    'href' => route('admin.affiliators.index'),
                    'icon' => 'bi-person-badge',
                    'route_name' => 'admin.affiliators.*',
                ],
            ],
        ],
        [
            'title' => 'Catalog',
            'items' => [
                [
                    'name' => 'Products',
                    'href' => route('admin.products.index'),
                    'icon' => 'bi-box',
                    'route_name' => 'admin.products.*',
                ],
                [
                    'name' => 'Categories',
                    'href' => route('admin.product-categories.index'),
                    'icon' => 'bi-tag',
                    'route_name' => 'admin.product-categories.*',
                ],
                [
                    'name' => 'Subscriptions',
                    'href' => route('admin.subscriptions.index'),
                    'icon' => 'bi-calendar',
                    'route_name' => 'admin.subscriptions.*',
                ],
                [
                    'name' => 'Licenses',
                    'href' => route('admin.licenses.index'),
                    'icon' => 'bi-key',
                    'route_name' => 'admin.licenses.*',
                ],
            ],
        ],
        [
            'title' => 'Sales & Finance',
            'items' => [
                [
                    'name' => 'Transactions',
                    'href' => route('admin.transactions.index'),
                    'icon' => 'bi-receipt',
                    'route_name' => 'admin.transactions.*',
                ],
                [
                    'name' => 'Settlements',
                    'href' => route('admin.settlements.index'),
                    'icon' => 'bi-cash-stack',
                    'route_name' => 'admin.settlements.*',
                ],
                [
                    'name' => 'Vouchers',
                    'href' => route('admin.vouchers.index'),
                    'icon' => 'bi-ticket',
                    'route_name' => 'admin.vouchers.*',
                ],
                [
                    'name' => 'ERP Requests',
                    'href' => route('admin.erp-requests.index'),
                    'icon' => 'bi-server',
                    'route_name' => 'admin.erp-requests.*',
                ],
            ],
        ],
        [
            'title' => 'Content',
            'items' => [
                [
                    'name' => 'Blog',
                    'href' => route('admin.blog.index'),
                    'icon' => 'bi-newspaper',
                    'route_name' => 'admin.blog.*',
                ],
                [
                    'name' => 'FAQs',
                    'href' => route('admin.faqs.index'),
                    'icon' => 'bi-question-circle',
                    'route_name' => 'admin.faqs.*',
                ],
                [
                    'name' => 'Testimonials',
                    'href' => route('admin.testimonials.index'),
                    'icon' => 'bi-chat-quote',
                    'route_name' => 'admin.testimonials.*',
                ],
                [
                    'name' => 'Reviews',
                    'href' => route('admin.reviews.index'),
                    'icon' => 'bi-star',
                    'route_name' => 'admin.reviews.*',
                ],
            ],
        ],
        [
            'title' => 'Communication',
            'items' => [
                [
                    'name' => 'Email Campaigns',
                    'href' => route('admin.email-campaigns.index'),
                    'icon' => 'bi-envelope',
                    'route_name' => 'admin.email-campaigns.*',
                ],
                [
                    'name' => 'Email Templates',
                    'href' => route('admin.email-templates.index'),
                    'icon' => 'bi-files',
                    'route_name' => 'admin.email-templates.*',
                ],
                [
                    'name' => 'Tickets',
                    'href' => route('admin.tickets.index'),
                    'icon' => 'bi-inbox',
                    'route_name' => 'admin.tickets.*',
                ],
            ],
        ],
        [
            'title' => 'System',
            'items' => [
                [
                    'name' => 'Settings',
                    'href' => route('admin.settings.index'),
                    'icon' => 'bi-gear',
                    'route_name' => 'admin.settings.*',
                ],
            ],
        ],
    ];
@endphp

@foreach ($navigationGroups as $group)
    <div class="sidebar-nav-group mb-3 px-3">
        <span class="sidebar-group-label text-muted text-uppercase fw-bold ls-wider mb-2 d-block"
            style="font-size: 0.7rem;">{{ $group['title'] }}</span>
        <ul class="sidebar-nav-list list-unstyled mb-0">
            @foreach ($group['items'] as $item)
                @php $isActive = request()->routeIs($item['route_name']); @endphp
                <li class="sidebar-nav-item mb-1">
                    <a href="{{ $item['href'] }}"
                        class="sidebar-nav-link text-decoration-none d-flex align-items-center py-2 px-3 rounded-2 transition-all {{ $isActive ? 'active bg-primary bg-opacity-10 text-primary fw-medium' : 'text-body-secondary hover-bg-light hover-text-primary' }}">
                        <i
                            class="bi {{ $item['icon'] }} sidebar-nav-icon me-3 fs-5 {{ $isActive ? 'text-primary' : '' }}"></i>
                        <span class="sidebar-nav-text">{{ $item['name'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endforeach
