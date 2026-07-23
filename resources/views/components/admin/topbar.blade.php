<nav class="navbar navbar-expand-lg glass rounded-4 px-3 py-2 mb-4 d-flex justify-content-between align-items-center shadow-sm"
    style="position: sticky; top: 1rem; z-index: 1030;">

    <!-- Left: Mobile Toggle & Breadcrumb -->
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-sm btn-link text-secondary p-1 d-lg-none" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
            <i class="bi bi-list fs-4"></i>
        </button>

        <nav aria-label="breadcrumb" class="d-none d-md-block mt-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-secondary text-decoration-none"><i
                            class="bi bi-house-door"></i></a></li>
                @if (isset($breadcrumb))
                    {{ $breadcrumb }}
                @else
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ request()->segment(2) ? ucfirst(request()->segment(2)) : 'Dashboard' }}</li>
                @endif
            </ol>
        </nav>
    </div>

    <!-- Right: Actions -->
    <div class="d-flex align-items-center gap-2 gap-md-3">

        <!-- Command Palette Trigger -->
        <button type="button"
            class="btn btn-sm bg-light text-secondary border-0 rounded-pill px-3 py-2 d-none d-md-flex align-items-center gap-2 hover-lift"
            onclick="document.dispatchEvent(new KeyboardEvent('keydown', {'key': 'k', 'ctrlKey': true}))">
            <i class="bi bi-search"></i>
            <span class="fs-7">Search...</span>
            <kbd class="bg-white border rounded px-1 text-muted" style="font-size: 0.7rem;">Ctrl K</kbd>
        </button>
        <button type="button" class="btn btn-sm btn-light rounded-circle p-2 d-md-none border-0 text-secondary"
            onclick="document.dispatchEvent(new KeyboardEvent('keydown', {'key': 'k', 'ctrlKey': true}))">
            <i class="bi bi-search"></i>
        </button>

        <!-- Theme Toggle -->
        <button id="theme-toggle" class="btn btn-sm btn-light rounded-circle p-2 border-0 text-secondary hover-lift"
            title="Toggle Theme">
            <i class="bi bi-moon-stars"></i>
        </button>

        <!-- Notification -->
        <div class="dropdown">
            <button class="btn btn-sm btn-light rounded-circle p-2 border-0 text-secondary position-relative hover-lift"
                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-bell"></i>
                <span
                    class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                    <span class="visually-hidden">New alerts</span>
                </span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 p-0 glass overflow-hidden"
                style="width: 320px;">
                <div class="p-3 border-bottom bg-light d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">Notifications</span>
                    <span class="badge bg-primary rounded-pill">4 New</span>
                </div>
                <div class="overflow-auto" style="max-height: 300px;">
                    <li><a class="dropdown-item p-3 border-bottom d-flex gap-3 hover-lift" href="#">
                            <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center"
                                style="width: 40px; height: 40px;"><i class="bi bi-hdd-network"></i></div>
                            <div>
                                <div class="fw-medium fs-7">New ERP Request</div>
                                <div class="text-secondary" style="font-size: 0.75rem;">PT. Maju Jaya requested ERP
                                    setup.</div>
                                <div class="text-muted mt-1" style="font-size: 0.7rem;">2 mins ago</div>
                            </div>
                        </a></li>
                    <!-- Add more dummy notifications here if needed -->
                </div>
                <div class="p-2 text-center bg-light">
                    <a href="#" class="text-decoration-none fs-7 fw-medium text-primary">View All</a>
                </div>
            </ul>
        </div>

        <!-- User Dropdown -->
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center gap-2 text-decoration-none dropdown-toggle-hide-arrow"
                data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://ui-avatars.com/api/?name=Admin+User&background=0D8ABC&color=fff"
                    class="rounded-circle border border-2 border-white shadow-sm" width="36" height="36"
                    alt="Avatar">
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-2 glass">
                <li class="px-3 py-2 border-bottom">
                    <div class="fw-semibold">{{ auth()->guard('admin')->user()->name ?? 'Administrator' }}</div>
                    <div class="text-secondary fs-7">{{ auth()->guard('admin')->user()->email ?? 'admin@cooca.id' }}
                    </div>
                </li>
                <li><a class="dropdown-item py-2 hover-lift" href="#"><i
                            class="bi bi-person me-2 text-secondary"></i> My Profile</a></li>
                <li><a class="dropdown-item py-2 hover-lift" href="#"><i
                            class="bi bi-gear me-2 text-secondary"></i> Settings</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <form action="{{ route('admin.logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="dropdown-item py-2 text-danger hover-lift"><i
                                class="bi bi-box-arrow-right me-2"></i> Logout</button>
                    </form>
                </li>
            </ul>
        </div>

    </div>
</nav>

<style>
    .dropdown-toggle-hide-arrow::after {
        display: none !important;
    }
</style>
