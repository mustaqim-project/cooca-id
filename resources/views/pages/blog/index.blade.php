@extends('layouts.guest')

@section('title', 'Blog - ' . ($setting->company_name ?? config('app.name')))

@section('content')




<!-- HERO -->
<section class="page-hero">
    <div class="page-hero-orb" style="width:500px;height:500px;background:var(--primary);top:-150px;right:-100px;"></div>
    <div class="page-hero-orb" style="width:300px;height:300px;background:var(--accent);bottom:-80px;left:-60px;"></div>
    <div class="grid-bg"></div>
    <div class="container text-center position-relative" style="z-index:2;">
        <div class="badge-pill reveal mb-4"><i class="bi bi-journal-richtext"></i> {{ setting('blog.hero.badge', 'Business Insights') }}</div>
        <h1 style="font-size:clamp(2.4rem,5vw,4rem);" class="reveal rv1">{!! setting('blog.hero.title', 'Guides for Businesses That <span class="text-gradient">Play to Win</span>') !!}</h1>
        <p style="font-size:1.1rem;max-width:560px;margin:20px auto 0;" class="reveal rv2">{!! setting('blog.hero.subtitle', 'Practical strategies, industry benchmarks, and operational playbooks — written for operators, not consultants.') !!}</p>
    </div>
</section>

