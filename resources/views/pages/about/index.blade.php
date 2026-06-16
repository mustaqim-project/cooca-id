@extends('layouts.guest')

@section('title', 'About - ' . ($setting->company_name ?? config('app.name')))

@section('content')





<!-- ======================== PAGE HERO (content unchanged) ======================== -->
<section class="page-hero">
    <div class="page-hero-orb" style="width:600px;height:600px;background:var(--primary);top:-200px;right:-100px;"></div>
    <div class="page-hero-orb" style="width:400px;height:400px;background:var(--accent);bottom:-100px;left:-100px;"></div>
    <div class="grid-bg"></div>
    <div class="container text-center position-relative" style="z-index:2;">
        <div class="badge-pill reveal mb-4"><i class="bi bi-building-fill"></i> {{ setting('about.hero.badge', 'Our Story') }}</div>
        <h1 style="font-size:clamp(2.4rem,5vw,4rem);" class="reveal rv1">{!! setting('about.hero.title', 'Built Because We Were <span class="text-gradient">Tired of Renting.</span>') !!}</h1>
        <p style="font-size:1.15rem;max-width:600px;margin:20px auto 0;" class="reveal rv2">{!! setting('about.hero.subtitle', 'COOCA was born from frustration — with subscription traps, fragmented tools, and software that grows your vendor\'s business more than yours.') !!}</p>
    </div>
</section>

<!-- MISSION section (unchanged) -->
<section class="sec">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 reveal">
                <div class="section-label"><i class="bi bi-bullseye"></i> {{ setting('about.mission.badge', 'Mission') }}</div>
                <h2 style="font-size:clamp(1.8rem,3.5vw,2.8rem);" class="mb-4">{!! setting('about.mission.title', 'Every Business Deserves to <span class="text-gradient">Own Its Infrastructure</span>') !!}</h2>
                <p class="mb-4">{!! setting('about.mission.p1', 'The SaaS model created a world where businesses rent the tools they depend on — indefinitely. Every month, cash flows out. Every year, the dependency deepens. And if you ever stop paying, you lose everything you built on top of it.') !!}</p>
                <p class="mb-4">{!! setting('about.mission.p2', 'COOCA flips this model. We believe business software should be an asset that appreciates, not a liability that bleeds. Our lifetime license model gives you permanent ownership with one investment — and our isolated infrastructure ensures your system belongs to you alone.') !!}</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('customer.register') }}" class="btn-cooca btn-primary-c">Start Free Trial <i class="bi bi-arrow-right"></i></a>
                    <a href="{{ route('solutions') }}" class="btn-cooca btn-outline-c">View Solutions</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6 reveal rv1"><div class="card-c" style="text-align:center;"><div class="stat-val">10K+</div><div class="stat-label">Businesses Powered</div></div></div>
                    <div class="col-6 reveal rv2"><div class="card-c" style="text-align:center;"><div class="stat-val">99.9%</div><div class="stat-label">Uptime SLA</div></div></div>
                    <div class="col-6 reveal rv3"><div class="card-c" style="text-align:center;"><div class="stat-val">500M+</div><div class="stat-label">Transactions Processed</div></div></div>
                    <div class="col-6 reveal rv4"><div class="card-c" style="text-align:center;"><div class="stat-val">9</div><div class="stat-label">Industry Verticals</div></div></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- VALUES section (unchanged) -->
