@extends('layouts.guest')
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>

      /* ==================================================================
           UNIFIED DESIGN SYSTEM â€” COOCA
           Blok ini HARUS persis sama di setiap halaman.
           ================================================================== */
      :root {
        --bg: #020617;
        --card: #0f172a;
        --card-alt: #1e293b;
        --text: #f8fafc;
        --text-muted: #94a3b8;
        --primary: #2563eb;
        --secondary: #1e40af;
        --accent: #38bdf8;
        --success: #10b981;
        --border: rgba(56, 189, 248, 0.12);
        --shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
        --shadow-lg: 0 24px 64px rgba(0, 0, 0, 0.6);
        --glass: rgba(15, 23, 42, 0.65);
        --glass-border: rgba(56, 189, 248, 0.14);
        --radius: 16px;
        --radius-sm: 10px;
        --radius-lg: 24px;
        --transition: 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        --font: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        --hero-gradient: linear-gradient(160deg, #020617 0%, #0f172a 35%, #1e3a5f 65%, #020617 100%);
        --grid-color: rgba(56, 189, 248, 0.05);
        --badge-bg: rgba(56, 189, 248, 0.15);
        --badge-border: rgba(56, 189, 248, 0.3);
        --badge-color: var(--accent);
        --table-th-bg: rgba(15, 23, 42, 0.85);
        --table-th-color: var(--text);
        --table-td-hover: rgba(30, 41, 59, 0.98);
        --table-td-hover-bg: rgba(56, 189, 248, 0.05);
        --accordion-bg: var(--card);
        --accordion-border: var(--border);
        --accordion-chevron-filter: invert(1);
        --newsletter-gradient: linear-gradient(135deg, rgba(37, 99, 235, 0.1), rgba(56, 189, 248, 0.06));
        --guarantee-gradient: linear-gradient(135deg, rgba(37, 99, 235, 0.1), rgba(16, 185, 129, 0.1));
        --guarantee-border: rgba(16, 185, 129, 0.3);
        --affiliate-gradient: linear-gradient(135deg, rgba(16, 185, 129, 0.08), rgba(37, 99, 235, 0.06));
        --dropdown-hover-bg: rgba(56, 189, 248, 0.1);
        --btn-outline-hover-color: var(--accent);
        --btn-outline-hover-border: var(--accent);
        --hero-orb-opacity: 0.12;
      }
      [data-theme="light"] {
        --bg: #f8fafc;
        --card: #ffffff;
        --card-alt: #f1f5f9;
        --text: #0f172a;
        --text-muted: #475569;
        --primary: #2563eb;
        --secondary: #7c3aed;
        --accent: #0ea5e9;
        --border: rgba(37, 99, 235, 0.12);
        --shadow: 0 8px 32px rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 24px 64px rgba(0, 0, 0, 0.1);
        --glass: rgba(255, 255, 255, 0.7);
        --glass-border: rgba(37, 99, 235, 0.1);
        --hero-gradient: linear-gradient(160deg, #f8fafc 0%, #eff6ff 35%, #dbeafe 65%, #f8fafc 100%);
        --grid-color: rgba(37, 99, 235, 0.08);
        --badge-bg: rgba(37, 99, 235, 0.1);
        --badge-border: rgba(37, 99, 235, 0.3);
        --badge-color: var(--primary);
        --table-th-bg: rgba(241, 245, 249, 0.95);
        --table-th-color: var(--text);
        --table-td-hover: rgba(226, 232, 240, 0.95);
        --table-td-hover-bg: rgba(37, 99, 235, 0.05);
        --accordion-bg: var(--card);
        --accordion-border: var(--border);
        --accordion-chevron-filter: none;
        --newsletter-gradient: linear-gradient(135deg, rgba(37, 99, 235, 0.08), rgba(37, 99, 235, 0.03));
        --guarantee-gradient: linear-gradient(135deg, rgba(37, 99, 235, 0.06), rgba(16, 185, 129, 0.06));
        --guarantee-border: rgba(16, 185, 129, 0.4);
        --affiliate-gradient: linear-gradient(135deg, rgba(16, 185, 129, 0.06), rgba(37, 99, 235, 0.04));
        --dropdown-hover-bg: rgba(37, 99, 235, 0.1);
        --btn-outline-hover-color: var(--primary);
        --btn-outline-hover-border: var(--primary);
        --hero-orb-opacity: 0.18;
      }
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
        transition:
          background var(--transition),
          color var(--transition);
        overflow-x: hidden;
        -webkit-font-smoothing: antialiased;
      }
      img {
        max-width: 100%;
        height: auto;
      }
      a {
        color: var(--accent);
        text-decoration: none;
        transition: color var(--transition);
      }
      a:hover {
        color: var(--primary);
      }
      h1,
      h2,
      h3,
      h4,
      h5,
      h6 {
        font-weight: 700;
        line-height: 1.2;
        letter-spacing: -0.02em;
      }
      h1 {
        font-size: clamp(2.4rem, 5vw, 4.2rem);
      }
      h2 {
        font-size: clamp(1.8rem, 3.5vw, 3rem);
      }
      h3 {
        font-size: clamp(1.2rem, 2vw, 1.5rem);
      }
      p {
        color: var(--text-muted);
      }
      ::-webkit-scrollbar {
        width: 6px;
      }
      ::-webkit-scrollbar-track {
        background: var(--bg);
      }
      ::-webkit-scrollbar-thumb {
        background: var(--primary);
        border-radius: 3px;
      }

            /* Swiper Styling */
      .pricing-swiper {
          padding: 20px 10px 60px;
          overflow: hidden;
      }
      .swiper-slide {
          height: auto;
      }
      .swiper-pagination-bullet {
          background: var(--text-muted);
          width: 10px;
          height: 10px;
          transition: all var(--transition);
      }
      .swiper-pagination-bullet-active {
          background: var(--accent);
          width: 28px;
          border-radius: 5px;
      }
      .swiper-button-next, .swiper-button-prev {
          width: 48px;
          height: 48px;
          border-radius: 50%;
          background: var(--card);
          border: 1px solid var(--border);
          color: var(--accent);
          box-shadow: var(--shadow);
          transition: all var(--transition);
      }
      .swiper-button-next:hover, .swiper-button-prev:hover {
          background: var(--primary);
          color: #fff;
          border-color: var(--primary);
          transform: scale(1.1);
      }
      /* Category Tabs Styling */
      .category-tabs-wrapper {
          display: flex;
          justify-content: center;
          margin-bottom: 48px;
          padding: 0 16px;
      }
      .category-tabs-container {
          display: flex;
          gap: 8px;
          background: var(--card);
          border: 1px solid var(--border);
          padding: 8px;
          border-radius: 50px;
          box-shadow: var(--shadow);
          overflow-x: auto;
          max-width: 100%;
          scrollbar-width: none;
      }
      .category-tabs-container::-webkit-scrollbar {
          display: none;
      }
      .cat-tab-btn {
          padding: 12px 28px;
          border-radius: 50px;
          border: none;
          background: transparent;
          color: var(--text-muted);
          font-weight: 700;
          font-size: 0.95rem;
          white-space: nowrap;
          transition: all var(--transition);
          cursor: pointer;
      }
      .cat-tab-btn:hover {
          color: var(--text);
          background: rgba(56, 189, 248, 0.08);
      }
      .cat-tab-btn.active {
          background: linear-gradient(135deg, var(--primary), var(--accent));
          color: #fff;
          box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4);
      }
      /* Utility Classes */
      .section-padding {
        padding: 100px 0;
      }
      .glass {
        background: var(--glass);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius);
      }
      .text-gradient {
        background: linear-gradient(
          135deg,
          var(--accent),
          var(--primary),
          var(--secondary)
        );
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
      }
      .badge-glow {
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
        color: var(--badge-color);
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
        background: var(--badge-bg);
        border: 1px solid var(--badge-border);
        color: var(--badge-color);
        text-transform: uppercase;
        margin-bottom: 16px;
      }
      .section-title {
        margin-bottom: 16px;
      }
      .section-subtitle {
        font-size: 1.1rem;
        max-width: 600px;
        margin: 0 auto 48px;
      }
      .text-center {
        text-align: center;
      }

      /* Buttons */
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
        position: relative;
        overflow: hidden;
        text-decoration: none;
        white-space: nowrap;
      }
      .btn-cooca-primary {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: #fff;
        box-shadow: 0 4px 20px rgba(37, 99, 235, 0.3);
      }
      .btn-cooca-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(37, 99, 235, 0.45);
        color: #fff;
      }
      .btn-cooca-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
        box-shadow: 0 4px 20px rgba(16, 185, 129, 0.3);
      }
      .btn-cooca-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(16, 185, 129, 0.45);
        color: #fff;
      }
      .btn-cooca-outline {
        background: transparent;
        color: var(--text);
        border: 1px solid var(--border);
      }
      .btn-cooca-outline:hover {
        border-color: var(--btn-outline-hover-border);
        color: var(--btn-outline-hover-color);
        transform: translateY(-2px);
      }
      .btn-cooca-sm {
        padding: 10px 22px;
        font-size: 0.85rem;
        border-radius: 10px;
      }
      .btn-cooca .btn-ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: scale(0);
        animation: ripple 0.6s linear;
        pointer-events: none;
      }
      @keyframes ripple {
        to {
          transform: scale(4);
          opacity: 0;
        }
      }

      /* Cards */
      .card-3d {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 32px;
        transition: all var(--transition);
        position: relative;
        overflow: hidden;
        transform-style: preserve-3d;
        perspective: 1000px;
      }
      .card-3d::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(
          90deg,
          transparent,
          var(--accent),
          transparent
        );
        opacity: 0;
        transition: opacity var(--transition);
      }
      .card-3d:hover::before {
        opacity: 1;
      }
      .card-3d:hover {
        transform: translateY(-8px);
        border-color: rgba(56, 189, 248, 0.3);
        box-shadow:
          0 20px 60px rgba(56, 189, 248, 0.08),
          var(--shadow);
      }
      .card-3d .card-glow {
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(
          circle,
          rgba(56, 189, 248, 0.05) 0%,
          transparent 60%
        );
        pointer-events: none;
        opacity: 0;
        transition: opacity var(--transition);
      }
      .card-3d:hover .card-glow {
        opacity: 1;
      }

      /* Animations */
      @keyframes float {
        0%,
        100% {
          transform: translateY(0px);
        }
        50% {
          transform: translateY(-20px);
        }
      }
      @keyframes float-delay {
        0%,
        100% {
          transform: translateY(0px);
        }
        50% {
          transform: translateY(-15px);
        }
      }
      @keyframes float-slow {
        0%,
        100% {
          transform: translateY(0px);
        }
        50% {
          transform: translateY(-10px);
        }
      }
      @keyframes pulse-scale {
        0% {
          opacity: 1;
          transform: scale(1);
        }
        50% {
          opacity: 0.7;
          transform: scale(1.05);
        }
        100% {
          opacity: 1;
          transform: scale(1);
        }
      }
      @keyframes fade-in-scale {
        0% {
          opacity: 0;
          transform: scale(0.8);
        }
        100% {
          opacity: 1;
          transform: scale(1);
        }
      }
      .float-anim {
        animation: float 6s ease-in-out infinite;
      }
      .float-anim-delay {
        animation: float-delay 5s ease-in-out 1s infinite;
      }

      .reveal {
        opacity: 0;
        transform: translateY(40px);
        transition:
          opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1),
          transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
      }
      .reveal.revealed {
        opacity: 1;
        transform: translateY(0);
      }
      .reveal-delay-1 {
        transition-delay: 0.1s;
      }
      .reveal-delay-2 {
        transition-delay: 0.2s;
      }
      .reveal-delay-3 {
        transition-delay: 0.3s;
      }
      .reveal-delay-4 {
        transition-delay: 0.4s;
      }
      .reveal-delay-5 {
        transition-delay: 0.5s;
      }

      /* Navbar */
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
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
      }
      .navbar-brand-cooca {
        font-size: 1.6rem;
        font-weight: 800;
        letter-spacing: -0.03em;
        color: var(--text) !important;
        display: flex;
        align-items: center;
        gap: 10px;
      }
      .navbar-brand-cooca .logo-icon {
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
      }
      .nav-link-cooca {
        color: var(--text-muted) !important;
        font-weight: 500;
        font-size: 0.9rem;
        padding: 8px 16px !important;
        transition: color var(--transition);
        position: relative;
      }
      .nav-link-cooca:hover,
      .nav-link-cooca.active {
        color: var(--accent) !important;
      }
      .nav-link-cooca::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 0;
        height: 2px;
        background: var(--accent);
        transition: width var(--transition);
        border-radius: 1px;
      }
      .nav-link-cooca:hover::after,
      .nav-link-cooca.active::after {
        width: 60%;
      }
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
        transition: all var(--transition);
        font-size: 1.1rem;
      }
      .theme-toggle:hover {
        border-color: var(--accent);
        color: var(--accent);
        transform: rotate(20deg);
      }

      /* Login dropdown */
      .dropdown-cooca .dropdown-toggle {
        background: transparent;
        border: none;
        color: var(--text-muted);
        font-weight: 500;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 4px;
      }
      .dropdown-cooca .dropdown-menu {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        box-shadow: var(--shadow);
        padding: 8px 0;
      }
      .dropdown-cooca .dropdown-item {
        color: var(--text);
        padding: 10px 20px;
        transition: background 0.2s;
        font-size: 0.9rem;
      }
      .dropdown-cooca .dropdown-item:hover {
        background: rgba(56, 189, 248, 0.1);
        color: var(--accent);
      }

      /* Offcanvas mobile */
      .offcanvas-cooca {
        background: var(--glass) !important;
        backdrop-filter: blur(30px);
        -webkit-backdrop-filter: blur(30px);
        border-left: 1px solid var(--glass-border);
      }
      .offcanvas-cooca .offcanvas-header {
        border-bottom: 1px solid var(--border);
      }
      .offcanvas-cooca .offcanvas-title {
        font-weight: 800;
      }
      .offcanvas-cooca .btn-close {
        filter: invert(1);
      }
      [data-theme="light"] .offcanvas-cooca .btn-close {
        filter: none;
      }
      .offcanvas-cooca .nav-link-cooca {
        display: block;
        padding: 14px 0 !important;
        font-size: 1rem;
        border-bottom: 1px solid var(--border);
      }
      .offcanvas-cooca .nav-link-cooca::after {
        display: none;
      }
      .offcanvas-cooca .theme-toggle {
        width: 100%;
        height: auto;
        padding: 12px 16px;
        border-radius: 10px;
        justify-content: flex-start;
        gap: 10px;
        font-size: 0.9rem;
        font-weight: 500;
        background: var(--card-alt);
        border: 1px solid var(--border);
        color: var(--text);
      }
      .offcanvas-cooca .theme-toggle:hover {
        border-color: var(--accent);
        color: var(--accent);
        transform: none;
      }

      /* Footer */
      .footer {
        padding: 60px 0 30px;
        border-top: 1px solid var(--border);
        background: var(--card);
      }
      .footer-brand {
        font-size: 1.4rem;
        font-weight: 800;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
      }
      .footer-brand .logo-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 0.9rem;
        font-weight: 800;
      }
      .footer-desc {
        font-size: 0.88rem;
        color: var(--text-muted);
        margin-bottom: 20px;
        max-width: 300px;
      }
      .footer-title {
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 16px;
        color: var(--text);
      }
      .footer-links {
        list-style: none;
        padding: 0;
      }
      .footer-links li {
        margin-bottom: 10px;
      }
      .footer-links a {
        color: var(--text-muted);
        font-size: 0.88rem;
        transition: color var(--transition);
      }
      .footer-links a:hover {
        color: var(--accent);
      }
      .footer-bottom {
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
      }
      .footer-bottom p {
        font-size: 0.82rem;
        color: var(--text-muted);
        margin: 0;
      }
      .footer-socials {
        display: flex;
        gap: 12px;
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
        transition: all var(--transition);
        font-size: 1rem;
      }
      .footer-socials a:hover {
        color: var(--accent);
        border-color: var(--accent);
        transform: translateY(-2px);
      }

      /* WhatsApp float */
      .whatsapp-float {
        position: fixed;
        bottom: 28px;
        right: 28px;
        z-index: 999;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #25d366;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        box-shadow: 0 6px 24px rgba(37, 211, 102, 0.35);
        transition: all var(--transition);
        text-decoration: none;
      }
      .whatsapp-float:hover {
        transform: scale(1.1);
        box-shadow: 0 10px 32px rgba(37, 211, 102, 0.5);
        color: #fff;
      }
      .whatsapp-float .pulse-ring {
        position: absolute;
        inset: -6px;
        border-radius: 50%;
        border: 2px solid #25d366;
        animation: pulse-ring 2s ease-out infinite;
      }
      @keyframes pulse-ring {
        0% {
          transform: scale(0.8);
          opacity: 1;
        }
        100% {
          transform: scale(1.6);
          opacity: 0;
        }
      }

      /* Grid background */
      .grid-bg {
        position: absolute;
        inset: 0;
        background-image:
          linear-gradient(var(--grid-color) 1px, transparent 1px),
          linear-gradient(90deg, var(--grid-color) 1px, transparent 1px);
        background-size: 60px 60px;
        pointer-events: none;
      }

      /* Page loader */
      .page-loader {
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: var(--bg);
        display: flex;
        align-items: center;
        justify-content: center;
        transition:
          opacity 0.5s ease,
          visibility 0.5s ease;
      }
      .page-loader.hidden {
        opacity: 0;
        visibility: hidden;
      }
      .loader-logo {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 20px;
      }
      .logo-icon-large {
        width: 80px;
        height: 80px;
        border-radius: 16px;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 2.5rem;
        font-weight: 800;
        animation:
          fade-in-scale 0.8s ease-out,
          pulse-scale 2s ease-in-out 0.8s infinite;
      }
      .logo-text {
        font-size: 1.8rem;
        font-weight: 800;
        letter-spacing: 0.1em;
        background: linear-gradient(135deg, var(--accent), var(--primary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: fade-in-scale 1s ease-out;
      }

      /* ================= PAGEâ€‘SPECIFIC STYLES (index.html) ================= */
      .hero-section {
        min-height: 100vh;
        display: flex;
        align-items: center;
        padding-top: 120px;
        padding-bottom: 80px;
        position: relative;
        overflow: hidden;
        background: var(--hero-gradient);
      }
      .hero-bg-orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: var(--hero-orb-opacity);
        pointer-events: none;
      }
      .hero-bg-orb-1 {
        width: 600px;
        height: 600px;
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
      .hero-bg-orb-3 {
        width: 300px;
        height: 300px;
        background: var(--secondary);
        top: 50%;
        left: 40%;
        animation: float 8s ease-in-out infinite;
      }
      .hero-content {
        position: relative;
        z-index: 2;
      }
      .hero-title {
        margin-bottom: 24px;
      }
      .hero-title .highlight {
        position: relative;
        display: inline-block;
      }
      .hero-title .highlight::after {
        content: "";
        position: absolute;
        bottom: 4px;
        left: 0;
        right: 0;
        height: 8px;
        background: rgba(56, 189, 248, 0.2);
        border-radius: 4px;
      }
      .hero-subtitle {
        font-size: clamp(1rem, 1.8vw, 1.2rem);
        max-width: 580px;
        margin-bottom: 36px;
      }
      .hero-cta {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 40px;
      }
      .hero-stats {
        display: flex;
        gap: 40px;
        flex-wrap: wrap;
        padding-top: 32px;
        border-top: 1px solid var(--border);
      }
      .hero-stat-value {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--accent);
      }
      .hero-stat-label {
        font-size: 0.85rem;
        color: var(--text-muted);
      }
      .hero-visual {
        position: relative;
        z-index: 2;
        perspective: 1200px;
      }
      .hero-dashboard {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 0;
        overflow: hidden;
        box-shadow: var(--shadow-lg);
        transform: rotateY(-5deg) rotateX(3deg);
        transition: transform 0.5s ease;
      }
      .hero-dashboard:hover {
        transform: rotateY(0deg) rotateX(0deg);
      }
      .dashboard-header {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 16px 20px;
        background: var(--card-alt);
        border-bottom: 1px solid var(--border);
      }
      .dashboard-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
      }
      .dashboard-dot.red {
        background: #ef4444;
      }
      .dashboard-dot.yellow {
        background: #f59e0b;
      }
      .dashboard-dot.green {
        background: #10b981;
      }
      .dashboard-body {
        padding: 24px;
      }
      .dashboard-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
      }
      .dash-widget {
        background: var(--card-alt);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 16px;
      }
      .dash-widget-title {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
      }
      .dash-widget-value {
        font-size: 1.4rem;
        font-weight: 700;
      }
      .dash-widget-change {
        font-size: 0.75rem;
        color: #10b981;
        margin-top: 4px;
      }
      .dash-chart {
        grid-column: 1 / -1;
        background: var(--card-alt);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 16px;
        height: 120px;
        display: flex;
        align-items: flex-end;
        gap: 6px;
        overflow: hidden;
      }
      .dash-chart-bar {
        flex: 1;
        background: linear-gradient(to top, var(--primary), var(--accent));
        border-radius: 4px 4px 0 0;
        min-height: 20px;
        transition: height 1s ease;
      }
      .floating-card {
        position: absolute;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 16px 20px;
        box-shadow: var(--shadow);
        backdrop-filter: blur(10px);
        z-index: 5;
      }
      .floating-card-1 {
        top: 10%;
        right: -30px;
      }
      .floating-card-2 {
        bottom: 15%;
        left: -40px;
      }
      .floating-card .fc-label {
        font-size: 0.7rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
      }
      .floating-card .fc-value {
        font-size: 1.1rem;
        font-weight: 700;
        margin-top: 4px;
      }
      .floating-card .fc-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 8px;
        font-size: 1rem;
      }
      .fc-icon.green {
        background: rgba(16, 185, 129, 0.15);
        color: #10b981;
      }
      .fc-icon.blue {
        background: rgba(56, 189, 248, 0.15);
        color: var(--accent);
      }
      .counter-section {
        padding: 60px 0;
        border-top: 1px solid var(--border);
        border-bottom: 1px solid var(--border);
        background: var(--card-alt);
      }
      .counter-item {
        text-align: center;
      }
      .counter-value {
        font-size: clamp(2rem, 4vw, 3.2rem);
        font-weight: 800;
        background: linear-gradient(135deg, var(--accent), var(--primary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
      }
      .counter-label {
        font-size: 0.9rem;
        color: var(--text-muted);
        margin-top: 4px;
      }
      .product-card {
        text-align: left;
      }
      .product-card .card-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 20px;
        background: linear-gradient(
          135deg,
          rgba(37, 99, 235, 0.15),
          rgba(56, 189, 248, 0.15)
        );
        color: var(--accent);
        border: 1px solid rgba(56, 189, 248, 0.2);
      }
      .product-card .card-title {
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: 8px;
      }
      .product-card .card-desc {
        font-size: 0.88rem;
        margin-bottom: 20px;
        min-height: 44px;
      }
      .product-card .card-actions {
        display: flex;
        gap: 10px;
      }
      .module-card {
        text-align: center;
      }
      .module-card .module-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        margin: 0 auto 16px;
        background: linear-gradient(
          135deg,
          rgba(37, 99, 235, 0.1),
          rgba(56, 189, 248, 0.1)
        );
        color: var(--accent);
        border: 1px solid rgba(56, 189, 248, 0.15);
        transition: all var(--transition);
      }
      .module-card:hover .module-icon {
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: #fff;
        box-shadow: 0 8px 30px rgba(56, 189, 248, 0.3);
        transform: scale(1.1);
      }
      .module-card .module-title {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 6px;
      }
      .module-card .module-desc {
        font-size: 0.85rem;
      }
      .feature-item {
        display: flex;
        align-items: flex-start;
        gap: 16px;
      }
      .feature-item .feature-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
        background: linear-gradient(
          135deg,
          rgba(37, 99, 235, 0.15),
          rgba(56, 189, 248, 0.15)
        );
        color: var(--accent);
        border: 1px solid rgba(56, 189, 248, 0.2);
      }
      .feature-item .feature-title {
        font-size: 0.95rem;
        font-weight: 700;
        margin-bottom: 4px;
      }
      .feature-item .feature-desc {
        font-size: 0.82rem;
        margin: 0;
      }
      .pricing-card {
        text-align: center;
        display: flex;
        flex-direction: column;
        height: 100%;
      }
      .pricing-card.popular {
        border-color: var(--accent);
        box-shadow: 0 0 40px rgba(56, 189, 248, 0.12);
        overflow: visible;
      }
      .pricing-card.popular::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(
          90deg,
          var(--primary),
          var(--accent),
          var(--secondary)
        );
        border-radius: var(--radius) var(--radius) 0 0;
      }
      .pricing-badge {
        position: absolute;
        top: -14px;
        left: 50%;
        transform: translateX(-50%);
        padding: 6px 20px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: #fff;
        letter-spacing: 0.05em;
        white-space: nowrap;
        z-index: 2;
      }
      .pricing-name {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 8px;
      }
      .pricing-price {
        font-size: 2.2rem;
        font-weight: 800;
        margin-bottom: 4px;
        color: var(--text);
      }
      .pricing-price .currency {
        font-size: 1rem;
        vertical-align: super;
      }
      .pricing-price .period {
        font-size: 0.8rem;
        color: var(--text-muted);
        font-weight: 400;
      }
      .pricing-price-range {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-bottom: 16px;
      }
      .pricing-desc {
        font-size: 0.85rem;
        margin-bottom: 20px;
      }
      .pricing-features {
        list-style: none;
        padding: 0;
        margin-bottom: 24px;
        text-align: left;
        flex-grow: 1;
      }
      .pricing-features li {
        padding: 8px 0;
        font-size: 0.88rem;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 1px solid var(--border);
        color: var(--text-muted);
      }
      .pricing-features li:last-child {
        border-bottom: none;
      }
      .pricing-features li i {
        color: var(--accent);
        font-size: 0.9rem;
      }
      .timeline {
        position: relative;
        padding: 20px 0;
      }
      .timeline::before {
        content: "";
        position: absolute;
        left: 50%;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(
          to bottom,
          transparent,
          var(--accent),
          var(--primary),
          transparent
        );
        transform: translateX(-50%);
      }
      .timeline-step {
        position: relative;
        padding: 24px 0;
      }
      .timeline-dot {
        position: absolute;
        left: 50%;
        top: 50%;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        transform: translate(-50%, -50%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 800;
        font-size: 1.1rem;
        box-shadow: 0 0 30px rgba(56, 189, 248, 0.3);
        z-index: 2;
      }
      .timeline-content {
        width: 42%;
        padding: 24px;
      }
      .timeline-content h4 {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 6px;
      }
      .timeline-content p {
        font-size: 0.85rem;
        margin: 0;
      }
      .timeline-step:nth-child(odd) .timeline-content {
        margin-right: auto;
        text-align: right;
      }
      .timeline-step:nth-child(even) .timeline-content {
        margin-left: auto;
        text-align: left;
      }
      .why-card {
        text-align: center;
      }
      .why-card .why-icon {
        width: 72px;
        height: 72px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin: 0 auto 20px;
        background: linear-gradient(
          135deg,
          rgba(37, 99, 235, 0.15),
          rgba(56, 189, 248, 0.15)
        );
        color: var(--accent);
        border: 1px solid rgba(56, 189, 248, 0.2);
      }
      .testimonial-card .testimonial-avatar {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--accent);
      }
      .testimonial-card .testimonial-stars {
        color: #f59e0b;
        font-size: 0.85rem;
        margin-bottom: 12px;
      }
      .testimonial-card .testimonial-text {
        font-size: 0.9rem;
        font-style: italic;
        margin-bottom: 16px;
      }
      .testimonial-card .testimonial-name {
        font-weight: 700;
        font-size: 0.95rem;
      }
      .testimonial-card .testimonial-role {
        font-size: 0.8rem;
        color: var(--text-muted);
      }
      .accordion-cooca .accordion-item {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius) !important;
        margin-bottom: 12px;
        overflow: hidden;
      }
      .accordion-cooca .accordion-button {
        background: transparent;
        color: var(--text);
        font-weight: 600;
        font-size: 0.95rem;
        padding: 18px 24px;
        box-shadow: none;
      }
      .accordion-cooca .accordion-button:not(.collapsed) {
        background: transparent;
        color: var(--accent);
      }
      .accordion-cooca .accordion-button::after {
        filter: invert(1);
      }
      [data-theme="dark"] .accordion-cooca .accordion-button::after {
        filter: invert(1);
      }
      [data-theme="light"] .accordion-cooca .accordion-button::after {
        filter: none;
      }
      .accordion-cooca .accordion-body {
        color: var(--text-muted);
        font-size: 0.9rem;
        padding: 0 24px 18px;
      }
      .contact-form .form-control {
        background: var(--card-alt);
        border: 1px solid var(--border);
        color: var(--text);
        border-radius: var(--radius-sm);
        padding: 14px 18px;
        font-size: 0.9rem;
        transition: all var(--transition);
      }
      .contact-form .form-control:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15);
        outline: none;
      }
      .contact-form .form-control::placeholder {
        color: var(--text-muted);
      }
      .contact-info-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        margin-bottom: 20px;
      }
      .contact-info-item .ci-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
        background: rgba(56, 189, 248, 0.1);
        color: var(--accent);
        border: 1px solid rgba(56, 189, 248, 0.2);
      }
      .contact-info-item .ci-title {
        font-weight: 700;
        font-size: 0.9rem;
        margin-bottom: 2px;
      }
      .contact-info-item .ci-text {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin: 0;
      }
      .final-cta {
        position: relative;
        overflow: hidden;
        padding: 100px 0;
      }
      .final-cta-bg {
        position: absolute;
        inset: 0;
        background: linear-gradient(
          135deg,
          var(--primary),
          var(--secondary),
          var(--accent)
        );
        opacity: 0.07;
      }
      .trust-anchor-card {
        background: var(--card);
        border: 2px solid rgba(56, 189, 248, 0.2);
        border-radius: var(--radius-lg);
        padding: 40px 32px;
        text-align: center;
        position: relative;
        overflow: hidden;
      }
      .trust-anchor-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(
          135deg,
          rgba(37, 99, 235, 0.2),
          rgba(56, 189, 248, 0.2)
        );
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 2rem;
        color: var(--accent);
        border: 2px solid rgba(56, 189, 248, 0.3);
      }
      .trust-pills {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 12px;
        margin-top: 20px;
      }
      .trust-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        background: rgba(56, 189, 248, 0.08);
        border: 1px solid rgba(56, 189, 248, 0.18);
        color: var(--text);
        transition: all var(--transition);
      }
      .trust-pill:hover {
        background: rgba(56, 189, 248, 0.14);
        border-color: rgba(56, 189, 248, 0.35);
        transform: translateY(-2px);
      }
      .affiliate-highlight {
        background: linear-gradient(
          135deg,
          rgba(16, 185, 129, 0.08),
          rgba(37, 99, 235, 0.06)
        );
        border: 1px solid rgba(16, 185, 129, 0.2);
        border-radius: var(--radius-lg);
        padding: 36px 28px;
        text-align: center;
      }
      .affiliate-highlight .affiliate-percent {
        font-size: clamp(3rem, 6vw, 4.5rem);
        font-weight: 900;
        background: linear-gradient(135deg, #10b981, var(--accent));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1;
      }
      .free-trial-cta {
        display: flex;
        justify-content: center;
        margin-top: 40px;
      }
      .nav-tabs {
        border-bottom: 1px solid var(--border);
        margin-bottom: 40px;
        justify-content: center;
        flex-wrap: wrap;
      }
      .nav-tabs .nav-link {
        color: var(--text-muted);
        border: none;
        border-bottom: 2px solid transparent;
        padding: 12px 20px;
        font-weight: 600;
        transition: all var(--transition);
        background: transparent;
      }
      .nav-tabs .nav-link:hover {
        color: var(--accent);
      }
      .nav-tabs .nav-link.active {
        color: var(--accent);
        border-bottom-color: var(--accent);
        background: transparent;
      }
      .tab-content {
        animation: fadeIn 0.3s ease-in;
      }
      @keyframes fadeIn {
        from {
          opacity: 0;
        }
        to {
          opacity: 1;
        }
      }

      /* Responsive rules */
      @media (max-width: 1199.98px) {
        .floating-card {
          display: none;
        }
        .pricing-card {
          margin-bottom: 20px;
        }
      }
      @media (max-width: 991.98px) {
        .hero-visual {
          margin-top: 60px;
        }
        .hero-dashboard {
          transform: none;
        }
        .timeline::before {
          left: 24px;
        }
        .timeline-dot {
          left: 24px;
          width: 40px;
          height: 40px;
          font-size: 0.9rem;
        }
        .timeline-content {
          width: calc(100% - 70px);
          margin-left: 70px !important;
          text-align: left !important;
        }
        .hero-stats {
          gap: 24px;
        }
      }
      @media (max-width: 767.98px) {
        .section-padding {
          padding: 60px 0;
        }
        .dashboard-grid {
          grid-template-columns: 1fr;
        }
        .hero-stats {
          justify-content: center;
        }
        .footer-bottom {
          justify-content: center;
          text-align: center;
          flex-direction: column;
        }
        .final-cta {
          padding: 60px 0;
        }
        .nav-tabs {
          gap: 8px;
        }
        .nav-tabs .nav-link {
          padding: 10px 14px;
          font-size: 0.85rem;
        }
      }
      @media (max-width: 575.98px) {
        .hero-cta {
          flex-direction: column;
        }
        .hero-cta .btn-cooca {
          width: 100%;
          justify-content: center;
        }
        .hero-stats {
          gap: 16px 28px;
          justify-content: flex-start;
        }
        .hero-stat-value {
          font-size: 1.5rem;
        }
        .hero-stat-label {
          font-size: 0.78rem;
        }
      }
      @media (max-width: 480px) {
        .timeline-content {
          padding: 16px 14px;
          width: calc(100% - 56px);
          margin-left: 56px !important;
        }
        .timeline-dot {
          left: 16px;
          width: 34px;
          height: 34px;
          font-size: 0.8rem;
        }
        .timeline::before {
          left: 16px;
        }
        .timeline-content h4 {
          font-size: 0.9rem;
        }
        .timeline-content p {
          font-size: 0.78rem;
        }
        .nav-tabs {
          flex-wrap: nowrap;
          overflow-x: auto;
          overflow-y: hidden;
          -webkit-overflow-scrolling: touch;
          scrollbar-width: none;
          gap: 2px;
          padding-bottom: 4px;
        }
        .nav-tabs::-webkit-scrollbar {
          display: none;
        }
        .nav-tabs .nav-link {
          white-space: nowrap;
          flex-shrink: 0;
          padding: 10px 14px;
          font-size: 0.8rem;
        }
        .nav-tabs .nav-item {
          flex-shrink: 0;
        }
        .whatsapp-float {
          width: 48px;
          height: 48px;
          bottom: 20px;
          right: 16px;
          font-size: 1.4rem;
        }
        .whatsapp-float .pulse-ring {
          inset: -4px;
        }
      }
    
</style>
@endpush
@section('content')
<!-- HERO SECTION -->
    <section class="hero-section" id="hero">
      <div class="hero-bg-orb hero-bg-orb-1"></div>
      <div class="hero-bg-orb hero-bg-orb-2"></div>
      <div class="hero-bg-orb hero-bg-orb-3"></div>
      <div class="grid-bg"></div>
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6 hero-content">
            <div class="badge-glow reveal">
              <i class="bi bi-circle-fill"></i> {{ __(setting('home.hero.badge', 'High-Ticket Business Infrastructure')) }}
            </div>
            <h1 class="hero-title reveal reveal-delay-1">
              {!! __(setting('home.hero.title', 'Your Business System<br />Deserves to Work<br /><span class="text-gradient">Like an Asset, Not a Liability.</span>')) !!}
            </h1>
            <p class="hero-subtitle reveal reveal-delay-2">
              {!! __(setting('home.hero.subtitle1', '<strong>Most businesses lose revenue through fragmented tools.</strong> Disconnected systems, recurring fees that never stop, and software that owns you — not the other way around. There\'s a better way.')) !!}
            </p>
            <p
              class="hero-subtitle reveal reveal-delay-2"
              style="font-size: 0.95rem; margin-top: -16px"
            >
              {!! __(setting('home.hero.subtitle2', 'COOCA replaces the chaos with <strong>one integrated system</strong> — lifetime license, modular ERP, and full control over your digital business infrastructure.')) !!}
            </p>
            <div class="hero-cta reveal reveal-delay-3">
              <a href="{{ route('pricing') }}" class="btn-cooca btn-cooca-primary">
                {{ __('View Pricing') }} <i class="bi bi-arrow-right"></i>
              </a>
              <a href="#howitworks" class="btn-cooca btn-cooca-outline">
                <i class="bi bi-play-circle"></i> {{ __('How It Works') }}
              </a>
            </div>
            <div class="hero-stats reveal reveal-delay-4">
              <div>
                <div class="hero-stat-value">{{ setting('home.stat1.value', '10,000+') }}</div>
                <div class="hero-stat-label">{{ __(setting('home.stat1.label', 'Businesses Running on COOCA')) }}</div>
              </div>
              <div>
                <div class="hero-stat-value">{{ setting('home.stat2.value', '99.9%') }}</div>
                <div class="hero-stat-label">{{ __(setting('home.stat2.label', 'System Uptime')) }}</div>
              </div>
              <div>
                <div class="hero-stat-value">{{ setting('home.stat3.value', '500M+') }}</div>
                <div class="hero-stat-label">{{ __(setting('home.stat3.label', 'Secure Transactions')) }}</div>
              </div>
            </div>
          </div>
          <div class="col-lg-6 hero-visual reveal reveal-delay-3">
            <div class="hero-dashboard">
              <div class="dashboard-header">
                <div class="dashboard-dot red"></div>
                <div class="dashboard-dot yellow"></div>
                <div class="dashboard-dot green"></div>
                <span
                  style="
                    margin-left: 12px;
                    font-size: 0.8rem;
                    color: var(--text-muted);
                  "
                  >COOCA Business System</span
                >
              </div>
              <div class="dashboard-body">
                <div class="dashboard-grid">
                  <div class="dash-widget">
                    <div class="dash-widget-title">Revenue This Month</div>
                    <div class="dash-widget-value text-accent">$284,500</div>
                    <div class="dash-widget-change">
                      <i class="bi bi-arrow-up-right"></i> +24.5% growth
                    </div>
                  </div>
                  <div class="dash-widget">
                    <div class="dash-widget-title">Active Licenses</div>
                    <div class="dash-widget-value">12,847</div>
                    <div class="dash-widget-change">
                      <i class="bi bi-arrow-up-right"></i> All protected
                    </div>
                  </div>
                  <div class="dash-chart">
                    <div class="dash-chart-bar" style="height: 40%"></div>
                    <div class="dash-chart-bar" style="height: 65%"></div>
                    <div class="dash-chart-bar" style="height: 45%"></div>
                    <div class="dash-chart-bar" style="height: 80%"></div>
                    <div class="dash-chart-bar" style="height: 55%"></div>
                    <div class="dash-chart-bar" style="height: 90%"></div>
                    <div class="dash-chart-bar" style="height: 70%"></div>
                    <div class="dash-chart-bar" style="height: 95%"></div>
                    <div class="dash-chart-bar" style="height: 60%"></div>
                    <div class="dash-chart-bar" style="height: 85%"></div>
                    <div class="dash-chart-bar" style="height: 75%"></div>
                    <div class="dash-chart-bar" style="height: 100%"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="floating-card floating-card-1 float-anim">
              <div class="fc-icon green">
                <i class="bi bi-shield-check"></i>
              </div>
              <div class="fc-label">System Status</div>
              <div class="fc-value" style="color: #10b981">Protected âœ“</div>
            </div>
            <div class="floating-card floating-card-2 float-anim-delay">
              <div class="fc-icon blue">
                <i class="bi bi-graph-up-arrow"></i>
              </div>
              <div class="fc-label">Monthly Growth</div>
              <div class="fc-value">+Rp48jt MRR</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- TRUST COUNTER -->
    <section class="counter-section" id="counters">
      <div class="container">
        <div class="row g-4">
          <div class="col-md-4 reveal">
            <div class="counter-item">
              <div class="counter-value">
                <span class="counter" data-target="10000">0</span>+
              </div>
              <div class="counter-label">Businesses Trust COOCA</div>
            </div>
          </div>
          <div class="col-md-4 reveal reveal-delay-2">
            <div class="counter-item">
              <div class="counter-value">
                <span class="counter" data-target="99.9" data-decimal="true"
                  >0</span
                >%
              </div>
              <div class="counter-label">Guaranteed Uptime SLA</div>
            </div>
          </div>
          <div class="col-md-4 reveal reveal-delay-4">
            <div class="counter-item">
              <div class="counter-value">
                <span class="counter" data-target="500">0</span>M+
              </div>
              <div class="counter-label">Transactions Processed</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- TRUST ANCHOR -->
    <section
      class="section-padding"
      id="trust-anchor"
      style="background: var(--card-alt)"
    >
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8 reveal">
            <div class="trust-anchor-card">
              <div class="trust-anchor-icon">
                <i class="bi bi-building-lock"></i>
              </div>
              <h3 style="font-size: 1.6rem; margin-bottom: 10px">
                1 Customer =
                <span class="text-gradient">1 Isolated System</span>
              </h3>
              <p
                style="
                  font-size: 1rem;
                  max-width: 500px;
                  margin: 0 auto;
                  color: var(--text-muted);
                "
              >
                Your own dedicated infrastructure. Fully separated. Independent
                security. Not shared â€” <strong>yours alone</strong>.
              </p>
              <div class="trust-pills">
                <span class="trust-pill"
                  ><i class="bi bi-shield-fill-check"></i> Dedicated
                  Environment</span
                >
                <span class="trust-pill"
                  ><i class="bi bi-lock-fill"></i> Zero Data Leakage</span
                >
                <span class="trust-pill"
                  ><i class="bi bi-graph-up-arrow"></i> Independent
                  Scaling</span
                >
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

        <!-- PRODUCT ECOSYSTEM — 3 CORE BUSINESS TABS -->
    <section class="section-padding" id="products">
      <div class="container">
        <div class="text-center">
          <div class="section-label reveal">
            <i class="bi bi-grid-3x3-gap-fill"></i> Industry Solutions
          </div>
          <h2 class="section-title reveal reveal-delay-1">
            Built for <span class="text-gradient">Every Industry</span>
          </h2>
          <p class="section-subtitle reveal reveal-delay-2">
            Nine specialized business systems — each engineered to replace
            fragmented tools that drain your time, revenue, and peace of mind.
          </p>
        </div>

        @php
            $groupedProducts = collect([]);
            if (isset($products) && count($products) > 0) {
                $groupedProducts = $products->groupBy(function($item) {
                    return $item->category ? $item->category->name : __('General Suite');
                });
            }
        @endphp

        @if($groupedProducts->count() > 0)
            <ul class="nav nav-tabs" id="productsTab" role="tablist">
              @foreach($groupedProducts as $catName => $catProducts)
                @php 
                    $tabId = \Illuminate\Support\Str::slug($catName); 
                    $catIcon = $catProducts->first()->category->icon ?? 'bi-grid-3x3-gap-fill';
                @endphp
                <li class="nav-item" role="presentation">
                  <button
                    class="nav-link {{ $loop->first ? 'active' : '' }}"
                    id="tab-{{ $tabId }}"
                    data-bs-toggle="tab"
                    data-bs-target="#products-{{ $tabId }}"
                    type="button"
                    role="tab"
                    aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                  >
                    <i class="bi {{ $catIcon }} me-2"></i>{{ $catName }}
                  </button>
                </li>
              @endforeach
            </ul>

            <div class="tab-content" id="productsTabContent">
              @foreach($groupedProducts as $catName => $catProducts)
                @php $tabId = \Illuminate\Support\Str::slug($catName); @endphp
                <div
                  class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                  id="products-{{ $tabId }}"
                  role="tabpanel"
                >
                  <div class="row g-4">
                    @foreach($catProducts as $index => $product)
                      <div class="col-lg-4 col-md-6 reveal {{ $index > 0 ? 'reveal-delay-' . $index : '' }}">
                        <div class="card-3d product-card" style="height: 100%">
                          <div class="card-glow"></div>
                          <div class="card-icon"><i class="bi {{ $product->icon ?? 'bi-box' }}"></i></div>
                          <div class="card-title">{{ $product->name }}</div>
                          <div class="card-desc">
                            {{ $product->short_description ?? \Illuminate\Support\Str::limit($product->description, 120) }}
                          </div>
                          <div class="card-actions">
                            <a href="{{ route('products.show', $product->slug) }}" class="btn-cooca btn-cooca-outline btn-cooca-sm"
                              >{{ __('Learn More') }}</a>
                            @if($product->demo_url)
                              <a
                                href="{{ $product->demo_url }}"
                                target="_blank"
                                class="btn-cooca btn-cooca-primary btn-cooca-sm"
                                >{{ __('Live Demo') }}</a
                              >
                            @endif
                          </div>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              @endforeach
            </div>
        @else
            <div class="glow-card text-center py-5 reveal">
                <i class="bi bi-inbox-fill" style="font-size: 3rem; color: var(--border);"></i>
                <p class="text-muted mt-3">{{ __('No products available at the moment.') }}</p>
            </div>
        @endif
      </div>
    </section>

    <!-- CORE CAPABILITIES â€” 3 GROUPED TABS -->
    <section
      class="section-padding"
      id="modules"
      style="background: var(--card-alt)"
    >
      <div class="container">
        <div class="text-center">
          <div class="section-label reveal">
            <i class="bi bi-puzzle-fill"></i> Business Capabilities
          </div>
          <h2 class="section-title reveal reveal-delay-1">
            Everything Your Business Needs to
            <span class="text-gradient">Scale</span>
          </h2>
          <p class="section-subtitle reveal reveal-delay-2">
            Ten integrated capabilities replacing dozens of separate
            subscriptions. Each one works with the others â€” because they were
            built to.
          </p>
        </div>

        <ul class="nav nav-tabs" id="modulesTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button
              class="nav-link active"
              id="tab-people"
              data-bs-toggle="tab"
              data-bs-target="#modules-people"
              type="button"
              role="tab"
              aria-selected="true"
            >
              <i class="bi bi-people me-2"></i>People &amp; Revenue
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button
              class="nav-link"
              id="tab-operations"
              data-bs-toggle="tab"
              data-bs-target="#modules-operations"
              type="button"
              role="tab"
              aria-selected="false"
            >
              <i class="bi bi-gear me-2"></i>Operations &amp; Finance
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button
              class="nav-link"
              id="tab-intelligence"
              data-bs-toggle="tab"
              data-bs-target="#modules-intelligence"
              type="button"
              role="tab"
              aria-selected="false"
            >
              <i class="bi bi-graph-up-arrow me-2"></i>Intelligence &amp; Growth
            </button>
          </li>
        </ul>

        <div class="tab-content" id="modulesTabContent">
          <!-- Tab 1: People & Revenue -->
          <div
            class="tab-pane fade show active"
            id="modules-people"
            role="tabpanel"
          >
            <div class="row g-4">
              <div class="col-lg-4 col-md-6 reveal">
                <div
                  class="card-3d module-card"
                  style="height: 100%; padding: 32px"
                >
                  <div class="module-icon" style="margin: 0 auto 20px">
                    <i class="bi bi-people"></i>
                  </div>
                  <div class="module-title">CRM</div>
                  <div class="module-desc" style="margin-top: 8px">
                    Turn leads into loyal customers with automated relationship
                    management. Full pipeline visibility from first contact to
                    closed deal.
                  </div>
                  <ul
                    style="
                      list-style: none;
                      padding: 0;
                      margin-top: 16px;
                      text-align: left;
                    "
                  >
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        border-bottom: 1px solid var(--border);
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Contact &amp; lead management
                    </li>
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        border-bottom: 1px solid var(--border);
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Sales pipeline tracking
                    </li>
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Loyalty &amp; retention programs
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col-lg-4 col-md-6 reveal reveal-delay-1">
                <div
                  class="card-3d module-card"
                  style="height: 100%; padding: 32px"
                >
                  <div class="module-icon" style="margin: 0 auto 20px">
                    <i class="bi bi-person-badge"></i>
                  </div>
                  <div class="module-title">HRM</div>
                  <div class="module-desc" style="margin-top: 8px">
                    Manage your entire team â€” from recruitment and onboarding to
                    payroll and performance â€” all in one place.
                  </div>
                  <ul
                    style="
                      list-style: none;
                      padding: 0;
                      margin-top: 16px;
                      text-align: left;
                    "
                  >
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        border-bottom: 1px solid var(--border);
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Attendance &amp; shift scheduling
                    </li>
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        border-bottom: 1px solid var(--border);
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Automated payroll calculation
                    </li>
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Performance &amp; leave management
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col-lg-4 col-md-6 reveal reveal-delay-2">
                <div
                  class="card-3d module-card"
                  style="height: 100%; padding: 32px"
                >
                  <div class="module-icon" style="margin: 0 auto 20px">
                    <i class="bi bi-whatsapp"></i>
                  </div>
                  <div class="module-title">WhatsApp Integration</div>
                  <div class="module-desc" style="margin-top: 8px">
                    Reach customers where they already are â€” instantly and
                    automatically. Blast notifications, confirmations, and
                    campaigns without leaving the system.
                  </div>
                  <ul
                    style="
                      list-style: none;
                      padding: 0;
                      margin-top: 16px;
                      text-align: left;
                    "
                  >
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        border-bottom: 1px solid var(--border);
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Broadcast &amp; bulk messaging
                    </li>
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        border-bottom: 1px solid var(--border);
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Automated order &amp; payment alerts
                    </li>
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Two-way customer chat
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <!-- Tab 2: Operations & Finance -->
          <div class="tab-pane fade" id="modules-operations" role="tabpanel">
            <div class="row g-4">
              <div class="col-lg-3 col-md-6 reveal">
                <div
                  class="card-3d module-card"
                  style="height: 100%; padding: 28px"
                >
                  <div class="module-icon" style="margin: 0 auto 16px">
                    <i class="bi bi-calculator"></i>
                  </div>
                  <div class="module-title">Accounting</div>
                  <div class="module-desc" style="margin-top: 8px">
                    Real-time financial clarity with automated bookkeeping,
                    journal entries, and balance sheets â€” no accountant
                    dependency.
                  </div>
                  <ul
                    style="
                      list-style: none;
                      padding: 0;
                      margin-top: 12px;
                      text-align: left;
                    "
                  >
                    <li
                      style="
                        font-size: 0.8rem;
                        color: var(--text-muted);
                        padding: 5px 0;
                        border-bottom: 1px solid var(--border);
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent); font-size: 0.75rem"
                      ></i>
                      Auto journal &amp; GL
                    </li>
                    <li
                      style="
                        font-size: 0.8rem;
                        color: var(--text-muted);
                        padding: 5px 0;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent); font-size: 0.75rem"
                      ></i>
                      P&amp;L &amp; balance sheets
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col-lg-3 col-md-6 reveal reveal-delay-1">
                <div
                  class="card-3d module-card"
                  style="height: 100%; padding: 28px"
                >
                  <div class="module-icon" style="margin: 0 auto 16px">
                    <i class="bi bi-bank"></i>
                  </div>
                  <div class="module-title">Finance</div>
                  <div class="module-desc" style="margin-top: 8px">
                    Budgeting, forecasting, and cash flow management that
                    empowers confident business decisions â€” not reactive panic.
                  </div>
                  <ul
                    style="
                      list-style: none;
                      padding: 0;
                      margin-top: 12px;
                      text-align: left;
                    "
                  >
                    <li
                      style="
                        font-size: 0.8rem;
                        color: var(--text-muted);
                        padding: 5px 0;
                        border-bottom: 1px solid var(--border);
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent); font-size: 0.75rem"
                      ></i>
                      Budget planning
                    </li>
                    <li
                      style="
                        font-size: 0.8rem;
                        color: var(--text-muted);
                        padding: 5px 0;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent); font-size: 0.75rem"
                      ></i>
                      Cash flow forecasting
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col-lg-3 col-md-6 reveal reveal-delay-2">
                <div
                  class="card-3d module-card"
                  style="height: 100%; padding: 28px"
                >
                  <div class="module-icon" style="margin: 0 auto 16px">
                    <i class="bi bi-box-seam"></i>
                  </div>
                  <div class="module-title">Inventory</div>
                  <div class="module-desc" style="margin-top: 8px">
                    Never run out of stock â€” or over-purchase. Smart tracking
                    across multiple warehouses with automated reorder alerts.
                  </div>
                  <ul
                    style="
                      list-style: none;
                      padding: 0;
                      margin-top: 12px;
                      text-align: left;
                    "
                  >
                    <li
                      style="
                        font-size: 0.8rem;
                        color: var(--text-muted);
                        padding: 5px 0;
                        border-bottom: 1px solid var(--border);
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent); font-size: 0.75rem"
                      ></i>
                      Multi-warehouse tracking
                    </li>
                    <li
                      style="
                        font-size: 0.8rem;
                        color: var(--text-muted);
                        padding: 5px 0;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent); font-size: 0.75rem"
                      ></i>
                      Auto reorder points
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col-lg-3 col-md-6 reveal reveal-delay-3">
                <div
                  class="card-3d module-card"
                  style="height: 100%; padding: 28px"
                >
                  <div class="module-icon" style="margin: 0 auto 16px">
                    <i class="bi bi-credit-card"></i>
                  </div>
                  <div class="module-title">Payments</div>
                  <div class="module-desc" style="margin-top: 8px">
                    Accept money from anywhere with fully integrated payment
                    processing â€” QRIS, transfer, cards, and e-wallets.
                  </div>
                  <ul
                    style="
                      list-style: none;
                      padding: 0;
                      margin-top: 12px;
                      text-align: left;
                    "
                  >
                    <li
                      style="
                        font-size: 0.8rem;
                        color: var(--text-muted);
                        padding: 5px 0;
                        border-bottom: 1px solid var(--border);
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent); font-size: 0.75rem"
                      ></i>
                      QRIS &amp; multi-gateway
                    </li>
                    <li
                      style="
                        font-size: 0.8rem;
                        color: var(--text-muted);
                        padding: 5px 0;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent); font-size: 0.75rem"
                      ></i>
                      Auto reconciliation
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <!-- Tab 3: Intelligence & Growth -->
          <div class="tab-pane fade" id="modules-intelligence" role="tabpanel">
            <div class="row g-4">
              <div class="col-lg-4 col-md-6 reveal">
                <div
                  class="card-3d module-card"
                  style="height: 100%; padding: 32px"
                >
                  <div class="module-icon" style="margin: 0 auto 20px">
                    <i class="bi bi-bar-chart-line"></i>
                  </div>
                  <div class="module-title">Reporting &amp; Analytics</div>
                  <div class="module-desc" style="margin-top: 8px">
                    See exactly where your money comes from and where it goes.
                    Real-time dashboards that surface the insights that actually
                    matter.
                  </div>
                  <ul
                    style="
                      list-style: none;
                      padding: 0;
                      margin-top: 16px;
                      text-align: left;
                    "
                  >
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        border-bottom: 1px solid var(--border);
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Custom dashboard builder
                    </li>
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        border-bottom: 1px solid var(--border);
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Revenue &amp; cost breakdown
                    </li>
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Exportable PDF &amp; Excel reports
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col-lg-4 col-md-6 reveal reveal-delay-1">
                <div
                  class="card-3d module-card"
                  style="height: 100%; padding: 32px"
                >
                  <div class="module-icon" style="margin: 0 auto 20px">
                    <i class="bi bi-gear-wide-connected"></i>
                  </div>
                  <div class="module-title">Workflow Automation</div>
                  <div class="module-desc" style="margin-top: 8px">
                    Eliminate repetitive work so your team focuses on growth.
                    Set rules once, let the system execute thousands of tasks
                    daily.
                  </div>
                  <ul
                    style="
                      list-style: none;
                      padding: 0;
                      margin-top: 16px;
                      text-align: left;
                    "
                  >
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        border-bottom: 1px solid var(--border);
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Trigger-based rule engine
                    </li>
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        border-bottom: 1px solid var(--border);
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Cross-module automation
                    </li>
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Scheduled &amp; event-driven tasks
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col-lg-4 col-md-6 reveal reveal-delay-2">
                <div
                  class="card-3d module-card"
                  style="height: 100%; padding: 32px"
                >
                  <div class="module-icon" style="margin: 0 auto 20px">
                    <i class="bi bi-robot"></i>
                  </div>
                  <div class="module-title">AI Assistant</div>
                  <div class="module-desc" style="margin-top: 8px">
                    Get actionable insights and smart recommendations that drive
                    better business decisions â€” before problems become
                    expensive.
                  </div>
                  <ul
                    style="
                      list-style: none;
                      padding: 0;
                      margin-top: 16px;
                      text-align: left;
                    "
                  >
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        border-bottom: 1px solid var(--border);
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Predictive revenue analytics
                    </li>
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        border-bottom: 1px solid var(--border);
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Anomaly &amp; fraud detection
                    </li>
                    <li
                      style="
                        font-size: 0.82rem;
                        color: var(--text-muted);
                        padding: 6px 0;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                      "
                    >
                      <i
                        class="bi bi-check-circle-fill"
                        style="color: var(--accent)"
                      ></i>
                      Natural language business Q&amp;A
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- LICENSE & SECURITY -->
    <section class="section-padding" id="features">
      <div class="container">
        <div class="row align-items-center g-5">
          <div class="col-lg-5 reveal">
            <div class="section-label">
              <i class="bi bi-shield-lock-fill"></i> {{ __(setting('home.security.badge', 'Asset Protection')) }}
            </div>
            <h2 class="section-title">
              {!! __(setting('home.security.title', 'Your License Is <span class="text-gradient">Your Asset.</span> We Protect It.')) !!}
            </h2>
            <p class="mb-4">
              {!! __(setting('home.security.desc', 'Most software companies rent you access. COOCA gives you <strong>ownership</strong> — with lifetime licenses, domain binding, and enterprise-grade security that treats your business system as the valuable asset it is.')) !!}
            </p>
            <a href="{{ route('pricing') }}" class="btn-cooca btn-cooca-primary"
              >{{ __('View Protection Options') }} <i class="bi bi-arrow-right"></i
            ></a>
          </div>
          <div class="col-lg-7">
            <div class="row g-3">
              <div class="col-md-6 reveal">
                <div class="card-3d feature-item" style="padding: 20px">
                  <div class="feature-icon"><i class="bi bi-infinity"></i></div>
                  <div>
                    <div class="feature-title">Lifetime License</div>
                    <p class="feature-desc">
                      Pay once. Own forever. No renewal anxiety, no surprise
                      price hikes.
                    </p>
                  </div>
                </div>
              </div>
              <div class="col-md-6 reveal reveal-delay-1">
                <div class="card-3d feature-item" style="padding: 20px">
                  <div class="feature-icon"><i class="bi bi-globe2"></i></div>
                  <div>
                    <div class="feature-title">Domain Binding</div>
                    <p class="feature-desc">
                      Your license is locked to your domain. Unauthorized use is
                      technically impossible.
                    </p>
                  </div>
                </div>
              </div>
              <div class="col-md-6 reveal reveal-delay-2">
                <div class="card-3d feature-item" style="padding: 20px">
                  <div class="feature-icon">
                    <i class="bi bi-lock-fill"></i>
                  </div>
                  <div>
                    <div class="feature-title">HMAC Security</div>
                    <p class="feature-desc">
                      Cryptographic validation ensures every request is genuine
                      and untampered.
                    </p>
                  </div>
                </div>
              </div>
              <div class="col-md-6 reveal reveal-delay-3">
                <div class="card-3d feature-item" style="padding: 20px">
                  <div class="feature-icon">
                    <i class="bi bi-cash-stack"></i>
                  </div>
                  <div>
                    <div class="feature-title">Flexible Investment</div>
                    <p class="feature-desc">
                      Monthly, annual, or lifetime â€” choose the model that fits
                      your current stage.
                    </p>
                  </div>
                </div>
              </div>
              <div class="col-md-6 reveal reveal-delay-4">
                <div class="card-3d feature-item" style="padding: 20px">
                  <div class="feature-icon">
                    <i class="bi bi-person-plus"></i>
                  </div>
                  <div>
                    <div class="feature-title">Affiliate System</div>
                    <p class="feature-desc">
                      Earn up to 30% commission by referring businesses to
                      COOCA.
                    </p>
                  </div>
                </div>
              </div>
              <div class="col-md-6 reveal reveal-delay-5">
                <div class="card-3d feature-item" style="padding: 20px">
                  <div class="feature-icon">
                    <i class="bi bi-journal-text"></i>
                  </div>
                  <div>
                    <div class="feature-title">Audit Logs</div>
                    <p class="feature-desc">
                      Every action is recorded. Full transparency for compliance
                      and trust.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- AFFILIATE PROGRAM -->
    <section
      class="section-padding"
      id="affiliate"
      style="background: var(--card-alt)"
    >
      <div class="container">
        <div class="text-center">
          <div class="section-label reveal">
            <i class="bi bi-cash-coin"></i> Partner Program
          </div>
          <h2 class="section-title reveal reveal-delay-1">
            Not Just a User â€”
            <span class="text-gradient">A Business Opportunity</span>
          </h2>
          <p class="section-subtitle reveal reveal-delay-2">
            You don't need to build software to earn from it. Distribute, refer,
            and earn â€” without ever touching a line of code.
          </p>
        </div>
        <div class="row g-4 align-items-center">
          <div class="col-lg-6 reveal">
            <div class="affiliate-highlight">
              <div class="affiliate-percent">Up to 30%</div>
              <p
                style="font-size: 1.2rem; font-weight: 600; margin-bottom: 8px"
              >
                Commission on Every Referral
              </p>
              <p style="max-width: 400px; margin: 0 auto 20px">
                Potential earnings of millions of rupiah every month. A system
                designed to generate referral-based income at scale.
              </p>
              <div
                class="d-flex flex-wrap justify-content-center gap-3"
                style="font-size: 0.85rem"
              >
                <span
                  ><i class="bi bi-check-circle-fill text-accent me-1"></i>
                  Real-time tracking</span
                >
                <span
                  ><i class="bi bi-check-circle-fill text-accent me-1"></i>
                  Monthly payouts</span
                >
                <span
                  ><i class="bi bi-check-circle-fill text-accent me-1"></i>
                  Marketing assets provided</span
                >
              </div>
            </div>
          </div>
          <div class="col-lg-6 reveal reveal-delay-2">
            <div class="card-3d" style="padding: 28px">
              <h4 style="margin-bottom: 16px">Perfect For Those Who:</h4>
              <ul style="list-style: none; padding: 0">
                <li
                  style="
                    padding: 10px 0;
                    border-bottom: 1px solid var(--border);
                    display: flex;
                    align-items: center;
                    gap: 10px;
                  "
                >
                  <i class="bi bi-check-circle-fill text-accent"></i> Digital
                  marketers looking for additional income streams
                </li>
                <li
                  style="
                    padding: 10px 0;
                    border-bottom: 1px solid var(--border);
                    display: flex;
                    align-items: center;
                    gap: 10px;
                  "
                >
                  <i class="bi bi-check-circle-fill text-accent"></i> Content
                  creators with a business-focused audience
                </li>
                <li
                  style="
                    padding: 10px 0;
                    border-bottom: 1px solid var(--border);
                    display: flex;
                    align-items: center;
                    gap: 10px;
                  "
                >
                  <i class="bi bi-check-circle-fill text-accent"></i> Network
                  builders who want to monetize their connections
                </li>
                <li
                  style="
                    padding: 10px 0;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                  "
                >
                  <i class="bi bi-check-circle-fill text-accent"></i> Anyone who
                  wants a business without building a product
                </li>
              </ul>
              <a
                href="{{ route('affiliate') }}"
                class="btn-cooca btn-cooca-success mt-3"
                style="width: 100%; justify-content: center"
                >{{ __('Join Affiliate Program') }} <i class="bi bi-arrow-right"></i
              ></a>
            </div>
          </div>
        </div>
      </div>
    </section>

