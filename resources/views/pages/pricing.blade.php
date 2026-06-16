@extends('layouts.guest')

@section('title', 'Pricing - COOCA Investment Plans')
@section('meta_description', 'Choose your COOCA plan. Monthly, quarterly, annual, or own it forever with a lifetime license.')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-bg-orb hero-bg-orb-1"></div>
    <div class="hero-bg-orb hero-bg-orb-2"></div>
    <div class="hero-bg-orb hero-bg-orb-3"></div>
    <div class="grid-bg"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center" style="position:relative;z-index:2;">
                <div class="badge-glow reveal mb-4">
                    <i class="bi bi-tag-fill"></i> {{ setting('pricing.badge', 'Transparent Pricing') }}
                </div>
                <h1 class="reveal reveal-delay-1" style="font-size:clamp(2.4rem,5vw,4rem);">
                    {{ setting('pricing.hero_title', 'Simple Pricing. <span class="text-gradient">Honest Value.</span>') }}
                </h1>
                <p class="reveal reveal-delay-2" style="font-size:1.15rem;max-width:600px;margin:20px auto 0;">
                    {!! setting('pricing.hero_description', 'All plans include full access to all modules with unlimited users. No hidden tiers. No module paywalls. Choose how you want to invest — not whether you can access the system.') !!}
                </p>
            </div>
        </div>
        <!-- Free Trial 30 Days CTA -->
        <div class="free-trial-cta reveal reveal-delay-3" style="display:flex;justify-content:center;margin-top:40px;">
            <a href="{{ route('register') }}" class="btn-cooca btn-cooca-primary" style="padding:18px 48px;font-size:1.1rem;border-radius:50px;">
                <i class="bi bi-gift-fill"></i> {{ setting('pricing.trial_cta', 'Start Free 30-Day Trial — No Credit Card') }}
            </a>
        </div>
    </div>
</section>

