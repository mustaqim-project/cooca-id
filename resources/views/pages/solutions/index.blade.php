@extends('layouts.guest')

@section('title', 'Solution - ' . ($setting->company_name ?? config('app.name')))

@section('content')

    

    <!-- =====================================
         MOBILE OFFCANVAS
         ===================================== -->
    

    <!-- =====================================
         HERO
         ===================================== -->
    <section class="hero-section">
        <div class="hero-bg-orb hero-bg-orb-1"></div>
        <div class="hero-bg-orb hero-bg-orb-2"></div>
        <div class="hero-bg-orb hero-bg-orb-3"></div>
        <div class="grid-bg"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center hero-content">
                    <div class="badge-glow reveal mb-4">
                        <i class="bi bi-grid-3x3-gap-fill"></i> {{ setting('solutions.hero.badge', 'Industry Solutions') }}
                    </div>
                    <h1 class="hero-title reveal reveal-delay-1">
                        {!! setting('solutions.hero.title', 'Purpose-Built for <span class="text-gradient">Every Industry</span>') !!}
                    </h1>
                    <p class="hero-subtitle reveal reveal-delay-2" style="font-size:1.15rem;max-width:600px;margin:20px auto 36px;">
                        {!! setting('solutions.hero.subtitle', 'Nine specialized systems — each engineered to replace the fragmented tools that drain your time, cash, and sanity. One license. One infrastructure. Yours forever.') !!}
                    </p>
                    <div class="d-flex justify-content-center gap-3 flex-wrap reveal reveal-delay-3">
                        <a href="{{ route('customer.register') }}" class="btn-cooca btn-cooca-primary">Start Free Trial <i class="bi bi-arrow-right"></i></a>
                        <a href="{{ route('pricing') }}" class="btn-cooca btn-cooca-outline">View Pricing</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =====================================
         COMMERCE & RETAIL
         ===================================== -->
    <section class="section-padding">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-label reveal"><i class="bi bi-building"></i> {{ setting('solutions.retail.badge', 'Commerce & Retail') }}</div>
                <h2 class="section-title reveal reveal-delay-1">{!! setting('solutions.retail.title', 'Sell More. Manage Less. <span class="text-gradient">Own Everything.</span>') !!}</h2>
                <p class="section-subtitle reveal reveal-delay-2">{!! setting('solutions.retail.subtitle', 'From multi-outlet POS to smart inventory — retail solutions that scale with your ambition.') !!}</p>
            </div>
            <div class="row g-4">
                <!-- RETAIL -->
                <div class="col-lg-4 col-md-6 reveal">
                    <div class="solution-card">
                        <div class="solution-card-header">
                            <div class="solution-icon"><i class="bi bi-cart3"></i></div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="solution-title">COOCA for Retail</div>
                                <span class="solution-tag" style="background:rgba(37,99,235,0.15);color:var(--primary);">POS + ERP</span>
                            </div>
                            <p style="font-size:0.88rem;margin:0;">Replace fragmented POS, inventory, and loyalty tools with one unified system that tracks every transaction across every outlet in real time.</p>
                        </div>
                        <div class="solution-card-body">
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Multi-outlet POS with offline mode</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Smart inventory with auto reorder</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Customer loyalty & membership points</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Real-time sales analytics per outlet</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Integrated QRIS & payment gateway</span></div>
                            <a href="{{ route('customer.register') }}" class="btn-cooca btn-cooca-primary">Start Free Trial <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <!-- SALON -->
                <div class="col-lg-4 col-md-6 reveal reveal-delay-1">
                    <div class="solution-card">
                        <div class="solution-card-header">
                            <div class="solution-icon"><i class="bi bi-scissors"></i></div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="solution-title">COOCA for Salon & Beauty</div>
                                <span class="solution-tag" style="background:rgba(16,185,129,0.12);color:#10B981;">Booking + CRM</span>
                            </div>
                            <p style="font-size:0.88rem;margin:0;">Appointment booking, stylist management, and membership programs — turn walk-ins into loyal customers without administrative overhead.</p>
                        </div>
                        <div class="solution-card-body">
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Online & walk-in appointment booking</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Stylist schedule & commission tracking</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Product & service inventory</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>WhatsApp reminder automation</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Membership & loyalty tiers</span></div>
                            <a href="{{ route('customer.register') }}" class="btn-cooca btn-cooca-primary">Start Free Trial <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <!-- LAUNDRY -->
                <div class="col-lg-4 col-md-6 reveal reveal-delay-2">
                    <div class="solution-card">
                        <div class="solution-card-header">
                            <div class="solution-icon"><i class="bi bi-water"></i></div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="solution-title">COOCA for Laundry</div>
                                <span class="solution-tag" style="background:rgba(56,189,248,0.12);color:var(--accent);">Order + Delivery</span>
                            </div>
                            <p style="font-size:0.88rem;margin:0;">Order tracking, delivery scheduling, and customer loyalty — one system that turns daily chaos into predictable, recurring revenue.</p>
                        </div>
                        <div class="solution-card-body">
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Order intake & status tracking</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Delivery schedule & route management</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Item-level pricing & weight calculation</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>WhatsApp order-ready notification</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Customer repeat order analytics</span></div>
                            <a href="{{ route('customer.register') }}" class="btn-cooca btn-cooca-primary">Start Free Trial <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =====================================
         HOSPITALITY & SERVICES
         ===================================== -->
    <section class="section-padding" style="background:var(--card-alt);">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-label reveal"><i class="bi bi-building"></i> {{ setting('solutions.hospitality.badge', 'Hospitality & Services') }}</div>
                <h2 class="section-title reveal reveal-delay-1">{!! setting('solutions.hospitality.title', 'Every Guest. Every Room. <span class="text-gradient">Every Revenue Source.</span>') !!}</h2>
                <p class="section-subtitle reveal reveal-delay-2">{!! setting('solutions.hospitality.subtitle', 'From table to hotel to rental — service industry systems that maximize occupancy and revenue.') !!}</p>
            </div>
            <div class="row g-4">
                <!-- RESTAURANT -->
                <div class="col-lg-4 col-md-6 reveal">
                    <div class="solution-card">
                        <div class="solution-card-header">
                            <div class="solution-icon"><i class="bi bi-cup-hot"></i></div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="solution-title">COOCA for Restaurant</div>
                                <span class="solution-tag" style="background:rgba(245,158,11,0.12);color:#F59E0B;">F&B + KDS</span>
                            </div>
                            <p style="font-size:0.88rem;margin:0;">Table management, kitchen display system, and multi-outlet control — know exactly which location is profitable at any given moment.</p>
                        </div>
                        <div class="solution-card-body">
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Table & reservation management</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Kitchen Display System (KDS)</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>QR code menu & digital ordering</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Split bill & multi-payment support</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Multi-outlet P&L comparison</span></div>
                            <a href="{{ route('customer.register') }}" class="btn-cooca btn-cooca-primary">Start Free Trial <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <!-- HOTEL -->
                <div class="col-lg-4 col-md-6 reveal reveal-delay-1">
                    <div class="solution-card">
                        <div class="solution-card-header">
                            <div class="solution-icon"><i class="bi bi-building"></i></div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="solution-title">COOCA for Hotel</div>
                                <span class="solution-tag" style="background:rgba(37,99,235,0.15);color:var(--primary);">PMS + Revenue</span>
                            </div>
                            <p style="font-size:0.88rem;margin:0;">Room booking, housekeeping workflow, and revenue management — stop leaving money on the table with manual occupancy tracking.</p>
                        </div>
                        <div class="solution-card-body">
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Room booking & availability calendar</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Housekeeping task management</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Dynamic pricing & occupancy dashboard</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Guest folio & in-room charges</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Online booking integration</span></div>
                            <a href="{{ route('customer.register') }}" class="btn-cooca btn-cooca-primary">Start Free Trial <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <!-- RENTAL -->
                <div class="col-lg-4 col-md-6 reveal reveal-delay-2">
                    <div class="solution-card">
                        <div class="solution-card-header">
                            <div class="solution-icon"><i class="bi bi-key"></i></div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="solution-title">COOCA for Rental</div>
                                <span class="solution-tag" style="background:rgba(16,185,129,0.12);color:#10B981;">Asset + Contract</span>
                            </div>
                            <p style="font-size:0.88rem;margin:0;">Asset tracking, contract automation, and maintenance alerts — always know where every asset is and when it's generating revenue.</p>
                        </div>
                        <div class="solution-card-body">
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Asset registry & condition tracking</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Contract generation & e-signature</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Maintenance schedule & alerts</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Revenue per asset analytics</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Deposit & billing automation</span></div>
                            <a href="{{ route('customer.register') }}" class="btn-cooca btn-cooca-primary">Start Free Trial <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =====================================
         HEALTH & PROFESSIONAL
         ===================================== -->
    <section class="section-padding">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-label reveal"><i class="bi bi-heart-pulse-fill"></i> {{ setting('solutions.health.badge', 'Health & Professional') }}</div>
                <h2 class="section-title reveal reveal-delay-1">{!! setting('solutions.health.title', 'Compliant. Integrated. <span class="text-gradient">Finally Stress-Free.</span>') !!}</h2>
                <p class="section-subtitle reveal reveal-delay-2">{!! setting('solutions.health.subtitle', 'EMR, workshop, education — professional systems designed for compliance and operational excellence.') !!}</p>
            </div>
            <div class="row g-4">
                <!-- CLINIC -->
                <div class="col-lg-4 col-md-6 reveal">
                    <div class="solution-card">
                        <div class="solution-card-header">
                            <div class="solution-icon"><i class="bi bi-hospital"></i></div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="solution-title">COOCA for Clinic</div>
                                <span class="solution-tag" style="background:rgba(239,68,68,0.12);color:#EF4444;">EMR + Pharmacy</span>
                            </div>
                            <p style="font-size:0.88rem;margin:0;">Patient records, pharmacy, and billing — fully integrated so your staff stops juggling four different apps for a single patient visit.</p>
                        </div>
                        <div class="solution-card-body">
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Electronic Medical Records (EMR)</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Queue & appointment management</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Pharmacy & medicine inventory</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Patient billing & insurance claims</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Doctor schedule & referral tracking</span></div>
                            <a href="{{ route('customer.register') }}" class="btn-cooca btn-cooca-primary">Start Free Trial <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <!-- WORKSHOP -->
                <div class="col-lg-4 col-md-6 reveal reveal-delay-1">
                    <div class="solution-card">
                        <div class="solution-card-header">
                            <div class="solution-icon"><i class="bi bi-tools"></i></div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="solution-title">COOCA for Workshop</div>
                                <span class="solution-tag" style="background:rgba(56,189,248,0.12);color:var(--accent);">Service + Parts</span>
                            </div>
                            <p style="font-size:0.88rem;margin:0;">Service orders, spare parts management, and full customer history — nothing slips through the cracks, every job gets paid on time.</p>
                        </div>
                        <div class="solution-card-body">
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Work order creation & tracking</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Spare parts inventory & cost tracking</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Vehicle / asset service history</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Mechanic productivity reports</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Invoice & payment collection</span></div>
                            <a href="{{ route('customer.register') }}" class="btn-cooca btn-cooca-primary">Start Free Trial <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <!-- EDUCATION -->
                <div class="col-lg-4 col-md-6 reveal reveal-delay-2">
                    <div class="solution-card">
                        <div class="solution-card-header">
                            <div class="solution-icon"><i class="bi bi-mortarboard"></i></div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="solution-title">COOCA for Education</div>
                                <span class="solution-tag" style="background:rgba(124,58,237,0.15);color:#7C3AED;">SIS + Finance</span>
                            </div>
                            <p style="font-size:0.88rem;margin:0;">Student enrollment, tuition billing, and grade management — one platform that parents, teachers, and administrators actually enjoy using.</p>
                        </div>
                        <div class="solution-card-body">
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Student enrollment & profile management</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Tuition billing & payment tracking</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Attendance & grade management</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Parent portal & communication</span></div>
                            <div class="solution-feature"><i class="bi bi-check-circle-fill"></i><span>Staff payroll & scheduling</span></div>
                            <a href="{{ route('customer.register') }}" class="btn-cooca btn-cooca-primary">Start Free Trial <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =====================================
         CTA
         ===================================== -->
    <section class="section-padding" style="background:var(--card-alt);">
        <div class="container text-center">
            <h2 class="reveal" style="font-size:clamp(1.8rem,3.5vw,2.8rem);">{!! setting('solutions.cta.title', 'Not Sure Which Solution <span class="text-gradient">Fits Your Business?</span>') !!}</h2>
            <p class="reveal reveal-delay-1" style="max-width:480px;margin:16px auto 36px;">{!! setting('solutions.cta.subtitle', 'Start your free 30-day trial and explore all nine industry systems. Or talk to our team — we\'ll match you in 15 minutes.') !!}</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap reveal reveal-delay-2">
                <a href="{{ route('customer.register') }}" class="btn-cooca btn-cooca-primary" style="padding:16px 40px;">Start 30-Day Free Trial <i class="bi bi-arrow-right"></i></a>
                <a href="{{ route('about') }}" class="btn-cooca btn-cooca-outline" style="padding:16px 40px;">Talk to Sales</a>
            </div>
        </div>
    </section>

    <!-- =====================================
         FOOTER — Standardized across all pages
         ===================================== -->
    

    <!-- Bootstrap JS -->

    <!-- =====================================
         SHARED JAVASCRIPT — Theme, Navbar, Animations
         Identical across all public pages.
         ===================================== -->
