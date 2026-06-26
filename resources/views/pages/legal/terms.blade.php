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
        background: rgba(37, 99, 235, 0.06);
        border: 1px solid rgba(37, 99, 235, 0.15);
        border-radius: 12px;
        padding: 16px 20px;
        margin: 20px 0;
    }

    .highlight-box.warning {
        background: rgba(245, 158, 11, 0.06);
        border-color: rgba(245, 158, 11, 0.2);
    }

    .highlight-box.success {
        background: rgba(16, 185, 129, 0.06);
        border-color: rgba(16, 185, 129, 0.2);
    }

    .highlight-box p {
        margin: 0;
        font-size: 0.9rem;
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
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
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
        font-size: 0.85rem;
        color: var(--text-muted);
        transition: color var(--transition-fast);
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
        transition: background var(--transition-fast);
    }

    .toc-list a:hover::before {
        background: var(--accent);
    }

    .toc-list a.toc-active {
        color: var(--accent);
    }

    .toc-list a.toc-active::before {
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
<section class="blog-hero">
    <div class="blog-hero-orb blog-hero-orb-1"></div>
    <div class="blog-hero-orb blog-hero-orb-2"></div>
    <div class="grid-bg"></div>
    <div class="container text-center position-relative" style="z-index:2;">
        <div class="badge-glow reveal mb-4">
            <i class="bi bi-file-text-fill"></i> {{ __(setting('terms.badge', 'Legal')) }}
        </div>
        <h1 class="hero-title reveal reveal-delay-1">
            {{ __(setting('terms.title', 'Terms of Service')) }}
        </h1>
        <p class="hero-subtitle reveal reveal-delay-2" style="font-size:1.15rem;max-width:600px;margin:20px auto 0;">
            {{ __(setting('terms.subtitle', 'We\'ve written these terms to be clear and honest. Please read them — they define our relationship, your rights, and our obligations to you.')) }}
        </p>
        <p class="reveal reveal-delay-3" style="font-size:.85rem;margin-top:24px;margin-bottom:0;color:var(--text-muted);">
            <strong>{{ __('Last updated:') }}</strong> {{ __(setting('terms.updated_date', 'June 1, 2026')) }} &nbsp;·&nbsp; <strong>{{ __('Version:') }}</strong> {{ __(setting('terms.version', '2.4')) }} &nbsp;·&nbsp; <a href="{{ route('privacy') }}" style="color:var(--accent);">{{ __('View Privacy Policy') }} →</a>
        </p>
    </div>
</section>

<!-- ========== BODY ========== -->
<section class="legal-body">
    <div class="container">
        <div class="row g-5">
            <!-- TOC SIDEBAR -->
            <div class="col-lg-3">
                <div class="toc reveal">
                    <div class="toc-title"><i class="bi bi-list-ul me-2"></i>{{ __('Contents') }}</div>
                    <ul class="toc-list">
                        <li><a href="#s1">{{ __('1. Acceptance of Terms') }}</a></li>
                        <li><a href="#s2">{{ __('2. Definitions') }}</a></li>
                        <li><a href="#s3">{{ __('3. Account Registration') }}</a></li>
                        <li><a href="#s4">{{ __('4. License & Ownership') }}</a></li>
                        <li><a href="#s5">{{ __('5. Free Trial') }}</a></li>
                        <li><a href="#s6">{{ __('6. Payments & Billing') }}</a></li>
                        <li><a href="#s7">{{ __('7. Data & Privacy') }}</a></li>
                        <li><a href="#s8">{{ __('8. Infrastructure & Isolation') }}</a></li>
                        <li><a href="#s9">{{ __('9. Acceptable Use') }}</a></li>
                        <li><a href="#s10">{{ __('10. Intellectual Property') }}</a></li>
                        <li><a href="#s11">{{ __('11. Termination') }}</a></li>
                        <li><a href="#s12">{{ __('12. Liability Limitation') }}</a></li>
                        <li><a href="#s13">{{ __('13. Disputes') }}</a></li>
                        <li><a href="#s14">{{ __('14. Changes to Terms') }}</a></li>
                        <li><a href="#s15">{{ __('15. Contact') }}</a></li>
                    </ul>
                </div>
            </div>

            <!-- CONTENT -->
            <div class="col-lg-9">
                <div class="highlight-box success reveal">
                    <p><i class="bi bi-info-circle-fill me-2" style="color:#10B981;"></i><strong>{{ __('Plain English Summary:') }}</strong> {{ __('You own your data. We own the software. Your system is isolated from other customers. You can cancel anytime. We won\'t sell your data. Read the full terms below for the legal details.') }}</p>
                </div>

                <div class="legal-section reveal" id="s1">
                    <h2>{{ __('1. Acceptance of Terms') }}</h2>
                    <p>{{ __('By accessing or using COOCA\'s software platform, website, or any associated services (collectively, "Services"), you agree to be bound by these Terms of Service ("Terms"). These Terms constitute a legally binding agreement between you ("Customer," "User," or "you") and PT COOCA Teknologi Indonesia ("COOCA," "we," "us," or "our").') }}</p>
                    <p>{{ __('If you are entering into these Terms on behalf of a company or other legal entity, you represent that you have the authority to bind such entity to these Terms. If you do not have such authority, or if you do not agree to these Terms, you may not use the Services.') }}</p>
                    <p>{{ __('Your continued use of the Services after any modification to these Terms constitutes acceptance of the updated Terms.') }}</p>
                </div>

                <div class="legal-section reveal" id="s2">
                    <h2>{{ __('2. Definitions') }}</h2>
                    <ul>
                        <li><strong>{{ __('"Services"') }}</strong> — {{ __('The COOCA software platform, dashboard, APIs, and all related tools and infrastructure.') }}</li>
                        <li><strong>{{ __('"Customer Data"') }}</strong> — {{ __('All data submitted, uploaded, or generated through your use of the Services.') }}</li>
                        <li><strong>{{ __('"Isolated Environment"') }}</strong> — {{ __('A dedicated infrastructure instance provisioned exclusively for a single Customer account.') }}</li>
                        <li><strong>{{ __('"Lifetime License"') }}</strong> — {{ __('A perpetual, non-transferable license to use a specific version of the COOCA software platform.') }}</li>
                        <li><strong>{{ __('"Subscription Plan"') }}</strong> — {{ __('A time-limited access plan (Monthly, Quarterly, or Annual) requiring recurring payment to maintain access.') }}</li>
                        <li><strong>{{ __('"Maintenance Fee"') }}</strong> — {{ __('An annual fee paid by Lifetime License holders to receive ongoing updates and infrastructure support.') }}</li>
                        <li><strong>{{ __('"Affiliate"') }}</strong> — {{ __('An approved partner participating in the COOCA affiliate referral program.') }}</li>
                    </ul>
                </div>

                <div class="legal-section reveal" id="s3">
                    <h2>{{ __('3. Account Registration') }}</h2>
                    <p>{{ __('To use the Services, you must create an account and provide accurate, complete, and up-to-date information. You are responsible for:') }}</p>
                    <ul>
                        <li>{{ __('Maintaining the confidentiality of your account credentials') }}</li>
                        <li>{{ __('All activity that occurs under your account') }}</li>
                        <li>{!! __('Notifying COOCA immediately of any unauthorized access at <a href="mailto:security@cooca.io">security@cooca.io</a>') !!}</li>
                        <li>{{ __('Ensuring your contact information remains current') }}</li>
                    </ul>
                    <p>{{ __('You may not create accounts for others without their consent, share login credentials, or use another user\'s account without authorization.') }}</p>
                    <div class="highlight-box">
                        <p><i class="bi bi-shield-fill me-2" style="color:var(--accent);"></i>{{ __('Each COOCA account is domain-bound and protected by HMAC cryptographic validation. Unauthorized access attempts are logged and may result in immediate account suspension.') }}</p>
                    </div>
                </div>

                <div class="legal-section reveal" id="s4">
                    <h2>{{ __('4. License & Ownership') }}</h2>
                    <h3>{{ __('4.1 Software License') }}</h3>
                    <p>{{ __('Subject to these Terms and payment of applicable fees, COOCA grants you a limited, non-exclusive, non-transferable, non-sublicensable license to access and use the Services during your subscription period or, for Lifetime License holders, in perpetuity.') }}</p>
                    <h3>{{ __('4.2 Lifetime License') }}</h3>
                    <p>{{ __('Lifetime License holders receive a perpetual license to use the specific software version active at the time of purchase. This license:') }}</p>
                    <ul>
                        <li>{{ __('Is tied to a single domain and business entity') }}</li>
                        <li>{{ __('Cannot be transferred, resold, or sublicensed without written consent') }}</li>
                        <li>{{ __('Includes ongoing feature updates contingent upon payment of the annual Maintenance Fee') }}</li>
                        <li>{{ __('Remains valid regardless of Maintenance Fee status, but updates are suspended if the fee lapses') }}</li>
                    </ul>
                    <h3>{{ __('4.3 Your Data Ownership') }}</h3>
                    <p>{!! __('You retain full ownership of all Customer Data. COOCA does not claim any intellectual property rights over your data. We process your data solely to provide the Services as described in our <a href=":url">Privacy Policy</a>.', ['url' => route('privacy')]) !!}</p>
                    <h3>{{ __('4.4 COOCA Intellectual Property') }}</h3>
                    <p>{{ __('COOCA retains all intellectual property rights in the Services, including the software, design, code, algorithms, and documentation. Nothing in these Terms transfers COOCA\'s intellectual property to you.') }}</p>
                </div>

                <div class="legal-section reveal" id="s5">
                    <h2>{{ __('5. Free Trial') }}</h2>
                    <p>{{ __('COOCA offers a 30-day free trial with full access to all modules and unlimited users. During the trial:') }}</p>
                    <ul>
                        <li>{{ __('No payment information is required') }}</li>
                        <li>{{ __('All features available under paid plans are accessible') }}</li>
                        <li>{{ __('A dedicated isolated environment is provisioned') }}</li>
                        <li>{{ __('Data entered during the trial is retained if you convert to a paid plan') }}</li>
                        <li>{{ __('If you do not convert, your data and environment are permanently deleted 30 days after trial expiry') }}</li>
                    </ul>
                    <div class="highlight-box warning">
                        <p><i class="bi bi-exclamation-triangle-fill me-2" style="color:#F59E0B;"></i>{{ __('Export your data before trial expiry if you choose not to continue. COOCA is not liable for data loss after the 30-day post-expiry deletion window.') }}</p>
                    </div>
                </div>

                <div class="legal-section reveal" id="s6">
                    <h2>{{ __('6. Payments & Billing') }}</h2>
                    <h3>{{ __('6.1 Subscription Plans') }}</h3>
                    <p>{{ __('Subscription fees are billed in advance for the selected period (monthly, quarterly, or annually). All fees are stated in Indonesian Rupiah (IDR) unless otherwise specified.') }}</p>
                    <h3>{{ __('6.2 Lifetime License Payment') }}</h3>
                    <p>{{ __('Lifetime License fees are collected as a single one-time payment. The optional annual Maintenance Fee is billed annually from the anniversary of your license purchase.') }}</p>
                    <h3>{{ __('6.3 Refund Policy') }}</h3>
                    <p>{!! __('COOCA offers a 14-day money-back guarantee on all first payments (excluding Maintenance Fees). Refund requests must be submitted to <a href="mailto:billing@cooca.io">billing@cooca.io</a> within 14 days of the charge date. After 14 days, all fees are non-refundable.') !!}</p>
                    <h3>{{ __('6.4 Price Changes') }}</h3>
                    <p>{{ __('COOCA may adjust pricing for Subscription Plans with 30 days\' advance written notice. Lifetime License fees are fixed at the time of purchase and are not subject to increases.') }}</p>
                    <h3>{{ __('6.5 Taxes') }}</h3>
                    <p>{{ __('Prices displayed exclude applicable taxes (including PPN). You are responsible for all applicable taxes in your jurisdiction.') }}</p>
                </div>

                <div class="legal-section reveal" id="s7">
                    <h2>{{ __('7. Data & Privacy') }}</h2>
                    <p>{!! __('Your privacy is taken seriously. Our <a href=":url">Privacy Policy</a> explains in detail how we collect, use, and protect your information. Key principles:', ['url' => route('privacy')]) !!}</p>
                    <ul>
                        <li>{{ __('We never sell your data to third parties') }}</li>
                        <li>{{ __('Your data is stored in your isolated environment, not commingled with other customers') }}</li>
                        <li>{{ __('We process data only as necessary to provide the Services') }}</li>
                        <li>{{ __('You may request a full data export at any time') }}</li>
                        <li>{{ __('Upon account termination, data is deleted within 90 days unless you request earlier deletion') }}</li>
                    </ul>
                </div>

                <div class="legal-section reveal" id="s8">
                    <h2>{{ __('8. Infrastructure & Isolation') }}</h2>
                    <p>{{ __('COOCA provisions a dedicated isolated infrastructure environment for each Customer. This means:') }}</p>
                    <ul>
                        <li>{{ __('Your database, storage, and application instance are separate from all other customers') }}</li>
                        <li>{{ __('No other customer can access your data through any COOCA interface') }}</li>
                        <li>{{ __('Infrastructure isolation is maintained as a core security guarantee, not merely a feature') }}</li>
                    </ul>
                    <h3>{{ __('8.1 Uptime SLA') }}</h3>
                    <p>{{ __('COOCA commits to 99.9% monthly uptime availability. Planned maintenance windows are communicated at least 48 hours in advance. Downtime credits are available for Subscription and Lifetime customers who experience availability below the SLA threshold.') }}</p>
                    <h3>{{ __('8.2 Backups') }}</h3>
                    <p>{{ __('COOCA performs automated daily backups with a 30-day retention period. Backup restoration is available upon request for all paid plan customers.') }}</p>
                </div>

                <div class="legal-section reveal" id="s9">
                    <h2>{{ __('9. Acceptable Use') }}</h2>
                    <p>{{ __('You agree not to use the Services to:') }}</p>
                    <ul>
                        <li>{{ __('Violate any applicable laws or regulations') }}</li>
                        <li>{{ __('Store, transmit, or process illegal content') }}</li>
                        <li>{{ __('Attempt to gain unauthorized access to COOCA systems or other customers\' environments') }}</li>
                        <li>{{ __('Reverse engineer, decompile, or attempt to extract source code from the Services') }}</li>
                        <li>{{ __('Resell or redistribute access to the Services without written authorization') }}</li>
                        <li>{{ __('Conduct denial-of-service attacks, spam, or other disruptive activities') }}</li>
                        <li>{{ __('Impersonate COOCA or misrepresent your relationship with COOCA') }}</li>
                    </ul>
                    <p>{{ __('COOCA reserves the right to suspend accounts that violate this policy, with immediate effect for severe violations and written notice for minor violations.') }}</p>
                </div>

                <div class="legal-section reveal" id="s10">
                    <h2>{{ __('10. Intellectual Property') }}</h2>
                    <p>{{ __('The COOCA name, logo, platform design, and all related intellectual property are owned exclusively by PT COOCA Teknologi Indonesia. You may not use our brand assets without prior written permission.') }}</p>
                    <p>{{ __('Affiliates granted permission to use COOCA brand materials must adhere to the Affiliate Brand Guidelines and may not modify, distort, or use COOCA branding in a misleading manner.') }}</p>
                </div>

                <div class="legal-section reveal" id="s11">
                    <h2>{{ __('11. Termination') }}</h2>
                    <h3>{{ __('11.1 By You') }}</h3>
                    <p>{{ __('You may cancel your subscription at any time through your account dashboard or by contacting support. Cancellation takes effect at the end of your current billing period. Lifetime License holders may deactivate at any time; the license itself does not expire.') }}</p>
                    <h3>{{ __('11.2 By COOCA') }}</h3>
                    <p>{{ __('COOCA may suspend or terminate your account with 30 days\' written notice for any reason, or immediately for:') }}</p>
                    <ul>
                        <li>{{ __('Serious violation of these Terms or Acceptable Use Policy') }}</li>
                        <li>{{ __('Failure to pay applicable fees after a 15-day grace period') }}</li>
                        <li>{{ __('Fraudulent, abusive, or illegal activity') }}</li>
                    </ul>
                    <h3>{{ __('11.3 Effect of Termination') }}</h3>
                    <p>{{ __('Upon termination, your access to the Services ceases. Customer Data is retained for 90 days during which you may request an export. After 90 days, data is permanently deleted.') }}</p>
                </div>

                <div class="legal-section reveal" id="s12">
                    <h2>{{ __('12. Liability Limitation') }}</h2>
                    <p>{{ __('To the maximum extent permitted by applicable law, COOCA\'s liability to you for any claims arising from these Terms or the Services shall not exceed the total amount paid by you in the 12 months preceding the claim.') }}</p>
                    <p>{{ __('COOCA is not liable for indirect, incidental, consequential, or punitive damages, including lost profits, data loss, or business interruption — even if COOCA has been advised of the possibility of such damages.') }}</p>
                    <p>{{ __('Nothing in these Terms limits liability for death or personal injury caused by negligence, fraud, or any other liability that cannot be excluded by law.') }}</p>
                </div>

                <div class="legal-section reveal" id="s13">
                    <h2>{{ __('13. Disputes') }}</h2>
                    <p>{{ __('These Terms are governed by the laws of the Republic of Indonesia. Any dispute arising from these Terms shall first be addressed through good-faith negotiation between the parties.') }}</p>
                    <p>{{ __('If negotiation fails within 30 days, disputes shall be resolved by binding arbitration under the rules of the Indonesian National Arbitration Board (BANI), with proceedings in Jakarta in the Indonesian language.') }}</p>
                    <p>{{ __('Notwithstanding the above, either party may seek injunctive relief in any competent court to prevent irreparable harm.') }}</p>
                </div>

                <div class="legal-section reveal" id="s14">
                    <h2>{{ __('14. Changes to These Terms') }}</h2>
                    <p>{{ __('COOCA may update these Terms periodically. Material changes will be communicated via email and in-app notification at least 30 days before taking effect. Your continued use of the Services after the effective date constitutes acceptance.') }}</p>
                    <p>{{ __('For Lifetime License holders, material changes to terms relating to your perpetual license require your explicit written consent to apply.') }}</p>
                </div>

                <div class="legal-section reveal" id="s15">
                    <h2>{{ __('15. Contact') }}</h2>
                    <p>{{ __('For legal inquiries, please contact:') }}</p>
                    <div style="background:var(--card-alt);border:1px solid var(--border);border-radius:12px;padding:20px;margin-top:16px;">
                        <p style="margin:0;"><strong style="color:var(--text);">{{ __(setting('company.name', 'PT COOCA Teknologi Indonesia')) }}</strong><br>
                            {{ __(setting('company.address', 'Jl. Jend. Sudirman Kav. 52–53, Jakarta Selatan 12190')) }}<br>
                            <strong>{{ __('Legal:') }}</strong> <a href="mailto:{{ setting('company.legal_email', 'legal@cooca.io') }}">{{ setting('company.legal_email', 'legal@cooca.io') }}</a><br>
                            <strong>{{ __('Privacy:') }}</strong> <a href="mailto:{{ setting('company.privacy_email', 'privacy@cooca.io') }}">{{ setting('company.privacy_email', 'privacy@cooca.io') }}</a><br>
                            <strong>{{ __('Support:') }}</strong> <a href="mailto:{{ setting('company.email', 'support@cooca.io') }}">{{ setting('company.email', 'support@cooca.io') }}</a></p>
                    </div>
                </div>

                <!-- SISTER LINKS -->
                <div class="reveal" style="display:flex;gap:12px;flex-wrap:wrap;margin-top:48px;padding-top:32px;border-top:1px solid var(--border);">
                    <a href="{{ route('privacy') }}" class="btn-cooca btn-cooca-outline"><i class="bi bi-shield-check"></i> {{ __('Privacy Policy') }}</a>
                    <a href="{{ route('contact') }}" class="btn-cooca btn-cooca-outline"><i class="bi bi-chat-dots"></i> {{ __('Contact Support') }}</a>
                    <a href="{{ route('customer.register') }}" class="btn-cooca btn-cooca-primary"><i class="bi bi-rocket-takeoff"></i> {{ __('Start Free Trial') }}</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
