@php
    $navigation = [
        [
            'name' => 'Dashboard',
            'href' => route('customer.dashboard'),
            'icon' => 'bi-speedometer',
            'route' => 'customer.dashboard',
        ],
        [
            'name' => 'Produk',
            'href' => route('customer.products.index'),
            'icon' => 'bi-box-seam',
            'route' => 'customer.products.*',
        ],
        [
            'name' => 'Trial',
            'href' => route('customer.trials.index'),
            'icon' => 'bi-clock-history',
            'route' => 'customer.trials.*',
        ],
        [
            'name' => 'Subscriptions',
            'href' => route('customer.subscriptions.index'),
            'icon' => 'bi-arrow-repeat',
            'route' => 'customer.subscriptions.*',
        ],
        [
            'name' => 'Licenses',
            'href' => route('customer.licenses.index'),
            'icon' => 'bi-key-fill',
            'route' => 'customer.licenses.*',
        ],
        [
            'name' => 'Invoices',
            'href' => route('customer.invoices.index'),
            'icon' => 'bi-receipt',
            'route' => 'customer.invoices.*',
        ],
        [
            'name' => 'Domains',
            'href' => route('customer.domains.index'),
            'icon' => 'bi-globe',
            'route' => 'customer.domains.*',
        ],
        [
            'name' => 'Payments',
            'href' => route('customer.payments.index'),
            'icon' => 'bi-credit-card',
            'route' => 'customer.payments.*',
        ],
        [
            'name' => 'Tickets',
            'href' => route('customer.tickets.index'),
            'icon' => 'bi-ticket-perforated',
            'route' => 'customer.tickets.*',
        ],
        [
            'name' => 'Reviews',
            'href' => route('customer.reviews.index'),
            'icon' => 'bi-star-fill',
            'route' => 'customer.reviews.*',
        ],
        [
            'name' => 'Profile',
            'href' => route('customer.profile.edit'),
            'icon' => 'bi-person',
            'route' => 'customer.profile.*',
        ],
    ];
@endphp

<aside class="app-sidebar shadow-sm" id="sidebar" role="navigation" aria-label="Main navigation">
    <div class="sidebar-brand">
        <a href="{{ route('customer.dashboard') }}" class="sidebar-brand-link">
            <span class="sidebar-brand-text">Cooca<span class="text-primary">.id</span></span>
        </a>
        <button class="sidebar-collapse-btn d-none d-lg-flex" id="sidebar-toggle" aria-label="Collapse sidebar">
            <i class="bi bi-chevron-left"></i>
        </button>
    </div>

    <nav class="sidebar-nav">
        <ul class="sidebar-nav-list">
            @foreach ($navigation as $item)
                <li class="sidebar-nav-item">
                    <a href="{{ $item['href'] }}"
                        class="sidebar-nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}"
                        title="{{ $item['name'] }}">
                        <i class="bi {{ $item['icon'] }} sidebar-nav-icon"></i>
                        <span class="sidebar-nav-text">{{ $item['name'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>
</aside>

<style>
    .app-sidebar {
        position: fixed;
        left: 0;
        top: 0;
        bottom: 0;
        width: var(--sidebar-width);
        background: var(--color-surface);
        border-right: 1px solid var(--color-border);
        transition: var(--transition-smooth);
        z-index: 1040;
        display: flex;
        flex-direction: column;
    }

    .sidebar-collapsed .app-sidebar {
        width: var(--sidebar-collapsed-width);
    }

    .sidebar-brand {
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 1.25rem;
        border-bottom: 1px solid var(--color-border);
    }

    .sidebar-brand-link {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--color-text-primary);
        text-decoration: none;
    }

    .sidebar-collapse-btn {
        background: transparent;
        border: none;
        color: var(--color-text-secondary);
        cursor: pointer;
        padding: 0.25rem;
        border-radius: 6px;
        transition: var(--transition-smooth);
    }

    .sidebar-collapse-btn:hover {
        background: var(--color-bg);
        color: var(--color-primary);
    }

    .sidebar-nav {
        flex: 1;
        overflow-y: auto;
        padding: 1rem 0;
    }

    .sidebar-nav-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-nav-item {
        margin: 0.25rem 0.5rem;
    }

    .sidebar-nav-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        color: var(--color-text-secondary);
        text-decoration: none;
        border-radius: var(--radius-md);
        transition: var(--transition-smooth);
        font-size: 0.875rem;
        font-weight: 500;
    }

    .sidebar-nav-link:hover {
        background: var(--color-bg);
        color: var(--color-primary);
    }

    .sidebar-nav-link.active {
        background: var(--color-primary);
        color: #fff;
    }

    .sidebar-nav-icon {
        font-size: 1.125rem;
        width: 20px;
        text-align: center;
    }

    .sidebar-collapsed .sidebar-nav-text {
        display: none;
    }

    .sidebar-collapsed .sidebar-brand-text {
        display: none;
    }

    .sidebar-collapsed .sidebar-brand {
        justify-content: center;
        padding: 0;
    }

    .sidebar-collapsed .sidebar-collapse-btn {
        display: none !important;
    }

    @media (max-width: 991.98px) {
        .app-sidebar {
            transform: translateX(-100%);
        }

        .sidebar-collapsed .app-sidebar {
            transform: translateX(0);
        }
    }
</style>