<section class="sec sec-alt">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-label reveal"><i class="bi bi-gem"></i> {{ setting('about.values.badge', 'Core Values') }}</div>
            <h2 style="font-size:clamp(1.8rem,3.5vw,2.8rem);" class="reveal rv1">{!! setting('about.values.title', 'The Principles We <span class="text-gradient">Never Compromise</span>') !!}</h2>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6 reveal"><div class="card-c h-100"><div class="value-icon"><i class="bi bi-shield-fill-check"></i></div><h4 class="mb-3">{{ setting('about.values.1.title', 'Ownership First') }}</h4><p>{!! setting('about.values.1.desc', 'We believe your business system should be an asset you own — not a service you rent. Every product decision is made with ownership in mind.') !!}</p></div></div>
            <div class="col-lg-4 col-md-6 reveal rv1"><div class="card-c h-100"><div class="value-icon"><i class="bi bi-transparency"></i></div><h4 class="mb-3">{{ setting('about.values.2.title', 'Radical Transparency') }}</h4><p>{!! setting('about.values.2.desc', 'No hidden fees, no surprise upgrades, no dark patterns. Pricing is honest, contracts are simple, and support is real humans — not bots.') !!}</p></div></div>
            <div class="col-lg-4 col-md-6 reveal rv2"><div class="card-c h-100"><div class="value-icon"><i class="bi bi-lock-fill"></i></div><h4 class="mb-3">{{ setting('about.values.3.title', 'Security as Architecture') }}</h4><p>{!! setting('about.values.3.desc', 'Security isn\'t a feature we added — it\'s the foundation we built on. Isolation, encryption, and domain binding are non-negotiable defaults.') !!}</p></div></div>
            <div class="col-lg-4 col-md-6 reveal rv3"><div class="card-c h-100"><div class="value-icon"><i class="bi bi-puzzle"></i></div><h4 class="mb-3">{{ setting('about.values.4.title', 'Modular by Design') }}</h4><p>{!! setting('about.values.4.desc', 'Start small, scale intelligently. Every module is built to work alone or in concert with the others — no forced bundles, no wasted spend.') !!}</p></div></div>
            <div class="col-lg-4 col-md-6 reveal rv4"><div class="card-c h-100"><div class="value-icon"><i class="bi bi-people-fill"></i></div><h4 class="mb-3">{{ setting('about.values.5.title', 'Partner, Not Vendor') }}</h4><p>{!! setting('about.values.5.desc', 'When you grow, we grow. Our affiliate program, migration support, and dedicated success team exist because your success is our business model.') !!}</p></div></div>
            <div class="col-lg-4 col-md-6 reveal rv1"><div class="card-c h-100"><div class="value-icon"><i class="bi bi-lightning-charge-fill"></i></div><h4 class="mb-3">{{ setting('about.values.6.title', 'Speed Matters') }}</h4><p>{!! setting('about.values.6.desc', '30-minute provisioning, 24/7 support response, automated updates. Business doesn\'t wait — neither do we.') !!}</p></div></div>
        </div>
    </div>
</section>

<!-- TIMELINE (unchanged) -->
<section class="sec">
    <div class="container">
        <div class="row g-5 align-items-start">
            <div class="col-lg-4 reveal">
                <div class="section-label"><i class="bi bi-clock-history"></i> {{ setting('about.journey.badge', 'Our Journey') }}</div>
                <h2 style="font-size:clamp(1.8rem,3.5vw,2.8rem);" class="mb-4">{!! setting('about.journey.title', 'From a <span class="text-gradient">Frustrated Founder</span> to 10,000+ Businesses') !!}</h2>
                <p>{!! setting('about.journey.subtitle', 'COOCA started as a solution to a problem we lived. As the system matured, so did our conviction: businesses deserved better than the SaaS status quo.') !!}</p>
            </div>
            <div class="col-lg-8">
                <div class="timeline-v">
                    <div class="tv-item reveal"><div class="tv-dot">20</div><div class="tv-year">2020</div><div class="tv-title">The Problem Identified</div><div class="tv-desc">Our founder's retail business was running 7 different SaaS tools costing Rp12M/month. None of them talked to each other. The idea for COOCA was born.</div></div>
                    <div class="tv-item reveal rv1"><div class="tv-dot">21</div><div class="tv-year">2021</div><div class="tv-title">First Version Ships</div><div class="tv-desc">COOCA v1 launched with 3 modules (POS, Inventory, CRM) and 50 early access businesses. The lifetime license model was validated in week one.</div></div>
                    <div class="tv-item reveal rv2"><div class="tv-dot">22</div><div class="tv-year">2022</div><div class="tv-title">Isolation Architecture</div><div class="tv-desc">We rebuilt the infrastructure from scratch around a "1 customer = 1 isolated system" promise after a competitor's data breach affected 50,000 businesses.</div></div>
                    <div class="tv-item reveal rv3"><div class="tv-dot">23</div><div class="tv-year">2023</div><div class="tv-title">9 Industry Verticals</div><div class="tv-desc">Expanded from Retail to cover Restaurant, Hotel, Clinic, Education, Salon, Laundry, Workshop, and Rental with purpose-built modules for each.</div></div>
                    <div class="tv-item reveal rv4"><div class="tv-dot">24</div><div class="tv-year">2024</div><div class="tv-title">AI &amp; Automation Layer</div><div class="tv-desc">AI Assistant, Workflow Automation, and WhatsApp Integration launched. 10,000+ businesses now running on COOCA. 500M+ transactions processed.</div></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- TEAM section (unchanged) -->