<section class="sec">
    <div class="container">
        <!-- FEATURED -->
        <div class="row mb-5">
            <div class="col-12 reveal">
                <div class="featured-card card-c">
                    <div class="row g-0 align-items-center">
                        <div class="col-lg-5">
                            <div class="featured-thumb" style="background:linear-gradient(135deg,rgba(37,99,235,.15),rgba(56,189,248,.1));">📊</div>
                        </div>
                        <div class="col-lg-7">
                            <div class="featured-body">
                                <span class="blog-cat" style="background:rgba(37,99,235,.12);color:var(--primary);">Featured</span>
                                <h2 style="font-size:1.6rem;margin-bottom:12px;"><a href="#" style="color:var(--text);">The Real Cost of Running Your Business on 7 Different SaaS Tools</a></h2>
                                <p style="margin-bottom:20px;">Most business owners underestimate how much fragmented software actually costs — in cash, time, and the hidden errors that slip through the gaps between systems. We ran the numbers for three common business profiles.</p>
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                    <div class="blog-author">
                                        <div class="author-avatar">AR</div>
                                        <span style="font-size:.85rem;color:var(--text-muted);">Arif Rahman · 12 min read</span>
                                    </div>
                                    <a href="#" class="btn-cooca btn-primary-c btn-sm-c">Read Article <i class="bi bi-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FILTER -->
        <div class="cat-filter reveal">
            <button class="cat-btn active">All</button>
            <button class="cat-btn">Business Strategy</button>
            <button class="cat-btn">Industry Guides</button>
            <button class="cat-btn">Technology</button>
            <button class="cat-btn">Finance & Revenue</button>
            <button class="cat-btn">Operations</button>
            <button class="cat-btn">Product Updates</button>
        </div>

        <div class="row g-4">
            <!-- MAIN POSTS -->
            <div class="col-lg-8">
                <div class="row g-4">
                    <div class="col-md-6 reveal">
                        <div class="blog-card card-c">
                            <div class="blog-thumb" style="background:linear-gradient(135deg,rgba(245,158,11,.1),rgba(239,68,68,.08));">🍜</div>
                            <div class="blog-body">
                                <span class="blog-cat" style="background:rgba(245,158,11,.1);color:#F59E0B;">Industry Guide</span>
                                <div class="blog-title"><a href="#">How Top-Performing Restaurants Use Real-Time Data to Cut Food Waste by 30%</a></div>
                                <p class="blog-excerpt">Waste is a margin killer in F&B. Here's the operational system that high-performing restaurants use to track, predict, and eliminate it.</p>
                                <div class="blog-meta">
                                    <div class="blog-author"><div class="author-avatar">DK</div><span>Dian Kusuma</span></div>
                                    <span>8 min read</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 reveal rv1">
                        <div class="blog-card card-c">
                            <div class="blog-thumb" style="background:linear-gradient(135deg,rgba(16,185,129,.1),rgba(37,99,235,.08));">💰</div>
                            <div class="blog-body">
                                <span class="blog-cat" style="background:rgba(16,185,129,.1);color:#10B981;">Finance</span>
                                <div class="blog-title"><a href="#">Lifetime License vs. SaaS Subscription: A 5-Year Cost Analysis for SMBs</a></div>
                                <p class="blog-excerpt">We modeled the true 5-year cost of subscription software vs. a lifetime license for three business types. The numbers are surprising.</p>
                                <div class="blog-meta">
                                    <div class="blog-author"><div class="author-avatar">RH</div><span>Reza Hidayat</span></div>
                                    <span>10 min read</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 reveal rv2">
                        <div class="blog-card card-c">
                            <div class="blog-thumb" style="background:linear-gradient(135deg,rgba(124,58,237,.1),rgba(56,189,248,.08));">🏥</div>
                            <div class="blog-body">
                                <span class="blog-cat" style="background:rgba(124,58,237,.12);color:#7C3AED;">Industry Guide</span>
                                <div class="blog-title"><a href="#">What Every Clinic Owner Should Know Before Choosing an EMR System</a></div>
                                <p class="blog-excerpt">EMR selection isn't just about features. Compliance, data ownership, and vendor lock-in matter more than most clinic owners realize until it's too late.</p>
                                <div class="blog-meta">
                                    <div class="blog-author"><div class="author-avatar">SP</div><span>Sari Pertiwi</span></div>
                                    <span>11 min read</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 reveal rv3">
                        <div class="blog-card card-c">
                            <div class="blog-thumb" style="background:linear-gradient(135deg,rgba(56,189,248,.1),rgba(37,99,235,.08));">🤖</div>
                            <div class="blog-body">
                                <span class="blog-cat" style="background:rgba(56,189,248,.1);color:var(--accent);">Technology</span>
                                <div class="blog-title"><a href="#">How AI Is Changing Inventory Management for Multi-Outlet Retail</a></div>
                                <p class="blog-excerpt">Predictive reordering, demand forecasting, and automated purchasing decisions — this is what AI-powered inventory looks like in practice.</p>
                                <div class="blog-meta">
                                    <div class="blog-author"><div class="author-avatar">DK</div><span>Dian Kusuma</span></div>
                                    <span>7 min read</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 reveal">
                        <div class="blog-card card-c">
                            <div class="blog-thumb" style="background:linear-gradient(135deg,rgba(239,68,68,.1),rgba(245,158,11,.08));">🏨</div>
                            <div class="blog-body">
                                <span class="blog-cat" style="background:rgba(239,68,68,.1);color:#EF4444;">Industry Guide</span>
                                <div class="blog-title"><a href="#">The Hotel Occupancy Formula: How High-Margin Properties Set Dynamic Rates</a></div>
                                <p class="blog-excerpt">Revenue management isn't just for big chains. Here's the systematic approach independent hotel owners use to maximize occupancy year-round.</p>
                                <div class="blog-meta">
                                    <div class="blog-author"><div class="author-avatar">AR</div><span>Arif Rahman</span></div>
                                    <span>9 min read</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 reveal rv1">
                        <div class="blog-card card-c">
                            <div class="blog-thumb" style="background:linear-gradient(135deg,rgba(37,99,235,.12),rgba(16,185,129,.08));">📱</div>
                            <div class="blog-body">
                                <span class="blog-cat" style="background:rgba(37,99,235,.1);color:var(--primary);">Operations</span>
                                <div class="blog-title"><a href="#">WhatsApp as a Business Operating Layer: Beyond Simple Notifications</a></div>
                                <p class="blog-excerpt">Forward-thinking SMBs are using WhatsApp not just for customer messaging — but as a core business communication and automation backbone.</p>
                                <div class="blog-meta">
                                    <div class="blog-author"><div class="author-avatar">RH</div><span>Reza Hidayat</span></div>
                                    <span>6 min read</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-5 reveal">
                    <button class="btn-cooca btn-outline-c" style="padding:14px 40px;">Load More Articles <i class="bi bi-arrow-down"></i></button>
                </div>
            </div>

            <!-- SIDEBAR -->
            <div class="col-lg-4">
                <div class="sidebar-widget card-c reveal">
                    <div class="widget-title">Popular This Week</div>
                    <div class="d-flex flex-column gap-3">
                        <div style="display:flex;gap:12px;align-items:flex-start;padding-bottom:12px;border-bottom:1px solid var(--border);">
                            <div style="font-size:1.5rem;font-weight:800;color:var(--border);width:28px;flex-shrink:0;">1</div>
                            <a href="#" style="font-size:.88rem;font-weight:600;color:var(--text);line-height:1.4;">The Real Cost of Running 7 Different SaaS Tools</a>
                        </div>
                        <div style="display:flex;gap:12px;align-items:flex-start;padding-bottom:12px;border-bottom:1px solid var(--border);">
                            <div style="font-size:1.5rem;font-weight:800;color:var(--border);width:28px;flex-shrink:0;">2</div>
                            <a href="#" style="font-size:.88rem;font-weight:600;color:var(--text);line-height:1.4;">Lifetime License vs. SaaS: 5-Year Cost Analysis</a>
                        </div>
                        <div style="display:flex;gap:12px;align-items:flex-start;padding-bottom:12px;border-bottom:1px solid var(--border);">
                            <div style="font-size:1.5rem;font-weight:800;color:var(--border);width:28px;flex-shrink:0;">3</div>
                            <a href="#" style="font-size:.88rem;font-weight:600;color:var(--text);line-height:1.4;">How to Cut Restaurant Food Waste by 30%</a>
                        </div>
                        <div style="display:flex;gap:12px;align-items:flex-start;">
                            <div style="font-size:1.5rem;font-weight:800;color:var(--border);width:28px;flex-shrink:0;">4</div>
                            <a href="#" style="font-size:.88rem;font-weight:600;color:var(--text);line-height:1.4;">Hotel Occupancy: The Dynamic Rate Formula</a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-widget card-c reveal rv1">
                    <div class="widget-title">Topics</div>
                    <div class="tag-cloud">
                        <span class="tag">Retail</span><span class="tag">Restaurant</span><span class="tag">Hotel</span><span class="tag">Clinic</span><span class="tag">Revenue</span><span class="tag">Inventory</span><span class="tag">AI</span><span class="tag">Finance</span><span class="tag">Automation</span><span class="tag">WhatsApp</span><span class="tag">CRM</span><span class="tag">Payroll</span><span class="tag">Pricing Strategy</span>
                    </div>
                </div>
                <div class="sidebar-widget card-c reveal rv2" style="background:linear-gradient(135deg,rgba(37,99,235,.08),rgba(56,189,248,.04));border-color:rgba(56,189,248,.15);">
                    <div class="widget-title">Try COOCA Free</div>
                    <p style="font-size:.85rem;margin-bottom:16px;">30-day full system access. No credit card. No commitment.</p>
                    <a href="{{ route('customer.register') }}" class="btn-cooca btn-primary-c btn-sm-c" style="width:100%;justify-content:center;">Start Free Trial <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