@endsection

@push('styles')
<style>
/* =====================================
           UNIFIED DESIGN SYSTEM — COOCA
           Shared across all public pages.
        ===================================== */
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
            --border: rgba(56, 189, 248, 0.12);
            --shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
            --shadow-lg: 0 24px 64px rgba(0, 0, 0, 0.6);
            --glass: rgba(15, 23, 42, 0.65);
            --glass-border: rgba(56, 189, 248, 0.14);
            --radius: 16px;
            --radius-sm: 10px;
            --radius-lg: 24px;
            --transition: 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            --font: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            --hero-gradient: linear-gradient(160deg, #020617 0%, #0F172A 35%, #1E3A5F 65%, #020617 100%);
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
            --border: rgba(37, 99, 235, 0.12);
            --shadow: 0 8px 32px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 24px 64px rgba(0, 0, 0, 0.1);
            --glass: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(37, 99, 235, 0.1);
            --hero-gradient: linear-gradient(160deg, #F8FAFC 0%, #EFF6FF 35%, #DBEAFE 65%, #F8FAFC 100%);
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
            transition: background var(--transition), color var(--transition);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
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

        /* ---- Utility Classes ---- */
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
            background: linear-gradient(135deg, var(--accent), var(--primary), var(--secondary));
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
            background: rgba(56, 189, 248, 0.1);
            border: 1px solid rgba(56, 189, 248, 0.2);
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
            background: rgba(37, 99, 235, 0.1);
            border: 1px solid rgba(37, 99, 235, 0.2);
            color: var(--primary);
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

        /* ---- Button Component System ---- */
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
            background: linear-gradient(135deg, #10B981, #059669);
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
            border-color: var(--accent);
            color: var(--accent);
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

        /* ---- Card Component System ---- */
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
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--accent), transparent);
            opacity: 0;
            transition: opacity var(--transition);
        }
        .card-3d:hover::before {
            opacity: 1;
        }
        .card-3d:hover {
            transform: translateY(-8px);
            border-color: rgba(56, 189, 248, 0.3);
            box-shadow: 0 20px 60px rgba(56, 189, 248, 0.08), var(--shadow);
        }
        .card-3d .card-glow {
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(56, 189, 248, 0.05) 0%, transparent 60%);
            pointer-events: none;
            opacity: 0;
            transition: opacity var(--transition);
        }
        .card-3d:hover .card-glow {
            opacity: 1;
        }

        /* ---- Animations ---- */
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

        /* ---- Reveal Animation System ---- */
        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1), transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
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

        /* ---- Navbar ---- */
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
            text-decoration: none;
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
            text-decoration: none;
            white-space: nowrap;
        }
        .nav-link-cooca:hover,
        .nav-link-cooca.active {
            color: var(--accent) !important;
        }
        .nav-link-cooca::after {
            content: '';
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
        .nav-link-cooca:hover::after {
            width: 60%;
        }
        .nav-link-cooca.active::after {
            width: 60%;
        }

        /* ---- Theme Toggle ---- */
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
            flex-shrink: 0;
        }
        .theme-toggle:hover {
            border-color: var(--accent);
            color: var(--accent);
            transform: rotate(20deg);
        }

        /* ---- Login Dropdown ---- */
        .dropdown-menu-cooca {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 8px 0;
            box-shadow: var(--shadow-lg);
            min-width: 180px;
        }
        .dropdown-menu-cooca .dropdown-item {
            color: var(--text);
            font-size: 0.88rem;
            padding: 10px 20px;
            font-weight: 500;
            transition: all var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .dropdown-menu-cooca .dropdown-item:hover {
            background: rgba(56, 189, 248, 0.08);
            color: var(--accent);
        }
        .dropdown-menu-cooca .dropdown-item i {
            font-size: 0.9rem;
            color: var(--text-muted);
            transition: color var(--transition);
        }
        .dropdown-menu-cooca .dropdown-item:hover i {
            color: var(--accent);
        }

        /* ---- Offcanvas ---- */
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
            color: var(--text);
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
        .offcanvas-divider {
            height: 1px;
            background: var(--border);
            margin: 8px 0;
        }

        /* ---- Hero Section ---- */
        .hero-section {
            min-height: 70vh;
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
            opacity: 0.12;
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
        .grid-bg {
            position: absolute;
            inset: 0;
            background-image: linear-gradient(rgba(56, 189, 248, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(56, 189, 248, 0.03) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none;
        }

        /* ---- Solution Cards (Page-Specific) ---- */
        .solution-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            transition: all var(--transition);
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .solution-card:hover {
            transform: translateY(-8px);
            border-color: rgba(56, 189, 248, 0.3);
            box-shadow: 0 24px 64px rgba(56, 189, 248, 0.08);
        }
        .solution-card-header {
            padding: 28px 28px 20px;
            border-bottom: 1px solid var(--border);
        }
        .solution-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.15), rgba(56, 189, 248, 0.15));
            border: 1px solid rgba(56, 189, 248, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--accent);
            margin-bottom: 16px;
        }
        .solution-title {
            font-size: 1.15rem;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .solution-tag {
            font-size: 0.7rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 50px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            white-space: nowrap;
        }
        .solution-card-body {
            padding: 20px 28px 28px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .solution-feature {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 10px 0;
            border-bottom: 1px solid var(--border);
            font-size: 0.88rem;
        }
        .solution-feature:last-child {
            border-bottom: none;
        }
        .solution-feature i {
            color: var(--accent);
            flex-shrink: 0;
            margin-top: 3px;
        }
        .solution-card .btn-cooca {
            margin-top: auto;
            justify-content: center;
        }

        /* ---- Footer ---- */
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
            color: var(--text);
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
            text-decoration: none;
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
            text-decoration: none;
        }
        .footer-socials a:hover {
            color: var(--accent);
            border-color: var(--accent);
            transform: translateY(-2px);
        }

        /* ---- Responsive ---- */
        @media (max-width: 991.98px) {
            .solution-card-header .d-flex.align-items-center.justify-content-between {
                flex-wrap: wrap;
                gap: 0.5rem;
            }
        }
        @media (max-width: 767.98px) {
            .section-padding {
                padding: 60px 0;
            }
            .hero-section {
                padding-top: 100px;
                padding-bottom: 60px;
            }
            .footer-bottom {
                justify-content: center;
                text-align: center;
                flex-direction: column;
            }
        }
        @media (max-width: 575.98px) {
            .hero-cta {
                flex-direction: column;
            }
        }
</style>
@endpush
