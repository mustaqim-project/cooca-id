@extends('layouts.guest')
@push('styles')
<style>
    .hero-section {
        min-height: 55vh;
        display: flex;
        align-items: center;
        padding-top: 120px;
        padding-bottom: 60px;
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
        width: 500px;
        height: 500px;
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
    .hero-content {
        position: relative;
        z-index: 2;
    }
    .accordion-cooca .accordion-item {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius) !important;
        margin-bottom: 16px;
        overflow: hidden;
        box-shadow: var(--shadow);
    }
    .accordion-cooca .accordion-button {
        background: transparent;
        color: var(--text);
        font-weight: 700;
        font-size: 1.1rem;
        padding: 22px 28px;
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
        font-size: 0.95rem;
        padding: 0 28px 24px;
        line-height: 1.8;
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
            <i class="bi bi-question-circle-fill"></i> {{ __(setting('faq.hero.badge', 'Help Center')) }}
        </div>
        <h1 class="hero-title reveal reveal-delay-1">
            {!! __(setting('faq.hero.title', 'Frequently Asked <span class="text-gradient">Questions.</span>')) !!}
        </h1>
        <p class="hero-subtitle reveal reveal-delay-2" style="font-size:1.15rem;max-width:600px;margin:20px auto 0;">
            {{ __(setting('faq.hero.subtitle', 'Everything you need to know about the product, billing, and technical support.')) }}
        </p>
    </div>
</section>

<!-- FAQ Section -->
<section class="section-padding" style="background:var(--card-alt);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion accordion-cooca" id="mainFaqAccordion">
                    <!-- FAQ 1 -->
                    <div class="accordion-item reveal">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqItem1">
                                {{ __(setting('faq.item1.question', 'What is COOCA?')) }}
                            </button>
                        </h2>
                        <div id="faqItem1" class="accordion-collapse collapse show" data-bs-parent="#mainFaqAccordion">
                            <div class="accordion-body">
                                {{ __(setting('faq.item1.answer', 'COOCA is an all-in-one business operating system designed for retail, F&B, and service businesses. It combines point-of-sale, inventory, CRM, accounting, and HR into a single, unified platform.')) }}
                            </div>
                        </div>
                    </div>
                    
                    <!-- FAQ 2 -->
                    <div class="accordion-item reveal reveal-delay-1">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqItem2">
                                {{ __(setting('faq.item2.question', 'Can I switch plans later?')) }}
                            </button>
                        </h2>
                        <div id="faqItem2" class="accordion-collapse collapse" data-bs-parent="#mainFaqAccordion">
                            <div class="accordion-body">
                                {{ __(setting('faq.item2.answer', 'Absolutely. You can upgrade to a higher tier or a lifetime license at any time. We will prorate the remaining balance on your current subscription.')) }}
                            </div>
                        </div>
                    </div>
                    
                    <!-- FAQ 3 -->
                    <div class="accordion-item reveal reveal-delay-2">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqItem3">
                                {{ __(setting('faq.item3.question', 'Do you provide onboarding support?')) }}
                            </button>
                        </h2>
                        <div id="faqItem3" class="accordion-collapse collapse" data-bs-parent="#mainFaqAccordion">
                            <div class="accordion-body">
                                {{ __(setting('faq.item3.answer', 'Yes, our annual and lifetime plans include dedicated onboarding sessions. We will help you migrate your data from your existing systems and train your staff.')) }}
                            </div>
                        </div>
                    </div>
                    
                    <!-- FAQ 4 -->
                    <div class="accordion-item reveal reveal-delay-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqItem4">
                                {{ __(setting('faq.item4.question', 'What hardware do I need?')) }}
                            </button>
                        </h2>
                        <div id="faqItem4" class="accordion-collapse collapse" data-bs-parent="#mainFaqAccordion">
                            <div class="accordion-body">
                                {{ __(setting('faq.item4.answer', 'COOCA runs in any modern web browser. For the POS, you can use any iPad, Android tablet, PC, or Mac. We integrate with most standard receipt printers and barcode scanners via USB or Bluetooth.')) }}
                            </div>
                        </div>
                    </div>
                    
                    <!-- FAQ 5 -->
                    <div class="accordion-item reveal reveal-delay-4">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqItem5">
                                {{ __(setting('faq.item5.question', 'Is my data secure?')) }}
                            </button>
                        </h2>
                        <div id="faqItem5" class="accordion-collapse collapse" data-bs-parent="#mainFaqAccordion">
                            <div class="accordion-body">
                                {{ __(setting('faq.item5.answer', 'Security is our top priority. Your data is encrypted at rest and in transit, and hosted on enterprise-grade cloud infrastructure with automatic daily backups.')) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 text-center reveal reveal-delay-5">
                    <p class="mb-4" style="font-size:1.1rem;color:var(--text-muted);">{{ __('Still have questions?') }}</p>
                    <a href="{{ route('contact') }}" class="btn-cooca btn-cooca-primary">{{ __('Contact Support') }} <i class="bi bi-chat-dots"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
