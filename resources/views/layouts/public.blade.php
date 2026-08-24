<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- SEO -->
    @php
        $siteName = setting('site.name', 'COOCA.ID');
        $favicon = setting('site.favicon') ? asset(setting('site.favicon')) : asset('favicon.svg');
        $logoLight = setting('site.logo_light')
            ? asset(setting('site.logo_light'))
            : (setting('site.logo')
                ? asset(setting('site.logo'))
                : null);
        $logoDark = setting('site.logo_dark')
            ? asset(setting('site.logo_dark'))
            : (setting('site.logo')
                ? asset(setting('site.logo'))
                : null);
        $waNumber = setting('contact.whatsapp', '6282134566667');
        $waCleanNumber = preg_replace('/[^0-9]/', '', $waNumber);
        $waLink = setting('contact.whatsapp_link') ?: 'https://wa.me/' . ($waCleanNumber ?: '6282134566667');
        $emailSupport = setting('contact.email', 'support@cooca.id');
        $contactAddress = setting('contact.address', 'Jl. Jend. Sudirman No. 52, Jakarta Selatan, DKI Jakarta 12920');
        $footerDesc = setting(
            'footer.description',
            'Platform ERP enterprise untuk UMKM, Klinik, Bengkel, Restoran, Retail dan semua skala bisnis. Cloud native, modular, dan selalu siap.',
        );
        $socialInsta = setting('social.instagram', '#');
        $socialFb = setting('social.facebook', '#');
        $socialTwitter = setting('social.twitter', '#');
        $socialYt = setting('social.youtube', '#');
        $socialGithub = setting('social.github', '#');
        $socialLinkedin = setting('social.linkedin', '#');
    @endphp

    <!-- ── Primary Meta ────────────────────────────────────────────────── -->
    <title>@yield('title', 'COOCA.ID - Move Faster. Decide Better | Software Bisnis')</title>
    <meta name="description" content="@yield('description', 'Eksekusi operasional lebih cepat dan ambil keputusan bisnis lebih tepat dengan COOCA.ID. Software manajemen bisnis terpadu untuk efisiensi total usaha Anda.')">
    <meta name="keywords" content="@yield('keywords', 'cooca id, software bisnis indonesia, move faster decide better, software manajemen operasional, platform otomatisasi bisnis, software pendukung keputusan')">
    <meta name="author" content="{{ $siteName }}">
    <meta name="robots" content="@yield('robots', 'index, follow')">
    <meta name="googlebot" content="@yield('robots', 'index, follow')">
    <link rel="canonical" href="@yield('canonical', url()->current())">
    <meta name="dmca-site-verification" content="R3Q5WVUxSFVWZmlsc3kxRExzSHBCZz090">

    <!-- ── Open Graph / Facebook ────────────────────────────────────────── -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:locale" content="{{ str_replace('-', '_', app()->getLocale()) }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', View::hasSection('og_title') ? View::getSection('og_title') : View::getSection('title', 'COOCA.ID - Move Faster. Decide Better | Software Bisnis'))">
    <meta property="og:description" content="@yield('og_description', View::hasSection('og_description') ? View::getSection('og_description') : View::getSection('description', 'Eksekusi operasional lebih cepat dan ambil keputusan bisnis lebih tepat dengan COOCA.ID. Software manajemen bisnis terpadu untuk efisiensi total usaha Anda.'))">
    <meta property="og:image" content="@yield('og_image', asset('images/og-image.png'))">
    <meta property="og:image:width" content="@yield('og_image_width', '1200')">
    <meta property="og:image:height" content="@yield('og_image_height', '630')">
    <meta property="og:image:alt" content="@yield('og_title', View::hasSection('og_title') ? View::getSection('og_title') : View::getSection('title', 'COOCA.ID - Move Faster. Decide Better | Software Bisnis'))">

    <!-- ── Twitter Card ─────────────────────────────────────────────────── -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@coocaid">
    <meta name="twitter:title" content="@yield('og_title', View::hasSection('og_title') ? View::getSection('og_title') : View::getSection('title', 'COOCA.ID - Move Faster. Decide Better | Software Bisnis'))">
    <meta name="twitter:description" content="@yield('og_description', View::hasSection('og_description') ? View::getSection('og_description') : View::getSection('description', 'Eksekusi operasional lebih cepat dan ambil keputusan bisnis lebih tepat dengan COOCA.ID. Software manajemen bisnis terpadu untuk efisiensi total usaha Anda.'))">
    <meta name="twitter:image" content="@yield('og_image', asset('images/og-image.png'))">

    <!-- ── Favicon ──────────────────────────────────────────────────────── -->
    <link rel="icon" type="image/svg+xml" href="{{ $favicon }}">
    <link rel="apple-touch-icon" href="{{ $favicon }}">

    <!-- ── JSON-LD: WebSite Schema ──────────────────────────────────────── -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "WebSite",
        "name": "{{ e($siteName) }}",
        "url": "{{ url('/') }}",
        "description": "Platform software manajemen dan otomatisasi bisnis terpadu. Move Faster. Decide Better.",
        "potentialAction": {
            "@@type": "SearchAction",
            "target": {
                "@@type": "EntryPoint",
                "urlTemplate": "{{ url('/blog') }}?search={search_term_string}"
            },
            "query-input": "required name=search_term_string"
        }
    }
    </script>

    <!-- ── JSON-LD: Organization Schema (Optimized for AI & GEO) ───── -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Organization",
        "name": "{{ e($siteName) }}",
        "legalName": "{{ e($siteName) }}",
        "url": "{{ url('/') }}",
        "logo": "{{ e($logoLight ?? $logoDark ?? asset('favicon.svg')) }}",
        "slogan": "Move Faster. Decide Better.",
        "description": "COOCA.ID adalah platform software manajemen bisnis terpadu di Indonesia yang menyediakan solusi otomatisasi operasional dan analitik data real-time.",
        "foundingLocation": {
            "@@type": "Place",
            "addressCountry": "ID"
        },
        "contactPoint": {
            "@@type": "ContactPoint",
            "telephone": "+{{ e($waCleanNumber) }}",
            "contactType": "customer support",
            "areaServed": "ID",
            "availableLanguage": "Indonesian"
        },
        "sameAs": [
            "https://www.instagram.com/cooca.id",
            "https://www.linkedin.com/company/cooca-id"
        ]
    }
    </script>

    <!-- ── Consent-Aware Google Analytics (GA4) ────────────────────────── -->
    <script>
        window.gaLoaded = false;

        function loadAnalyticsIfConsented() {
            var consent = localStorage.getItem('cooca_cookie_consent');
            if (consent === 'all' && !window.gaLoaded) {
                window.gaLoaded = true;
                var script = document.createElement('script');
                script.async = true;
                script.src = 'https://www.googletagmanager.com/gtag/js?id=G-HZSN9QHGN1';
                document.head.appendChild(script);

                window.dataLayer = window.dataLayer || [];

                function gtag() {
                    dataLayer.push(arguments);
                }
                window.gtag = gtag;
                gtag('js', new Date());
                gtag('config', 'G-HZSN9QHGN1', {
                    'anonymize_ip': true,
                    'cookie_flags': 'SameSite=None;Secure'
                });
            }
        }
        loadAnalyticsIfConsented();
    </script>

    <!-- ── Performance: DNS prefetch / Preconnect ───────────────────────── -->
    <link rel="dns-prefetch" href="https://www.googletagmanager.com">
    <link rel="dns-prefetch" href="https://www.google-analytics.com">
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- ── Fonts (non-blocking) ─────────────────────────────────────────── -->
    <link rel="preload" as="style"
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@600;700;800&display=swap">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@600;700;800&display=swap"
        media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@600;700;800&display=swap">
    </noscript>

    <!-- ── Icons (non-blocking) ─────────────────────────────────────────── -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        as="style">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </noscript>

    <!-- ── App CSS ──────────────────────────────────────────────────────── -->
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">

    <!-- ── SweetAlert2 (deferred) ───────────────────────────────────────── -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>

    @stack('styles')
