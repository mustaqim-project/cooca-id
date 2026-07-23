<aside id="sidebar" class="glass position-fixed top-0 bottom-0 start-0 d-flex flex-column p-3"
    style="width: var(--sidebar-width); z-index: 1040; transition: var(--transition-smooth); border-right: 1px solid var(--color-border);">
    <!-- Brand / Logo -->
    <div class="d-flex align-items-center justify-content-between mb-4 px-2">
        <a href="{{ route('affiliator.dashboard') }}"
            class="d-flex align-items-center gap-2 text-decoration-none text-primary fw-bold fs-5">
            <div class="bg-primary text-white rounded-3 d-flex align-items-center justify-content-center"
                style="width: 36px; height: 36px;">
                <i class="bi bi-diagram-3-fill fs-6"></i>
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
                <a href="{{ route('affiliator.dashboard') }}"
                    class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-decoration-none {{ request()->routeIs('affiliator.dashboard') ? 'bg-primary text-white fw-medium' : 'text-secondary hover-lift' }}">
                    <i class="bi bi-grid-1x2-fill fs-5"></i>
                    <span class="sidebar-text flex-grow-1">Dashboard</span>
                </a>
            </li>
        </ul>

        <!-- Section: Network & Earnings -->
        <div class="sidebar-heading text-uppercase fs-7 fw-semibold text-secondary px-2 mb-2 mt-3">
            <span class="sidebar-text">Network & Earnings</span>
        </div>
        <ul class="nav flex-column gap-1 mb-3">
            <li class="nav-item">
                <a href="{{ route('affiliator.referrals.index') }}"
                    class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-decoration-none {{ request()->routeIs('affiliator.referrals.*') ? 'bg-primary text-white fw-medium' : 'text-secondary hover-lift' }}">
                    <i class="bi bi-people fs-5"></i>
                    <span class="sidebar-text">My Referrals</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('affiliator.downlines.index') }}"
                    class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-decoration-none {{ request()->routeIs('affiliator.downlines.*') ? 'bg-primary text-white fw-medium' : 'text-secondary hover-lift' }}">
                    <i class="bi bi-diagram-3 fs-5"></i>
                    <span class="sidebar-text">Downlines</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('affiliator.commissions.index') }}"
                    class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-decoration-none {{ request()->routeIs('affiliator.commissions.*') ? 'bg-primary text-white fw-medium' : 'text-secondary hover-lift' }}">
                    <i class="bi bi-cash-coin fs-5"></i>
                    <span class="sidebar-text flex-grow-1">Commissions</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('affiliator.withdrawals.index') }}"
                    class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-decoration-none {{ request()->routeIs('affiliator.withdrawals.*') ? 'bg-primary text-white fw-medium' : 'text-secondary hover-lift' }}">
                    <i class="bi bi-bank fs-5"></i>
                    <span class="sidebar-text">Withdrawals</span>
                </a>
            </li>
        </ul>

        <!-- Section: Marketing -->
        <div class="sidebar-heading text-uppercase fs-7 fw-semibold text-secondary px-2 mb-2 mt-3">
            <span class="sidebar-text">Marketing & Tools</span>
        </div>
        <ul class="nav flex-column gap-1 mb-3">
            <li class="nav-item">
                <a href="{{ route('affiliator.marketing.index') }}"
                    class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-decoration-none {{ request()->routeIs('affiliator.marketing.*') ? 'bg-primary text-white fw-medium' : 'text-secondary hover-lift' }}">
                    <i class="bi bi-megaphone fs-5"></i>
                    <span class="sidebar-text">Marketing Assets</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('affiliator.reviews.index') }}"
                    class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-decoration-none {{ request()->routeIs('affiliator.reviews.*') ? 'bg-primary text-white fw-medium' : 'text-secondary hover-lift' }}">
                    <i class="bi bi-star fs-5"></i>
                    <span class="sidebar-text">My Reviews</span>
                </a>
            </li>
        </ul>

        <!-- Section: Settings -->
        <div class="sidebar-heading text-uppercase fs-7 fw-semibold text-secondary px-2 mb-2 mt-3">
            <span class="sidebar-text">Settings</span>
        </div>
        <ul class="nav flex-column gap-1 mb-3">
            <li class="nav-item">
                <a href="{{ route('affiliator.profile.edit') }}"
                    class="nav-link d-flex align-items-center gap-3 rounded-3 px-3 py-2 text-decoration-none {{ request()->routeIs('affiliator.profile.*') ? 'bg-primary text-white fw-medium' : 'text-secondary hover-lift' }}">
                    <i class="bi bi-person fs-5"></i>
                    <span class="sidebar-text">Profile Setup</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- User Mini Profile -->
    <div class="pt-3 border-top mt-auto d-flex align-items-center gap-3 px-2">
        <div class="position-relative">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->guard('affiliator')->user()->name ?? 'Affiliate') }}&background=0D8ABC&color=fff" class="rounded-circle"
                width="36" height="36" alt="Avatar">
            <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle"></span>
        </div>
        <div class="sidebar-text overflow-hidden">
            <div class="fw-semibold text-truncate fs-7">{{ auth()->guard('affiliator')->user()->name ?? 'Affiliate Partner' }}
            </div>
            <div class="text-secondary text-truncate" style="font-size: 0.75rem;">Affiliator</div>
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