<section class="sec sec-alt">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-label reveal"><i class="bi bi-person-badge-fill"></i> {{ setting('about.team.badge', 'Leadership') }}</div>
            <h2 style="font-size:clamp(1.8rem,3.5vw,2.8rem);" class="reveal rv1">{!! setting('about.team.title', 'The Team Behind <span class="text-gradient">the System</span>') !!}</h2>
            <p class="reveal rv2" style="max-width:500px;margin:16px auto 0;">{!! setting('about.team.subtitle', 'Operators, engineers, and designers who\'ve built and run businesses — and built the tools they wished they had.') !!}</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-lg-3 col-md-6 reveal"><div class="card-c team-card h-100"><div class="team-avatar">AR</div><div class="team-name">Arif Rahman</div><div class="team-role">Founder & CEO</div><p class="team-bio">Former retail owner who built COOCA after losing Rp200M to fragmented systems. Operator-turned-builder.</p></div></div>
            <div class="col-lg-3 col-md-6 reveal rv1"><div class="card-c team-card h-100"><div class="team-avatar">SP</div><div class="team-name">Sari Pertiwi</div><div class="team-role">CTO</div><p class="team-bio">10 years in enterprise security architecture. Built the isolated infrastructure that protects 10K+ business systems.</p></div></div>
            <div class="col-lg-3 col-md-6 reveal rv2"><div class="card-c team-card h-100"><div class="team-avatar">DK</div><div class="team-name">Dian Kusuma</div><div class="team-role">Head of Product</div><p class="team-bio">Previously led product at two fintech unicorns. Obsessed with making enterprise-grade tools feel simple.</p></div></div>
            <div class="col-lg-3 col-md-6 reveal rv3"><div class="card-c team-card h-100"><div class="team-avatar">RH</div><div class="team-name">Reza Hidayat</div><div class="team-role">Head of Growth</div><p class="team-bio">Scaled the affiliate program from 0 to 2,000 active partners. Believes distribution is the real product.</p></div></div>
        </div>
    </div>
</section>

<!-- CTA (unchanged) -->
<section class="sec" style="background:linear-gradient(160deg,var(--bg) 0%,#0F172A 50%,var(--bg) 100%);">
    <div class="container text-center">
        <h2 style="font-size:clamp(1.8rem,3.5vw,2.8rem);" class="reveal">{!! setting('cta.title', 'Ready to Own Your <span class="text-gradient">Business Infrastructure?</span>') !!}</h2>
        <p class="reveal rv1" style="max-width:480px;margin:16px auto 36px;">{!! setting('cta.subtitle', 'Join 10,000+ businesses that chose ownership over renting. Start your 30-day free trial — no credit card required.') !!}</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap reveal rv2">
            <a href="{{ route('customer.register') }}" class="btn-cooca btn-primary-c" style="padding:16px 40px;">Start Free Trial <i class="bi bi-arrow-right"></i></a>
            <a href="{{ route('contact') }}" class="btn-cooca btn-outline-c" style="padding:16px 40px;">{{ setting('cta.btn_outline', 'Talk to Sales') }}</a>
        </div>
    </div>
</section>

<!-- ======================== UNIFIED FOOTER ======================== -->

@endsection

