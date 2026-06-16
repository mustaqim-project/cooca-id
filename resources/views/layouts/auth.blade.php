<!DOCTYPE html>
<html lang="en" data-theme="{{ session('theme', 'dark') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'COOCA')</title>
    <meta name="description" content="@yield('meta_description', 'Enterprise Business Infrastructure')">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg: #020617;
            --card: #0F172A;
            --card-alt: #1E293B;
            --text: #F8FAFC;
            --text-muted: #94A3B8;
            --primary: #2563EB;
            --secondary: #1E40AF;
            --accent: #38BDF8;
            --success: #10B981;
            --danger: #EF4444;
            --border: rgba(56, 189, 248, 0.12);
            --shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
            --shadow-lg: 0 24px 64px rgba(0, 0, 0, 0.6);
            --glass: rgba(15, 23, 42, 0.65);
            --glass-border: rgba(56, 189, 248, 0.14);
            --radius: 16px;
            --radius-sm: 10px;
            --radius-lg: 24px;
            --transition: 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            --font: 'Inter', -apple-system, sans-serif;
            --navbar-height: 72px;
            --footer-bg: #0B1120;
        }
        [data-theme="light"] {
            --bg: #F8FAFC;
            --card: #FFFFFF;
            --card-alt: #F1F5F9;
            --text: #0F172A;
            --text-muted: #475569;
            --primary: #2563EB;
            --secondary: #7C3AED;
            --accent: #0EA5E9;
            --success: #10B981;
            --border: rgba(37, 99, 235, 0.12);
            --shadow: 0 8px 32px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 24px 64px rgba(0, 0, 0, 0.1);
            --glass: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(37, 99, 235, 0.1);
            --footer-bg: #E2E8F0;
        }

        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
        html{scroll-behavior:smooth;overflow-x:hidden;}
        body{
            font-family:var(--font);
            background:var(--bg);
            color:var(--text);
            line-height:1.7;
            transition:background var(--transition),color var(--transition);
            overflow-x:hidden;
            -webkit-font-smoothing:antialiased;
            display:flex;
            flex-direction:column;
            min-height:100vh;
        }
        img{max-width:100%;height:auto;}
        a{color:var(--accent);text-decoration:none;transition:color var(--transition);}
        a:hover{color:var(--primary);}
        h1,h2,h3,h4,h5,h6{font-weight:700;line-height:1.2;letter-spacing:-0.02em;}
        p{color:var(--text-muted);}
        
        /* ========== NAVBAR ========== */
        .navbar-cooca{position:fixed;top:0;left:0;right:0;z-index:1050;padding:16px 0;transition:all var(--transition);background:transparent;}
        .navbar-cooca.scrolled{padding:10px 0;background:var(--glass);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border-bottom:1px solid var(--glass-border);box-shadow:0 4px 30px rgba(0,0,0,0.1);}
        .navbar-brand-cooca{font-size:1.6rem;font-weight:800;letter-spacing:-0.03em;color:var(--text)!important;display:flex;align-items:center;gap:10px;}
        .navbar-brand-cooca .logo-icon{width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,var(--primary),var(--accent));display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.1rem;font-weight:800;}
        .nav-link-cooca{color:var(--text-muted)!important;font-weight:500;font-size:0.9rem;padding:8px 16px!important;transition:color var(--transition);position:relative;}
        .nav-link-cooca:hover,.nav-link-cooca.active{color:var(--accent)!important;}
        .nav-link-cooca::after{content:'';position:absolute;bottom:0;left:50%;transform:translateX(-50%);width:0;height:2px;background:var(--accent);transition:width var(--transition);border-radius:1px;}
        .nav-link-cooca:hover::after{width:60%;}
        .theme-toggle{width:42px;height:42px;border-radius:12px;border:1px solid var(--border);background:var(--card);color:var(--text);display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all var(--transition);font-size:1.1rem;}
        .theme-toggle:hover{border-color:var(--accent);color:var(--accent);transform:rotate(20deg);}
        
        /* Login Dropdown */
        .login-dropdown-wrapper{position:relative;}
        .login-dropdown-menu{position:absolute;top:calc(100% + 10px);right:0;min-width:190px;background:var(--card);border:1px solid var(--border);border-radius:var(--radius-sm);box-shadow:var(--shadow-lg);padding:8px 0;opacity:0;visibility:hidden;transform:translateY(-8px);transition:opacity 0.25s ease,transform 0.25s ease,visibility 0.25s ease;z-index:1060;}
        .login-dropdown-menu.show{opacity:1;visibility:visible;transform:translateY(0);}
        .dropdown-item-c{display:flex;align-items:center;gap:10px;padding:12px 20px;font-size:0.9rem;font-weight:500;color:var(--text);text-decoration:none;transition:all 0.2s ease;white-space:nowrap;}
        .dropdown-item-c:hover{background:rgba(56,189,248,0.08);color:var(--accent);}
        .dropdown-item-c i{font-size:1rem;color:var(--text-muted);transition:color 0.2s ease;}
        .dropdown-item-c:hover i{color:var(--accent);}
        
        /* WhatsApp Float */
        .whatsapp-float{position:fixed;bottom:28px;right:28px;z-index:999;width:56px;height:56px;border-radius:50%;background:#25D366;color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.6rem;box-shadow:0 6px 24px rgba(37,211,102,0.35);transition:all var(--transition);text-decoration:none;}
        .whatsapp-float:hover{transform:scale(1.1);box-shadow:0 10px 32px rgba(37,211,102,0.5);color:#fff;}
        .whatsapp-float .pulse-ring{position:absolute;inset:-6px;border-radius:50%;border:2px solid #25D366;animation:pulse-ring 2s ease-out infinite;}
        @keyframes pulse-ring{0%{transform:scale(0.8);opacity:1;}100%{transform:scale(1.6);opacity:0;}}
        
        /* Offcanvas Mobile */
        .offcanvas-cooca{background:var(--glass)!important;backdrop-filter:blur(30px);-webkit-backdrop-filter:blur(30px);border-left:1px solid var(--glass-border);}
        .offcanvas-cooca .offcanvas-header{border-bottom:1px solid var(--border);}
        .offcanvas-cooca .btn-close{filter:invert(1);}
        [data-theme="light"] .offcanvas-cooca .btn-close{filter:none;}
        .offcanvas-cooca .nav-link-cooca{display:block;padding:14px 0!important;font-size:1rem;border-bottom:1px solid var(--border);}
        .offcanvas-cooca .nav-link-cooca::after{display:none;}
        
        /* ========== AUTH LAYOUT ========== */
        .auth-layout{flex:1;display:grid;grid-template-columns:1fr 1fr;margin-top:var(--navbar-height);min-height:calc(100vh - var(--navbar-height));}
        .auth-panel{display:flex;align-items:center;justify-content:center;padding:48px 40px;}
        .auth-left{background:linear-gradient(160deg,#020617 0%,#0F172A 40%,#1E3A5F 80%,#020617 100%);position:relative;overflow:hidden;}
        .auth-right{background:var(--bg);}
        .grid-bg{position:absolute;inset:0;background-image:linear-gradient(rgba(56,189,248,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(56,189,248,.04) 1px,transparent 1px);background-size:60px 60px;}
        .orb{position:absolute;border-radius:50%;filter:blur(80px);opacity:.15;pointer-events:none;}
        .left-content{position:relative;z-index:2;max-width:420px;text-align:center;}
        .left-content h2{font-size:2rem;font-weight:800;line-height:1.2;margin-bottom:16px;letter-spacing:-0.02em;}
        .text-gradient{background:linear-gradient(135deg,var(--accent),var(--primary));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
        .trust-items{display:flex;flex-direction:column;gap:12px;margin-top:36px;text-align:left;}
        .trust-item{display:flex;align-items:center;gap:12px;padding:14px 16px;background:rgba(255,255,255,.04);border:1px solid rgba(56,189,248,.1);border-radius:12px;}
        .trust-icon{width:36px;height:36px;border-radius:10px;background:rgba(56,189,248,.1);display:flex;align-items:center;justify-content:center;color:var(--accent);font-size:1rem;flex-shrink:0;}
        .trust-text{font-size:.85rem;color:rgba(248,250,252,.7);}
        .form-panel{width:100%;max-width:420px;}
        .form-title{font-size:1.8rem;font-weight:800;margin-bottom:6px;letter-spacing:-0.02em;}
        .form-subtitle{font-size:.92rem;margin-bottom:32px;}
        
        /* Form Elements */
        .input-wrap{position:relative;margin-bottom:16px;}
        .input-label{font-size:.82rem;font-weight:600;margin-bottom:8px;display:block;color:var(--text);}
        .input-field{width:100%;padding:14px 16px 14px 46px;border-radius:12px;border:1px solid var(--border);background:var(--card-alt);color:var(--text);font-family:var(--font);font-size:.95rem;outline:none;transition:all var(--transition);}
        .input-field:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(56,189,248,.1);background:var(--card);}
        .input-field::placeholder{color:var(--text-muted);}
        .input-icon{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:1rem;pointer-events:none;}
        .input-toggle{position:absolute;right:14px;top:50%;transform:translateY(-50%);cursor:pointer;color:var(--text-muted);font-size:1rem;transition:color var(--transition);}
        .input-toggle:hover{color:var(--accent);}
        
        .divider{display:flex;align-items:center;gap:12px;margin:20px 0;}
        .divider::before,.divider::after{content:'';flex:1;height:1px;background:var(--border);}
        .divider span{font-size:.78rem;color:var(--text-muted);white-space:nowrap;}
        
        .social-btn{display:flex;align-items:center;justify-content:center;gap:10px;width:100%;padding:13px;border-radius:12px;border:1px solid var(--border);background:transparent;color:var(--text);font-family:var(--font);font-size:.9rem;font-weight:600;cursor:pointer;transition:all var(--transition);margin-bottom:10px;}
        .social-btn:hover{border-color:var(--accent);background:rgba(56,189,248,.04);}
        
        .check-wrap{display:flex;align-items:center;gap:10px;margin-bottom:20px;}
        .check-wrap input[type="checkbox"]{width:18px;height:18px;accent-color:var(--primary);border-radius:4px;cursor:pointer;flex-shrink:0;}
        .check-wrap label{font-size:.85rem;color:var(--text-muted);cursor:pointer;}
        
        .error-msg{background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);border-radius:10px;padding:12px 16px;font-size:.85rem;color:#EF4444;margin-bottom:16px;display:none;}
        .success-msg{background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.2);border-radius:10px;padding:12px 16px;font-size:.85rem;color:#10B981;margin-bottom:16px;display:none;}
        
        /* Buttons */
        .btn-cooca{display:inline-flex;align-items:center;gap:8px;padding:14px 32px;border-radius:12px;font-weight:600;font-size:0.95rem;border:none;cursor:pointer;transition:all var(--transition);position:relative;overflow:hidden;text-decoration:none;}
        .btn-cooca-primary{background:linear-gradient(135deg,var(--primary),var(--secondary));color:#fff;box-shadow:0 4px 20px rgba(37,99,235,0.3);}
        .btn-cooca-primary:hover{transform:translateY(-2px);box-shadow:0 8px 30px rgba(37,99,235,0.45);color:#fff;}
        .btn-cooca-success{background:linear-gradient(135deg,#10B981,#059669);color:#fff;box-shadow:0 4px 20px rgba(16,185,129,0.3);}
        .btn-cooca-success:hover{transform:translateY(-2px);box-shadow:0 8px 30px rgba(16,185,129,0.45);color:#fff;}
        .btn-cooca-outline{background:transparent;color:var(--text);border:1px solid var(--border);}
        .btn-cooca-outline:hover{border-color:var(--accent);color:var(--accent);transform:translateY(-2px);}
        .btn-cooca-sm{padding:10px 22px;font-size:0.85rem;border-radius:10px;}
        
        /* ========== FOOTER ========== */
        .footer{padding:60px 0 30px;border-top:1px solid var(--border);background:var(--card);}
        .footer-brand{font-size:1.4rem;font-weight:800;margin-bottom:12px;display:flex;align-items:center;gap:8px;}
        .footer-brand .logo-icon{width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,var(--primary),var(--accent));display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.9rem;font-weight:800;}
        .footer-desc{font-size:0.88rem;color:var(--text-muted);margin-bottom:20px;max-width:300px;}
        .footer-title{font-size:0.85rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:16px;color:var(--text);}
        .footer-links{list-style:none;padding:0;}
        .footer-links li{margin-bottom:10px;}
        .footer-links a{color:var(--text-muted);font-size:0.88rem;transition:color var(--transition);}
        .footer-links a:hover{color:var(--accent);}
        .footer-bottom{margin-top:40px;padding-top:20px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;}
        .footer-bottom p{font-size:0.82rem;color:var(--text-muted);margin:0;}
        .footer-socials{display:flex;gap:12px;}
        .footer-socials a{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;background:var(--card-alt);color:var(--text-muted);border:1px solid var(--border);transition:all var(--transition);font-size:1rem;}
        .footer-socials a:hover{color:var(--accent);border-color:var(--accent);transform:translateY(-2px);}
        
        /* Page Loader */
        .page-loader{position:fixed;inset:0;z-index:9999;background:var(--bg);display:flex;align-items:center;justify-content:center;transition:opacity 0.5s,visibility 0.5s;}
        .loader-logo{display:flex;flex-direction:column;align-items:center;gap:20px;}
        @keyframes fade-in-scale{0%{opacity:0;transform:scale(0.8);}100%{opacity:1;transform:scale(1);}}
        @keyframes pulse-scale{0%{opacity:1;transform:scale(1);}50%{opacity:0.7;transform:scale(1.05);}100%{opacity:1;transform:scale(1);}}
        
        /* Responsive */
        @media(max-width:767px){
            .auth-layout{grid-template-columns:1fr;}
            .auth-left{display:none;}
            .auth-right{min-height:calc(100vh - var(--navbar-height));}
            .auth-panel{padding:32px 24px;}
            .footer-bottom{justify-content:center;text-align:center;flex-direction:column;}
        }
        @media(max-width:575px){
            .whatsapp-float{width:48px;height:48px;bottom:20px;right:16px;font-size:1.4rem;}
            .whatsapp-float .pulse-ring{inset:-4px;}
        }
    </style>
    
    @stack('styles')
</head>
<body>

<!-- Page Loader -->
<div class="page-loader" id="pageLoader">
    <div class="loader-logo">
        <div style="width:80px;height:80px;border-radius:16px;background:linear-gradient(135deg,var(--primary),var(--accent));display:flex;align-items:center;justify-content:center;color:#fff;font-size:2.5rem;font-weight:800;animation:fade-in-scale 0.8s ease-out, pulse-scale 2s ease-in-out 0.8s infinite;">C</div>
        <div style="font-size:1.8rem;font-weight:800;letter-spacing:0.1em;background:linear-gradient(135deg,var(--accent),var(--primary));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;animation:fade-in-scale 1s ease-out;">COOCA</div>
    </div>
</div>

<!-- Floating WhatsApp -->
<a href="{{ config('settings.whatsapp_number', 'https://wa.me/6281234567890') }}" class="whatsapp-float" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
    <span class="pulse-ring"></span>
    <i class="bi bi-whatsapp"></i>
</a>

<!-- Navbar -->
<nav class="navbar-cooca" id="mainNav">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            <a href="{{ route('home') }}" class="navbar-brand-cooca">
                <div class="logo-icon">C</div>
                {{ config('app.name', 'COOCA') }}
            </a>
            <div class="d-none d-lg-flex align-items-center gap-1">
                <a href="{{ route('solutions') }}" class="nav-link-cooca">Solutions</a>
                <a href="{{ route('pricing') }}" class="nav-link-cooca">Pricing</a>
                <a href="{{ route('affiliate') }}" class="nav-link-cooca">Affiliate</a>
                <a href="{{ route('blog.index') }}" class="nav-link-cooca">Blog</a>
                <a href="{{ route('about') }}" class="nav-link-cooca">About</a>
            </div>
            <div class="d-flex align-items-center gap-3">
                <button class="theme-toggle d-none d-lg-flex" id="themeToggle" aria-label="Toggle theme">
                    <i class="bi bi-moon-fill" id="themeIcon"></i>
                </button>
                <a href="{{ route('customer.login') }}" class="btn-cooca btn-cooca-outline btn-cooca-sm d-none d-md-inline-flex">Login</a>
                <a href="{{ route('customer.register') }}" class="btn-cooca btn-cooca-primary btn-cooca-sm d-none d-md-inline-flex">Start Free Trial</a>
                <button class="btn btn-outline-secondary d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" style="border-color:var(--border);color:var(--text);border-radius:10px;padding:8px 12px;">
                    <i class="bi bi-list" style="font-size:1.3rem;"></i>
                </button>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Offcanvas -->
<div class="offcanvas offcanvas-end offcanvas-cooca" tabindex="-1" id="mobileMenu">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">{{ config('app.name', 'COOCA') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <div class="d-flex flex-column gap-0">
            <a href="{{ route('solutions') }}" class="nav-link-cooca" data-bs-dismiss="offcanvas">Solutions</a>
            <a href="{{ route('pricing') }}" class="nav-link-cooca" data-bs-dismiss="offcanvas">Pricing</a>
            <a href="{{ route('affiliate') }}" class="nav-link-cooca" data-bs-dismiss="offcanvas">Affiliate</a>
            <a href="{{ route('blog.index') }}" class="nav-link-cooca" data-bs-dismiss="offcanvas">Blog</a>
            <a href="{{ route('about') }}" class="nav-link-cooca" data-bs-dismiss="offcanvas">About</a>
        </div>
        <div class="mt-4 offcanvas-login-group">
            <div class="offcanvas-login-divider" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-muted);text-align:center;padding:4px 0;">Login</div>
            <a href="{{ route('customer.login') }}" class="btn-cooca btn-cooca-outline" data-bs-dismiss="offcanvas" style="width:100%;justify-content:center;margin-bottom:8px;"><i class="bi bi-person-fill"></i> Client Login</a>
            <a href="{{ route('affiliator.login') }}" class="btn-cooca btn-cooca-outline" data-bs-dismiss="offcanvas" style="width:100%;justify-content:center;"><i class="bi bi-people-fill"></i> Affiliate Login</a>
            <div class="offcanvas-login-divider mt-3">Get Started</div>
            <a href="{{ route('customer.register') }}" class="btn-cooca btn-cooca-primary" data-bs-dismiss="offcanvas" style="width:100%;justify-content:center;">Start Free Trial</a>
            <button class="theme-toggle d-lg-none" id="themeToggleMobile" aria-label="Toggle theme" style="justify-content:center;width:100%;margin-top:12px;">
                <i class="bi bi-moon-fill" id="themeIconMobile"></i>
                <span style="margin-left:10px;font-size:0.9rem;">Theme</span>
            </button>
        </div>
    </div>
</div>

<!-- Main Content -->
@yield('content')

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="footer-brand"><div class="logo-icon">C</div>{{ config('app.name', 'COOCA') }}</div>
                <p class="footer-desc">{{ config('settings.company_description', 'The business system that works like an asset. Lifetime license, modular ERP, and complete digital infrastructure for serious businesses.') }}</p>
                <div class="footer-socials">
                    <a href="{{ config('settings.social_twitter', '#') }}"><i class="bi bi-twitter-x"></i></a>
                    <a href="{{ config('settings.social_linkedin', '#') }}"><i class="bi bi-linkedin"></i></a>
                    <a href="{{ config('settings.social_github', '#') }}"><i class="bi bi-github"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-6">
                <div class="footer-title">Solutions</div>
                <ul class="footer-links">
                    <li><a href="{{ route('products.restoran') }}">Retail</a></li>
                    <li><a href="{{ route('products.restoran') }}">Restaurant</a></li>
                    <li><a href="{{ route('products.klinik') }}">Hotel</a></li>
                    <li><a href="{{ route('products.klinik') }}">Clinic</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-6 col-6">
                <div class="footer-title">Company</div>
                <ul class="footer-links">
                    <li><a href="{{ route('about') }}">About</a></li>
                    <li><a href="{{ route('blog.index') }}">Blog</a></li>
                    <li><a href="{{ route('faq') }}">FAQ</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-6 col-6">
                <div class="footer-title">Legal</div>
                <ul class="footer-links">
                    <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                    <li><a href="{{ route('terms') }}">Terms of Service</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-6 col-6">
                <div class="footer-title">Account</div>
                <ul class="footer-links">
                    <li><a href="{{ route('customer.login') }}">Login</a></li>
                    <li><a href="{{ route('customer.register') }}">Sign Up</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© {{ date('Y') }} {{ config('app.name', 'COOCA') }}. All rights reserved.</p>
            <p>Enterprise Business Infrastructure — Built for Ownership.</p>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function() {
    'use strict';
    
    // Page Loader
    window.addEventListener('load', function() {
        setTimeout(function() {
            const loader = document.getElementById('pageLoader');
            if (loader) {
                loader.style.opacity = '0';
                loader.style.visibility = 'hidden';
            }
        }, 1200);
    });
    
    // Theme handling
    const html = document.documentElement;
    const themeToggle = document.getElementById('themeToggle');
    const themeToggleMobile = document.getElementById('themeToggleMobile');
    const themeIcon = document.getElementById('themeIcon');
    const themeIconMobile = document.getElementById('themeIconMobile');
    
    function getSystemTheme() {
        return window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark';
    }
    
    function setTheme(theme) {
        html.setAttribute('data-theme', theme);
        localStorage.setItem('cooca-theme', theme);
        const icon = theme === 'dark' ? 'bi-moon-fill' : 'bi-sun-fill';
        if (themeIcon) themeIcon.className = 'bi ' + icon;
        if (themeIconMobile) themeIconMobile.className = 'bi ' + icon;
    }
    
    const savedTheme = localStorage.getItem('cooca-theme');
    if (savedTheme) {
        setTheme(savedTheme);
    } else {
        setTheme(getSystemTheme());
    }
    
    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            const currentTheme = html.getAttribute('data-theme');
            setTheme(currentTheme === 'dark' ? 'light' : 'dark');
        });
    }
    
    if (themeToggleMobile) {
        themeToggleMobile.addEventListener('click', function() {
            const currentTheme = html.getAttribute('data-theme');
            setTheme(currentTheme === 'dark' ? 'light' : 'dark');
        });
    }
    
    // Navbar scroll effect
    const mainNav = document.getElementById('mainNav');
    if (mainNav) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                mainNav.classList.add('scrolled');
            } else {
                mainNav.classList.remove('scrolled');
            }
        });
    }
    
    // Password toggle
    const pwToggles = document.querySelectorAll('.input-toggle');
    pwToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const input = this.previousElementSibling || this.parentElement.querySelector('input');
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    });
})();
</script>
@stack('scripts')
</body>
</html>
