@extends('layouts.guest')
@push('styles')
<style>

        /* ============================================================
           COOCA UNIFIED DESIGN SYSTEM â€” Contact Page
           Shared variables, components, and patterns across all pages
           ============================================================ */
        :root {
            /* Dark Theme (default) */
            --bg: #020617;
            --card: #0F172A;
            --card-alt: #1E293B;
            --card-hover: #1A2744;
            --text: #F8FAFC;
            --text-muted: #94A3B8;
            --primary: #2563EB;
            --secondary: #1E40AF;
            --accent: #38BDF8;
            --accent-dark: #0EA5E9;
            --success: #10B981;
            --success-soft: rgba(16, 185, 129, 0.08);
            --success-border: rgba(16, 185, 129, 0.2);
            --border: rgba(56, 189, 248, 0.12);
            --border-strong: rgba(56, 189, 248, 0.22);
            --shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
            --shadow-lg: 0 16px 48px rgba(0, 0, 0, 0.6);
            --glass: rgba(15, 23, 42, 0.65);
            --glass-border: rgba(56, 189, 248, 0.14);
            --radius: 16px;
            --radius-sm: 10px;
            --radius-xs: 8px;
            --radius-pill: 50px;
            --transition: 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-fast: 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            --font: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            --hero-gradient: linear-gradient(160deg, #020617 0%, #0F172A 40%, #1E3A5F 70%, #020617 100%);
            --hero-overlay: rgba(2, 6, 23, 0.25);
            --hero-grid-color: rgba(56, 189, 248, 0.03);
            --hero-orb-opacity: 0.12;
            --input-bg: #1E293B;
            --input-focus-shadow: rgba(56, 189, 248, 0.12);
            --badge-bg: rgba(56, 189, 248, 0.1);
            --badge-border: rgba(56, 189, 248, 0.2);
            --section-label-bg: rgba(37, 99, 235, 0.1);
            --section-label-border: rgba(37, 99, 235, 0.2);
            --dropdown-bg: #0F172A;
            --dropdown-hover: #1E293B;
            --offcanvas-bg: rgba(15, 23, 42, 0.65);
            --btn-outline-border: rgba(56, 189, 248, 0.25);
            --select-arrow: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2394A3B8' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
        }

        /* Light Theme */
        [data-theme="light"] {
            --bg: #F8FAFC;
            --card: #FFFFFF;
            --card-alt: #F1F5F9;
            --card-hover: #E8EEF4;
            --text: #0F172A;
            --text-muted: #475569;
            --border: rgba(37, 99, 235, 0.14);
            --border-strong: rgba(37, 99, 235, 0.25);
            --shadow: 0 8px 32px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 16px 48px rgba(0, 0, 0, 0.08);
            --glass: rgba(255, 255, 255, 0.72);
            --glass-border: rgba(37, 99, 235, 0.1);
            --hero-gradient: linear-gradient(160deg, #F0F4FF 0%, #E0E8FF 40%, #C7D8FF 70%, #F0F4FF 100%);
            --hero-overlay: rgba(255, 255, 255, 0.3);
            --hero-grid-color: rgba(37, 99, 235, 0.05);
            --hero-orb-opacity: 0.08;
            --input-bg: #F1F5F9;
            --input-focus-shadow: rgba(37, 99, 235, 0.18);
            --badge-bg: rgba(14, 165, 233, 0.1);
            --badge-border: rgba(14, 165, 233, 0.22);
            --section-label-bg: rgba(37, 99, 235, 0.08);
            --section-label-border: rgba(37, 99, 235, 0.18);
            --dropdown-bg: #FFFFFF;
            --dropdown-hover: #F1F5F9;
            --offcanvas-bg: rgba(255, 255, 255, 0.72);
            --btn-outline-border: rgba(37, 99, 235, 0.28);
            --select-arrow: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23475569' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
        }

        /* Reset & Base */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        html {
            scroll-behavior: smooth;
            overflow-x: hidden;
        }
        body {
            font-family: var(--font);
            background: var(--bg);
            color: var(--text);
            line-height: 1.7;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            transition: background var(--transition), color var(--transition);
        }
        p {
            color: var(--text-muted);
        }
        h1,
        h2,
        h3,
        h4,
        h5 {
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: -0.02em;
            color: var(--text);
        }
        a {
            color: var(--accent);
            text-decoration: none;
            transition: color var(--transition-fast);
        }
        a:hover {
            color: var(--accent-dark);
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
        }
        ::-webkit-scrollbar-track {
            background: var(--bg);
        }
        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 3px;
        }

        /* ============================================================
           NAVBAR â€” Standardized across all pages
           ============================================================ */
        .navbar-cooca {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1050;
            padding: 16px 0;
            transition: all var(--transition);
            background: transparent;
        }
        .navbar-cooca.scrolled {
            padding: 10px 0;
            background: var(--glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
            box-shadow: var(--shadow);
        }
        .navbar-brand-cooca {
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--text) !important;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .navbar-brand-cooca:hover {
            color: var(--text) !important;
        }
        .logo-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.1rem;
            font-weight: 800;
            flex-shrink: 0;
        }
        .nav-link-cooca {
            color: var(--text-muted) !important;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 8px 16px !important;
            transition: color var(--transition-fast);
            text-decoration: none;
            white-space: nowrap;
        }
        .nav-link-cooca:hover,
        .nav-link-cooca.active {
            color: var(--accent) !important;
        }
        .nav-link-cooca.active {
            font-weight: 600;
        }

        /* Theme Toggle */
        .theme-toggle {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: var(--card);
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all var(--transition-fast);
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        .theme-toggle:hover {
            border-color: var(--accent);
            color: var(--accent);
            transform: scale(1.05);
        }

        /* Login Dropdown */
        .dropdown-menu-c {
            background: var(--dropdown-bg);
            border: 1px solid var(--border-strong);
            border-radius: 12px;
            padding: 8px;
            box-shadow: var(--shadow-lg);
            min-width: 180px;
            animation: dropdownFadeIn 0.2s ease-out;
        }
        @keyframes dropdownFadeIn {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .dropdown-menu-c .dropdown-item {
            color: var(--text);
            border-radius: 8px;
            padding: 10px 16px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all var(--transition-fast);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .dropdown-menu-c .dropdown-item:hover {
            background: var(--dropdown-hover);
            color: var(--accent);
        }
        .dropdown-menu-c .dropdown-divider {
            border-color: var(--border);
            margin: 6px 0;
        }

        /* ============================================================
           BUTTONS â€” Standardized
           ============================================================ */
        .btn-cooca {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            border: none;
            cursor: pointer;
            transition: all var(--transition);
            text-decoration: none;
            white-space: nowrap;
            font-family: var(--font);
            line-height: 1.2;
        }
        .btn-primary-c {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff !important;
            box-shadow: 0 4px 20px rgba(37, 99, 235, 0.3);
            border: none;
        }
        .btn-primary-c:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(37, 99, 235, 0.45);
            color: #fff !important;
        }
        .btn-outline-c {
            background: transparent;
            color: var(--text);
            border: 1px solid var(--btn-outline-border);
        }
        .btn-outline-c:hover {
            border-color: var(--accent);
            color: var(--accent);
            transform: translateY(-2px);
        }
        .btn-sm-c {
            padding: 10px 22px;
            font-size: 0.85rem;
            border-radius: 10px;
        }
        .btn-cooca:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* Dropdown toggle button */
        .btn-cooca.dropdown-toggle::after {
            margin-left: 2px;
            vertical-align: middle;
            border-top: 5px solid;
            border-right: 5px solid transparent;
            border-left: 5px solid transparent;
            transition: transform var(--transition-fast);
        }
        .btn-cooca.dropdown-toggle[aria-expanded="true"]::after {
            transform: rotate(180deg);
        }

        /* ============================================================
           TYPOGRAPHY UTILITIES
           ============================================================ */
        .text-gradient {
            background: linear-gradient(135deg, var(--accent), var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 0 1px rgba(0,0,0,0.05); /* subtle definition */
        }
        [data-theme="light"] .text-gradient {
            text-shadow: none;
            font-weight: 800;
        }
        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            background: var(--badge-bg);
            border: 1px solid var(--badge-border);
            color: var(--accent);
            text-transform: uppercase;
        }
        .section-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            background: var(--section-label-bg);
            border: 1px solid var(--section-label-border);
            color: var(--primary);
            text-transform: uppercase;
            margin-bottom: 16px;
        }

        /* ============================================================
           PAGE HERO â€” Enhanced contrast for light & dark
           ============================================================ */
        .page-hero {
            padding: 160px 0 80px;
            position: relative;
            overflow: hidden;
            background: var(--hero-gradient);
            transition: background var(--transition);
            isolation: isolate;
        }
        /* Contrast overlay â€” ensures text always pops */
        .page-hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background: var(--hero-overlay);
            z-index: 1;
            pointer-events: none;
            transition: background var(--transition);
        }
        .grid-bg {
            position: absolute;
            inset: 0;
            z-index: 0;
            background-image:
                linear-gradient(var(--hero-grid-color) 1px, transparent 1px),
                linear-gradient(90deg, var(--hero-grid-color) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none;
            transition: background-image var(--transition);
        }
        .page-hero-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: var(--hero-orb-opacity);
            pointer-events: none;
            transition: opacity var(--transition);
            z-index: 0;
        }
        /* Hero content sits above overlay */
        .page-hero .container {
            position: relative;
            z-index: 2;
        }

        /* ============================================================
           SECTIONS
           ============================================================ */
        .sec {
            padding: 100px 0;
        }
        .sec-alt {
            background: var(--card-alt);
            transition: background var(--transition);
        }

        /* ============================================================
           FORM COMPONENTS â€” Standardized
           ============================================================ */
        .form-control-c,
        .form-select-c {
            width: 100%;
            padding: 14px 18px;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: var(--input-bg);
            color: var(--text);
            font-family: var(--font);
            font-size: 0.95rem;
            transition: all var(--transition-fast);
            outline: none;
            line-height: 1.5;
        }
        .form-control-c:focus,
        .form-select-c:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--input-focus-shadow);
            background: var(--card);
        }
        .form-control-c::placeholder {
            color: var(--text-muted);
            opacity: 0.7;
        }
        .form-control-c:-webkit-autofill,
        .form-control-c:-webkit-autofill:hover,
        .form-control-c:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 30px var(--card) inset !important;
            -webkit-text-fill-color: var(--text) !important;
            caret-color: var(--text);
            border: 1px solid var(--border);
        }
        .form-label-c {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text);
        }
        .form-group {
            margin-bottom: 20px;
        }
        textarea.form-control-c {
            resize: vertical;
            min-height: 140px;
        }
        select.form-select-c {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: var(--select-arrow);
            background-repeat: no-repeat;
            background-position: right 14px center;
            background-size: 16px 12px;
            padding-right: 42px;
            cursor: pointer;
        }
        select.form-select-c option {
            background: var(--card);
            color: var(--text);
            padding: 10px;
        }
        select.form-select-c:invalid {
            color: var(--text-muted);
        }

        /* ============================================================
           CONTACT INFO CARDS
           ============================================================ */
        .ci-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 28px;
            transition: all var(--transition);
            display: flex;
            align-items: flex-start;
            gap: 16px;
        }
        .ci-card:hover {
            border-color: var(--border-strong);
            transform: translateX(4px);
            box-shadow: var(--shadow);
        }
        .ci-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.15), rgba(56, 189, 248, 0.15));
            border: 1px solid var(--badge-border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: var(--accent);
            flex-shrink: 0;
        }
        .ci-title {
            font-weight: 700;
            font-size: 0.95rem;
            margin-bottom: 4px;
            color: var(--text);
        }
        .ci-detail {
            font-size: 0.88rem;
            color: var(--text-muted);
            line-height: 1.6;
        }

        /* ============================================================
           CHANNEL CARDS
           ============================================================ */
        .channel-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 28px 24px;
            text-align: center;
            transition: all var(--transition);
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .channel-card:hover {
            border-color: var(--border-strong);
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(56, 189, 248, 0.06);
        }
        .channel-icon {
            font-size: 2.2rem;
            margin-bottom: 14px;
            color: var(--accent);
            transition: transform var(--transition-fast);
        }
        .channel-card:hover .channel-icon {
            transform: scale(1.1);
        }
        .channel-title {
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 8px;
            color: var(--text);
        }

        /* ============================================================
           FORM SUCCESS STATE
           ============================================================ */
        .form-success {
            display: none;
            background: var(--success-soft);
            border: 1px solid var(--success-border);
            border-radius: 16px;
            padding: 28px 24px;
            text-align: center;
            margin-top: 16px;
            animation: fadeInUp 0.5s ease-out;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ============================================================
           FOOTER â€” Standardized across all pages
           ============================================================ */
        .footer {
            background: var(--card);
            border-top: 1px solid var(--border);
            padding: 60px 0 30px;
            transition: background var(--transition);
        }
        .footer-brand {
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
            color: var(--text);
        }
        .footer-desc {
            font-size: 0.88rem;
            color: var(--text-muted);
            max-width: 280px;
            line-height: 1.7;
        }
        .footer-title {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--text-muted);
            margin-bottom: 16px;
        }
        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .footer-links li {
            margin-bottom: 10px;
        }
        .footer-links a {
            color: var(--text-muted);
            font-size: 0.88rem;
            transition: color var(--transition-fast);
            text-decoration: none;
        }
        .footer-links a:hover {
            color: var(--accent);
        }
        .footer-socials {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .footer-socials a {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--card-alt);
            color: var(--text-muted);
            border: 1px solid var(--border);
            transition: all var(--transition-fast);
            text-decoration: none;
        }
        .footer-socials a:hover {
            color: var(--accent);
            border-color: var(--accent);
            transform: translateY(-2px);
        }
        .footer-bottom {
            margin-top: 48px;
            padding-top: 24px;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }
        .footer-bottom p {
            font-size: 0.82rem;
            color: var(--text-muted);
            margin: 0;
        }

        /* ============================================================
           REVEAL ANIMATIONS â€” Shared across all pages
           ============================================================ */
        .reveal {
            opacity: 0;
            transform: translateY(32px);
            transition: opacity 0.7s cubic-bezier(0.4, 0, 0.2, 1),
                transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .reveal.revealed {
            opacity: 1;
            transform: translateY(0);
        }
        .rv1 {
            transition-delay: 0.1s;
        }
        .rv2 {
            transition-delay: 0.2s;
        }
        .rv3 {
            transition-delay: 0.3s;
        }
        .rv4 {
            transition-delay: 0.4s;
        }

        /* ============================================================
           OFFCANVAS MOBILE MENU
           ============================================================ */
        .offcanvas-cooca {
            background: var(--offcanvas-bg) !important;
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border-left: 1px solid var(--glass-border);
        }
        .offcanvas-cooca .btn-close {
            filter: invert(1);
            opacity: 0.8;
        }
        [data-theme="light"] .offcanvas-cooca .btn-close {
            filter: none;
            opacity: 0.6;
        }
        .offcanvas-cooca .nav-link-cooca {
            display: block;
            padding: 14px 0 !important;
            font-size: 1rem;
            border-bottom: 1px solid var(--border);
        }
        .offcanvas-cooca .offcanvas-header {
            border-bottom: 1px solid var(--border);
        }
        .offcanvas-cooca .offcanvas-title {
            font-weight: 800;
            color: var(--text);
        }

        /* ============================================================
           RESPONSIVE
           ============================================================ */
        @media(max-width: 991px) {
            .page-hero {
                padding: 130px 0 60px;
            }
            .sec {
                padding: 60px 0;
            }
            .ci-card {
                padding: 20px;
                gap: 12px;
            }
            .ci-icon {
                width: 40px;
                height: 40px;
                font-size: 1.1rem;
                border-radius: 10px;
            }
            .channel-card {
                padding: 20px 16px;
            }
            .footer-bottom {
                flex-direction: column;
                text-align: center;
                gap: 8px;
            }
        }
        @media(max-width: 767px) {
            .page-hero {
                padding: 110px 0 50px;
            }
            .sec {
                padding: 50px 0;
            }
            .btn-cooca {
                padding: 12px 22px;
                font-size: 0.9rem;
            }
            .btn-sm-c {
                padding: 9px 18px;
                font-size: 0.82rem;
            }
            .ci-card {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            .channel-card {
                padding: 18px 14px;
            }
            .channel-icon {
                font-size: 1.8rem;
                margin-bottom: 10px;
            }
        }
        @media(max-width: 414px) {
            .page-hero {
                padding: 100px 0 40px;
            }
            .navbar-brand-cooca {
                font-size: 1.3rem;
                gap: 8px;
            }
            .logo-icon {
                width: 30px;
                height: 30px;
                font-size: 0.9rem;
                border-radius: 8px;
            }
            .btn-cooca {
                padding: 10px 18px;
                font-size: 0.82rem;
                border-radius: 10px;
            }
            .btn-sm-c {
                padding: 8px 14px;
                font-size: 0.78rem;
                border-radius: 8px;
            }
            .form-control-c,
            .form-select-c {
                padding: 12px 14px;
                font-size: 0.9rem;
                border-radius: 10px;
            }
            textarea.form-control-c {
                min-height: 110px;
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
            <i class="bi bi-chat-dots-fill"></i> {{ __(setting('contact.badge', 'Get in Touch')) }}
        </div>
        <h1 class="hero-title reveal reveal-delay-1">
            {!! __(setting('contact.title', 'We Respond Fast <span class="text-gradient">Because Business Can\'t Wait.</span>')) !!}
        </h1>
        <p class="hero-subtitle reveal reveal-delay-2" style="font-size:1.15rem;max-width:620px;margin:20px auto 0;">
            {{ __(setting('contact.subtitle', 'Sales questions, technical support, partnership inquiries, or just not sure where to start — our team is ready.')) }}
        </p>
    </div>
</section>

<!-- ============================================================
CONTACT CHANNELS
============================================================ -->
<section class="sec">
    <div class="container">
        <div class="row g-4 justify-content-center mb-5">
            <div class="col-lg-3 col-md-6 reveal">
                <div class="channel-card">
                    <div class="channel-icon"><i class="bi bi-whatsapp" style="color:#25D366;"></i></div>
                    <div class="channel-title">WhatsApp</div>
                    <p style="font-size:.85rem;margin-bottom:16px;flex-grow:1;">{{ __('Fastest response. Our sales team is active during business hours.') }}</p>
                    <a href="{{ setting('contact.whatsapp_link', 'https://wa.me/6281234567890') }}" target="_blank" rel="noopener" class="btn-cooca btn-primary-c btn-sm-c" style="justify-content:center;width:100%;">{{ __('Open WhatsApp') }}</a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 reveal rv1">
                <div class="channel-card">
                    <div class="channel-icon"><i class="bi bi-envelope-fill"></i></div>
                    <div class="channel-title">Email</div>
                    <p style="font-size:.85rem;margin-bottom:16px;flex-grow:1;">{{ __('For detailed inquiries. We respond within 8 hours on business days.') }}</p>
                    <a href="mailto:{{ setting('contact.email', 'support@cooca.io') }}" class="btn-cooca btn-outline-c btn-sm-c" style="justify-content:center;width:100%;">{{ setting('contact.email', 'support@cooca.io') }}</a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 reveal rv2">
                <div class="channel-card">
                    <div class="channel-icon"><i class="bi bi-calendar-check"></i></div>
                    <div class="channel-title">{{ __('Book a Demo') }}</div>
                    <p style="font-size:.85rem;margin-bottom:16px;flex-grow:1;">{{ __('Schedule a 30-minute live walkthrough of your industry solution.') }}</p>
                    <a href="{{ route('customer.register') }}" class="btn-cooca btn-outline-c btn-sm-c" style="justify-content:center;width:100%;">{{ __('Book Demo') }}</a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 reveal rv3">
                <div class="channel-card">
                    <div class="channel-icon"><i class="bi bi-headset"></i></div>
                    <div class="channel-title">{{ __('Support Center') }}</div>
                    <p style="font-size:.85rem;margin-bottom:16px;flex-grow:1;">{{ __('Already a customer? Access our knowledge base and ticket system.') }}</p>
                    <a href="javascript:void(0)" class="btn-cooca btn-outline-c btn-sm-c" style="justify-content:center;width:100%;">{{ __('Go to Support') }}</a>
                </div>
            </div>
        </div>

        <div class="row g-5 align-items-start">
            <div class="col-lg-7 reveal">
                <div style="background:var(--card);border:1px solid var(--border);border-radius:24px;padding:40px;transition:background var(--transition);">
                    <h3 style="font-size:1.4rem;margin-bottom:8px;">{{ __('Send Us a Message') }}</h3>
                    <p style="font-size:.9rem;margin-bottom:28px;">{{ __('Fill out the form and we\'ll route it to the right person immediately.') }}</p>
                    <form id="contactForm" novalidate>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label-c">{{ __('Full Name') }} *</label>
                                    <input type="text" class="form-control-c" placeholder="Ahmad Kurniawan" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label-c">{{ __('Email Address') }} *</label>
                                    <input type="email" class="form-control-c" placeholder="ahmad@company.com" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label-c">{{ __('Phone / WhatsApp') }}</label>
                                    <input type="tel" class="form-control-c" placeholder="+62 812 3456 7890">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label-c">{{ __('Company Name') }}</label>
                                    <input type="text" class="form-control-c" placeholder="RetailMax Indonesia">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label-c">{{ __('Industry') }} *</label>
                                    <select class="form-control-c form-select-c" required>
                                        <option value="" disabled selected>{{ __('Select your industry') }}</option>
                                        <option>{{ __('Retail') }}</option>
                                        <option>{{ __('Restaurant & F&B') }}</option>
                                        <option>{{ __('Hotel & Hospitality') }}</option>
                                        <option>{{ __('Clinic & Healthcare') }}</option>
                                        <option>{{ __('Education') }}</option>
                                        <option>{{ __('Salon & Beauty') }}</option>
                                        <option>{{ __('Laundry') }}</option>
                                        <option>{{ __('Workshop & Automotive') }}</option>
                                        <option>{{ __('Rental') }}</option>
                                        <option>{{ __('Other') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label-c">{{ __('How can we help?') }} *</label>
                                    <select class="form-control-c form-select-c" required>
                                        <option value="" disabled selected>{{ __('Select topic') }}</option>
                                        <option>{{ __('Sales & Pricing') }}</option>
                                        <option>{{ __('Book a Demo') }}</option>
                                        <option>{{ __('Technical Support') }}</option>
                                        <option>{{ __('Migration Assistance') }}</option>
                                        <option>{{ __('Partnership / Affiliate') }}</option>
                                        <option>{{ __('Enterprise / Custom') }}</option>
                                        <option>{{ __('Other') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label-c">{{ __('Your Message') }} *</label>
                                    <textarea class="form-control-c" placeholder="{{ __('Tell us about your business, current challenges, and what you\'re looking for...') }}" required></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn-cooca btn-primary-c" style="width:100%;justify-content:center;padding:16px;">
                                    {{ __('Send Message') }} <i class="bi bi-send-fill"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="form-success" id="formSuccess">
                        <i class="bi bi-check-circle-fill" style="font-size:2.5rem;color:#10B981;margin-bottom:12px;display:block;"></i>
                        <h5 style="font-weight:700;margin-bottom:6px;color:var(--text);">{{ __('Message Sent!') }}</h5>
                        <p style="margin:0;font-size:.9rem;">{{ __('We\'ve received your inquiry and will respond within 8 business hours.') }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 reveal rv2">
                <div class="section-label mb-4"><i class="bi bi-geo-alt-fill"></i> {{ __('Find Us') }}</div>
                <div class="d-flex flex-column gap-3 mb-5">
                    <div class="ci-card">
                        <div class="ci-icon"><i class="bi bi-geo-alt-fill"></i></div>
                        <div>
                            <div class="ci-title">{{ __('Headquarters') }}</div>
                            <div class="ci-detail">{!! __(setting('contact.address', 'Jl. Jend. Sudirman Kav. 52–53<br>Jakarta Selatan 12190, Indonesia')) !!}</div>
                        </div>
                    </div>
                    <div class="ci-card">
                        <div class="ci-icon"><i class="bi bi-envelope-fill"></i></div>
                        <div>
                            <div class="ci-title">{{ __('General Inquiries') }}</div>
                            <div class="ci-detail">{{ setting('contact.email', 'support@cooca.io') }}<br>sales@cooca.io</div>
                        </div>
                    </div>
                    <div class="ci-card">
                        <div class="ci-icon"><i class="bi bi-whatsapp"></i></div>
                        <div>
                            <div class="ci-title">{{ __('WhatsApp Sales') }}</div>
                            <div class="ci-detail">{{ setting('contact.whatsapp', '+62 812 3456 7890') }}<br>Mon–Fri · 08:00–18:00 WIB</div>
                        </div>
                    </div>
                    <div class="ci-card">
                        <div class="ci-icon"><i class="bi bi-clock-fill"></i></div>
                        <div>
                            <div class="ci-title">{{ __('Response Times') }}</div>
                            <div class="ci-detail">{{ __('WhatsApp: < 2 hours') }}<br>{{ __('Email: < 8 business hours') }}<br>{{ __('Enterprise SLA: custom') }}</div>
                        </div>
                    </div>
                </div>
                <div class="section-label mb-3"><i class="bi bi-share-fill"></i> {{ __('Follow Us') }}</div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ setting('social.twitter', '#') }}" class="btn-cooca btn-outline-c btn-sm-c"><i class="bi bi-twitter-x"></i> Twitter</a>
                    <a href="{{ setting('social.linkedin', '#') }}" class="btn-cooca btn-outline-c btn-sm-c"><i class="bi bi-linkedin"></i> LinkedIn</a>
                    <a href="{{ setting('social.instagram', '#') }}" class="btn-cooca btn-outline-c btn-sm-c"><i class="bi bi-instagram"></i> Instagram</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
FOOTER — Standardized across all pages
============================================================ -->
@endsection
@push('scripts')
<script>
  const contactForm = document.getElementById('contactForm');
  if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const btn = this.querySelector('button[type="submit"]');
      const originalText = btn.innerHTML;
      btn.innerHTML = '<i class="bi bi-check-circle"></i> Message Received!';
      btn.style.background = 'linear-gradient(135deg, #10B981, #059669)';
      setTimeout(function() {
        btn.innerHTML = originalText;
        btn.style.background = '';
        contactForm.reset();
      }, 3000);
    });
  }
</script>
@endpush
