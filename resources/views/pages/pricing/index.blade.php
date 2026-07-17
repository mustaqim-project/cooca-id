@extends('layouts.guest')
@section('content')

    <section class="page-hero">
        <div class="page-hero-orb page-hero-orb-1"></div>
        <div class="page-hero-orb page-hero-orb-2"></div>
        <div class="grid-bg"></div>
        <div class="container text-center position-relative" style="z-index:2;">
            <div class="badge-glow reveal mb-4">
                <i class="bi bi-star-fill"></i> {{ __('Pricing') }}
            </div>
            <h1 class="hero-title reveal rv-delay-1">{!! __('Simple, <span class="text-gradient">Transparent</span> Pricing.') !!}</h1>
            <p class="hero-subtitle reveal rv-delay-2" style="font-size:1.1rem;max-width:640px;margin:16px auto 0;">
                {{ __('Choose the plan that fits your business. No hidden fees, no surprises.') }}
            </p>
        </div>
    </section>
    <section class="section">
        <div class="container">
            @if (isset($products) && count($products))
                @foreach ($products as $product)
                    @if ($product->subscriptionPlans && $product->subscriptionPlans->where('is_active', true)->count())
                        <div class="mb-5">
                            <div class="text-center mb-4 reveal">
                                <span
                                    class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2 mb-2">
                                    {{ $product->product_type_label ?? $product->name }}
                                </span>
                                <h3 class="fw-bold">{{ $product->name }}</h3>
                                @if ($product->short_description)
                                    <p class="text-secondary" style="max-width:600px;margin:0 auto;">
                                        {{ $product->short_description }}</p>
                                @endif
                            </div>
                            <div class="row g-4 justify-content-center">
                                @foreach ($product->subscriptionPlans->where('is_active', true)->sortBy('sort_order') as $plan)
                                    @php
                                        // ponytail: period label from duration_months; upgrade to plan.period_label column if custom labels needed
                                        $periodLabel = match (true) {
                                            $plan->duration_months === null || $plan->duration_months === 0 => __(
                                                'lifetime',
                                            ),
                                            $plan->duration_months === 1 => __('month'),
                                            $plan->duration_months === 3 => __('quarter'),
                                            $plan->duration_months === 6 => __('6 months'),
                                            $plan->duration_months === 12 => __('year'),
                                            $plan->duration_months === 24 => __('2 years'),
                                            $plan->duration_months === 36 => __('3 years'),
                                            default => $plan->duration_months . ' ' . __('months'),
                                        };
                                        $finalPrice = $plan->discount_percent
                                            ? $plan->price * (1 - $plan->discount_percent / 100)
                                            : $plan->price;
                                    @endphp
                                    <div class="col-lg-4 col-md-6 reveal">
                                        <div
                                            class="card pricing-card {{ $product->is_featured && $loop->first ? 'popular' : '' }}">
                                            @if ($product->is_featured && $loop->first)
                                                <div class="pricing-badge">{{ __('Most Popular') }}</div>
                                            @endif
                                            <div class="pricing-name">{{ $plan->name }}</div>
                                            <div class="pricing-price">
                                                @if ($plan->discount_percent)
                                                    <small class="text-decoration-line-through text-secondary me-1"
                                                        style="font-size:0.5em;">
                                                        {{ setting('currency.symbol', 'Rp') }}{{ number_format($plan->price, 0, ',', '.') }}
                                                    </small>
                                                @endif
                                                <span
                                                    class="currency">{{ setting('currency.symbol', 'Rp') }}</span>{{ number_format($finalPrice, 0, ',', '.') }}
                                                <span class="period">/{{ $periodLabel }}</span>
                                            </div>
                                            @if ($product->setup_fee && $product->setup_fee > 0)
                                                <p class="text-secondary fs-7 mb-2">
                                                    +
                                                    {{ setting('currency.symbol', 'Rp') }}{{ number_format($product->setup_fee, 0, ',', '.') }}
                                                    {{ __('setup fee') }}
                                                </p>
                                            @endif
                                            <p class="pricing-desc">
                                                {{ Str::limit($product->short_description ?? $product->description, 80) }}
                                            </p>
                                            @if ($product->features && is_array($product->features))
                                                <ul class="pricing-features">
                                                    @foreach (array_slice($product->features, 0, 5) as $feature)
                                                        <li><i class="bi bi-check-circle-fill"></i>
                                                            {{ is_array($feature) ? $feature['name'] ?? ($feature[0] ?? '') : $feature }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <ul class="pricing-features">
                                                    <li><i class="bi bi-check-circle-fill"></i> {{ __('Full Access') }}
                                                    </li>
                                                    <li><i class="bi bi-check-circle-fill"></i>
                                                        {{ __('Priority Support') }}</li>
                                                    <li><i class="bi bi-check-circle-fill"></i> {{ __('Regular Updates') }}
                                                    </li>
                                                </ul>
                                            @endif
                                            <a href="{{ route('customer.register') }}"
                                                class="btn {{ $product->is_featured && $loop->first ? 'btn-primary' : 'btn-outline' }} btn-block">
                                                {{ __('Get Started') }}
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="text-center py-5 reveal">
                    <div class="empty-state">
                        <div class="empty-state-icon"><i class="bi bi-cash-stack"></i></div>
                        <h4>{{ __('Pricing Coming Soon') }}</h4>
                        <p>{{ __('Our pricing plans are being finalized. Contact sales for early access pricing.') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <section class="section section-alt">
        <div class="container text-center">
            <div class="reveal">
                <div class="section-label"><i class="bi bi-question-circle"></i> {{ __('FAQ') }}</div>
                <h2 class="section-title">{!! __('Common <span class="text-gradient">Questions</span>') !!}</h2>
                <div class="row justify-content-center mt-4">
                    <div class="col-lg-8 text-start">
                        <div class="accordion accordion-c" id="pricingFaq">
                            <div class="accordion-item">
                                <h2 class="accordion-header"><button class="accordion-button" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#pf1">{{ __('What does lifetime license include?') }}</button></h2>
                                <div id="pf1" class="accordion-collapse collapse show" data-bs-parent="#pricingFaq">
                                    <div class="accordion-body">
                                        {{ __('All modules, unlimited users, isolated infrastructure, support during setup, and updates for 1 year.') }}
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header"><button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#pf2">{{ __('Are there hidden fees?') }}</button></h2>
                                <div id="pf2" class="accordion-collapse collapse" data-bs-parent="#pricingFaq">
                                    <div class="accordion-body">
                                        {{ __('No. The price you see is the price you pay. No hidden costs.') }}
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header"><button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#pf3">{{ __('Can I switch plans later?') }}</button></h2>
                                <div id="pf3" class="accordion-collapse collapse" data-bs-parent="#pricingFaq">
                                    <div class="accordion-body">
                                        {{ __('Yes, you can upgrade or change your plan at any time. Contact support for assistance.') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="cta-bg"></div>
        <div class="container cta-content text-center">
            <div class="reveal">
                <h2 class="section-title">{!! __('Ready to <span class="text-gradient">Get Started?</span>') !!}</h2>
                <p class="section-subtitle">{{ __('Start your free trial today. No credit card required.') }}</p>
                <a href="{{ route('customer.register') }}" class="btn btn-primary btn-lg">{{ __('Start Free Trial') }} <i
                        class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </section>
@endsection
