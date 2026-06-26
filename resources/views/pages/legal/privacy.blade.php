@extends('layouts.guest')
@push('styles')
<style>
    /* ========== LEGAL PAGE SPECIFIC ========== */
    .legal-hero {
        padding: 140px 0 60px;
        position: relative;
        overflow: hidden;
        background: var(--hero-gradient);
        border-bottom: 1px solid var(--border);
        transition: background var(--transition);
    }
    .legal-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 16px;
        border-radius: 50px;
        font-size: .75rem;
        font-weight: 700;
        letter-spacing: .06em;
        background: rgba(16, 185, 129, .1);
        border: 1px solid rgba(16, 185, 129, .2);
        color: #10B981;
        text-transform: uppercase;
        margin-bottom: 16px;
    }
    .legal-body {
        padding: 80px 0;
    }
    .legal-section {
        margin-bottom: 56px;
        scroll-margin-top: 100px;
    }
    .legal-section h2 {
        font-size: 1.35rem;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--border);
    }
    .legal-section h3 {
        font-size: 1.05rem;
        margin: 20px 0 10px;
    }
    .highlight-box {
        background: rgba(37, 99, 235, .06);
        border: 1px solid rgba(37, 99, 235, .15);
        border-radius: 12px;
        padding: 16px 20px;
        margin: 20px 0;
    }
    .highlight-box.success {
        background: rgba(16, 185, 129, .06);
        border-color: rgba(16, 185, 129, .2);
    }
    .highlight-box.warning {
        background: rgba(245, 158, 11, .06);
        border-color: rgba(245, 158, 11, .2);
    }
    .highlight-box p {
        margin: 0;
        font-size: .9rem;
    }
    /* ========== TOC SIDEBAR ========== */
    .toc {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 28px;
        position: sticky;
        top: 100px;
    }
    .toc-title {
        font-size: .78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: var(--text-muted);
        margin-bottom: 16px;
    }
    .toc-list {
        list-style: none;
        padding: 0;
    }
    .toc-list li {
        margin-bottom: 8px;
    }
    .toc-list a {
        font-size: .85rem;
        color: var(--text-muted);
        transition: color var(--transition);
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    .toc-list a:hover {
        color: var(--accent);
    }
    .toc-list a::before {
        content: '';
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: var(--border);
        flex-shrink: 0;
        transition: background var(--transition);
    }
    .toc-list a:hover::before {
        background: var(--accent);
    }
    @media (max-width: 991px) {
        .toc {
            display: none;
        }
    }
    @media (max-width: 767px) {
        .legal-hero {
            padding: 110px 0 40px;
        }
        .legal-body {
            padding: 50px 0;
        }
    }
</style>
@endpush
@section('content')
<!-- HERO -->
<section class="blog-hero">
    <div class="blog-hero-orb blog-hero-orb-1"></div>
    <div class="blog-hero-orb blog-hero-orb-2"></div>
    <div class="grid-bg"></div>
    <div class="container text-center position-relative" style="z-index:2;">
        <div class="badge-glow reveal mb-4">
            <i class="bi bi-shield-check-fill"></i> {{ __(setting('privacy.badge', 'Privacy')) }}
        </div>
        <h1 class="hero-title reveal reveal-delay-1">
            {{ __(setting('privacy.title', 'Privacy Policy')) }}
        </h1>
        <p class="hero-subtitle reveal reveal-delay-2" style="font-size:1.15rem;max-width:620px;margin:20px auto 0;">
            {{ __(setting('privacy.subtitle', 'We believe privacy is a right, not a checkbox. This policy explains exactly what data we collect, why we collect it, and what we\'ll never do with it — in plain language you can actually understand.')) }}
        </p>
        <p class="reveal reveal-delay-3" style="font-size:.85rem;margin-top:24px;margin-bottom:0;color:var(--text-muted);">
            <strong>{{ __('Last updated:') }}</strong> {{ __(setting('privacy.updated_date', 'June 1, 2026')) }} &nbsp;·&nbsp; <strong>{{ __('Version:') }}</strong> {{ __(setting('privacy.version', '2.2')) }} &nbsp;·&nbsp; <a href="{{ route('terms') }}" style="color:var(--accent);">{{ __('View Terms of Service') }} →</a>
        </p>
    </div>
</section>

<!-- BODY -->
<section class="legal-body">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-3">
                <div class="toc reveal">
                    <div class="toc-title"><i class="bi bi-list-ul me-2"></i>{{ __('Contents') }}</div>
                    <ul class="toc-list">
                        <li><a href="#p1">{{ __('1. Our Privacy Commitments') }}</a></li>
                        <li><a href="#p2">{{ __('2. Who We Are') }}</a></li>
                        <li><a href="#p3">{{ __('3. Data We Collect') }}</a></li>
                        <li><a href="#p4">{{ __('4. How We Use Your Data') }}</a></li>
                        <li><a href="#p5">{{ __('5. Data Isolation') }}</a></li>
                        <li><a href="#p6">{{ __('6. Data Sharing') }}</a></li>
                        <li><a href="#p7">{{ __('7. Data Retention') }}</a></li>
                        <li><a href="#p8">{{ __('8. Security') }}</a></li>
                        <li><a href="#p9">{{ __('9. Cookies') }}</a></li>
                        <li><a href="#p10">{{ __('10. Your Rights') }}</a></li>
                        <li><a href="#p11">{{ __('11. Children\'s Privacy') }}</a></li>
                        <li><a href="#p12">{{ __('12. International Transfers') }}</a></li>
                        <li><a href="#p13">{{ __('13. Changes to Policy') }}</a></li>
                        <li><a href="#p14">{{ __('14. Contact Us') }}</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-9">
                <!-- QUICK SUMMARY -->
                <div class="reveal" style="background:linear-gradient(135deg,rgba(16,185,129,.08),rgba(37,99,235,.04));border:1px solid rgba(16,185,129,.2);border-radius:16px;padding:28px;margin-bottom:48px;">
                    <h3 style="font-size:1.1rem;margin-bottom:16px;"><i class="bi bi-lightning-charge-fill me-2" style="color:#10B981;"></i>{{ __('Privacy at a Glance') }}</h3>
                    <div class="row g-3">
                        <div class="col-sm-6"><div style="display:flex;align-items:center;gap:10px;font-size:.88rem;"><i class="bi bi-check-circle-fill" style="color:#10B981;flex-shrink:0;"></i><span>{!! __('We <strong>never sell</strong> your data to third parties') !!}</span></div></div>
                        <div class="col-sm-6"><div style="display:flex;align-items:center;gap:10px;font-size:.88rem;"><i class="bi bi-check-circle-fill" style="color:#10B981;flex-shrink:0;"></i><span>{!! __('Your system is <strong>fully isolated</strong> — zero cross-tenant access') !!}</span></div></div>
                        <div class="col-sm-6"><div style="display:flex;align-items:center;gap:10px;font-size:.88rem;"><i class="bi bi-check-circle-fill" style="color:#10B981;flex-shrink:0;"></i><span>{!! __('You can <strong>export or delete</strong> your data anytime') !!}</span></div></div>
                        <div class="col-sm-6"><div style="display:flex;align-items:center;gap:10px;font-size:.88rem;"><i class="bi bi-check-circle-fill" style="color:#10B981;flex-shrink:0;"></i><span>{!! __('We collect <strong>only what\'s necessary</strong> to run the service') !!}</span></div></div>
                        <div class="col-sm-6"><div style="display:flex;align-items:center;gap:10px;font-size:.88rem;"><i class="bi bi-check-circle-fill" style="color:#10B981;flex-shrink:0;"></i><span>{!! __('Data encrypted <strong>in transit and at rest</strong>') !!}</span></div></div>
                        <div class="col-sm-6"><div style="display:flex;align-items:center;gap:10px;font-size:.88rem;"><i class="bi bi-check-circle-fill" style="color:#10B981;flex-shrink:0;"></i><span>{!! __('Compliant with <strong>Indonesian UU PDP</strong> and GDPR principles') !!}</span></div></div>
                    </div>
                </div>

                <div class="legal-section reveal" id="p1"><h2>{{ __('1. Our Privacy Commitments') }}</h2><p>{{ __('COOCA is built on a foundation of data ownership...') }}</p><ul><li><strong>{{ __('No data selling:') }}</strong> {{ __('We have never sold customer data and will never do so.') }}</li><li><strong>{{ __('No advertising profiles:') }}</strong> {{ __('We do not build advertising profiles from your business data.') }}</li><li><strong>{{ __('No unauthorized access:') }}</strong> {{ __('COOCA staff access your data only with explicit permission or for urgent security/support purposes, with logged audit trails.') }}</li><li><strong>{{ __('Full portability:') }}</strong> {{ __('You can export your data in standard formats at any time, at no charge.') }}</li><li><strong>{{ __('Right to deletion:') }}</strong> {{ __('You can request permanent deletion of all your data at any time.') }}</li></ul></div>
                <div class="legal-section reveal" id="p2"><h2>{{ __('2. Who We Are') }}</h2><p>{{ __('This Privacy Policy applies to :company ("COOCA")...', ['company' => setting('company.name', 'PT COOCA Teknologi Indonesia')]) }}</p></div>
                <div class="legal-section reveal" id="p3"><h2>{{ __('3. Data We Collect') }}</h2><p>{{ __('We collect the minimum data necessary to provide, improve, and support our Services...') }}</p></div>
                <div class="legal-section reveal" id="p4"><h2>{{ __('4. How We Use Your Data') }}</h2><p>{{ __('Your data is used exclusively for the following purposes...') }}</p></div>
                <div class="legal-section reveal" id="p5"><h2>{{ __('5. Data Isolation — Our Core Commitment') }}</h2><div class="highlight-box success"><p><i class="bi bi-shield-lock-fill me-2" style="color:#10B981;"></i><strong>{{ __('1 Customer = 1 Isolated System.') }}</strong></p></div></div>
                <div class="legal-section reveal" id="p6"><h2>{{ __('6. Data Sharing') }}</h2><p>{{ __('We do not sell, rent, or trade your personal data...') }}</p></div>
                <div class="legal-section reveal" id="p7"><h2>{{ __('7. Data Retention') }}</h2><p>{{ __('We retain data only for as long as necessary...') }}</p></div>
                <div class="legal-section reveal" id="p8"><h2>{{ __('8. Security') }}</h2><p>{{ __('We implement multiple layers of technical and organizational security measures...') }}</p></div>
                <div class="legal-section reveal" id="p9"><h2>{{ __('9. Cookies & Tracking') }}</h2><p>{{ __('Our website and platform use cookies and similar technologies...') }}</p></div>
                <div class="legal-section reveal" id="p10"><h2>{{ __('10. Your Privacy Rights') }}</h2><p>{{ __('Regardless of your location, COOCA honors the following rights for all customers...') }}</p></div>
                <div class="legal-section reveal" id="p11"><h2>{{ __('11. Children\'s Privacy') }}</h2><p>{{ __('COOCA\'s Services are designed for business use and are not directed at children under 18...') }}</p></div>
                <div class="legal-section reveal" id="p12"><h2>{{ __('12. International Data Transfers') }}</h2><p>{{ __('COOCA stores and processes data primarily within Indonesia and Singapore...') }}</p></div>
                <div class="legal-section reveal" id="p13"><h2>{{ __('13. Changes to This Policy') }}</h2><p>{{ __('We may update this Privacy Policy from time to time...') }}</p></div>
                <div class="legal-section reveal" id="p14"><h2>{{ __('14. Contact Us') }}</h2><p>{!! __('For any privacy-related questions, requests, or concerns, please contact <a href="mailto::email">:email</a>.', ['email' => setting('company.email', 'support@cooca.io')]) !!}</p></div>

                <!-- FOOTER LINKS -->
                <div class="reveal" style="display:flex;gap:12px;flex-wrap:wrap;margin-top:48px;padding-top:32px;border-top:1px solid var(--border);">
                    <a href="{{ route('terms') }}" class="btn-cooca btn-cooca-outline"><i class="bi bi-file-text"></i> {{ __('Terms of Service') }}</a>
                    <a href="{{ route('contact') }}" class="btn-cooca btn-cooca-outline"><i class="bi bi-envelope"></i> {{ __('Privacy Questions') }}</a>
                    <a href="{{ route('customer.register') }}" class="btn-cooca btn-cooca-primary"><i class="bi bi-rocket-takeoff"></i> {{ __('Start Free Trial') }}</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