</head>

<body class="landing-page" id="app">

    {{-- ── FLOATING NAVBAR ─────────────────────────────── --}}
    <nav class="lp-navbar" id="lpNavbar" role="navigation" aria-label="Main navigation">
        {{-- Logo (Light & Dark settings integration) --}}
        <a href="{{ route('home') }}" class="lp-nav-logo" aria-label="{{ $siteName }} Home">
            @if ($logoLight)
                <img src="{{ $logoLight }}" alt="{{ $siteName }}" class="logo-light-only" loading="eager"
                    fetchpriority="high" width="auto" height="32"
                    style="height: 32px; width: auto; object-fit: contain;">
            @endif
            @if ($logoDark)
                <img src="{{ $logoDark }}" alt="{{ $siteName }}" class="logo-dark-only" loading="eager"
                    fetchpriority="high" width="auto" height="32"
                    style="height: 32px; width: auto; object-fit: contain;">
            @endif
            @if (!$logoLight && !$logoDark)
                <svg width="28" height="28" viewBox="0 0 28 28" fill="none"
                    xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <rect width="28" height="28" rx="8" fill="url(#logo_grad)" />
                    <path
                        d="M7 14C7 10.134 10.134 7 14 7C17.866 7 21 10.134 21 14C21 17.866 17.866 21 14 21C10.134 21 7 17.866 7 14Z"
                        fill="white" fill-opacity="0.2" />
                    <path
                        d="M10.5 14C10.5 12.067 12.067 10.5 14 10.5C15.933 10.5 17.5 12.067 17.5 14C17.5 15.933 15.933 17.5 14 17.5C12.067 17.5 10.5 15.933 10.5 14Z"
                        fill="white" />
                    <defs>
                        <linearGradient id="logo_grad" x1="0" y1="0" x2="28" y2="28"
                            gradientUnits="userSpaceOnUse">
                            <stop stop-color="#4F46E5" />
                            <stop offset="1" stop-color="#06B6D4" />
                        </linearGradient>
                    </defs>
                </svg>
                <span>{{ $siteName }}</span>
            @endif
        </a>

        {{-- Desktop Menu --}}
        <ul class="lp-nav-menu" role="list">
            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
            </li>
            <li><a href="{{ route('products.index') }}"
                    class="{{ request()->routeIs('products.*') ? 'active' : '' }}">Produk ERP</a></li>
            <li><a href="{{ route('affiliate') }}"
                    class="{{ request()->routeIs('affiliate') ? 'active' : '' }}">Partner</a></li>
            <li><a href="{{ route('blog.index') }}"
                    class="{{ request()->routeIs('blog.*') ? 'active' : '' }}">Blog</a></li>
            <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">Tentang</a>
            </li>
            <li><a href="{{ route('contact') }}"
                    class="{{ request()->routeIs('contact') ? 'active' : '' }}">Kontak</a></li>
        </ul>

        {{-- Actions --}}
        <div class="lp-nav-actions">
            <button class="theme-toggle" id="themeToggle" aria-label="Toggle dark mode" title="Toggle theme">
                <span id="themeIcon">🌙</span>
            </button>

            @php
                $isAdmin = auth('admin')->check();
                $isCustomer = auth('customer')->check();
                $isAffiliator = auth('affiliator')->check();
            @endphp
            @if($isAdmin)
            <a href="{{ route('admin.dashboard') }}" class="btn-primary-glow"
                style="display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-shield-halved"></i> Dashboard Admin
            </a>
            @elseif($isCustomer)
            <a href="{{ route('customer.dashboard') }}" class="btn-primary-glow"
                style="display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-building"></i> Dashboard Bisnis
            </a>
            @elseif($isAffiliator)
            <a href="{{ route('affiliator.dashboard') }}" class="btn-primary-glow"
                style="display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-handshake"></i> Portal Partner
            </a>
            @else
            <div class="nav-dropdown" style="position: relative; display: inline-block;">
                <button class="btn-ghost" id="nav-login-btn"
                    style="display: flex; align-items: center; gap: 8px; cursor: pointer; border: none; font-family: inherit;">
                    Masuk <i class="fa-solid fa-chevron-down" style="font-size: 10px; opacity: 0.6;"></i>
                </button>
                <div class="nav-dropdown-content"
                    style="position: absolute; right: 0; top: 100%; margin-top: 8px; background: var(--surface); min-width: 200px; box-shadow: var(--shadow-xl); border-radius: 12px; border: 1px solid var(--border); z-index: 1000; overflow: hidden; padding: 4px; opacity: 0; visibility: hidden; transform: translateY(-10px); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); transition-delay: 0.1s;">
                    <a href="{{ route('customer.login') }}"
                        style="color: var(--text); padding: 10px 16px; text-decoration: none; display: flex; align-items: center; gap: 12px; font-size: 14px; font-weight: 600; border-radius: 8px; transition: background .2s;"
                        onmouseover="this.style.background='var(--bg)'; this.style.color='var(--primary)'"
                        onmouseout="this.style.background='transparent'; this.style.color='var(--text)'">
                        <i class="fa-solid fa-building" style="width: 16px; text-align: center;"></i> Pelanggan
                    </a>
                    <a href="{{ route('affiliator.login') }}"
                        style="color: var(--text); padding: 10px 16px; text-decoration: none; display: flex; align-items: center; gap: 12px; font-size: 14px; font-weight: 600; border-radius: 8px; transition: background .2s;"
                        onmouseover="this.style.background='var(--bg)'; this.style.color='var(--primary)'"
                        onmouseout="this.style.background='transparent'; this.style.color='var(--text)'">
                        <i class="fa-solid fa-handshake" style="width: 16px; text-align: center;"></i> Partner
                    </a>
                </div>
            </div>

            <a href="{{ route('customer.register') }}" class="btn-primary-glow" id="nav-cta-btn">
                Coba Gratis 14 Hari
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
                    <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </a>
            @endif
        </div>

        {{-- Mobile Toggle --}}
        <button class="lp-nav-mobile-toggle" id="mobileNavToggle" aria-label="Toggle mobile menu"
            aria-expanded="false">
            ☰
        </button>
    </nav>

    {{-- Mobile Menu --}}
    <div class="lp-mobile-menu" id="mobileMenu" role="dialog" aria-label="Mobile navigation">
        <a href="{{ route('home') }}">Beranda</a>
        <a href="{{ route('products.index') }}">Produk ERP</a>
        <a href="{{ route('affiliate') }}">Partner</a>
        <a href="{{ route('blog.index') }}">Blog</a>
        <a href="{{ route('about') }}">Tentang</a>
        <a href="{{ route('contact') }}">Kontak</a>
        <div
            style="padding: 12px 0 4px; border-top: 1px solid var(--border); margin-top: 8px; display: flex; flex-direction: column; gap: 8px;">
            @if($isAdmin)
            <a href="{{ route('admin.dashboard') }}" class="btn-primary-glow mobile-cta"
                style="text-align:center; justify-content:center;">Dashboard Admin →</a>
            @elseif($isCustomer)
            <a href="{{ route('customer.dashboard') }}" class="btn-primary-glow mobile-cta"
                style="text-align:center; justify-content:center;">Dashboard Bisnis →</a>
            @elseif($isAffiliator)
            <a href="{{ route('affiliator.dashboard') }}" class="btn-primary-glow mobile-cta"
                style="text-align:center; justify-content:center;">Portal Partner →</a>
            @else
            <a href="{{ route('customer.login') }}" class="btn-ghost" style="text-align:center;">Login Pelanggan</a>
            <a href="{{ route('affiliator.login') }}" class="btn-ghost" style="text-align:center;">Login Partner</a>
            <a href="{{ route('customer.register') }}" class="btn-primary-glow mobile-cta"
                style="text-align:center; justify-content:center;">Coba Gratis 14 Hari →</a>
            @endif
        </div>
    </div>

    {{-- Page Content --}}
    <main id="main-content">
        @yield('content')
    </main>

    {{-- ── FLOATING WHATSAPP CHATBOT WIDGET ────────────────────── --}}
    <div id="waChatbotWidgetContainer"
        style="position: fixed; bottom: 24px; right: 24px; z-index: 99999; font-family: inherit;">

        {{-- Floating Popup Window (Hidden by Default) --}}
        <div id="waChatbotWindow"
            style="display: none; width: 350px; max-width: calc(100vw - 32px); background: #FFFFFF; border-radius: 18px; box-shadow: 0 20px 40px -10px rgba(0,0,0,0.25), 0 0 0 1px rgba(0,0,0,0.05); overflow: hidden; transform-origin: bottom right; transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); margin-bottom: 12px;">

            {{-- Header --}}
            <div
                style="background: linear-gradient(135deg, #075E54 0%, #128C7E 100%); color: white; padding: 14px 16px; display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="position: relative;">
                        <div
                            style="width: 36px; height: 36px; border-radius: 50%; background: #25D366; display: flex; align-items: center; justify-content: center; font-size: 18px; color: white;">
                            <i class="fa-brands fa-whatsapp"></i>
                        </div>
                        <span
                            style="position: absolute; bottom: 0; right: 0; width: 10px; height: 10px; background: #22C55E; border: 2px solid #075E54; border-radius: 50%;"></span>
                    </div>
                    <div>
                        <div style="font-weight: 700; font-size: 14px; line-height: 1.2;">Cooca.id Live Support</div>
                        <div style="font-size: 11px; opacity: 0.9;" id="waHeaderSubtitle">● Realtime Active</div>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 6px;">
                    <button type="button" id="waEndChatHeaderBtn" onclick="endLiveChatSession()"
                        style="display: none; background: rgba(239,68,68,0.3); border: none; color: white; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; cursor: pointer;"
                        onmouseover="this.style.background='rgba(239,68,68,0.5)'"
                        onmouseout="this.style.background='rgba(239,68,68,0.3)'">
                        <i class="fa-solid fa-flag-checkered"></i> Akhiri Chat
                    </button>
                    <button type="button" onclick="toggleWaChatbot()"
                        style="background: rgba(255,255,255,0.2); border: none; color: white; width: 26px; height: 26px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 12px;">
                        ✕
                    </button>
                </div>
            </div>

            {{-- 1. Initial Form Screen & Interactive Helpdesk --}}
            <div id="waChatFormScreen"
                style="padding: 16px; background: #ECE5DD; max-height: 460px; overflow-y: auto;">
                <div
                    style="background: white; border-radius: 12px 12px 12px 2px; padding: 12px 14px; max-width: 94%; box-shadow: 0 2px 4px rgba(0,0,0,0.06); font-size: 13px; color: #1E293B; margin-bottom: 12px; line-height: 1.5;">
                    <div style="font-weight: 700; color: #075E54; margin-bottom: 2px;">Tim Support Cooca.id</div>
                    Halo! 👋 Pilih topik informasi di bawah ini untuk jawaban instan, atau hubungi Admin Realtime.
                </div>

                {{-- Interactive Select Option Helpdesk (Managed via Admin Panel FAQ) --}}
                <div
                    style="background: white; border-radius: 14px; padding: 14px; box-shadow: 0 4px 12px rgba(0,0,0,0.06); margin-bottom: 14px;">
                    <label
                        style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
                        📌 Pilih Topik Informasi Cepat:
                    </label>
                    <select id="waHelpdeskSelect" onchange="handleHelpdeskSelectChange(this)"
                        style="width: 100%; padding: 10px 12px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 12px; background: #F8FAFC; color: #1E293B; font-weight: 600; outline: none; cursor: pointer; box-sizing: border-box;">
                        <option value="">-- Loading Topik Informasi... --</option>
                    </select>

                    {{-- Answer Card --}}
                    <div id="waHelpdeskAnswerBox"
                        style="display: none; margin-top: 12px; padding: 12px; background: #F0FDF4; border: 1px solid #BBF7D0; border-left: 4px solid #16A34A; border-radius: 8px; font-size: 12px; line-height: 1.5; color: #1E293B;">
                    </div>

                    <button type="button" id="waConnectLiveChatBtn" onclick="showLiveChatFormDirectly()"
                        style="margin-top: 12px; width: 100%; background: #128C7E; color: white; border: none; padding: 10px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px;">
                        <i class="fa-solid fa-headset"></i> Hubungi Admin Realtime (Live Chat)
                    </button>
                </div>

                {{-- Live Chat Registration Form (Toggled when user wants live chat) --}}
                <div id="waLiveChatFormWrapper" style="display: none;">
                    <form id="waChatbotForm" onsubmit="startLiveChatSession(event)"
                        style="background: white; border-radius: 14px; padding: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); display: flex; flex-direction: column; gap: 12px;">
                        @csrf
                        <div
                            style="font-size: 12px; font-weight: 700; color: #075E54; border-bottom: 1px solid #E2E8F0; padding-bottom: 6px; display: flex; align-items: center; justify-content: space-between;">
                            <span>💬 Form Hubungi Admin Realtime</span>
                            <button type="button" onclick="hideLiveChatFormDirectly()"
                                style="background: none; border: none; color: #64748B; cursor: pointer; font-size: 12px;">Tutup</button>
                        </div>
                        <div>
                            <label
                                style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Nama
                                Lengkap *</label>
                            <input type="text" name="name" required placeholder="Contoh: Budi Santoso"
                                style="width: 100%; padding: 9px 12px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 13px; outline: none; box-sizing: border-box;">
                        </div>
                        <div>
                            <label
                                style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Nomor
                                WhatsApp *</label>
                            <input type="tel" name="phone" required placeholder="Contoh: 081234567890"
                                style="width: 100%; padding: 9px 12px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 13px; outline: none; box-sizing: border-box;">
                        </div>
                        <div>
                            <label
                                style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Alamat
                                Email *</label>
                            <input type="email" name="email" required placeholder="Contoh: budi@gmail.com"
                                style="width: 100%; padding: 9px 12px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 13px; outline: none; box-sizing: border-box;">
                        </div>
                        <div>
                            <label
                                style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Pesan
                                Awal Anda *</label>
                            <textarea name="message" id="waFormInitialMessage" required rows="3" placeholder="Tuliskan pertanyaan Anda..."
                                style="width: 100%; padding: 9px 12px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 13px; outline: none; box-sizing: border-box; resize: none;"></textarea>
                        </div>
                        <button type="submit" id="waChatbotSubmitBtn"
                            style="background: linear-gradient(135deg, #25D366 0%, #128C7E 100%); color: white; border: none; padding: 12px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 10px rgba(37,211,102,0.3);">
                            <i class="fa-brands fa-whatsapp" style="font-size: 16px;"></i> Mulai Live Chat Realtime
                        </button>
                    </form>
                </div>
            </div>

            {{-- 2. Live Conversation Screen (Hidden by Default) --}}
            <div id="waLiveChatConversationScreen"
                style="display: none; flex-direction: column; height: 420px; background: #ECE5DD;">
                {{-- Message Container --}}
                <div id="lcMessageContainer"
                    style="flex: 1; padding: 14px; overflow-y: auto; display: flex; flex-direction: column; gap: 10px;">
                    <!-- Messages will be rendered dynamically here -->
                </div>

                {{-- Reply Bar --}}
                <form id="lcCustomerReplyForm" onsubmit="submitCustomerReply(event)"
                    style="padding: 10px; background: #F0F2F5; display: flex; gap: 8px; align-items: center; border-top: 1px solid #CBD5E1;">
                    <input type="text" id="lcCustomerReplyInput" required placeholder="Tulis pesan Anda..."
                        style="flex: 1; padding: 10px 14px; border: 1px solid #CBD5E1; border-radius: 20px; font-size: 13px; outline: none; background: white;">
                    <button type="submit" id="lcCustomerSendBtn"
                        style="background: #128C7E; color: white; border: none; width: 36px; height: 36px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>

        {{-- Floating Trigger Button --}}
        <button type="button" onclick="toggleWaChatbot()" class="wa-floating-btn"
            aria-label="Hubungi kami via Live Chat" style="border: none; cursor: pointer;">
            <span class="wa-floating-badge"></span>
            <svg class="wa-floating-icon" viewBox="0 0 24 24">
                <path
                    d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
            </svg>
            <span class="wa-floating-label">Chat Kami</span>
        </button>
    </div>

    {{-- Pusher JS (Optional Realtime Websocket) --}}
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

    <script>
        var loadedFaqsList = [];
        var lcToken = localStorage.getItem('cooca_lc_token') || null;
        var lcLastMessageId = 0;
        var lcPollTimer = null;
        var pusherInstance = null;

        function initPusherIfAvailable() {
            var pusherKey = "{{ env('PUSHER_APP_KEY', '') }}";
            var pusherCluster = "{{ env('PUSHER_APP_CLUSTER', 'ap1') }}";
            if (pusherKey && typeof Pusher !== 'undefined' && !pusherInstance) {
                try {
                    pusherInstance = new Pusher(pusherKey, {
                        cluster: pusherCluster,
                        forceTLS: true
                    });
                } catch (e) {
                    console.warn('Pusher init skipped:', e);
                }
            }
        }

        function fetchChatbotHelpdeskOptions() {
            fetch("{{ route('live-chat.options') }}")
                .then(function(res) {
                    return res.json();
                })
                .then(function(data) {
                    if (data.success && data.data) {
                        loadedFaqsList = data.data;
                        var select = document.getElementById('waHelpdeskSelect');
                        if (!select) return;

                        select.innerHTML = '<option value="">-- Pilih Topik Informasi --</option>';

                        loadedFaqsList.forEach(function(faq) {
                            var opt = document.createElement('option');
                            opt.value = faq.id;
                            opt.textContent = faq.question;
                            select.appendChild(opt);
                        });

                        var optLive = document.createElement('option');
                        optLive.value = 'live_chat';
                        optLive.textContent = '💬 Hubungi Admin Realtime (Live Chat)';
                        select.appendChild(optLive);
                    }
                })
                .catch(function(err) {
                    console.error('Failed to load FAQs:', err);
                });
        }

        function handleHelpdeskSelectChange(selectEl) {
            var val = selectEl.value;
            var box = document.getElementById('waHelpdeskAnswerBox');

            if (!val) {
                box.style.display = 'none';
                return;
            }

            if (val === 'live_chat') {
                box.style.display = 'none';
                showLiveChatFormDirectly();
                return;
            }

            var selectedFaq = loadedFaqsList.find(function(f) {
                return f.id == val;
            });
            if (selectedFaq) {
                box.innerHTML = '<div style="font-weight:700; color:#075E54; margin-bottom:4px;">' + selectedFaq.question +
                    '</div>' +
                    '<div>' + selectedFaq.answer.replace(/\n/g, '<br>') + '</div>' +
                    '<div style="margin-top:10px; border-top:1px dashed #A7F3D0; padding-top:6px; font-size:11px; color:#047857; font-weight:600;">💡 Masih butuh bantuan? Klik "Hubungi Admin Realtime" di bawah!</div>';
                box.style.display = 'block';

                var msgArea = document.getElementById('waFormInitialMessage');
                if (msgArea) msgArea.value = "Tanya seputar: " + selectedFaq.question;
            }
        }

        function showLiveChatFormDirectly() {
            var formWrap = document.getElementById('waLiveChatFormWrapper');
            if (formWrap) {
                formWrap.style.display = 'block';
                formWrap.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        }

        function hideLiveChatFormDirectly() {
            var formWrap = document.getElementById('waLiveChatFormWrapper');
            if (formWrap) {
                formWrap.style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            fetchChatbotHelpdeskOptions();
            initPusherIfAvailable();
            if (lcToken) {
                resumeLiveChatSession();
            }
        });

        function toggleWaChatbot() {
            var win = document.getElementById('waChatbotWindow');
            if (win.style.display === 'none' || !win.style.display) {
                win.style.display = 'block';
                if (lcToken) {
                    resumeLiveChatSession();
                }
            } else {
                win.style.display = 'none';
            }
        }

        function startLiveChatSession(e) {
            e.preventDefault();
            var form = document.getElementById('waChatbotForm');
            var btn = document.getElementById('waChatbotSubmitBtn');
            var origHtml = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menghubungkan...';

            var formData = new FormData(form);

            fetch("{{ route('live-chat.start') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(function(res) {
                    return res.json();
                })
                .then(function(data) {
                    btn.disabled = false;
                    btn.innerHTML = origHtml;

                    if (data.success) {
                        lcToken = data.session_token;
                        localStorage.setItem('cooca_lc_token', lcToken);

                        document.getElementById('waChatFormScreen').style.display = 'none';
                        document.getElementById('waLiveChatConversationScreen').style.display = 'flex';
                        document.getElementById('waEndChatHeaderBtn').style.display = 'block';

                        renderWidgetMessages(data.messages, true);
                        startLcPolling();
                    } else {
                        alert(data.message || (data.errors ? Object.values(data.errors).flat().join("\n") :
                            'Gagal memulai chat.'));
                    }
                })
                .catch(function(err) {
                    btn.disabled = false;
                    btn.innerHTML = origHtml;
                    alert('Gagal menghubungkan ke server live chat. Silakan periksa koneksi internet Anda.');
                });
        }

        function resumeLiveChatSession() {
            if (!lcToken) return;

            document.getElementById('waChatFormScreen').style.display = 'none';
            document.getElementById('waLiveChatConversationScreen').style.display = 'flex';
            document.getElementById('waEndChatHeaderBtn').style.display = 'block';

            fetchLiveChatMessages();
            startLcPolling();
        }

        function startLcPolling() {
            if (lcPollTimer) clearInterval(lcPollTimer);
            // Fast realtime polling (1.5 seconds)
            lcPollTimer = setInterval(fetchLiveChatMessages, 1500);
        }

        function fetchLiveChatMessages() {
            if (!lcToken) return;

            fetch("{{ route('live-chat.messages') }}?session_token=" + encodeURIComponent(lcToken) + "&last_id=" +
                    lcLastMessageId)
                .then(function(res) {
                    return res.json();
                })
                .then(function(data) {
                    if (data.success) {
                        if (data.status === 'ended') {
                            handleChatEndedLocally();
                            return;
                        }
                        if (data.messages && data.messages.length > 0) {
                            renderWidgetMessages(data.messages, false);
                        }
                    }
                })
                .catch(function(err) {
                    console.warn('Poll error:', err);
                });
        }

        function renderWidgetMessages(messages, isInitial) {
            var container = document.getElementById('lcMessageContainer');
            if (!container) return;

            if (isInitial) {
                container.innerHTML = '';
                lcLastMessageId = 0;
            }

            messages.forEach(function(m) {
                if (m.id > lcLastMessageId) {
                    lcLastMessageId = m.id;
                }

                var isCustomer = m.sender_type === 'customer';
                var isSystem = m.sender_type === 'system';

                var div = document.createElement('div');
                if (isSystem) {
                    div.style.textAlign = 'center';
                    div.style.margin = '6px 0';
                    div.innerHTML =
                        '<span style="background: rgba(0,0,0,0.08); padding: 4px 10px; border-radius: 10px; font-size: 11px; color: #475569; font-weight: 600;">' +
                        m.message + '</span>';
                } else if (isCustomer) {
                    div.style.display = 'flex';
                    div.style.flexDirection = 'column';
                    div.style.alignItems = 'flex-end';
                    div.innerHTML =
                        '<div style="background: #DCF8C6; color: #1E293B; border-radius: 12px 12px 2px 12px; padding: 8px 12px; max-width: 84%; box-shadow: 0 1px 3px rgba(0,0,0,0.08); font-size: 12px; line-height: 1.4; white-space: pre-wrap; word-break: break-word;">' +
                        '<div style="font-weight: 700; font-size: 10px; color: #128C7E; margin-bottom: 2px;">Anda</div>' +
                        m.message +
                        '</div>';
                } else {
                    div.style.display = 'flex';
                    div.style.flexDirection = 'column';
                    div.style.alignItems = 'flex-start';
                    div.innerHTML =
                        '<div style="background: white; color: #1E293B; border-radius: 12px 12px 12px 2px; padding: 8px 12px; max-width: 84%; box-shadow: 0 1px 3px rgba(0,0,0,0.08); font-size: 12px; line-height: 1.4; white-space: pre-wrap; word-break: break-word;">' +
                        '<div style="font-weight: 700; font-size: 10px; color: #075E54; margin-bottom: 2px;">Admin Cooca</div>' +
                        m.message +
                        '</div>';
                }

                container.appendChild(div);
            });

            container.scrollTop = container.scrollHeight;
        }

        function submitCustomerReply(e) {
            e.preventDefault();
            if (!lcToken) return;

            var input = document.getElementById('lcCustomerReplyInput');
            var btn = document.getElementById('lcCustomerSendBtn');
            var messageText = input.value.trim();
            if (!messageText) return;

            input.value = '';
            btn.disabled = true;

            fetch("{{ route('live-chat.send') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        session_token: lcToken,
                        message: messageText
                    })
                })
                .then(function(res) {
                    return res.json();
                })
                .then(function(data) {
                    btn.disabled = false;
                    if (data.success) {
                        fetchLiveChatMessages();
                    } else {
                        alert(data.error || 'Gagal mengirim pesan.');
                        input.value = messageText;
                    }
                })
                .catch(function(err) {
                    btn.disabled = false;
                    alert('Gagal mengirim pesan. Periksa koneksi internet.');
                    input.value = messageText;
                });
        }

        function endLiveChatSession() {
            if (!lcToken) return;
            if (!confirm(
                    'Apakah Anda yakin ingin mengakhiri sesi percakapan ini? Transkrip lengkap percakapan akan otomatis dikirimkan ke WhatsApp dan Email Anda.'
                    )) return;

            fetch("{{ route('live-chat.end') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        session_token: lcToken
                    })
                })
                .then(function(res) {
                    return res.json();
                })
                .then(function(data) {
                    if (data.success) {
                        alert(
                            'Sesi percakapan telah berakhir. Ringkasan transkrip telah dikirimkan ke WhatsApp dan Email Anda.');
                        handleChatEndedLocally();
                    }
                })
                .catch(function(err) {
                    handleChatEndedLocally();
                });
        }

        function handleChatEndedLocally() {
            if (lcPollTimer) clearInterval(lcPollTimer);
            lcToken = null;
            localStorage.removeItem('cooca_lc_token');

            document.getElementById('waChatFormScreen').style.display = 'block';
            document.getElementById('waLiveChatConversationScreen').style.display = 'none';
            document.getElementById('waEndChatHeaderBtn').style.display = 'none';
        }
    </script>



    {{-- ── MEGA FOOTER ──────────────────────────────────── --}}
    <footer class="lp-footer" role="contentinfo">
        <div class="lp-container">
            <div class="footer-grid">
                {{-- Brand Column --}}
                <div class="footer-brand">
                    <div class="brand-name">
                        @if ($logoLight)
                            <img src="{{ $logoLight }}" alt="{{ $siteName }}" class="logo-light-only"
                                style="height: 32px; width: auto; object-fit: contain;">
                        @endif
                        @if ($logoDark)
                            <img src="{{ $logoDark }}" alt="{{ $siteName }}" class="logo-dark-only"
                                style="height: 32px; width: auto; object-fit: contain;">
                        @endif
                        @if (!$logoLight && !$logoDark)
                            <svg width="24" height="24" viewBox="0 0 28 28" fill="none"
                                aria-hidden="true">
                                <rect width="28" height="28" rx="8" fill="url(#footer_logo_grad)" />
                                <path
                                    d="M10.5 14C10.5 12.067 12.067 10.5 14 10.5C15.933 10.5 17.5 12.067 17.5 14C17.5 15.933 15.933 17.5 14 17.5C12.067 17.5 10.5 15.933 10.5 14Z"
                                    fill="white" />
                                <defs>
                                    <linearGradient id="footer_logo_grad" x1="0" y1="0"
                                        x2="28" y2="28" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#4F46E5" />
                                        <stop offset="1" stop-color="#06B6D4" />
                                    </linearGradient>
                                </defs>
                            </svg>
                            <span>{{ $siteName }}</span>
                        @endif
                    </div>
                    <p class="brand-desc">{{ $footerDesc }}</p>
                    <div class="footer-socials">
                        @if ($socialInsta && $socialInsta !== '#')
                            <a href="{{ $socialInsta }}" target="_blank" class="social-icon"
                                aria-label="Instagram">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5" />
                                    <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z" />
                                    <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" />
                                </svg>
                            </a>
                        @endif
                        @if ($socialFb && $socialFb !== '#')
                            <a href="{{ $socialFb }}" target="_blank" class="social-icon"
                                aria-label="Facebook">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z" />
                                </svg>
                            </a>
                        @endif
                        @if ($socialTwitter && $socialTwitter !== '#')
                            <a href="{{ $socialTwitter }}" target="_blank" class="social-icon"
                                aria-label="Twitter/X">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                                </svg>
                            </a>
                        @endif
                        @if ($socialYt && $socialYt !== '#')
                            <a href="{{ $socialYt }}" target="_blank" class="social-icon" aria-label="YouTube">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M22.54 6.42a2.78 2.78 0 00-1.95-1.95C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 00-1.95 1.96C1 8.12 1 12 1 12s0 3.88.46 5.58a2.78 2.78 0 001.95 1.95C5.12 20 12 20 12 20s6.88 0 8.59-.47a2.78 2.78 0 001.95-1.95C23 15.88 23 12 23 12s0-3.88-.46-5.58zM9.75 15.02V8.98L15.5 12l-5.75 3.02z" />
                                </svg>
                            </a>
                        @endif
                        @if ($socialLinkedin && $socialLinkedin !== '#')
                            <a href="{{ $socialLinkedin }}" target="_blank" class="social-icon"
                                aria-label="LinkedIn">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z" />
                                </svg>
                            </a>
                        @endif
                        @if ($socialGithub && $socialGithub !== '#')
                            <a href="{{ $socialGithub }}" target="_blank" class="social-icon" aria-label="GitHub">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Navigasi --}}
                <div>
                    <p class="footer-col-title">Navigasi</p>
                    <nav class="footer-links" aria-label="Footer navigation">
                        <a href="{{ route('home') }}">Beranda</a>
                        <a href="{{ route('about') }}">Tentang</a>
                        <a href="{{ route('products.index') }}">Produk ERP</a>
                        <a href="{{ route('contact') }}">Kontak</a>
                        <a href="{{ route('affiliate') }}">Partner</a>
                        <a href="{{ route('blog.index') }}">Blog</a>
                    </nav>
                </div>

                {{-- Layanan --}}
                <div>
                    <p class="footer-col-title">Layanan</p>
                    <nav class="footer-links" aria-label="Services">
                        <a href="{{ route('products.index') }}">POS ERP Restoran</a>
                        <a href="{{ route('products.index') }}">Klinik ERP EMR</a>
                        <a href="{{ route('products.index') }}">Bengkel ERP</a>
                        <a href="{{ route('products.index') }}">Notaris Legal</a>
                        <a href="{{ route('products.index') }}">Retail POS</a>
                    </nav>
                </div>

                {{-- Sumber Daya --}}
                <div>
                    <p class="footer-col-title">Sumber Daya</p>
                    <nav class="footer-links" aria-label="Resources">
                        <a href="{{ route('blog.index') }}">Blog</a>
                        <a href="{{ route('faq') }}">FAQ</a>
                        <a href="{{ route('terms') }}">Syarat &amp; Ketentuan</a>
                        <a href="{{ route('privacy') }}">Kebijakan Privasi</a>
                        <a href="javascript:void(0)" onclick="openCookieConsentSettings()">Pengaturan Cookie</a>
                    </nav>
                </div>

                {{-- Kontak + Newsletter --}}
                <div>
                    <p class="footer-col-title">Kontak</p>
                    <div class="footer-links" style="margin-bottom: 24px;">
                        <span style="font-size:13px; color:var(--text-muted);">
                            {!! nl2br(e($contactAddress)) !!}
                        </span>
                        <a href="https://wa.me/{{ $waCleanNumber }}" target="_blank"
                            style="font-size:13px;">{{ $waNumber }}</a>
                        <a href="mailto:{{ $emailSupport }}" style="font-size:13px;">{{ $emailSupport }}</a>
                    </div>
                    <p class="footer-col-title">Newsletter</p>
                    <form class="footer-newsletter" action="{{ route('newsletter.subscribe') }}" method="POST">
                        @csrf
                        <input type="email" name="email" placeholder="Email kamu..." required
                            aria-label="Email untuk newsletter">
                        <button type="submit">Berlangganan →</button>
                    </form>
                </div>
            </div>

            {{-- Bottom Bar --}}
            <div class="footer-bottom">
                <p class="footer-copy">© {{ date('Y') }} {{ $siteName }}. All rights reserved. Made with ❤️
                    in Indonesia.</p>
                <div class="footer-badges">
                    <a href="https://www.dmca.com/compliance/cooca.id"
                        title="DMCA Compliance information for cooca.id"><img
                            src="https://images.dmca.com/Badges/dmca-badge-w100-2x1-03.png?ID=8a1fbcb5-5cdf-401c-8d19-df395c18212e"
                            alt="DMCA compliant image" width="100" height="25" loading="lazy" /></a>
                </div>
            </div>
        </div>
    </footer>

    {{-- ── SCRIPTS ──────────────────────────────────────── --}}
    <script>
        (function() {
            'use strict';

            // Theme Management
            const html = document.documentElement;
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');

            const savedTheme = localStorage.getItem('cooca-theme') || 'dark';
            html.setAttribute('data-theme', savedTheme);
            themeIcon.textContent = savedTheme === 'dark' ? '☀️' : '🌙';

            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    const current = html.getAttribute('data-theme');
                    const next = current === 'dark' ? 'light' : 'dark';
                    html.setAttribute('data-theme', next);
                    localStorage.setItem('cooca-theme', next);
                    themeIcon.textContent = next === 'dark' ? '☀️' : '🌙';
                });
            }

            // Navbar Scroll Effect
            const navbar = document.getElementById('lpNavbar');
            if (navbar) {
                let ticking = false;
                window.addEventListener('scroll', function() {
                    if (!ticking) {
                        requestAnimationFrame(function() {
                            if (window.scrollY > 60) {
                                navbar.classList.add('scrolled');
                            } else {
                                navbar.classList.remove('scrolled');
                            }
                            ticking = false;
                        });
                        ticking = true;
                    }
                });
            }

            // Mobile Menu Toggle
            const mobileToggle = document.getElementById('mobileNavToggle');
            const mobileMenu = document.getElementById('mobileMenu');
            if (mobileToggle && mobileMenu) {
                mobileToggle.addEventListener('click', function() {
                    const isOpen = mobileMenu.classList.toggle('open');
                    mobileToggle.setAttribute('aria-expanded', isOpen);
                    mobileToggle.textContent = isOpen ? '✕' : '☰';
                });
                // Close on outside click
                document.addEventListener('click', function(e) {
                    if (!navbar.contains(e.target) && !mobileMenu.contains(e.target)) {
                        mobileMenu.classList.remove('open');
                        mobileToggle.setAttribute('aria-expanded', 'false');
                        mobileToggle.textContent = '☰';
                    }
                });
            }

            // Scroll Reveal
            const revealElements = document.querySelectorAll('.reveal');
            if ('IntersectionObserver' in window && revealElements.length) {
                const observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('visible');
                            observer.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.1,
                    rootMargin: '0px 0px -40px 0px'
                });
                revealElements.forEach(function(el) {
                    observer.observe(el);
                });
            } else {
                revealElements.forEach(function(el) {
                    el.classList.add('visible');
                });
            }

            // Number Counter Animation
            function animateCounter(el) {
                const target = parseInt(el.getAttribute('data-target') || el.textContent);
                const suffix = el.getAttribute('data-suffix') || '';
                const prefix = el.getAttribute('data-prefix') || '';
                const duration = 2000;
                const step = target / (duration / 16);
                let current = 0;
                const timer = setInterval(function() {
                    current = Math.min(current + step, target);
                    el.textContent = prefix + Math.floor(current).toLocaleString('id-ID') + suffix;
                    if (current >= target) clearInterval(timer);
                }, 16);
            }

            const counters = document.querySelectorAll('.counter[data-target]');
            if ('IntersectionObserver' in window && counters.length) {
                const counterObserver = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            animateCounter(entry.target);
                            counterObserver.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.5
                });
                counters.forEach(function(el) {
                    counterObserver.observe(el);
                });
            }

            // FAQ Accordion
            document.querySelectorAll('.faq-question').forEach(function(q) {
                q.addEventListener('click', function() {
                    const item = q.closest('.faq-item');
                    const isOpen = item.classList.contains('open');
                    // Close all
                    document.querySelectorAll('.faq-item').forEach(function(i) {
                        i.classList.remove('open');
                    });
                    // Open clicked
                    if (!isOpen) item.classList.add('open');
                });
            });

            // Preview Tabs
            document.querySelectorAll('.preview-tab').forEach(function(tab) {
                tab.addEventListener('click', function() {
                    const target = tab.getAttribute('data-target');
                    // Tabs
                    document.querySelectorAll('.preview-tab').forEach(function(t) {
                        t.classList.remove('active');
                    });
                    tab.classList.add('active');
                    // Screens
                    document.querySelectorAll('.preview-screen').forEach(function(s) {
                        s.classList.remove('active');
                    });
                    const screen = document.getElementById(target);
                    if (screen) screen.classList.add('active');
                });
            });

        })();
    </script>

    {{-- Mobile Bottom Nav --}}
    <div class="lp-mobile-bottom-nav">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
            <i class="fa-solid fa-house"></i>
            <span>Beranda</span>
        </a>
        <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">
            <i class="fa-solid fa-box-open"></i>
            <span>Produk</span>
        </a>
        <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">
            <i class="fa-solid fa-building"></i>
            <span>Tentang</span>
        </a>
        <a href="{{ route('blog.index') }}" class="{{ request()->routeIs('blog.*') ? 'active' : '' }}">
            <i class="fa-solid fa-newspaper"></i>
            <span>Blog</span>
        </a>
        @if($isAdmin)
        <a href="{{ route('admin.dashboard') }}">
            <i class="fa-solid fa-gauge"></i>
            <span>Admin</span>
        </a>
        @elseif($isCustomer)
        <a href="{{ route('customer.dashboard') }}">
            <i class="fa-solid fa-chart-pie"></i>
            <span>Bisnis</span>
        </a>
        @elseif($isAffiliator)
        <a href="{{ route('affiliator.dashboard') }}">
            <i class="fa-solid fa-handshake"></i>
            <span>Partner</span>
        </a>
        @else
        <a href="{{ route('customer.login') }}"
            class="{{ request()->routeIs('customer.login') ? 'active' : '' }}">
            <i class="fa-regular fa-user"></i>
            <span>Login</span>
        </a>
        @endif
    </div>

    {{-- ── FLOATING COOKIE CONSENT BANNER ────────────────────────── --}}
    <div id="coocaCookieBanner"
        style="position: fixed; bottom: 20px; left: 20px; z-index: 99998; max-width: 420px; width: calc(100vw - 40px); background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 18px; box-shadow: var(--shadow-xl); opacity: 0; transform: translateY(20px); transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); display: none;">
        <div style="display: flex; align-items: flex-start; gap: 12px; margin-bottom: 12px;">
            <div
                style="width: 36px; height: 36px; border-radius: 10px; background: rgba(79,70,229,0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
                🍪
            </div>
            <div>
                <h4 style="font-size: 14px; font-weight: 700; color: var(--text); margin-bottom: 4px;">Pemberitahuan
                    Cookie &amp; Privasi</h4>
                <p style="font-size: 12px; color: var(--text-muted); line-height: 1.5; margin: 0;">
                    Kami menggunakan cookie esensial untuk keamanan sesi dan analitik anonim guna meningkatkan
                    kenyamanan Anda di COOCA.ID. Pelajari selengkapnya di <a href="{{ route('privacy') }}"
                        style="color: var(--primary); font-weight: 600; text-decoration: none;">Kebijakan Privasi</a>.
                </p>
            </div>
        </div>
        <div style="display: flex; align-items: center; justify-content: flex-end; gap: 8px; flex-wrap: wrap;">
            <button type="button" onclick="acceptCookieConsent('essential')"
                style="padding: 8px 14px; background: transparent; border: 1px solid var(--border); border-radius: 8px; color: var(--text); font-size: 12px; font-weight: 600; cursor: pointer; transition: background .2s;">
                Hanya Esensial
            </button>
            <button type="button" onclick="acceptCookieConsent('all')" class="btn-primary-glow"
                style="padding: 8px 16px; font-size: 12px; font-weight: 700; border-radius: 8px;">
                Terima Semua
            </button>
        </div>
    </div>

    <script>
        (function() {
            var consent = localStorage.getItem('cooca_cookie_consent');
            if (!consent) {
                var banner = document.getElementById('coocaCookieBanner');
                if (banner) {
                    banner.style.display = 'block';
                    setTimeout(function() {
                        banner.style.opacity = '1';
                        banner.style.transform = 'translateY(0)';
                    }, 300);
                }
            }
        })();

        function acceptCookieConsent(type) {
            localStorage.setItem('cooca_cookie_consent', type);
            if (type === 'all') {
                loadAnalyticsIfConsented();
            }
            var banner = document.getElementById('coocaCookieBanner');
            if (banner) {
                banner.style.opacity = '0';
                banner.style.transform = 'translateY(20px)';
                setTimeout(function() {
                    banner.style.display = 'none';
                }, 400);
            }
        }

        function openCookieConsentSettings() {
            var banner = document.getElementById('coocaCookieBanner');
            if (banner) {
                banner.style.display = 'block';
                setTimeout(function() {
                    banner.style.opacity = '1';
                    banner.style.transform = 'translateY(0)';
                }, 50);
            }
        }
    </script>

    @stack('scripts')
    @yield('scripts')
</body>

</html>
