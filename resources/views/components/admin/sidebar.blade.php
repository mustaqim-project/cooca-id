<aside id="sidebar" class="glass position-fixed top-0 bottom-0 start-0 d-flex flex-column p-3"
    style="width: var(--sidebar-width); z-index: 1040; transition: var(--transition-smooth); border-right: 1px solid var(--color-border);">
    <!-- Brand / Logo -->
    <div class="d-flex align-items-center justify-content-between mb-4 px-2">
        <a href="{{ route('admin.dashboard') }}"
            class="d-flex align-items-center gap-2 text-decoration-none text-primary fw-bold fs-5">
            <div class="bg-primary text-white rounded-3 d-flex align-items-center justify-content-center"
                style="width: 36px; height: 36px;">
                <i class="bi bi-layers-half fs-6"></i>
            </div>
            <span class="sidebar-text">{{ config('app.name', 'Cooca ID') }}</span>
        </a>
        <button id="sidebar-toggle" class="btn btn-sm btn-link text-secondary p-1 d-none d-lg-block">
            <i class="bi bi-layout-sidebar"></i>
        </button>
    </div>

    <!-- Search Menu -->
    <div class="mb-3 px-1">
        <div class="input-group input-group-sm bg-light rounded-pill border-0 px-2 py-1 align-items-center"
            style="background: var(--color-bg) !important;">
            <i class="bi bi-search text-secondary me-2"></i>
            <input type="text" id="sidebar-menu-search"
                class="form-control border-0 bg-transparent p-0 shadow-none text-secondary"
                placeholder="Quick search..." style="font-size: 0.8rem;">
        </div>
    </div>

    <!-- Navigation Links -->
    <div class="sidebar-nav flex-grow-1 overflow-auto pe-1">
        <!-- Section: Overview -->
        <div class="sidebar-heading text-uppercase fs-7 fw-semibold text-secondary px-2 mb-2 mt-2">
            <span class="sidebar-text">Overview</span>
        </div>
        <ul class="nav flex-column gap-1 mb-3">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-white fw-medium' : 'text-secondary hover-lift' }}">
                    <i class="bi bi-grid-1x2-fill fs-5"></i>
                    <span class="sidebar-text flex-grow-1">Dashboard</span>
                    <span class="badge bg-success rounded-pill sidebar-text">New</span>
                </a>
            </li>
        </ul>

        <!-- Section: Core & ERP -->
        <div class="sidebar-heading text-uppercase fs-7 fw-semibold text-secondary px-2 mb-2 mt-3">
            <span class="sidebar-text">Core Management</span>
        </div>
        <ul class="nav flex-column gap-1 mb-3">
            <li class="nav-item">
                <a href="{{ route('admin.erp-requests.index') }}"
                    class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.erp-requests.*') ? 'bg-primary text-white fw-medium' : 'text-secondary hover-lift' }}">
                    <i class="bi bi-hdd-network fs-5"></i>
                    <span class="sidebar-text flex-grow-1">ERP Requests</span>
                    <span class="badge bg-danger rounded-pill sidebar-text">3</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.products.index') }}"
                    class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.products.*') ? 'bg-primary text-white fw-medium' : 'text-secondary hover-lift' }}">
                    <i class="bi bi-box-seam fs-5"></i>
                    <span class="sidebar-text">Products</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.product-categories.index') }}"
                    class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.product-categories.*') ? 'bg-primary text-white fw-medium' : 'text-secondary hover-lift' }}">
                    <i class="bi bi-tags fs-5"></i>
                    <span class="sidebar-text">Categories</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.licenses.index') }}"
                    class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.licenses.*') ? 'bg-primary text-white fw-medium' : 'text-secondary hover-lift' }}">
                    <i class="bi bi-key fs-5"></i>
                    <span class="sidebar-text">Licenses</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.subscriptions.index') }}"
                    class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.subscriptions.*') ? 'bg-primary text-white fw-medium' : 'text-secondary hover-lift' }}">
                    <i class="bi bi-arrow-repeat fs-5"></i>
                    <span class="sidebar-text">Subscriptions</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.transactions.index') }}"
                    class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.transactions.*') ? 'bg-primary text-white fw-medium' : 'text-secondary hover-lift' }}">
                    <i class="bi bi-receipt fs-5"></i>
                    <span class="sidebar-text">Transactions</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.settlements.index') }}"
                    class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.settlements.*') ? 'bg-primary text-white fw-medium' : 'text-secondary hover-lift' }}">
                    <i class="bi bi-cash-coin fs-5"></i>
                    <span class="sidebar-text">Settlements</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.vouchers.index') }}"
                    class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.vouchers.*') ? 'bg-primary text-white fw-medium' : 'text-secondary hover-lift' }}">
                    <i class="bi bi-ticket-perforated fs-5"></i>
                    <span class="sidebar-text">Vouchers</span>
                </a>
            </li>
        </ul>

        <!-- Section: People & Partners -->
        <div class="sidebar-heading text-uppercase fs-7 fw-semibold text-secondary px-2 mb-2 mt-3">
            <span class="sidebar-text">People</span>
        </div>
        <ul class="nav flex-column gap-1 mb-3">
            <li class="nav-item">
                <a href="{{ route('admin.customers.index') }}"
                    class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.customers.*') ? 'bg-primary text-white fw-medium' : 'text-secondary hover-lift' }}">
                    <i class="bi bi-people fs-5"></i>
                    <span class="sidebar-text">Customers</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.affiliators.index') }}"
                    class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.affiliators.*') ? 'bg-primary text-white fw-medium' : 'text-secondary hover-lift' }}">
                    <i class="bi bi-share fs-5"></i>
                    <span class="sidebar-text">Affiliators</span>
                </a>
            </li>
        </ul>

        <!-- Section: Content & CMS -->
        <div class="sidebar-heading text-uppercase fs-7 fw-semibold text-secondary px-2 mb-2 mt-3">
            <span class="sidebar-text">Content</span>
        </div>
        <ul class="nav flex-column gap-1 mb-3">
            <li class="nav-item">
                <a href="{{ route('admin.cms.landing.index') }}"
                    class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.cms.landing.*') ? 'bg-primary text-white fw-medium' : 'text-secondary hover-lift' }}">
                    <i class="bi bi-window fs-5"></i>
                    <span class="sidebar-text">Landing Page</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.cms.pages.index') }}"
                    class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.cms.pages.*') ? 'bg-primary text-white fw-medium' : 'text-secondary hover-lift' }}">
                    <i class="bi bi-file-earmark-text fs-5"></i>
                    <span class="sidebar-text">Pages</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.blog.index') }}"
                    class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.blog.*') ? 'bg-primary text-white fw-medium' : 'text-secondary hover-lift' }}">
                    <i class="bi bi-journal-text fs-5"></i>
                    <span class="sidebar-text">Blog</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.faqs.index') }}"
                    class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.faqs.*') ? 'bg-primary text-white fw-medium' : 'text-secondary hover-lift' }}">
                    <i class="bi bi-question-circle fs-5"></i>
                    <span class="sidebar-text">FAQs</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.testimonials.index') }}"
                    class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.testimonials.*') ? 'bg-primary text-white fw-medium' : 'text-secondary hover-lift' }}">
                    <i class="bi bi-chat-quote fs-5"></i>
                    <span class="sidebar-text">Testimonials</span>
                </a>
            </li>
        </ul>

        <!-- Section: Support & Marketing -->
        <div class="sidebar-heading text-uppercase fs-7 fw-semibold text-secondary px-2 mb-2 mt-3">
            <span class="sidebar-text">Support & Marketing</span>
        </div>
        <ul class="nav flex-column gap-1 mb-3">
            <li class="nav-item">
                <a href="{{ route('admin.tickets.index') }}"
                    class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.tickets.*') ? 'bg-primary text-white fw-medium' : 'text-secondary hover-lift' }}">
                    <i class="bi bi-headset fs-5"></i>
                    <span class="sidebar-text flex-grow-1">Tickets</span>
                    <span class="badge bg-warning text-dark rounded-pill sidebar-text">12</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.reviews.index') }}"
                    class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.reviews.*') ? 'bg-primary text-white fw-medium' : 'text-secondary hover-lift' }}">
                    <i class="bi bi-star fs-5"></i>
                    <span class="sidebar-text">Reviews</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.email-campaigns.index') }}"
                    class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.email-campaigns.*') ? 'bg-primary text-white fw-medium' : 'text-secondary hover-lift' }}">
                    <i class="bi bi-envelope-paper fs-5"></i>
                    <span class="sidebar-text">Email Campaigns</span>
                </a>
            </li>
        </ul>

        <!-- Section: System & Settings -->
        <div class="sidebar-heading text-uppercase fs-7 fw-semibold text-secondary px-2 mb-2 mt-3">
            <span class="sidebar-text">System</span>
        </div>
        <ul class="nav flex-column gap-1 mb-3">
            <li class="nav-item">
                <a href="{{ route('admin.api-integrations.index') }}"
                    class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.api-integrations.*') ? 'bg-primary text-white fw-medium' : 'text-secondary hover-lift' }}">
                    <i class="bi bi-plug fs-5"></i>
                    <span class="sidebar-text">API Integrations</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.email-templates.index') }}"
                    class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.email-templates.*') ? 'bg-primary text-white fw-medium' : 'text-secondary hover-lift' }}">
                    <i class="bi bi-envelope-open fs-5"></i>
                    <span class="sidebar-text">Email Templates</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.audit-logs.index') }}"
                    class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.audit-logs.*') ? 'bg-primary text-white fw-medium' : 'text-secondary hover-lift' }}">
                    <i class="bi bi-shield-check fs-5"></i>
                    <span class="sidebar-text">Audit Logs</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.error-logs.index') }}"
                    class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.error-logs.*') ? 'bg-primary text-white fw-medium' : 'text-secondary hover-lift' }}">
                    <i class="bi bi-bug fs-5"></i>
                    <span class="sidebar-text">Error Logs</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.settings.index') }}"
                    class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.settings.*') ? 'bg-primary text-white fw-medium' : 'text-secondary hover-lift' }}">
                    <i class="bi bi-gear fs-5"></i>
                    <span class="sidebar-text">Settings</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- User Mini Profile -->
    <div class="pt-3 border-top mt-auto d-flex align-items-center gap-3 px-2">
        <div class="position-relative">
            <img src="https://ui-avatars.com/api/?name=Admin+User&background=0D8ABC&color=fff" class="rounded-circle"
                width="36" height="36" alt="Avatar">
            <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle"></span>
        </div>
        <div class="sidebar-text overflow-hidden">
            <div class="fw-semibold text-truncate fs-7">{{ auth()->guard('admin')->user()->name ?? 'Administrator' }}
            </div>
            <div class="text-secondary text-truncate" style="font-size: 0.75rem;">Super Admin</div>
        </div>
    </div>
</aside>

<!-- Styles specifically for sidebar collapsing and visual tuning -->
<style>
    .sidebar-collapsed #sidebar {
        width: var(--sidebar-collapsed-width) !important;
        padding-left: 0.75rem !important;
        padding-right: 0.75rem !important;
    }

    .sidebar-collapsed .sidebar-text,
    .sidebar-collapsed #sidebar-menu-search {
        display: none !important;
    }

    .sidebar-collapsed .nav-link {
        justify-content: center;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    .hover-lift:hover {
        background: rgba(var(--color-primary-rgb), 0.05);
        color: var(--color-primary) !important;
    }

    .fs-7 {
        font-size: 0.75rem;
    }
</style>

<script>
    // Simple filter inside sidebar
    document.getElementById('sidebar-menu-search')?.addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase();
        document.querySelectorAll('.sidebar-nav .nav-item').forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(query) ? '' : 'none';
        });
    });
</script>
