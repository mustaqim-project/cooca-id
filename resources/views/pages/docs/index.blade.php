@extends('layouts.guest')
@push('styles')
<style>
    .hero-section {
        min-height: 50vh;
        display: flex;
        align-items: center;
        padding-top: 120px;
        padding-bottom: 60px;
        position: relative;
        overflow: hidden;
        background: var(--hero-gradient);
    }
    .hero-bg-orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.12;
        pointer-events: none;
    }
    .hero-bg-orb-1 {
        width: 500px;
        height: 500px;
        background: var(--primary);
        top: -200px;
        right: -100px;
    }
    .hero-bg-orb-2 {
        width: 400px;
        height: 400px;
        background: var(--accent);
        bottom: -100px;
        left: -100px;
    }
    .hero-content {
        position: relative;
        z-index: 2;
    }
    .docs-container {
        padding: 80px 0;
        background: var(--card-alt);
    }
    .docs-sidebar {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 28px 24px;
        box-shadow: var(--shadow);
        position: sticky;
        top: 100px;
    }
    .docs-sidebar-title {
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-muted);
        margin-bottom: 12px;
    }
    .docs-sidebar-list {
        list-style: none;
        padding: 0;
        margin-bottom: 24px;
    }
    .docs-sidebar-list li {
        margin-bottom: 8px;
    }
    .docs-sidebar-list a {
        color: var(--text-muted);
        font-size: 0.92rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        border-radius: 8px;
        transition: all var(--transition);
    }
    .docs-sidebar-list a:hover,
    .docs-sidebar-list a.active {
        color: var(--accent);
        background: rgba(56, 189, 248, 0.08);
        transform: translateX(4px);
    }
    .docs-content-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 48px;
        box-shadow: var(--shadow);
    }
    .docs-content-card h2 {
        font-size: 1.8rem;
        margin-bottom: 16px;
        color: var(--text);
    }
    .docs-content-card h3 {
        font-size: 1.4rem;
        margin: 32px 0 16px;
        color: var(--text);
    }
    .docs-content-card p {
        font-size: 1rem;
        line-height: 1.8;
        margin-bottom: 20px;
        color: var(--text-muted);
    }
    .docs-alert {
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.15), rgba(56, 189, 248, 0.15));
        border-left: 4px solid var(--accent);
        border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
        padding: 24px;
        margin: 32px 0;
        box-shadow: var(--shadow);
    }
    .docs-alert p {
        margin: 0;
        color: var(--text);
        font-size: 0.95rem;
    }
    .code-block {
        background: #020617;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 20px 24px;
        font-family: 'Courier New', Courier, monospace;
        color: #38BDF8;
        font-size: 0.95rem;
        margin-bottom: 24px;
        overflow-x: auto;
        box-shadow: inset 0 2px 8px rgba(0,0,0,0.5);
    }
    @media (max-width: 991.98px) {
        .docs-sidebar {
            position: relative;
            top: 0;
            margin-bottom: 32px;
        }
        .docs-content-card {
            padding: 32px 24px;
        }
    }
</style>
@endpush
@section('content')
<section class="blog-hero">
    <div class="blog-hero-orb blog-hero-orb-1"></div>
    <div class="blog-hero-orb blog-hero-orb-2"></div>
    <div class="grid-bg"></div>
    <div class="container text-center position-relative" style="z-index:2;">
        <div class="badge-glow reveal mb-4">
            <i class="bi bi-book-fill"></i> {{ __(setting('docs.hero.badge', 'Documentation')) }}
        </div>
        <h1 class="hero-title reveal reveal-delay-1">
            {!! __(setting('docs.hero.title', 'Developer <span class="text-gradient">Docs.</span>')) !!}
        </h1>
        <p class="hero-subtitle reveal reveal-delay-2" style="font-size:1.15rem;max-width:600px;margin:20px auto 0;">
            {{ __(setting('docs.hero.subtitle', 'Everything you need to integrate, customize, and extend COOCA for your business needs.')) }}
        </p>
    </div>
</section>

<!-- Content Section -->
<section class="docs-container">
    <div class="container">
        <div class="row g-5">
            <!-- Sidebar Navigation -->
            <div class="col-lg-3">
                <div class="docs-sidebar reveal">
                    <div class="docs-sidebar-title">{{ __('Getting Started') }}</div>
                    <ul class="docs-sidebar-list">
                        <li><a href="#intro" class="active"><i class="bi bi-chevron-right"></i> {{ __('Introduction') }}</a></li>
                        <li><a href="#auth"><i class="bi bi-chevron-right"></i> {{ __('Authentication') }}</a></li>
                        <li><a href="#webhooks"><i class="bi bi-chevron-right"></i> {{ __('Webhooks') }}</a></li>
                    </ul>
                    
                    <div class="docs-sidebar-title">{{ __('API Reference') }}</div>
                    <ul class="docs-sidebar-list" style="margin-bottom:0;">
                        <li><a href="#products"><i class="bi bi-chevron-right"></i> {{ __('Products') }}</a></li>
                        <li><a href="#customers"><i class="bi bi-chevron-right"></i> {{ __('Customers') }}</a></li>
                        <li><a href="#orders"><i class="bi bi-chevron-right"></i> {{ __('Orders') }}</a></li>
                        <li><a href="#inventory"><i class="bi bi-chevron-right"></i> {{ __('Inventory') }}</a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="docs-content-card reveal reveal-delay-1">
                    <h2 id="intro">{{ __('Introduction') }}</h2>
                    <p>{{ __('Welcome to the COOCA API documentation. Our API is built on REST principles, uses standard HTTP methods, and returns JSON responses.') }}</p>
                    
                    <div class="docs-alert">
                        <p><strong>{{ __('Note:') }}</strong> {{ __('The API is currently in beta. If you encounter any issues, please contact our developer support team.') }}</p>
                    </div>
                    
                    <h3>{{ __('Base URL') }}</h3>
                    <p>{{ __('All API requests should be made to the following base URL:') }}</p>
                    <div class="code-block">https://api.cooca.id/v1</div>
                    
                    <h3 id="auth">{{ __('Authentication') }}</h3>
                    <p>{{ __('Authenticate your account by including your secret API key in the request header.') }}</p>
                    <div class="code-block">Authorization: Bearer YOUR_API_KEY</div>
                    <p>{{ __('You can manage your API keys in the developer dashboard within your COOCA admin panel.') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
