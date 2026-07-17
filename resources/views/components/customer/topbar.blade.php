<header class="app-header" role="banner">
    <div class="header-left">
        <button class="header-hamburger d-lg-none" id="sidebar-toggle-mobile" aria-label="Toggle sidebar">
            <i class="bi bi-list"></i>
        </button>
        
        <nav class="header-breadcrumb" aria-label="Breadcrumb">
            <ol class="breadcrumb-list">
                <li class="breadcrumb-item">
                    <a href="{{ route('customer.dashboard') }}" class="breadcrumb-link">
                        <i class="bi bi-house-door"></i>
                    </a>
                </li>
                <li class="breadcrumb-separator">/</li>
                <li class="breadcrumb-item active" aria-current="page">
                    @yield('title', 'Dashboard')
                </li>
            </ol>
        </nav>
    </div>

    <div class="header-right">
        <div class="header-actions">
            <button class="header-action-btn" id="theme-toggle" title="Toggle dark mode">
                <i class="bi bi-moon-stars-fill theme-icon-light"></i>
                <i class="bi bi-sun-fill theme-icon-dark"></i>
            </button>
            
            <button class="header-action-btn notification-btn" title="Notifications">
                <i class="bi bi-bell"></i>
                <span class="notification-badge">0</span>
            </button>
        </div>

        <div class="header-divider"></div>

        <div class="dropdown profile-dropdown">
            <button class="profile-btn" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="profile-avatar">
                    {{ strtoupper(substr(auth('customer')->user()->name ?? 'C', 0, 1)) }}
                </div>
                <div class="profile-info">
                    <span class="profile-name">{{ auth('customer')->user()->name ?? 'Customer' }}</span>
                    <span class="profile-role">Customer</span>
                </div>
                <i class="bi bi-chevron-down profile-chevron"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end profile-menu shadow-lg">
                <li class="dropdown-header">
                    <div class="profile-avatar-sm">
                        {{ strtoupper(substr(auth('customer')->user()->name ?? 'C', 0, 1)) }}
                    </div>
                    <div>
                        <div class="fw-semibold">{{ auth('customer')->user()->name ?? 'Customer' }}</div>
                        <div class="text-muted small">Customer Account</div>
                    </div>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('customer.dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('customer.profile.edit') }}">
                        <i class="bi bi-person"></i> Profile
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('customer.logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item d-flex align-items-center gap-2 text-danger">
                            <i class="bi bi-box-arrow-right"></i> Sign out
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>

<style>
    .app-header {
        height: 64px;
        background: var(--color-surface);
        border-bottom: 1px solid var(--color-border);
        border-radius: var(--radius-md);
        padding: 0 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .header-left, .header-right {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .header-hamburger {
        background: transparent;
        border: none;
        color: var(--color-text-primary);
        font-size: 1.25rem;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 6px;
    }
    
    .header-hamburger:hover {
        background: var(--color-bg);
    }
    
    .breadcrumb-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
    }
    
    .breadcrumb-link {
        color: var(--color-text-secondary);
        text-decoration: none;
    }
    
    .breadcrumb-separator {
        color: var(--color-border);
    }
    
    .breadcrumb-item.active {
        color: var(--color-text-primary);
        font-weight: 500;
    }
    
    .header-actions {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .header-action-btn {
        background: transparent;
        border: none;
        color: var(--color-text-secondary);
        font-size: 1.125rem;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 6px;
        position: relative;
        transition: var(--transition-smooth);
    }
    
    .header-action-btn:hover {
        background: var(--color-bg);
        color: var(--color-primary);
    }
    
    .notification-badge {
        position: absolute;
        top: 2px;
        right: 2px;
        background: var(--color-danger);
        color: #fff;
        font-size: 0.625rem;
        font-weight: 700;
        padding: 0.125rem 0.375rem;
        border-radius: 10px;
        min-width: 16px;
        text-align: center;
    }
    
    .header-divider {
        width: 1px;
        height: 24px;
        background: var(--color-border);
    }
    
    .profile-btn {
        background: transparent;
        border: none;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 8px;
        transition: var(--transition-smooth);
    }
    
    .profile-btn:hover {
        background: var(--color-bg);
    }
    
    .profile-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--color-primary), var(--color-accent));
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.875rem;
    }
    
    .profile-info {
        display: flex;
        flex-direction: column;
        text-align: left;
    }
    
    .profile-name {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--color-text-primary);
    }
    
    .profile-role {
        font-size: 0.75rem;
        color: var(--color-text-secondary);
    }
    
    .profile-chevron {
        font-size: 0.75rem;
        color: var(--color-text-secondary);
    }
    
    .profile-menu {
        min-width: 220px;
        border: 1px solid var(--color-border);
        border-radius: var(--radius-md);
    }
    
    .dropdown-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
    }
    
    .profile-avatar-sm {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--color-primary), var(--color-accent));
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }
    
    .dropdown-item {
        padding: 0.625rem 1rem;
        color: var(--color-text-primary);
        text-decoration: none;
        transition: var(--transition-smooth);
        border: none;
        background: transparent;
        width: 100%;
        text-align: left;
        cursor: pointer;
    }
    
    .dropdown-item:hover {
        background: var(--color-bg);
    }
    
    .dropdown-item.text-danger:hover {
        background: rgba(239, 68, 68, 0.1);
        color: var(--color-danger);
    }
    
    .dropdown-divider {
        margin: 0.5rem 0;
        border-top: 1px solid var(--color-border);
    }
    
    @media (max-width: 767.98px) {
        .profile-info {
            display: none;
        }
    }
</style>