<!-- PRICING -->
    <section
      class="section-padding"
      id="pricing"
      style="background: var(--card-alt)"
    >
      <div class="container">
        <div class="text-center">
          <div class="section-label reveal">
            <i class="bi bi-tag-fill"></i> Investment
          </div>
          <h2 class="section-title reveal reveal-delay-1">
            Choose the Model that
            <span class="text-gradient">Fits Your Stage</span>
          </h2>
          <p class="section-subtitle reveal reveal-delay-2">
            All plans include <strong>full access to all modules</strong> with
            <strong>unlimited users</strong>. From flexible monthly to lifetime
            ownership — pick what fits your business today.
          </p>
        </div>
        <div class="row g-4 justify-content-center">
          <div class="col-lg col-md-6 reveal">
            <div class="card-3d pricing-card" style="height: 100%">
              <div class="card-glow"></div>
              <div class="pricing-name">Monthly</div>
              <div class="pricing-price">
                <span class="currency">Rp</span>690K<span class="period"
                  >–990K</span
                >
              </div>
              <div class="pricing-price-range">/ month</div>
              <div class="pricing-desc">Flexible, pay as you go</div>
              <ul class="pricing-features">
                <li>
                  <i class="bi bi-check-circle-fill"></i> Full Module Access
                </li>
                <li><i class="bi bi-check-circle-fill"></i> Unlimited Users</li>
                <li><i class="bi bi-check-circle-fill"></i> Email Support</li>
                <li>
                  <i class="bi bi-check-circle-fill"></i> Automatic Updates
                </li>
              </ul>
              <a
                href="#"
                class="btn-cooca btn-cooca-outline"
                style="width: 100%; justify-content: center"
                >Start Monthly</a
              >
            </div>
          </div>
          <div class="col-lg col-md-6 reveal reveal-delay-1">
            <div class="card-3d pricing-card" style="height: 100%">
              <div class="card-glow"></div>
              <div class="pricing-name">3 Months</div>
              <div class="pricing-price">
                <span class="currency">Rp</span>1,99jt<span class="period"
                  >–2,79jt</span
                >
              </div>
              <div class="pricing-price-range">/ 3 months</div>
              <div class="pricing-desc">Save more per month</div>
              <ul class="pricing-features">
                <li>
                  <i class="bi bi-check-circle-fill"></i> Full Module Access
                </li>
                <li><i class="bi bi-check-circle-fill"></i> Unlimited Users</li>
                <li>
                  <i class="bi bi-check-circle-fill"></i> Priority Email Support
                </li>
                <li>
                  <i class="bi bi-check-circle-fill"></i> Automatic Updates
                </li>
              </ul>
              <a
                href="#"
                class="btn-cooca btn-cooca-outline"
                style="width: 100%; justify-content: center"
                >Start 3 Months</a
              >
            </div>
          </div>
          <div class="col-lg col-md-6 reveal reveal-delay-2">
            <div class="card-3d pricing-card popular" style="height: 100%">
              <div class="pricing-badge">Best Value</div>
              <div class="card-glow"></div>
              <div class="pricing-name">Annual</div>
              <div class="pricing-price">
                <span class="currency">Rp</span>5,9jt<span class="period"
                  >–7,9jt</span
                >
              </div>
              <div class="pricing-price-range">/ year</div>
              <div class="pricing-desc">The smartest long-term investment</div>
              <ul class="pricing-features">
                <li>
                  <i class="bi bi-check-circle-fill"></i> Full Module Access
                </li>
                <li><i class="bi bi-check-circle-fill"></i> Unlimited Users</li>
                <li>
                  <i class="bi bi-check-circle-fill"></i> Priority Support
                </li>
                <li>
                  <i class="bi bi-check-circle-fill"></i> AI Assistant Included
                </li>
              </ul>
              <a
                href="#"
                class="btn-cooca btn-cooca-primary"
                style="width: 100%; justify-content: center"
                >Start Annual</a
              >
            </div>
          </div>
          <div class="col-lg col-md-6 reveal reveal-delay-3">
            <div class="card-3d pricing-card" style="height: 100%">
              <div class="card-glow"></div>
              <div class="pricing-name">Lifetime</div>
              <div class="pricing-price">
                <span class="currency">Rp</span>19,9jt
              </div>
              <div class="pricing-price-range">one-time · yours forever</div>
              <div class="pricing-desc">
                Own it forever. Zero recurring fees.
              </div>
              <ul class="pricing-features">
                <li>
                  <i class="bi bi-check-circle-fill"></i> Full Module Access
                </li>
                <li><i class="bi bi-check-circle-fill"></i> Unlimited Users</li>
                <li>
                  <i class="bi bi-check-circle-fill"></i> Lifetime Updates
                </li>
                <li>
                  <i class="bi bi-check-circle-fill"></i> Dedicated Support
                </li>
                <li>
                  <i class="bi bi-check-circle-fill"></i> Maintenance:
                  Rp2,4jt–3,6jt/yr
                </li>
              </ul>
              <a
                href="#"
                class="btn-cooca btn-cooca-primary"
                style="width: 100%; justify-content: center"
                >Own It Forever</a
              >
            </div>
          </div>
          <div class="col-lg col-md-6 reveal reveal-delay-4">
            <div
              class="card-3d pricing-card"
              style="height: 100%; border: 2px dashed rgba(56, 189, 248, 0.25)"
            >
              <div class="card-glow"></div>
              <div class="pricing-name">
                Custom
                <span
                  style="
                    font-size: 0.7rem;
                    background: rgba(56, 189, 248, 0.15);
                    color: var(--accent);
                    padding: 3px 10px;
                    border-radius: 50px;
                    margin-left: 6px;
                  "
                  >Enterprise</span
                >
              </div>
              <div class="pricing-price" style="font-size: 1.6rem">
                Need Custom?
              </div>
              <div class="pricing-desc">
                Solutions tailored to your unique business requirements.
              </div>
              <ul class="pricing-features">
                <li>
                  <i class="bi bi-check-circle-fill"></i> Full Module Access
                </li>
                <li><i class="bi bi-check-circle-fill"></i> Unlimited Users</li>
                <li>
                  <i class="bi bi-check-circle-fill"></i> Custom Integrations
                </li>
                <li>
                  <i class="bi bi-check-circle-fill"></i> Dedicated Account
                  Manager
                </li>
                <li>
                  <i class="bi bi-check-circle-fill"></i> SLA &amp; On-Prem
                  Option
                </li>
              </ul>
              <a
                href="#"
                class="btn-cooca btn-cooca-outline"
                style="width: 100%; justify-content: center"
                >Contact Us <i class="bi bi-chat-dots"></i
              ></a>
            </div>
          </div>
        </div>
        <div class="free-trial-cta reveal reveal-delay-3">
          <a
            href="#"
            class="btn-cooca btn-cooca-primary"
            style="padding: 18px 48px; font-size: 1.1rem; border-radius: 50px"
          >
            <i class="bi bi-gift-fill"></i> Start Free 30-Day Trial — No Credit
            Card
          </a>
        </div>
      </div>
    </section>

    

    <!-- ACTIVATION -->
    <section
      class="section-padding"
      id="howitworks"
      style="background: var(--card-alt)"
    >
      <div class="container">
        <div class="text-center">
          <div class="section-label reveal">
            <i class="bi bi-diagram-3-fill"></i> Activation
          </div>
          <h2 class="section-title reveal reveal-delay-1">
            From Signup to
            <span class="text-gradient">Trial-Ready in 30 Minutes</span>
          </h2>
          <p class="section-subtitle reveal reveal-delay-2">
            Four simple steps. No technical expertise needed. No waiting for
            lengthy deployments.
          </p>
        </div>
        <div class="timeline">
          <div class="timeline-step reveal">
            <div class="timeline-dot">1</div>
            <div class="timeline-content card-3d">
              <h4>Sign Up</h4>
              <p>
                Register with your email. No credit card. Instantly enter your
                dashboard.
              </p>
            </div>
          </div>
          <div class="timeline-step reveal">
            <div class="timeline-dot">2</div>
            <div class="timeline-content card-3d">
              <h4>Choose Your Industry</h4>
              <p>
                Select your business type from 9 industry solutions. The system
                auto-configures your modules.
              </p>
            </div>
          </div>
          <div class="timeline-step reveal">
            <div class="timeline-dot">3</div>
            <div class="timeline-content card-3d">
              <h4>Wait 30 Minutes</h4>
              <p>
                Your isolated infrastructure is provisioned. A dedicated
                environment â€” not a shared tenant.
              </p>
            </div>
          </div>
          <div class="timeline-step reveal">
            <div class="timeline-dot">4</div>
            <div class="timeline-content card-3d">
              <h4>System Trial Ready</h4>
              <p>
                Full access to all modules. 30-day free trial. Run your business
                from one platform.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- WHY COOCA -->
    <section class="section-padding" id="why">
      <div class="container">
        <div class="text-center">
          <div class="section-label reveal">
            <i class="bi bi-trophy-fill"></i> Why COOCA
          </div>
          <h2 class="section-title reveal reveal-delay-1">
            Why Businesses <span class="text-gradient">Choose COOCA</span>
          </h2>
        </div>
        <div class="row g-4">
          <div class="col-lg-4 reveal">
            <div class="card-3d why-card" style="height: 100%">
              <div class="card-glow"></div>
              <div class="why-icon">
                <i class="bi bi-shield-fill-check"></i>
              </div>
              <h4>Enterprise-Grade Security</h4>
              <p>
                HMAC encryption, domain binding, and an isolated environment
                ensure your business data is protected at a level most platforms
                can't match.
              </p>
            </div>
          </div>
          <div class="col-lg-4 reveal reveal-delay-1">
            <div class="card-3d why-card" style="height: 100%">
              <div class="card-glow"></div>
              <div class="why-icon"><i class="bi bi-diagram-3"></i></div>
              <h4>Modular &amp; Scalable</h4>
              <p>
                Start with what you need today. Add capabilities as your revenue
                grows. The system scales alongside your ambition â€” not against
                it.
              </p>
            </div>
          </div>
          <div class="col-lg-4 reveal reveal-delay-2">
            <div class="card-3d why-card" style="height: 100%">
              <div class="card-glow"></div>
              <div class="why-icon"><i class="bi bi-headset"></i></div>
              <h4>24/7 Premium Support</h4>
              <p>
                When your business runs on COOCA, you're never alone. Our team
                responds fast because we understand what's at stake.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- TESTIMONIALS -->
    <section
      class="section-padding"
      id="testimonials"
      style="background: var(--card-alt)"
    >
      <div class="container">
        <div class="text-center">
          <div class="section-label reveal">
            <i class="bi bi-chat-quote-fill"></i> Real Results
          </div>
          <h2 class="section-title reveal reveal-delay-1">
            Businesses That <span class="text-gradient">Transformed</span>
          </h2>
          <p class="section-subtitle reveal reveal-delay-2">
            Not features. Not specs. Real outcomes from real business owners.
          </p>
        </div>
        <div class="row g-4">
          <div class="col-lg-4 col-md-6 reveal">
            <div class="card-3d testimonial-card" style="height: 100%">
              <div class="card-glow"></div>
              <div class="testimonial-stars">
                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i
                ><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i
                ><i class="bi bi-star-fill"></i>
              </div>
              <p class="testimonial-text">
                "We saved 200 hours of manual work per month. That's equivalent
                to Rp8 million in productivity gains â€” every single month."
              </p>
              <div class="d-flex align-items-center gap-3">
                <img
                  src="https://placehold.co/52x52/1E3A5F/38BDF8?text=AK"
                  alt="Avatar"
                  class="testimonial-avatar"
                />
                <div>
                  <div class="testimonial-name">Ahmad Kurniawan</div>
                  <div class="testimonial-role">CEO, RetailMax Indonesia</div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 reveal reveal-delay-1">
            <div class="card-3d testimonial-card" style="height: 100%">
              <div class="card-glow"></div>
              <div class="testimonial-stars">
                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i
                ><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i
                ><i class="bi bi-star-fill"></i>
              </div>
              <p class="testimonial-text">
                "Zero unauthorized access in 2 years. Our previous system was
                breached three times. This peace of mind is priceless."
              </p>
              <div class="d-flex align-items-center gap-3">
                <img
                  src="https://placehold.co/52x52/1E40AF/38BDF8?text=SP"
                  alt="Avatar"
                  class="testimonial-avatar"
                />
                <div>
                  <div class="testimonial-name">Sarah Putri</div>
                  <div class="testimonial-role">CTO, Medica Health Group</div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 reveal reveal-delay-2">
            <div class="card-3d testimonial-card" style="height: 100%">
              <div class="card-glow"></div>
              <div class="testimonial-stars">
                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i
                ><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i
                ><i class="bi bi-star-fill"></i>
              </div>
              <p class="testimonial-text">
                "Migrated from our legacy system in one week. Revenue visibility
                improved dramatically. We found Rp12 million in unbilled
                services in the first month."
              </p>
              <div class="d-flex align-items-center gap-3">
                <img
                  src="https://placehold.co/52x52/7C3AED/F8FAFC?text=DR"
                  alt="Avatar"
                  class="testimonial-avatar"
                />
                <div>
                  <div class="testimonial-name">David Rahardjo</div>
                  <div class="testimonial-role">
                    Director, Grand Hotel Chain
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- FAQ -->
    <section class="section-padding" id="faq">
      <div class="container">
        <div class="text-center">
          <div class="section-label reveal">
            <i class="bi bi-question-circle-fill"></i> FAQ
          </div>
          <h2 class="section-title reveal reveal-delay-1">
            Frequently Asked <span class="text-gradient">Questions</span>
          </h2>
        </div>
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="accordion accordion-cooca" id="faqAccordion">
              <div class="accordion-item reveal">
                <h2 class="accordion-header">
                  <button
                    class="accordion-button"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#faq1"
                  >
                    What's the difference between a lifetime license and regular
                    software?
                  </button>
                </h2>
                <div
                  id="faq1"
                  class="accordion-collapse collapse show"
                  data-bs-parent="#faqAccordion"
                >
                  <div class="accordion-body">
                    Most software is rented â€” stop paying, lose access. COOCA's
                    lifetime license is full ownership. One payment gives you
                    permanent access, free updates, and a system that keeps
                    generating value without recurring costs. It's an asset, not
                    an expense.
                  </div>
                </div>
              </div>
              <div class="accordion-item reveal reveal-delay-1">
                <h2 class="accordion-header">
                  <button
                    class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#faq2"
                  >
                    Do all plans really include full module access?
                  </button>
                </h2>
                <div
                  id="faq2"
                  class="accordion-collapse collapse"
                  data-bs-parent="#faqAccordion"
                >
                  <div class="accordion-body">
                    Yes. Every plan â€” Monthly, 3-Month, Annual, Lifetime, and
                    Custom â€” includes full access to all modules with no user
                    limits. There are no hidden modules behind premium tiers.
                    The difference between plans is duration, support level, and
                    license ownership.
                  </div>
                </div>
              </div>
              <div class="accordion-item reveal reveal-delay-2">
                <h2 class="accordion-header">
                  <button
                    class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#faq3"
                  >
                    How does activation work and how long does it take?
                  </button>
                </h2>
                <div
                  id="faq3"
                  class="accordion-collapse collapse"
                  data-bs-parent="#faqAccordion"
                >
                  <div class="accordion-body">
                    It's simple: Sign Up â†’ Choose Industry â†’ Wait 30 Minutes â†’
                    System Trial Ready. Your dedicated infrastructure is
                    provisioned in ~30 minutes. Once ready, you have full access
                    to all modules for 30 days, completely free.
                  </div>
                </div>
              </div>
              <div class="accordion-item reveal reveal-delay-3">
                <h2 class="accordion-header">
                  <button
                    class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#faq4"
                  >
                    Is my data truly isolated from other customers?
                  </button>
                </h2>
                <div
                  id="faq4"
                  class="accordion-collapse collapse"
                  data-bs-parent="#faqAccordion"
                >
                  <div class="accordion-body">
                    Yes. Every COOCA customer operates in a fully isolated
                    environment. Your data never touches another customer's
                    system. This is not shared-tenant architecture â€” it's
                    dedicated infrastructure for every business. That's our "1
                    Customer = 1 Isolated Business System" commitment.
                  </div>
                </div>
              </div>
              <div class="accordion-item reveal reveal-delay-4">
                <h2 class="accordion-header">
                  <button
                    class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#faq5"
                  >
                    How does the affiliate program work?
                  </button>
                </h2>
                <div
                  id="faq5"
                  class="accordion-collapse collapse"
                  data-bs-parent="#faqAccordion"
                >
                  <div class="accordion-body">
                    You receive a unique referral link. When someone signs up
                    through your link and becomes a customer, you earn up to 30%
                    commission. We provide marketing materials, a tracking
                    dashboard, and monthly payouts. Some of our affiliates earn
                    more than a full-time salary.
                  </div>
                </div>
              </div>
              <div class="accordion-item reveal reveal-delay-5">
                <h2 class="accordion-header">
                  <button
                    class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#faq6"
                  >
                    What if I need help migrating from my current system?
                  </button>
                </h2>
                <div
                  id="faq6"
                  class="accordion-collapse collapse"
                  data-bs-parent="#faqAccordion"
                >
                  <div class="accordion-body">
                    We provide built-in import tools plus guided migration
                    support for Annual and Lifetime plans. Our team ensures zero
                    data loss and minimal downtime. Most migrations from legacy
                    systems complete within 3â€“5 business days.
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CONTACT -->
    <section
      class="section-padding"
      id="contact"
      style="background: var(--card-alt)"
    >
      <div class="container">
        <div class="text-center">
          <div class="section-label reveal">
            <i class="bi bi-envelope-fill"></i> Contact
          </div>
          <h2 class="section-title reveal reveal-delay-1">
            Talk to <span class="text-gradient">Our Team</span>
          </h2>
          <p class="section-subtitle reveal reveal-delay-2">
            Not sure which path is right for your business? Let's discuss your
            situation.
          </p>
        </div>
        <div class="row g-5 justify-content-center">
          <div class="col-lg-5 reveal">
            <form class="contact-form" id="contactForm">
              <div class="mb-3">
                <input
                  type="text"
                  class="form-control"
                  placeholder="Your Name"
                  required
                />
              </div>
              <div class="mb-3">
                <input
                  type="email"
                  class="form-control"
                  placeholder="Email Address"
                  required
                />
              </div>
              <div class="mb-3">
                <input
                  type="text"
                  class="form-control"
                  placeholder="Company Name"
                />
              </div>
              <div class="mb-4">
                <textarea
                  class="form-control"
                  rows="5"
                  placeholder="Tell us about your business and what you're looking for..."
                  required
                ></textarea>
              </div>
              <button
                type="submit"
                class="btn-cooca btn-cooca-primary"
                style="width: 100%; justify-content: center"
              >
                Send Message <i class="bi bi-send"></i>
              </button>
            </form>
          </div>
          <div class="col-lg-4 reveal reveal-delay-2">
            <div class="contact-info-item">
              <div class="ci-icon"><i class="bi bi-envelope"></i></div>
              <div>
                <div class="ci-title">{{ __('Email') }}</div>
                <p class="ci-text">{{ setting('contact.email', 'support@cooca.io') }}</p>
              </div>
            </div>
            <div class="contact-info-item">
              <div class="ci-icon"><i class="bi bi-whatsapp"></i></div>
              <div>
                <div class="ci-title">{{ __('WhatsApp') }}</div>
                <p class="ci-text">{{ setting('contact.whatsapp', '+62 812 3456 7890') }}</p>
              </div>
            </div>
            <div class="contact-info-item">
              <div class="ci-icon"><i class="bi bi-geo-alt"></i></div>
              <div>
                <div class="ci-title">{{ __('Office') }}</div>
                <p class="ci-text">
                  {!! setting('contact.address', 'Jl. Sudirman Kav. 52-53<br />Jakarta Selatan 12190') !!}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- FINAL CTA -->
    <section class="final-cta" id="finalcta">
      <div class="final-cta-bg"></div>
      <div
        class="floating-shape"
        style="
          width: 200px;
          height: 200px;
          top: 10%;
          left: 5%;
          animation: float 8s ease-in-out infinite;
        "
      ></div>
      <div
        class="floating-shape"
        style="
          width: 150px;
          height: 150px;
          top: 60%;
          right: 10%;
          animation: float-delay 6s ease-in-out 1s infinite;
        "
      ></div>
      <div class="container text-center position-relative" style="z-index: 2">
        <h2 class="reveal" style="font-size: clamp(2rem, 4vw, 3.2rem)">
          Ready to Own Your
          <span class="text-gradient">Business Infrastructure?</span>
        </h2>
        <p
          class="reveal reveal-delay-1"
          style="max-width: 550px; margin: 16px auto 36px; font-size: 1.1rem"
        >
          Join 10,000+ businesses that stopped renting software and started
          owning their systems. Start your 30-day free trial today.
        </p>
        <div
          class="d-flex flex-wrap justify-content-center gap-3 reveal reveal-delay-2"
        >
          <a
            href="{{ route('pricing') }}"
            class="btn-cooca btn-cooca-primary"
            style="padding: 16px 40px; font-size: 1rem"
            >{{ __('Get 30-Day Free Trial') }} <i class="bi bi-arrow-right"></i
          ></a>
          <a
            href="{{ route('contact') }}"
            class="btn-cooca btn-cooca-outline"
            style="padding: 16px 40px; font-size: 1rem"
            >{{ __('Talk to Sales') }} <i class="bi bi-chat-dots"></i
          ></a>
        </div>
      </div>
    </section>

    <!-- UNIFIED FOOTER -->
@endsection