@include('partials.newsletter')


@endsection

@push('styles')
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
            --border: rgba(56,189,248,0.12);
            --shadow: 0 8px 32px rgba(0,0,0,0.5);
            --glass: rgba(15,23,42,0.65);
            --glass-border: rgba(56,189,248,0.14);
            --radius: 16px;
            --transition: 0.35s cubic-bezier(0.4,0,0.2,1);
            --font: 'Inter', -apple-system, sans-serif;
            /* Hero gradient for dark */
            --hero-bg: linear-gradient(160deg, var(--bg) 0%, #0F172A 40%, #1E3A5F 70%, var(--bg) 100%);
            --hero-grid-color: rgba(56,189,248,0.03);
            --hero-orb-opacity: 0.1;
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
            /* Hero gradient for light */
            --hero-bg: linear-gradient(160deg, #FFFFFF 0%, #F1F5F9 30%, #E2E8F0 70%, #FFFFFF 100%);
            --hero-grid-color: rgba(37,99,235,0.08);
            --hero-orb-opacity: 0.04;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; overflow-x: hidden; }
        body {
            font-family: var(--font);
            background: var(--bg);
            color: var(--text);
            line-height: 1.7;
            -webkit-font-smoothing: antialiased;
            transition: background var(--transition), color var(--transition);
        }
        p { color: var(--text-muted); }
        h1, h2, h3, h4 { font-weight: 700; line-height: 1.25; letter-spacing: -0.02em; }
        a { color: var(--accent); text-decoration: none; transition: color var(--transition); }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: var(--bg); }
        ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 3px; }

        /* NAVBAR */
        .navbar-cooca {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1050;
            padding: 16px 0; transition: all var(--transition); background: transparent;
        }
        .navbar-cooca.scrolled {
            padding: 10px 0; background: var(--glass);
            backdrop-filter: blur(20px); border-bottom: 1px solid var(--glass-border);
        }
        .navbar-brand-cooca {
            font-size: 1.6rem; font-weight: 800; letter-spacing: -0.03em;
            color: var(--text) !important; display: flex; align-items: center; gap: 10px;
        }
        .logo-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 1.1rem; font-weight: 800;
        }
        .nav-link-cooca {
            color: var(--text-muted) !important; font-weight: 500; font-size: 0.9rem;
            padding: 8px 16px !important; transition: color var(--transition);
        }
        .nav-link-cooca:hover, .nav-link-cooca.active { color: var(--accent) !important; }
        .theme-toggle {
            width: 42px; height: 42px; border-radius: 12px; border: 1px solid var(--border);
            background: var(--card); color: var(--text); display: flex;
            align-items: center; justify-content: center; cursor: pointer;
            transition: all var(--transition); font-size: 1.1rem;
        }
        .theme-toggle:hover { border-color: var(--accent); color: var(--accent); }

        /* BUTTONS */
        .btn-cooca {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 14px 32px; border-radius: 12px; font-weight: 600;
            font-size: 0.95rem; border: none; cursor: pointer;
            transition: all var(--transition); text-decoration: none;
        }
        .btn-primary-c {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff; box-shadow: 0 4px 20px rgba(37,99,235,0.3);
        }
        .btn-primary-c:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(37,99,235,0.45); color: #fff; }
        .btn-outline-c { background: transparent; color: var(--text); border: 1px solid var(--border); }
        .btn-outline-c:hover { border-color: var(--accent); color: var(--accent); }
        .btn-sm-c { padding: 10px 22px; font-size: 0.85rem; border-radius: 10px; }

        /* BADGES */
        .badge-pill {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 14px; border-radius: 50px; font-size: 0.75rem;
            font-weight: 600; letter-spacing: 0.05em;
            background: rgba(56,189,248,0.1); border: 1px solid rgba(56,189,248,0.2);
            color: var(--accent); text-transform: uppercase;
        }
        .section-label {
            display: inline-block; font-size: 0.8rem; font-weight: 700;
            letter-spacing: 0.12em; text-transform: uppercase;
            color: var(--primary); margin-bottom: 0.5rem;
        }

        /* CARDS — base class */
        .card-c {
            background: var(--card); border: 1px solid var(--border);
            border-radius: var(--radius); box-shadow: var(--shadow);
            transition: all var(--transition);
        }
        .card-c:hover {
            border-color: rgba(56,189,248,0.25);
            box-shadow: 0 20px 60px rgba(56,189,248,0.08);
        }
        .blog-card { overflow: hidden; height: 100%; display: flex; flex-direction: column; }
        .blog-card:hover { transform: translateY(-6px); }
        .blog-thumb {
            height: 200px; display: flex; align-items: center; justify-content: center;
            font-size: 3rem; position: relative; overflow: hidden;
        }
        .blog-body { padding: 24px; flex-grow: 1; display: flex; flex-direction: column; }
        .blog-cat {
            font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.07em; padding: 4px 12px; border-radius: 50px;
            display: inline-block; margin-bottom: 12px;
        }
        .blog-title { font-size: 1.05rem; font-weight: 700; margin-bottom: 10px; line-height: 1.4; color: var(--text); }
        .blog-title a { color: var(--text); transition: color var(--transition); }
        .blog-title a:hover { color: var(--accent); }
        .blog-excerpt { font-size: 0.87rem; flex-grow: 1; margin-bottom: 16px; }
        .blog-meta { display: flex; align-items: center; justify-content: space-between; font-size: 0.78rem; color: var(--text-muted); }
        .blog-author { display: flex; align-items: center; gap: 8px; }
        .author-avatar {
            width: 28px; height: 28px; border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            display: flex; align-items: center; justify-content: center;
            font-size: 0.65rem; font-weight: 700; color: #fff; flex-shrink: 0;
        }

        .featured-card { border-radius: 24px; overflow: hidden; }
        .featured-thumb {
            height: 320px; display: flex; align-items: center; justify-content: center;
            font-size: 5rem; position: relative;
        }
        .featured-body { padding: 36px; }

        /* FORM */
        .form-control-c {
            padding: 14px 18px; border-radius: 12px; border: 1px solid var(--border);
            background: var(--card); color: var(--text); font-family: var(--font);
            font-size: 0.95rem; outline: none; transition: border-color var(--transition);
            width: 100%;
        }
        .form-control-c:focus { border-color: var(--accent); }
        .form-control-c::placeholder { color: var(--text-muted); }

        .newsletter-box {
            background: linear-gradient(135deg, rgba(37,99,235,0.1), rgba(56,189,248,0.06));
            border: 1px solid rgba(56,189,248,0.15); border-radius: 24px; padding: 48px; text-align: center;
        }
        .newsletter-input { display: flex; gap: 12px; max-width: 480px; margin: 24px auto 0; }

        .sidebar-widget { padding: 24px; margin-bottom: 24px; }
        .widget-title {
            font-size: 0.8rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.08em; color: var(--text-muted); margin-bottom: 16px;
        }
        .tag-cloud { display: flex; flex-wrap: wrap; gap: 8px; }
        .tag {
            padding: 6px 14px; border-radius: 50px; font-size: 0.78rem; font-weight: 600;
            border: 1px solid var(--border); color: var(--text-muted);
            transition: all var(--transition); cursor: pointer;
        }
        .tag:hover { border-color: var(--accent); color: var(--accent); }

        /* CATEGORY FILTER */
        .cat-filter { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 48px; }
        .cat-btn {
            padding: 8px 20px; border-radius: 50px; font-size: 0.85rem; font-weight: 600;
            cursor: pointer; transition: all var(--transition); border: 1px solid var(--border);
            background: transparent; color: var(--text-muted);
        }
        .cat-btn:hover, .cat-btn.active { background: var(--primary); border-color: var(--primary); color: #fff; }

        .text-gradient {
            background: linear-gradient(135deg, var(--accent), var(--primary), var(--secondary));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* HERO - now driven by CSS variables for each theme */
        .page-hero {
            padding: 160px 0 80px; position: relative; overflow: hidden;
            background: var(--hero-bg);
            transition: background var(--transition);
        }
        .grid-bg {
            position: absolute; inset: 0;
            background-image: linear-gradient(var(--hero-grid-color) 1px, transparent 1px),
                              linear-gradient(90deg, var(--hero-grid-color) 1px, transparent 1px);
            background-size: 60px 60px; pointer-events: none;
        }
        .page-hero-orb {
            position: absolute; border-radius: 50%; filter: blur(80px);
            opacity: var(--hero-orb-opacity); pointer-events: none;
        }
        .sec { padding: 100px 0; }
        .sec-alt { background: var(--card-alt); }

        /* FOOTER */
        .footer {
            background: var(--card); border-top: 1px solid var(--border);
            padding: 60px 0 30px;
        }
        .footer-brand {
            font-size: 1.4rem; font-weight: 800; letter-spacing: -0.02em;
            display: flex; align-items: center; gap: 10px; margin-bottom: 12px;
        }
        .footer-desc { font-size: 0.88rem; color: var(--text-muted); max-width: 280px; line-height: 1.6; }
        .footer-title {
            font-size: 0.75rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.1em; color: var(--text-muted); margin-bottom: 16px;
        }
        .footer-links { list-style: none; padding: 0; }
        .footer-links li { margin-bottom: 10px; }
        .footer-links a { color: var(--text-muted); font-size: 0.88rem; transition: color var(--transition); }
        .footer-links a:hover { color: var(--accent); }
        .footer-socials { display: flex; gap: 10px; margin-top: 20px; }
        .footer-socials a {
            width: 38px; height: 38px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            background: var(--card-alt); color: var(--text-muted);
            border: 1px solid var(--border); transition: all var(--transition);
        }
        .footer-socials a:hover { color: var(--accent); border-color: var(--accent); }
        .footer-bottom {
            margin-top: 48px; padding-top: 24px; border-top: 1px solid var(--border);
            display: flex; justify-content: space-between; flex-wrap: wrap; gap: 12px;
        }
        .footer-bottom p { font-size: 0.82rem; color: var(--text-muted); margin: 0; }

        /* ANIMATIONS */
        .reveal { opacity: 0; transform: translateY(32px); transition: opacity 0.7s cubic-bezier(.4,0,.2,1), transform 0.7s cubic-bezier(.4,0,.2,1); }
        .reveal.revealed { opacity: 1; transform: translateY(0); }
        .rv1 { transition-delay: 0.1s; }
        .rv2 { transition-delay: 0.2s; }
        .rv3 { transition-delay: 0.3s; }
        .rv4 { transition-delay: 0.4s; }

        .offcanvas-cooca {
            background: var(--glass) !important; backdrop-filter: blur(30px);
            border-left: 1px solid var(--glass-border);
        }
        .offcanvas-cooca .btn-close { filter: invert(1); }
        [data-theme="light"] .offcanvas-cooca .btn-close { filter: none; }
        .offcanvas-cooca .nav-link-cooca {
            display: block; padding: 14px 0 !important; font-size: 1rem;
            border-bottom: 1px solid var(--border);
        }

        @media (max-width: 767px) {
            .sec { padding: 60px 0; }
            .page-hero { padding: 120px 0 60px; }
            .newsletter-input { flex-direction: column; }
        }
</style>
@endpush
