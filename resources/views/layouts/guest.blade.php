<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="{{ session('theme', 'dark') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Meta --}}
    <title>@yield('title', setting('seo.title', config('app.name', 'COOCA'))) - @yield('subtitle', setting('branding.tagline', 'The Business System That Works Like an Asset'))</title>
    <meta name="description" content="@yield('meta_description', setting('seo.description', 'Stop losing revenue to fragmented systems. COOCA is the integrated business infrastructure that gives you lifetime license protection, modular ERP, and a system that scales with your ambition.'))">
    <meta name="keywords" content="@yield('meta_keywords', 'ERP, Business System, SaaS, COOCA, Enterprise Resource Planning')">
    <meta name="author" content="@yield('meta_author', setting('branding.name', config('app.name')))">
    <meta name="robots" content="@yield('meta_robots', 'index, follow')">

    {{-- Open Graph --}}
    <meta property="og:title" content="@yield('og_title', yield('title', setting('seo.title', config('app.name'))))">
    <meta property="og:description" content="@yield('og_description', yield('meta_description', setting('seo.description')))">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:image" content="@yield('og_image', asset(setting('seo.og_image', 'images/og-default.jpg')))">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:site_name" content="{{ setting('branding.name', config('app.name')) }}">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="@yield('twitter_card', 'summary_large_image')">
    <meta name="twitter:title" content="@yield('twitter_title', yield('title'))">
    <meta name="twitter:description" content="@yield('twitter_description', yield('meta_description'))">
    <meta name="twitter:image" content="@yield('twitter_image', yield('og_image'))">

    {{-- Canonical --}}
    <link rel="canonical" href="@yield('canonical', url()->current())">

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset(setting('branding.favicon', 'favicon.ico')) }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset(setting('branding.apple_touch_icon', 'images/apple-touch-icon.png')) }}">

    {{-- Preconnect --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    {{-- AOS Animation --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    {{-- Animation Libraries --}}
    @stack('animation-libs')

    {{-- Custom Styles --}}
    @include('partials._styles')
    @stack('styles')

    {{-- Additional Head --}}
    @yield('head')
</head>
<body class="antialiased" data-bs-spy="scroll" data-bs-target="#mainNav" data-bs-offset="100">
    {{-- Page Loader --}}
    @include('partials._loader')

    {{-- WhatsApp Float --}}
    @include('partials._whatsapp')

    {{-- Navigation --}}
    @include('partials._navbar')

    {{-- Mobile Menu --}}
    @include('partials._mobile-menu')

    {{-- Main Content --}}
    <main id="main-content" role="main">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('partials._footer')

    {{-- Scripts --}}
    @include('partials._scripts')
    @stack('scripts')
</body>
</html>