@push('styles')
<style>
/* ----- DESIGN SYSTEM ROOT VARIABLES (UNIFIED) ----- */
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
            --border: rgba(56,189,248,0.12);
            --shadow: 0 8px 32px rgba(0,0,0,0.5);
            --glass: rgba(15,23,42,0.65);
            --glass-border: rgba(56,189,248,0.14);
            --radius: 16px;
            --radius-sm: 10px;
            --radius-lg: 24px;
            --transition: 0.35s cubic-bezier(0.4,0,0.2,1);
            --font: 'Inter', -apple-system, sans-serif;
        }
        [data-theme="light"] {
            --bg: #F8FAFC;
            --card: #FFFFFF;
            --card-alt: #F1F5F9;
            --text: #0F172A;
            --text-muted: #475569;
            --border: rgba(37,99,235,0.12);
            --shadow: 0 8px 32px rgba(0,0,0,0.06);
            --glass: rgba(255,255,255,0.7);
            --glass-border: rgba(37,99,235,0.1);
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
            -webkit-font-smoothing: antialiased;
            transition: background var(--transition), color var(--transition);
        }
        p {
            color: var(--text-muted);
        }
        h1, h2, h3, h4 {
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }
        a {
            color: var(--accent);
            text-decoration: none;
            transition: color var(--transition);
        }
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
        /* ----- NAVBAR (UNIFIED) ----- */
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
            border-bottom: 1px solid var(--glass-border);
            box-shadow: 0 4px 30px rgba(0,0,0,.1);
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
        }
        .nav-link-cooca {
            color: var(--text-muted) !important;
            font-weight: 500;
            font-size: .9rem;
            padding: 8px 16px !important;
            transition: color var(--transition);
            position: relative;
        }
        .nav-link-cooca:hover,
        .nav-link-cooca.active {
            color: var(--accent) !important;
        }
        /* dropdown menu (unified) */
        .dropdown-menu-custom {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            box-shadow: var(--shadow);
            padding: 8px 0;
            min-width: 180px;
        }
        .dropdown-menu-custom .dropdown-item {
            color: var(--text);
            padding: 8px 20px;
            font-size: .9rem;
            font-weight: 500;
            transition: all var(--transition);
        }
        .dropdown-menu-custom .dropdown-item:hover {
            background: var(--card-alt);
            color: var(--accent);
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
        }
        /* ----- BUTTONS (UNIFIED) ----- */
        .btn-cooca {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 600;
            font-size: .95rem;
            border: none;
            cursor: pointer;
            transition: all var(--transition);
            position: relative;
            overflow: hidden;
            text-decoration: none;
        }
        .btn-primary-c {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            box-shadow: 0 4px 20px rgba(37,99,235,.3);
        }
        .btn-primary-c:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(37,99,235,.45);
            color: #fff;
        }
        .btn-outline-c {
            background: transparent;
            color: var(--text);
            border: 1px solid var(--border);
        }
        .btn-outline-c:hover {
            border-color: var(--accent);
            color: var(--accent);
            transform: translateY(-2px);
        }
        .btn-sm-c {
            padding: 10px 22px;
            font-size: .85rem;
            border-radius: 10px;
        }
        /* ----- CARDS (UNIFIED) ----- */
        .card-c {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 32px;
            transition: all var(--transition);
            position: relative;
            overflow: hidden;
        }
        .card-c::before {
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
        .card-c:hover::before {
            opacity: 1;
        }
        .card-c:hover {
            transform: translateY(-6px);
            border-color: rgba(56,189,248,.3);
            box-shadow: 0 20px 60px rgba(56,189,248,.08), var(--shadow);
        }
        /* ----- TYPOGRAPHY & BADGES ----- */
        .text-gradient {
            background: linear-gradient(135deg, var(--accent), var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 50px;
            font-size: .75rem;
            font-weight: 600;
            letter-spacing: .05em;
            background: rgba(56,189,248,.1);
            border: 1px solid rgba(56,189,248,.2);
            color: var(--accent);
            text-transform: uppercase;
        }
        .section-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 20px;
            border-radius: 50px;
            font-size: .8rem;
            font-weight: 600;
            letter-spacing: .08em;
            background: rgba(37,99,235,.1);
            border: 1px solid rgba(37,99,235,.2);
            color: var(--primary);
            text-transform: uppercase;
            margin-bottom: 16px;
        }
        /* ----- PAGE HERO (about specific, but consistent with global hero style) ----- */
        .page-hero {
            padding: 160px 0 100px;
            position: relative;
            overflow: hidden;
            background: linear-gradient(160deg, var(--bg) 0%, #0F172A 40%, #1E3A5F 70%, var(--bg) 100%);
        }
        .page-hero .grid-bg {
            position: absolute;
            inset: 0;
            background-image: linear-gradient(rgba(56,189,248,.03) 1px, transparent 1px), linear-gradient(90deg, rgba(56,189,248,.03) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none;
        }
        .page-hero-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: .1;
            pointer-events: none;
        }
        /* ----- SECTIONS ----- */
        .sec {
            padding: 100px 0;
        }
        .sec-alt {
            background: var(--card-alt);
        }
        /* ----- STATS (unified card style) ----- */
        .stat-val {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            background: linear-gradient(135deg, var(--accent), var(--primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
        }
        .stat-label {
            font-size: .9rem;
            color: var(--text-muted);
            margin-top: 8px;
        }
        /* ----- TEAM CARDS ----- */
        .team-card {
            text-align: center;
        }
        .team-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 16px;
            border: 2px solid var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, rgba(37,99,235,.2), rgba(56,189,248,.2));
            color: var(--accent);
        }
        .team-name {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .team-role {
            font-size: .82rem;
            color: var(--accent);
        }
        .team-bio {
            font-size: .82rem;
            margin-top: 8px;
        }
        /* ----- TIMELINE ----- */
        .timeline-v {
            position: relative;
            padding-left: 32px;
        }
        .timeline-v::before {
            content: '';
            position: absolute;
            left: 12px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, var(--accent), var(--primary), transparent);
        }
        .tv-item {
            position: relative;
            margin-bottom: 40px;
        }
        .tv-dot {
            position: absolute;
            left: -32px;
            top: 4px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: .65rem;
            font-weight: 700;
        }
        .tv-year {
            font-size: .75rem;
            color: var(--accent);
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .tv-title {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .tv-desc {
            font-size: .85rem;
            color: var(--text-muted);
        }
        /* ----- VALUES ICON ----- */
        .value-icon {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            background: linear-gradient(135deg, rgba(37,99,235,.15), rgba(56,189,248,.15));
            border: 1px solid rgba(56,189,248,.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            color: var(--accent);
            margin-bottom: 20px;
        }
        /* ----- FOOTER (UNIFIED) ----- */
        .footer {
            background: var(--card);
            border-top: 1px solid var(--border);
            padding: 60px 0 30px;
        }
        .footer-brand {
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: -.02em;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }
        .footer-desc {
            font-size: .88rem;
            color: var(--text-muted);
            max-width: 280px;
            line-height: 1.6;
        }
        .footer-title {
            font-size: .75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: var(--text-muted);
            margin-bottom: 16px;
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
            font-size: .88rem;
            transition: color var(--transition);
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
            transition: all var(--transition);
            font-size: 1rem;
        }
        .footer-socials a:hover {
            color: var(--accent);
            border-color: var(--accent);
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
            font-size: .82rem;
            color: var(--text-muted);
            margin: 0;
        }
        /* ----- REVEAL ANIMATION (UNIFIED) ----- */
        .reveal {
            opacity: 0;
            transform: translateY(32px);
            transition: opacity .7s cubic-bezier(.4,0,.2,1), transform .7s cubic-bezier(.4,0,.2,1);
        }
        .reveal.revealed {
            opacity: 1;
            transform: translateY(0);
        }
        .rv1 { transition-delay: .1s; }
        .rv2 { transition-delay: .2s; }
        .rv3 { transition-delay: .3s; }
        .rv4 { transition-delay: .4s; }
        /* ----- MOBILE OFF CANVAS (UNIFIED) ----- */
        .offcanvas-cooca {
            background: var(--glass) !important;
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border-left: 1px solid var(--glass-border);
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
        /* ----- RESPONSIVE RULES ----- */
        @media(max-width: 767px) {
            .sec { padding: 60px 0; }
            .page-hero { padding: 120px 0 70px; }
        }
</style>
@endpush