<!-- Pricing Cards Section -->
<section class="section-padding">
    <div class="container">
        <div class="row g-4 justify-content-center align-items-stretch">
            @foreach($plans as $index => $plan)
            <div class="col-lg col-md-6 reveal {{ $index > 0 ? 'reveal-delay-' . $index : '' }}">
                <div class="pricing-card {{ $plan['is_popular'] ?? false ? 'popular' : '' }}" 
                     @if($plan['highlight_border'] ?? false) style="border-color:rgba(16,185,129,0.3);" @endif
                     @if($plan['is_enterprise'] ?? false) style="border:2px dashed rgba(56,189,248,0.2);" @endif>
                    
                    @if($plan['is_popular'] ?? false)
                    <div class="pricing-badge">{{ $plan['badge'] ?? 'Most Popular' }}</div>
                    @endif
                    
                    <div class="plan-name">
                        {{ $plan['name'] }}
                        @if($plan['is_enterprise'] ?? false)
                            <span style="font-size:0.68rem;background:rgba(56,189,248,0.1);color:var(--accent);padding:3px 10px;border-radius:50px;margin-left:6px;vertical-align:middle;">{{ $plan['enterprise_label'] ?? 'Custom' }}</span>
                        @endif
                    </div>
                    
                    <div class="plan-price" @if($plan['is_enterprise'] ?? false) style="font-size:1.6rem;" @endif>
                        @if($plan['is_enterprise'] ?? false)
                            {{ $plan['price_display'] ?? "Let's Talk" }}
                        @else
                            <span class="currency">{{ $plan['currency'] ?? 'Rp' }}</span>{{ $plan['price'] }}<span class="suffix">{{ $plan['price_range'] ?? '' }}</span>
                        @endif
                    </div>
                    
                    <div class="plan-period">{{ $plan['period'] }}</div>
                    <div class="plan-desc">{{ $plan['description'] }}</div>
                    
                    <ul class="plan-features">
                        @foreach($plan['features'] as $feature)
                        <li>
                            <i class="bi {{ $feature['enabled'] ?? true ? 'bi-check-circle-fill' : 'bi-x-circle' }}"></i>
                            {{ $feature['text'] }}
                        </li>
                        @endforeach
                    </ul>
                    
                    <a href="{{ $plan['is_enterprise'] ?? false ? route('contact') : route('register') }}" 
                       class="btn-cooca {{ $plan['button_class'] ?? 'btn-cooca-outline' }}" 
                       style="width:100%;justify-content:center;{{ $plan['custom_button_style'] ?? '' }}">
                        {{ $plan['button_text'] }} @if($plan['is_enterprise'] ?? false)<i class="bi bi-chat-dots"></i>@endif
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Comparison Table Section -->
<section class="section-padding" style="background:var(--card-alt);">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-label reveal"><i class="bi bi-table"></i> {{ setting('pricing.comparison_title', 'Feature Comparison') }}</div>
            <h2 class="section-title reveal reveal-delay-1">{!! setting('pricing.comparison_subtitle', 'Side-by-Side <span class="text-gradient">Plan Breakdown</span>') !!}</h2>
        </div>
        <div class="compare-wrap reveal">
            <table class="compare-table">
                <thead>
                    <tr>
                        <th style="min-width:200px;">Feature</th>
                        @foreach($comparisonHeaders as $header)
                        <th style="{{ $header['style'] ?? '' }}">{{ $header['label'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($comparisonTable as $category)
                    <tr class="cat-row"><td colspan="{{ count($comparisonHeaders) + 1 }}">{{ $category['name'] }}</td></tr>
                    @foreach($category['features'] as $feature)
                    <tr>
                        <td>{{ $feature['name'] }}</td>
                        @foreach($feature['values'] as $value)
                        <td>
                            @if(is_bool($value))
                                <i class="bi {{ $value ? 'bi-check-circle-fill check' : 'bi-x-circle cross' }}"></i>
                            @else
                                {{ $value }}
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Pricing FAQ Section -->
<section class="section-padding">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-label reveal"><i class="bi bi-question-circle-fill"></i> {{ setting('pricing.faq_title', 'Pricing FAQ') }}</div>
            <h2 class="section-title reveal reveal-delay-1">{!! setting('pricing.faq_subtitle', 'Questions About <span class="text-gradient">Investment?</span>') !!}</h2>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="pricingFaqAccordion">
                    @foreach($faqs as $index => $faq)
                    <div class="accordion-item reveal {{ $index > 0 ? 'reveal-delay-' . ($index % 5) : '' }}">
                        <h2 class="accordion-header">
                            <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" 
                                    type="button" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#pricingFaq{{ $index }}">
                                {{ $faq['question'] }}
                            </button>
                        </h2>
                        <div id="pricingFaq{{ $index }}" 
                             class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" 
                             data-bs-parent="#pricingFaqAccordion">
                            <div class="accordion-body">{{ $faq['answer'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Final CTA Section -->
<section class="section-padding" style="background:var(--card-alt);">
    <div class="container text-center">
        <h2 class="reveal" style="font-size:clamp(1.8rem,3.5vw,2.8rem);">
            {!! setting('pricing.cta_title', 'Start Free. <span class="text-gradient">Upgrade When Ready.</span>') !!}
        </h2>
        <p class="reveal reveal-delay-1" style="max-width:480px;margin:16px auto 36px;">
            {{ setting('pricing.cta_description', '30 days. Full access. No credit card. The only risk is not finding out how much revenue you\'ve been leaving on the table.') }}
        </p>
        <div class="d-flex justify-content-center gap-3 flex-wrap reveal reveal-delay-2">
            <a href="{{ route('register') }}" class="btn-cooca btn-cooca-primary" style="padding:16px 40px;">
                {{ setting('pricing.cta_primary_button', 'Start Free Trial') }} <i class="bi bi-arrow-right"></i>
            </a>
            <a href="{{ route('contact') }}" class="btn-cooca btn-cooca-outline" style="padding:16px 40px;">
                {{ setting('pricing.cta_secondary_button', 'Talk to Sales') }}
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Pricing page specific scripts handled by global system
</script>
@endpush
