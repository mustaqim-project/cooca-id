@extends('layouts.guest')
@push('styles')
<style>
    .badge-glow-success {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.05em;
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.25);
        color: #10B981;
        text-transform: uppercase;
    }
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
        opacity: var(--hero-orb-opacity, 0.12);
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
        content: '';
        position: absolute;
        bottom: 4px;
        left: 0;
        right: 0;
        height: 8px;
        background: rgba(16, 185, 129, 0.2);
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
    .dashboard-dot.red { background: #EF4444; }
    .dashboard-dot.yellow { background: #F59E0B; }
    .dashboard-dot.green { background: #10B981; }
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
        color: #10B981;
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
    .floating-card-1 { top: 10%; right: -30px; }
    .floating-card-2 { bottom: 15%; left: -40px; }
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
    .fc-icon.green { background: rgba(16, 185, 129, 0.15); color: #10B981; }
    .fc-icon.blue { background: rgba(56, 189, 248, 0.15); color: var(--accent); }
    .fc-icon.purple { background: rgba(124, 58, 237, 0.15); color: #A78BFA; }
    
    .counter-section {
        padding: 60px 0;
        border-top: 1px solid var(--border);
        border-bottom: 1px solid var(--border);
        background: var(--card-alt);
    }
    .pricing-card {
        text-align: center;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .pricing-card.popular {
        border-color: var(--accent);
        box-shadow: 0 0 40px rgba(56, 189, 248, 0.12);
    }
    .pricing-card.popular::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--primary), var(--accent), var(--secondary));
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
    .pricing-price .period {
        font-size: 0.8rem;
        color: var(--text-muted);
        font-weight: 400;
    }
    .pricing-desc {
        font-size: 0.85rem;
        margin-bottom: 20px;
        flex-grow: 0;
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
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.15), rgba(56, 189, 248, 0.15));
        color: var(--accent);
        border: 1px solid rgba(56, 189, 248, 0.2);
    }
    .testimonial-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .testimonial-card .testimonial-avatar {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--accent);
    }
    .testimonial-card .testimonial-stars {
        color: #F59E0B;
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
    .final-cta {
        position: relative;
        overflow: hidden;
        padding: 100px 0;
    }
    .final-cta-bg {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, var(--primary), var(--secondary), var(--accent));
        opacity: 0.07;
    }
    .final-cta .floating-shape {
        position: absolute;
        border-radius: 50%;
        background: var(--accent);
        opacity: 0.04;
    }
    .calculator-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 36px 28px;
        position: relative;
        overflow: hidden;
    }
    .calculator-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, #10B981, var(--accent), transparent);
    }
    .calculator-input {
        background: var(--card-alt);
        border: 2px solid var(--border);
        color: var(--text);
        border-radius: var(--radius-sm);
        padding: 16px 18px;
        font-size: 1.1rem;
        font-weight: 600;
        width: 100%;
        transition: all var(--transition);
        font-family: var(--font);
        letter-spacing: 0.02em;
    }
    .calculator-input:focus {
        border-color: #10B981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        outline: none;
    }
    .calculator-input::placeholder {
        color: var(--text-muted);
        font-weight: 400;
    }
    .calculator-result-card {
        background: var(--card-alt);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 20px;
        text-align: center;
        transition: all var(--transition);
    }
    .calculator-result-card.total-highlight {
        border: 2px solid #10B981;
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.06), rgba(37, 99, 235, 0.04));
        box-shadow: 0 0 30px rgba(16, 185, 129, 0.08);
    }
    .calculator-result-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--text-muted);
        margin-bottom: 6px;
    }
    .calculator-result-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--text);
    }
    .calculator-result-value.total-value {
        font-size: 2rem;
        background: linear-gradient(135deg, #10B981, var(--accent));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .commission-flow-container {
        position: relative;
        max-width: 700px;
        margin: 0 auto;
    }
    .commission-flow-row {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
        position: relative;
    }
    .commission-flow-arrow {
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent);
        font-size: 1.8rem;
        font-weight: 700;
        flex-shrink: 0;
        transition: transform 0.3s ease;
    }
    .commission-mini-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px 18px;
        text-align: center;
        min-width: 160px;
        flex-shrink: 0;
        transition: all var(--transition);
        cursor: pointer;
        position: relative;
    }
    .commission-mini-card:hover {
        border-color: var(--accent);
        box-shadow: 0 12px 28px rgba(56, 189, 248, 0.1);
        transform: translateY(-4px);
    }
    .commission-mini-card.highlight-override {
        border-color: rgba(16, 185, 129, 0.4);
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.06), rgba(37, 99, 235, 0.03));
    }
    .commission-mini-card .cmc-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-muted);
        margin-bottom: 4px;
        font-weight: 600;
    }
    .commission-mini-card .cmc-value {
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 2px;
    }
    .commission-mini-card .cmc-sub {
        font-size: 0.78rem;
        color: var(--text-muted);
        margin-top: 2px;
    }
    .commission-mini-card .cmc-icon {
        font-size: 1.8rem;
        margin-bottom: 10px;
    }
    .commission-connector {
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, var(--accent), #10B981);
        position: relative;
        margin: 8px 0;
        border-radius: 1px;
        transform-origin: left;
        animation: connector-grow 1.2s ease-out forwards;
        opacity: 0;
    }
    .commission-connector.revealed {
        opacity: 1;
        animation: connector-grow 1.2s ease-out forwards;
    }
    @keyframes connector-grow {
        from { transform: scaleX(0); }
        to { transform: scaleX(1); }
    }
    .commission-popover-icon {
        position: absolute;
        top: 8px;
        right: 8px;
        font-size: 1rem;
        color: var(--accent);
        opacity: 0.6;
        transition: opacity 0.2s;
    }
    .commission-mini-card:hover .commission-popover-icon {
        opacity: 1;
    }
    .resource-card {
        text-align: center;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        padding: 28px 20px;
    }
    .resource-card .resource-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin: 0 auto 16px;
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.12), rgba(56, 189, 248, 0.12));
        color: #10B981;
        border: 1px solid rgba(16, 185, 129, 0.2);
        transition: all var(--transition);
    }
    .resource-card:hover .resource-icon {
        background: linear-gradient(135deg, #10B981, var(--accent));
        color: #fff;
        box-shadow: 0 8px 30px rgba(16, 185, 129, 0.3);
        transform: scale(1.1);
    }
    .resource-card .resource-title {
        font-size: 0.95rem;
        font-weight: 700;
        margin-bottom: 4px;
    }
    .resource-card .resource-desc {
        font-size: 0.8rem;
        color: var(--text-muted);
    }
    .step-card-affiliate {
        text-align: center;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        padding: 36px 24px;
        position: relative;
    }
    .step-number {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, #10B981, var(--primary));
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        font-weight: 800;
        margin: 0 auto 20px;
        box-shadow: 0 8px 28px rgba(16, 185, 129, 0.25);
        position: relative;
        z-index: 2;
    }
    .step-card-affiliate .step-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 8px;
    }
    .step-card-affiliate .step-desc {
        font-size: 0.85rem;
        color: var(--text-muted);
    }
    .metric-highlight-card {
        text-align: center;
        padding: 32px 20px;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        transition: all var(--transition);
        position: relative;
        overflow: hidden;
    }
    .metric-highlight-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #10B981, var(--accent));
        opacity: 0;
        transition: opacity var(--transition);
    }
    .metric-highlight-card:hover::before {
        opacity: 1;
    }
    .metric-highlight-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow);
    }
    .metric-highlight-card .metric-icon {
        font-size: 1.6rem;
        color: #10B981;
        margin-bottom: 12px;
    }
    .metric-highlight-card .metric-value {
        font-size: clamp(1.8rem, 3vw, 2.4rem);
        font-weight: 800;
        background: linear-gradient(135deg, #10B981, var(--accent));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .metric-highlight-card .metric-label {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-top: 4px;
    }
    @media (max-width: 1199.98px) {
        .pricing-card { margin-bottom: 20px; }
        .floating-card { display: none; }
    }
    @media (max-width: 991.98px) {
        .hero-visual { margin-top: 60px; }
        .hero-dashboard { transform: none; }
        .hero-stats { gap: 24px; }
        .commission-flow-row { flex-direction: column; gap: 12px; }
        .commission-flow-arrow { transform: rotate(90deg); margin: -4px 0; }
        .commission-mini-card { min-width: 200px; width: 100%; max-width: 280px; }
        .commission-connector { max-width: 250px; margin: 8px auto; }
    }
    @media (max-width: 767.98px) {
        .dashboard-grid { grid-template-columns: 1fr; }
        .hero-stats { justify-content: center; }
        .calculator-result-value { font-size: 1.2rem; }
        .calculator-result-value.total-value { font-size: 1.6rem; }
    }
    @media (max-width: 575.98px) {
        .hero-cta { flex-direction: column; }
        .hero-cta .btn-cooca { width: 100%; justify-content: center; }
        .hero-stats { gap: 16px 28px; justify-content: flex-start; }
        .hero-stat-value { font-size: 1.5rem; }
        .hero-stat-label { font-size: 0.78rem; }
        .calculator-card { padding: 24px 16px; }
        .calculator-input { padding: 14px 16px; font-size: 1rem; }
        .commission-mini-card { padding: 16px; min-width: 160px; }
        .commission-mini-card .cmc-value { font-size: 1.2rem; }
    }
</style>
@endpush
@section('content')
<section class="hero-section" id="hero">
    <div class="hero-bg-orb hero-bg-orb-1"></div>
    <div class="hero-bg-orb hero-bg-orb-2"></div>
    <div class="hero-bg-orb hero-bg-orb-3"></div>
    <div class="grid-bg"></div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 hero-content">
                <div class="badge-glow-success reveal">
                    <i class="bi bi-cash-stack"></i> {{ __('Affiliate Partner Program') }}
                </div>
                <h1 class="hero-title reveal reveal-delay-1">
                    {!! __(setting('affiliate.hero_title', 'Earn <span class="text-gradient">Recurring Revenue</span><br>with COOCA<br><span class="highlight">Affiliate Program</span>')) !!}
                </h1>
                <p class="hero-subtitle reveal reveal-delay-2">
                    {{ __(setting('affiliate.hero_subtitle', 'Promosikan COOCA dan dapatkan komisi dari setiap customer yang berhasil Anda referensikan. Sistem komisi transparan dengan potensi penghasilan berulang setiap bulan.')) }}
                </p>
                <div class="hero-cta reveal reveal-delay-3">
                    <a href="#finalcta" class="btn-cooca btn-cooca-success">
                        {{ __('Join Affiliate Program') }} <i class="bi bi-arrow-right"></i>
                    </a>
                    <a href="#commission" class="btn-cooca btn-cooca-outline">
                        <i class="bi bi-percent"></i> {{ __('View Commission Plan') }}
                    </a>
                </div>
                <div class="hero-stats reveal reveal-delay-4">
                    <div>
                        <div class="hero-stat-value" style="color:#10B981;">{{ setting('affiliate.stat1_value', 'Up to 30%') }}</div>
                        <div class="hero-stat-label">{{ __('Commission Rate') }}</div>
                    </div>
                    <div>
                        <div class="hero-stat-value">{{ setting('affiliate.stat2_value', 'Monthly') }}</div>
                        <div class="hero-stat-label">{{ __('Payout Schedule') }}</div>
                    </div>
                    <div>
                        <div class="hero-stat-value" data-count="2500">{{ setting('affiliate.stat3_value', '2,500+') }}</div>
                        <div class="hero-stat-label">{{ __('Active Affiliates') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 hero-visual reveal reveal-delay-3">
                <div class="hero-dashboard">
                    <div class="dashboard-header">
                        <div class="dashboard-dot red"></div>
                        <div class="dashboard-dot yellow"></div>
                        <div class="dashboard-dot green"></div>
                        <span style="margin-left:12px;font-size:0.8rem;color:var(--text-muted);">{{ __('Affiliate Performance Dashboard') }}</span>
                    </div>
                    <div class="dashboard-body">
                        <div class="dashboard-grid">
                            <div class="dash-widget">
                                <div class="dash-widget-title">{{ __('Total Commission') }}</div>
                                <div class="dash-widget-value" style="color:#10B981;">Rp42.500.000</div>
                                <div class="dash-widget-change"><i class="bi bi-arrow-up-right"></i> {{ __('+18.2% growth') }}</div>
                            </div>
                            <div class="dash-widget">
                                <div class="dash-widget-title">{{ __('Active Referrals') }}</div>
                                <div class="dash-widget-value">1,247</div>
                                <div class="dash-widget-change"><i class="bi bi-arrow-up-right"></i> {{ __('All tracked') }}</div>
                            </div>
                            <div class="dash-chart">
                                <div class="dash-chart-bar" style="height:35%"></div>
                                <div class="dash-chart-bar" style="height:50%"></div>
                                <div class="dash-chart-bar" style="height:42%"></div>
                                <div class="dash-chart-bar" style="height:68%"></div>
                                <div class="dash-chart-bar" style="height:55%"></div>
                                <div class="dash-chart-bar" style="height:80%"></div>
                                <div class="dash-chart-bar" style="height:62%"></div>
                                <div class="dash-chart-bar" style="height:90%"></div>
                                <div class="dash-chart-bar" style="height:72%"></div>
                                <div class="dash-chart-bar" style="height:88%"></div>
                                <div class="dash-chart-bar" style="height:78%"></div>
                                <div class="dash-chart-bar" style="height:100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="floating-card floating-card-1 float-anim">
                    <div class="fc-icon green"><i class="bi bi-graph-up-arrow"></i></div>
                    <div class="fc-label">{{ __('Commission Rate') }}</div>
                    <div class="fc-value" style="color:#10B981;">{{ __('25% Direct') }}</div>
                </div>
                <div class="floating-card floating-card-2 float-anim-delay">
                    <div class="fc-icon purple"><i class="bi bi-people-fill"></i></div>
                    <div class="fc-label">{{ __('Team Override') }}</div>
                    <div class="fc-value">{{ __('+5% Bonus') }}</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== WHY JOIN ==================== -->
<section class="section-padding" id="why-join" style="background:var(--card-alt);">
    <div class="container">
        <div class="text-center">
            <div class="section-label reveal"><i class="bi bi-star-fill"></i> {{ __('Benefits') }}</div>
            <h2 class="section-title reveal reveal-delay-1">{!! __('Why Join <span class="text-gradient">COOCA Affiliate</span>') !!}</h2>
            <p class="section-subtitle reveal reveal-delay-2">{{ __(setting('affiliate.why_subtitle', 'Enam alasan kuat mengapa program affiliate COOCA adalah peluang penghasilan terbaik untuk Anda.')) }}</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6 reveal">
                <div class="card-3d why-card" style="height:100%;">
                    <div class="card-glow"></div>
                    <div class="why-icon"><i class="bi bi-percent"></i></div>
                    <h4>{{ __('25% Direct Commission') }}</h4>
                    <p>{{ __('Komisi langsung 25% dari setiap penjualan pribadi yang Anda hasilkan melalui referral link Anda.') }}</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 reveal reveal-delay-1">
                <div class="card-3d why-card" style="height:100%;">
                    <div class="card-glow"></div>
                    <div class="why-icon"><i class="bi bi-diagram-3"></i></div>
                    <h4>{{ __('5% Team Override') }}</h4>
                    <p>{{ __('Dapatkan tambahan 5% dari penjualan setiap affiliator level 1 yang berada di bawah jaringan Anda.') }}</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 reveal reveal-delay-2">
                <div class="card-3d why-card" style="height:100%;">
                    <div class="card-glow"></div>
                    <div class="why-icon"><i class="bi bi-arrow-repeat"></i></div>
                    <h4>{{ __('Recurring Revenue') }}</h4>
                    <p>{{ __('Komisi bersifat berulang — setiap kali customer Anda memperpanjang langganan, Anda terus mendapatkan komisi.') }}</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 reveal">
                <div class="card-3d why-card" style="height:100%;">
                    <div class="card-glow"></div>
                    <div class="why-icon"><i class="bi bi-file-earmark-zip"></i></div>
                    <h4>{{ __('Marketing Assets Ready') }}</h4>
                    <p>{{ __('Materi pemasaran lengkap — banner, landing page, brosur, email template — siap pakai untuk promosi Anda.') }}</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 reveal reveal-delay-1">
                <div class="card-3d why-card" style="height:100%;">
                    <div class="card-glow"></div>
                    <div class="why-icon"><i class="bi bi-headset"></i></div>
                    <h4>{{ __('Dedicated Partner Support') }}</h4>
                    <p>{{ __('Tim support khusus partner siap membantu Anda — dari teknis tracking hingga strategi promosi.') }}</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 reveal reveal-delay-2">
                <div class="card-3d why-card" style="height:100%;">
                    <div class="card-glow"></div>
                    <div class="why-icon"><i class="bi bi-rocket-takeoff"></i></div>
                    <h4>{{ __('High Demand SaaS Product') }}</h4>
                    <p>{{ __('COOCA adalah solusi bisnis yang dicari — memudahkan Anda menjual karena produk memiliki value kuat.') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== COMMISSION STRUCTURE ==================== -->
<section class="section-padding" id="commission">
    <div class="container">
        <div class="text-center">
            <div class="section-label reveal"><i class="bi bi-trophy-fill"></i> {{ __('Commission Tiers') }}</div>
            <h2 class="section-title reveal reveal-delay-1">{!! __('Commission <span class="text-gradient">Structure</span>') !!}</h2>
            <p class="section-subtitle reveal reveal-delay-2">{{ __(setting('affiliate.tiers_subtitle', 'Tiga tingkatan partnership yang dirancang untuk mengakomodasi berbagai level kontribusi Anda.')) }}</p>
        </div>
        <div class="row g-4 justify-content-center">
            <!-- Affiliate -->
            <div class="col-lg-4 col-md-6 reveal">
                <div class="card-3d pricing-card" style="height:100%;">
                    <div class="card-glow"></div>
                    <div class="pricing-name">{{ __('Affiliate') }}</div>
                    <div class="pricing-price"><span style="font-size:2.8rem;font-weight:900;">25%</span></div>
                    <div class="pricing-desc">{{ __('Direct Commission') }}</div>
                    <ul class="pricing-features">
                        <li><i class="bi bi-check-circle-fill"></i> {{ __('25% komisi dari penjualan pribadi') }}</li>
                        <li><i class="bi bi-check-circle-fill"></i> {{ __('Referral link unik') }}</li>
                        <li><i class="bi bi-check-circle-fill"></i> {{ __('Dashboard tracking real-time') }}</li>
                        <li><i class="bi bi-check-circle-fill"></i> {{ __('Akses marketing assets') }}</li>
                        <li><i class="bi bi-check-circle-fill"></i> {{ __('Monthly payout') }}</li>
                    </ul>
                    <a href="#finalcta" class="btn-cooca btn-cooca-outline" style="width:100%;justify-content:center;">{{ __('Join as Affiliate') }}</a>
                </div>
            </div>
            <!-- Senior Affiliate -->
            <div class="col-lg-4 col-md-6 reveal reveal-delay-1">
                <div class="card-3d pricing-card popular" style="height:100%;">
                    <div class="pricing-badge">{{ __('Most Popular') }}</div>
                    <div class="card-glow"></div>
                    <div class="pricing-name">{{ __('Senior Affiliate') }}</div>
                    <div class="pricing-price"><span style="font-size:2.8rem;font-weight:900;">25%</span><span class="period"> + 5%</span></div>
                    <div class="pricing-desc">{{ __('Direct + Override Commission') }}</div>
                    <ul class="pricing-features">
                        <li><i class="bi bi-check-circle-fill"></i> {{ __('25% komisi dari penjualan pribadi') }}</li>
                        <li><i class="bi bi-check-circle-fill"></i> <strong>{{ __('5% override') }}</strong> {{ __('dari affiliator level 1') }}</li>
                        <li><i class="bi bi-check-circle-fill"></i> {{ __('Referral link unik + tracking tim') }}</li>
                        <li><i class="bi bi-check-circle-fill"></i> {{ __('Dashboard advance dengan team view') }}</li>
                        <li><i class="bi bi-check-circle-fill"></i> {{ __('Priority partner support') }}</li>
                        <li><i class="bi bi-check-circle-fill"></i> {{ __('Monthly payout') }}</li>
                    </ul>
                    <a href="#finalcta" class="btn-cooca btn-cooca-success" style="width:100%;justify-content:center;">{{ __('Join as Senior Affiliate') }}</a>
                </div>
            </div>
            <!-- Strategic Partner -->
            <div class="col-lg-4 col-md-6 reveal reveal-delay-2">
                <div class="card-3d pricing-card" style="height:100%;border:2px dashed rgba(56,189,248,0.25);">
                    <div class="card-glow"></div>
                    <div class="pricing-name">{{ __('Strategic Partner') }} <span style="font-size:0.7rem;background:rgba(56,189,248,0.15);color:var(--accent);padding:3px 10px;border-radius:50px;margin-left:6px;">{{ __('Enterprise') }}</span></div>
                    <div class="pricing-price" style="font-size:1.5rem;">{{ __('Custom Partnership') }}</div>
                    <div class="pricing-desc">{{ __('Untuk Agency & Enterprise Partner') }}</div>
                    <ul class="pricing-features">
                        <li><i class="bi bi-check-circle-fill"></i> {{ __('Komisi kustom dinegosiasikan') }}</li>
                        <li><i class="bi bi-check-circle-fill"></i> {{ __('Dedicated account manager') }}</li>
                        <li><i class="bi bi-check-circle-fill"></i> {{ __('Co-branded marketing materials') }}</li>
                        <li><i class="bi bi-check-circle-fill"></i> {{ __('Prioritas integrasi teknis') }}</li>
                        <li><i class="bi bi-check-circle-fill"></i> {{ __('Revenue share arrangement') }}</li>
                    </ul>
                    <a href="#finalcta" class="btn-cooca btn-cooca-outline" style="width:100%;justify-content:center;">{{ __('Contact Partnership Team') }} <i class="bi bi-chat-dots"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== COMMISSION EXPLAINER (VISUAL FLOW) ==================== -->
<section class="section-padding" id="commission-explainer" style="background:var(--card-alt);">
    <div class="container">
        <div class="text-center">
            <div class="section-label reveal"><i class="bi bi-diagram-3-fill"></i> {{ __('Cara Kerja Komisi') }}</div>
            <h2 class="section-title reveal reveal-delay-1">{!! __('Visual <span class="text-gradient">Alur Komisi</span>') !!}</h2>
            <p class="section-subtitle reveal reveal-delay-2">
                {{ __('Lihat bagaimana komisi kamu dihitung — dari penjualan langsung hingga bonus tim. Klik kartu untuk detail.') }}
            </p>
        </div>
        <div class="commission-flow-container">
            <!-- Baris 1: Affiliate A Menjual -->
            <div class="commission-flow-row reveal">
                <div class="commission-mini-card" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="top" title="{{ __('Peran Kamu') }}" data-bs-content="{{ __('Sebagai Affiliate A, kamu mempromosikan COOCA dan berhasil menjual paket langganan senilai total Rp100.000.000.') }}">
                    <div class="cmc-icon">🤝</div>
                    <div class="cmc-label">{{ __('Kamu (Affiliate A)') }}</div>
                    <div class="cmc-value" style="color:var(--accent);">Rp100.000.000</div>
                    <div class="cmc-sub">{{ __('Total Penjualan Pribadi') }}</div>
                    <i class="bi bi-info-circle commission-popover-icon"></i>
                </div>
                <div class="commission-flow-arrow">→</div>
                <div class="commission-mini-card highlight-override" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="top" title="{{ __('Komisi Langsung 25%') }}" data-bs-content="{{ __('Dari penjualanmu sendiri, kamu langsung mendapat 25% komisi. Itu artinya Rp25.000.000 masuk ke saldo kamu.') }}">
                    <div class="cmc-icon">💰</div>
                    <div class="cmc-label">{{ __('Komisi Kamu (25%)') }}</div>
                    <div class="cmc-value" style="color:#10B981;">Rp25.000.000</div>
                    <div class="cmc-sub">{{ __('Direct Commission') }}</div>
                    <i class="bi bi-info-circle commission-popover-icon"></i>
                </div>
            </div>

            <!-- Konektor -->
            <div class="commission-connector reveal reveal-delay-1" style="max-width:300px;margin:8px auto;"></div>

            <!-- Baris 2: Affiliate B di bawah A menjual -->
            <div class="commission-flow-row reveal reveal-delay-1">
                <div class="commission-mini-card" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="top" title="{{ __('Tim Affiliate Kamu') }}" data-bs-content="{{ __('Affiliate B adalah orang yang kamu rekrut menjadi tim. Dia juga berjualan dan menghasilkan Rp100.000.000.') }}">
                    <div class="cmc-icon">👥</div>
                    <div class="cmc-label">{{ __('Affiliate B (Tim Kamu)') }}</div>
                    <div class="cmc-value" style="color:var(--accent);">Rp100.000.000</div>
                    <div class="cmc-sub">{{ __('Penjualan Tim') }}</div>
                    <i class="bi bi-info-circle commission-popover-icon"></i>
                </div>
                <div class="commission-flow-arrow">→</div>
                <div class="commission-mini-card" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="top" title="{{ __('Komisi Affiliate B') }}" data-bs-content="{{ __('Affiliate B juga mendapatkan 25% dari penjualannya sendiri, jadi dia dapat Rp25.000.000. Komisi ini tidak mengurangi komisi kamu.') }}">
                    <div class="cmc-icon">💸</div>
                    <div class="cmc-label">{{ __('Komisi B (25%)') }}</div>
                    <div class="cmc-value" style="color:#10B981;">Rp25.000.000</div>
                    <div class="cmc-sub">{{ __('Untuk Affiliate B') }}</div>
                    <i class="bi bi-info-circle commission-popover-icon"></i>
                </div>
            </div>

            <!-- Konektor -->
            <div class="commission-connector reveal reveal-delay-2" style="max-width:300px;margin:8px auto;background:linear-gradient(90deg,#10B981,#10B981);"></div>

            <!-- Baris 3: Override untuk A -->
            <div class="commission-flow-row reveal reveal-delay-2">
                <div class="commission-mini-card highlight-override" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="top" title="{{ __('Bonus Override 5%') }}" data-bs-content="{{ __('Karena kamu memiliki tim, kamu mendapat bonus 5% dari total penjualan Affiliate B. Itu tambahan Rp5.000.000 tanpa mengurangi komisi B.') }}">
                    <div class="cmc-icon">🎁</div>
                    <div class="cmc-label">{{ __('Bonus Override Kamu') }}</div>
                    <div class="cmc-value" style="color:#10B981;font-size:1.6rem;">+Rp5.000.000</div>
                    <div class="cmc-sub">{{ __('5% dari penjualan tim') }}</div>
                    <i class="bi bi-info-circle commission-popover-icon"></i>
                </div>
                <div class="commission-flow-arrow" style="color:#10B981;">+</div>
                <div class="commission-mini-card highlight-override" style="border:2px solid rgba(16,185,129,0.6);background:linear-gradient(135deg,rgba(16,185,129,0.12),rgba(37,99,235,0.06));" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="top" title="{{ __('Total Komisi Kamu') }}" data-bs-content="{{ __('Gabungan komisi langsung (Rp25jt) + bonus override (Rp5jt) = Rp30.000.000. Inilah kekuatan membangun jaringan!') }}">
                    <div class="cmc-icon">🚀</div>
                    <div class="cmc-label">{{ __('Total Komisi Kamu') }}</div>
                    <div class="cmc-value" style="color:#10B981;font-size:1.8rem;font-weight:900;">Rp30.000.000</div>
                    <div class="cmc-sub">{{ __('Rp25jt + Rp5jt override') }}</div>
                    <i class="bi bi-info-circle commission-popover-icon"></i>
                </div>
            </div>
        </div>
        <div class="text-center mt-4 reveal reveal-delay-3">
            <small class="text-muted">💡 <em>{{ __('Klik atau sentuh kartu untuk penjelasan lebih detail.') }}</em></small>
        </div>
    </div>
</section>

<!-- ==================== EARNINGS CALCULATOR ==================== -->
<section class="section-padding" id="calculator">
    <div class="container">
        <div class="text-center">
            <div class="section-label reveal"><i class="bi bi-calculator-fill"></i> {{ __('Earnings Simulator') }}</div>
            <h2 class="section-title reveal reveal-delay-1">{!! __('Hitung Potensi <span class="text-gradient">Komisi Anda</span>') !!}</h2>
            <p class="section-subtitle reveal reveal-delay-2">{{ __('Masukkan estimasi penjualan Anda dan lihat berapa komisi yang bisa Anda dapatkan.') }}</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="calculator-card reveal reveal-delay-1">
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label style="font-weight:600;font-size:0.9rem;margin-bottom:8px;display:block;color:var(--text);">
                                <i class="bi bi-person-fill" style="color:var(--accent);"></i> {{ __('Personal Sales (Rp)') }}
                            </label>
                            <input type="text" class="calculator-input" id="personalSales" placeholder="Contoh: 100.000.000" inputmode="numeric" aria-label="Personal Sales Amount">
                            <small style="color:var(--text-muted);font-size:0.75rem;margin-top:6px;display:block;">{{ __('Penjualan dari referral link pribadi Anda') }}</small>
                        </div>
                        <div class="col-md-6">
                            <label style="font-weight:600;font-size:0.9rem;margin-bottom:8px;display:block;color:var(--text);">
                                <i class="bi bi-people-fill" style="color:#A78BFA;"></i> {{ __('Junior Affiliate Sales (Rp)') }}
                            </label>
                            <input type="text" class="calculator-input" id="juniorSales" placeholder="Contoh: 50.000.000" inputmode="numeric" aria-label="Junior Affiliate Sales Amount">
                            <small style="color:var(--text-muted);font-size:0.75rem;margin-top:6px;display:block;">{{ __('Total penjualan affiliator level 1 di bawah Anda') }}</small>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="calculator-result-card">
                                <div class="calculator-result-label">{{ __('Personal Commission (25%)') }}</div>
                                <div class="calculator-result-value" id="personalCommission">Rp0</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="calculator-result-card">
                                <div class="calculator-result-label">{{ __('Override Commission (5%)') }}</div>
                                <div class="calculator-result-value" id="overrideCommission">Rp0</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="calculator-result-card total-highlight">
                                <div class="calculator-result-label">{{ __('Total Commission') }}</div>
                                <div class="calculator-result-value total-value" id="totalCommission">Rp0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== HOW IT WORKS ==================== -->
<section class="section-padding" id="how-it-works" style="background:var(--card-alt);">
    <div class="container">
        <div class="text-center">
            <div class="section-label reveal"><i class="bi bi-gear-wide-connected"></i> {{ __('Getting Started') }}</div>
            <h2 class="section-title reveal reveal-delay-1">{!! __('How It <span class="text-gradient">Works</span>') !!}</h2>
            <p class="section-subtitle reveal reveal-delay-2">{{ __('Empat langkah mudah untuk mulai menghasilkan sebagai COOCA Affiliate Partner.') }}</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6 reveal">
                <div class="card-3d step-card-affiliate" style="height:100%;">
                    <div class="card-glow"></div>
                    <div class="step-number">1</div>
                    <div class="step-title">{{ __('Register Affiliate Account') }}</div>
                    <p class="step-desc">{{ __('Daftar akun affiliate gratis dalam 2 menit. Isi data diri dan pilih tier partnership yang sesuai.') }}</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 reveal reveal-delay-1">
                <div class="card-3d step-card-affiliate" style="height:100%;">
                    <div class="card-glow"></div>
                    <div class="step-number">2</div>
                    <div class="step-title">{{ __('Get Your Referral Link') }}</div>
                    <p class="step-desc">{{ __('Dapatkan referral link unik Anda. Setiap klik dan konversi tercatat real-time di dashboard.') }}</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 reveal reveal-delay-2">
                <div class="card-3d step-card-affiliate" style="height:100%;">
                    <div class="card-glow"></div>
                    <div class="step-number">3</div>
                    <div class="step-title">{{ __('Promote COOCA') }}</div>
                    <p class="step-desc">{{ __('Gunakan marketing assets yang sudah disediakan — banner, landing page, konten sosial media.') }}</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 reveal reveal-delay-3">
                <div class="card-3d step-card-affiliate" style="height:100%;">
                    <div class="card-glow"></div>
                    <div class="step-number">4</div>
                    <div class="step-title">{{ __('Earn Commission') }}</div>
                    <p class="step-desc">{{ __('Terima komisi setiap bulan. Pantau performa dan penghasilan Anda melalui dashboard real-time.') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== MARKETING RESOURCES ==================== -->
<section class="section-padding" id="resources">
    <div class="container">
        <div class="text-center">
            <div class="section-label reveal"><i class="bi bi-tools"></i> {{ __('Partner Toolkit') }}</div>
            <h2 class="section-title reveal reveal-delay-1">{!! __('Marketing <span class="text-gradient">Resources</span>') !!}</h2>
            <p class="section-subtitle reveal reveal-delay-2">{{ __('Semua materi pemasaran yang Anda butuhkan — siap pakai, profesional, dan terus diperbarui.') }}</p>
        </div>
        <div class="row g-3">
            <div class="col-lg-3 col-md-4 col-6 reveal">
                <div class="card-3d resource-card" style="height:100%;">
                    <div class="card-glow"></div>
                    <div class="resource-icon"><i class="bi bi-image"></i></div>
                    <div class="resource-title">{{ __('Banner Pack') }}</div>
                    <p class="resource-desc">{{ __('Berbagai ukuran banner siap pakai') }}</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-6 reveal reveal-delay-1">
                <div class="card-3d resource-card" style="height:100%;">
                    <div class="card-glow"></div>
                    <div class="resource-icon"><i class="bi bi-file-earmark-text"></i></div>
                    <div class="resource-title">{{ __('Landing Pages') }}</div>
                    <p class="resource-desc">{{ __('Template landing page konversi tinggi') }}</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-6 reveal reveal-delay-2">
                <div class="card-3d resource-card" style="height:100%;">
                    <div class="card-glow"></div>
                    <div class="resource-icon"><i class="bi bi-book"></i></div>
                    <div class="resource-title">{{ __('Product Brochure') }}</div>
                    <p class="resource-desc">{{ __('Brosur produk lengkap & profesional') }}</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-6 reveal reveal-delay-3">
                <div class="card-3d resource-card" style="height:100%;">
                    <div class="card-glow"></div>
                    <div class="resource-icon"><i class="bi bi-share"></i></div>
                    <div class="resource-title">{{ __('Social Media Assets') }}</div>
                    <p class="resource-desc">{{ __('Konten siap posting di semua platform') }}</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-6 reveal">
                <div class="card-3d resource-card" style="height:100%;">
                    <div class="card-glow"></div>
                    <div class="resource-icon"><i class="bi bi-envelope-paper"></i></div>
                    <div class="resource-title">{{ __('Email Templates') }}</div>
                    <p class="resource-desc">{{ __('Template email marketing siap kirim') }}</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-6 reveal reveal-delay-1">
                <div class="card-3d resource-card" style="height:100%;">
                    <div class="card-glow"></div>
                    <div class="resource-icon"><i class="bi bi-easel"></i></div>
                    <div class="resource-title">{{ __('Sales Deck') }}</div>
                    <p class="resource-desc">{{ __('Presentasi penjualan profesional') }}</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-6 reveal reveal-delay-2">
                <div class="card-3d resource-card" style="height:100%;">
                    <div class="card-glow"></div>
                    <div class="resource-icon"><i class="bi bi-play-circle"></i></div>
                    <div class="resource-title">{{ __('Product Demo Video') }}</div>
                    <p class="resource-desc">{{ __('Video demo produk untuk promosi') }}</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-6 reveal reveal-delay-3">
                <div class="card-3d resource-card" style="height:100%;">
                    <div class="card-glow"></div>
                    <div class="resource-icon"><i class="bi bi-journal-check"></i></div>
                    <div class="resource-title">{{ __('Case Studies') }}</div>
                    <p class="resource-desc">{{ __('Studi kasus & success story COOCA') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== SUCCESS METRICS ==================== -->
<section class="counter-section" id="metrics" style="background:var(--card-alt);">
    <div class="container">
        <div class="text-center mb-4">
            <div class="section-label reveal"><i class="bi bi-bar-chart-line-fill"></i> {{ __('Performance') }}</div>
            <h2 class="section-title reveal reveal-delay-1">{!! __('Affiliate <span class="text-gradient">Success Metrics</span>') !!}</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-3 col-6 reveal">
                <div class="metric-highlight-card">
                    <div class="metric-icon"><i class="bi bi-people-fill"></i></div>
                    <div class="metric-value"><span class="counter" data-target="2500">0</span>+</div>
                    <div class="metric-label">{{ __('Active Affiliates') }}</div>
                </div>
            </div>
            <div class="col-md-3 col-6 reveal reveal-delay-1">
                <div class="metric-highlight-card">
                    <div class="metric-icon"><i class="bi bi-cash-stack"></i></div>
                    <div class="metric-value">Rp<span class="counter" data-target="8500">0</span>jt+</div>
                    <div class="metric-label">{{ __('Total Payout') }}</div>
                </div>
            </div>
            <div class="col-md-3 col-6 reveal reveal-delay-2">
                <div class="metric-highlight-card">
                    <div class="metric-icon"><i class="bi bi-graph-up-arrow"></i></div>
                    <div class="metric-value"><span class="counter" data-target="12.8" data-decimal="true">0</span>%</div>
                    <div class="metric-label">{{ __('Avg Conversion Rate') }}</div>
                </div>
            </div>
            <div class="col-md-3 col-6 reveal reveal-delay-3">
                <div class="metric-highlight-card">
                    <div class="metric-icon"><i class="bi bi-wallet2"></i></div>
                    <div class="metric-value">Rp<span class="counter" data-target="12">0</span>jt</div>
                    <div class="metric-label">{{ __('Avg Monthly Commission') }}</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== TESTIMONIALS ==================== -->
<section class="section-padding" id="testimonials" style="background:var(--bg);">
    <div class="container">
        <div class="text-center">
            <div class="section-label reveal"><i class="bi bi-chat-quote-fill"></i> {{ __('Partner Stories') }}</div>
            <h2 class="section-title reveal reveal-delay-1">{!! __('Affiliate <span class="text-gradient">Testimonials</span>') !!}</h2>
            <p class="section-subtitle reveal reveal-delay-2">{{ __('Cerita nyata dari affiliate partner yang telah menghasilkan bersama COOCA.') }}</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6 reveal">
                <div class="card-3d testimonial-card" style="height:100%;">
                    <div class="card-glow"></div>
                    <div class="testimonial-stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
                    <p class="testimonial-text">{{ __('"Bergabung sebagai COOCA affiliate adalah keputusan terbaik. Komisi 25% sangat kompetitif dan sistem tracking-nya transparan. Saya menghasilkan rata-rata Rp15 juta per bulan hanya dari referral."') }}</p>
                    <div class="d-flex align-items-center gap-3">
                        <img src="https://placehold.co/52x52/1E3A5F/38BDF8?text=RA" alt="Avatar" class="testimonial-avatar">
                        <div><div class="testimonial-name">Rizky Ardiansyah</div><div class="testimonial-role">{{ __('Digital Marketer & Content Creator') }}</div></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 reveal reveal-delay-1">
                <div class="card-3d testimonial-card" style="height:100%;">
                    <div class="card-glow"></div>
                    <div class="testimonial-stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
                    <p class="testimonial-text">{{ __('"Saya merekomendasikan COOCA ke klien agency saya. Produknya solid, tim support responsif, dan komisi recurring benar-benar memberikan passive income. Override commission dari tim saya tambah lagi penghasilan."') }}</p>
                    <div class="d-flex align-items-center gap-3">
                        <img src="https://placehold.co/52x52/1E40AF/38BDF8?text=MW" alt="Avatar" class="testimonial-avatar">
                        <div><div class="testimonial-name">Maya Wulandari</div><div class="testimonial-role">{{ __('Business Consultant & Agency Owner') }}</div></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 reveal reveal-delay-2">
                <div class="card-3d testimonial-card" style="height:100%;">
                    <div class="card-glow"></div>
                    <div class="testimonial-stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
                    <p class="testimonial-text">{{ __('"Awalnya saya ragu, tapi setelah 3 bulan pertama komisi saya sudah Rp28 juta. COOCA menyediakan semua materi marketing — saya tinggal promosi. Partnership experience terbaik yang pernah saya alami."') }}</p>
                    <div class="d-flex align-items-center gap-3">
                        <img src="https://placehold.co/52x52/7C3AED/F8FAFC?text=BH" alt="Avatar" class="testimonial-avatar">
                        <div><div class="testimonial-name">Budi Hartono</div><div class="testimonial-role">{{ __('Software Reseller & Komunitas Bisnis') }}</div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== FAQ ==================== -->
<section class="section-padding" id="faq" style="background:var(--card-alt);">
    <div class="container">
        <div class="text-center">
            <div class="section-label reveal"><i class="bi bi-question-circle-fill"></i> {{ __('FAQ') }}</div>
            <h2 class="section-title reveal reveal-delay-1">{!! __('Frequently Asked <span class="text-gradient">Questions</span>') !!}</h2>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion accordion-cooca" id="faqAccordion">
                    <div class="accordion-item reveal">
                        <h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">{{ __('Bagaimana cara mendapatkan komisi?') }}</button></h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion"><div class="accordion-body">{{ __('Anda akan mendapatkan referral link unik setelah mendaftar. Setiap customer yang mendaftar dan berlangganan COOCA melalui link Anda akan menghasilkan komisi 25% dari nilai langganan mereka. Komisi dihitung otomatis dan bisa Anda pantau real-time di dashboard affiliate.') }}</div></div>
                    </div>
                    <div class="accordion-item reveal reveal-delay-1">
                        <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">{{ __('Kapan komisi dibayarkan?') }}</button></h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">{{ __('Komisi dibayarkan setiap bulan pada tanggal 15, selama saldo komisi Anda mencapai minimum payout. Pembayaran dilakukan melalui transfer bank ke rekening yang Anda daftarkan. Riwayat pembayaran lengkap tersedia di dashboard Anda.') }}</div></div>
                    </div>
                    <div class="accordion-item reveal reveal-delay-2">
                        <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">{{ __('Apakah ada biaya pendaftaran affiliate?') }}</button></h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">{{ __('Tidak. Pendaftaran affiliate COOCA 100% gratis. Tidak ada biaya tersembunyi, biaya administrasi, atau biaya bulanan untuk menjadi affiliate partner. Anda cukup mendaftar, mendapatkan referral link, dan mulai mempromosikan.') }}</div></div>
                    </div>
                    <div class="accordion-item reveal reveal-delay-3">
                        <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">{{ __('Apakah ada minimum payout?') }}</button></h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">{{ __('Ya, minimum payout adalah Rp500.000. Jika saldo komisi Anda di bawah jumlah tersebut, komisi akan diakumulasi hingga mencapai minimum payout pada periode pembayaran berikutnya. Sistem ini membantu efisiensi proses pembayaran.') }}</div></div>
                    </div>
                    <div class="accordion-item reveal reveal-delay-4">
                        <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">{{ __('Apakah komisi bersifat recurring?') }}</button></h2>
                        <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">{{ __('Ya. Setiap kali customer yang Anda referensikan memperpanjang langganan COOCA (untuk paket monthly, 3-month, atau annual), Anda terus mendapatkan komisi 25% dari nilai perpanjangan tersebut. Ini menciptakan aliran pendapatan berulang yang terus tumbuh seiring waktu.') }}</div></div>
                    </div>
                    <div class="accordion-item reveal reveal-delay-5">
                        <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq6">{{ __('Bagaimana sistem affiliate level bekerja?') }}</button></h2>
                        <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">{{ __('Sebagai Senior Affiliate, Anda bisa memiliki affiliator level 1 di bawah Anda. Ketika affiliator di bawah Anda menghasilkan penjualan, Anda mendapatkan override commission 5% dari nilai penjualan mereka — tanpa mengurangi komisi yang mereka terima. Ini adalah bonus tambahan dari COOCA untuk membangun jaringan.') }}</div></div>
                    </div>
                    <div class="accordion-item reveal">
                        <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq7">{{ __('Bagaimana tracking referral bekerja?') }}</button></h2>
                        <div id="faq7" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">{{ __('COOCA menggunakan sistem tracking berbasis cookie dan referral code. Setiap klik pada link referral Anda dicatat, dan ketika terjadi pendaftaran serta pembayaran, sistem secara otomatis mengatribusikan konversi tersebut ke akun Anda. Dashboard real-time menampilkan semua data klik, konversi, dan komisi secara transparan.') }}</div></div>
                    </div>
                    <div class="accordion-item reveal reveal-delay-1">
                        <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq8">{{ __('Siapa yang cocok menjadi affiliate COOCA?') }}</button></h2>
                        <div id="faq8" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">{{ __('Program affiliate COOCA cocok untuk digital marketer, freelancer, agency owner, konsultan bisnis, influencer, content creator, software reseller, komunitas bisnis, trainer, dan coach — siapapun yang memiliki audiens atau jaringan yang membutuhkan solusi business system. Anda tidak perlu menjadi expert teknologi untuk sukses.') }}</div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== FINAL CTA ==================== -->
<section class="final-cta" id="finalcta">
    <div class="final-cta-bg"></div>
    <div class="floating-shape" style="width:200px;height:200px;top:10%;left:5%;animation:float 8s ease-in-out infinite;"></div>
    <div class="floating-shape" style="width:150px;height:150px;top:60%;right:10%;animation:float-delay 6s ease-in-out 1s infinite;"></div>
    <div class="container text-center position-relative" style="z-index:2;">
        <h2 class="reveal" style="font-size:clamp(2rem,4vw,3.2rem);">{!! __('Start Earning with <span class="text-gradient">COOCA Today</span>') !!}</h2>
        <p class="reveal reveal-delay-1" style="max-width:550px;margin:16px auto 36px;font-size:1.1rem;">{{ __('Gabung dengan partner yang membantu bisnis tumbuh sambil menghasilkan pendapatan tambahan. Daftar gratis dan mulai dapatkan komisi.') }}</p>
        <div class="d-flex flex-wrap justify-content-center gap-3 reveal reveal-delay-2">
            <a href="{{ route('customer.register') }}" class="btn-cooca btn-cooca-success" style="padding:16px 40px;font-size:1rem;">{{ __('Become an Affiliate') }} <i class="bi bi-arrow-right"></i></a>
            <a href="{{ route('contact') }}" class="btn-cooca btn-cooca-outline" style="padding:16px 40px;font-size:1rem;">{{ __('Contact Partnership Team') }} <i class="bi bi-chat-dots"></i></a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    });

    // Calculator logic
    const personalSalesInput = document.getElementById('personalSales');
    const juniorSalesInput = document.getElementById('juniorSales');
    const personalCommissionEl = document.getElementById('personalCommission');
    const overrideCommissionEl = document.getElementById('overrideCommission');
    const totalCommissionEl = document.getElementById('totalCommission');

    function calculateCommission() {
        let personalSales = parseFloat(personalSalesInput.value.replace(/[^0-9]/g, '')) || 0;
        let juniorSales = parseFloat(juniorSalesInput.value.replace(/[^0-9]/g, '')) || 0;

        let personalComm = personalSales * 0.25;
        let overrideComm = juniorSales * 0.05;
        let totalComm = personalComm + overrideComm;

        personalCommissionEl.textContent = 'Rp' + Math.floor(personalComm).toLocaleString('id-ID');
        overrideCommissionEl.textContent = 'Rp' + Math.floor(overrideComm).toLocaleString('id-ID');
        totalCommissionEl.textContent = 'Rp' + Math.floor(totalComm).toLocaleString('id-ID');
    }

    if (personalSalesInput && juniorSalesInput) {
        personalSalesInput.addEventListener('input', function(e) {
            let val = this.value.replace(/[^0-9]/g, '');
            if(val) {
                this.value = parseInt(val, 10).toLocaleString('id-ID');
            } else {
                this.value = '';
            }
            calculateCommission();
        });
        juniorSalesInput.addEventListener('input', function(e) {
            let val = this.value.replace(/[^0-9]/g, '');
            if(val) {
                this.value = parseInt(val, 10).toLocaleString('id-ID');
            } else {
                this.value = '';
            }
            calculateCommission();
        });
    }
});
</script>
@endpush
