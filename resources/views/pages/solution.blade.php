@extends('layouts.guest')

@section('title', 'Solutions - COOCA Industry Systems')
@section('meta_description', 'Explore COOCA\'s purpose-built systems for Retail, Restaurant, Hotel, Clinic, Education, and more.')

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
                    <i class="bi bi-grid-3x3-gap-fill"></i> {{ setting('solutions.badge', 'Industry Solutions') }}
                </div>
                <h1 class="reveal reveal-delay-1" style="font-size:clamp(2.4rem,5vw,4rem);">
                    {!! setting('solutions.hero_title', 'Nine Industries. <span class="text-gradient">One Complete System.</span>') !!}
                </h1>
                <p class="reveal reveal-delay-2" style="font-size:1.15rem;max-width:600px;margin:20px auto 0;">
                    {{ setting('solutions.hero_description', 'COOCA isn\'t generic software with industry labels. Each solution is purpose-built from the ground up to solve the unique challenges of your vertical — with the shared infrastructure that makes it all work seamlessly.') }}
                </p>
            </div>
        </div>
        <!-- Free Trial CTA -->
        <div class="free-trial-cta reveal reveal-delay-3" style="display:flex;justify-content:center;margin-top:40px;">
            <a href="{{ route('register') }}" class="btn-cooca btn-cooca-primary" style="padding:18px 48px;font-size:1.1rem;border-radius:50px;">
                <i class="bi bi-gift-fill"></i> {{ setting('solutions.trial_cta', 'Start Free 30-Day Trial — Explore All Solutions') }}
            </a>
        </div>
    </div>
</section>

<!-- Core Business Section -->
<section class="section-padding">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-label reveal"><i class="bi bi-briefcase-fill"></i> {{ setting('solutions.core_badge', 'Core Business Systems') }}</div>
            <h2 class="section-title reveal reveal-delay-1">{!! setting('solutions.core_title', 'Retail & Commerce. <span class="text-gradient">Built for Growth.</span>') !!}</h2>
            <p class="section-subtitle reveal reveal-delay-2">{{ setting('solutions.core_subtitle', 'From single-store retail to multi-outlet F&B — systems designed to scale with your ambition.') }}</p>
        </div>
        <div class="row g-4">
            @foreach($coreSolutions as $index => $solution)
            <div class="col-lg-4 col-md-6 reveal {{ $index > 0 ? 'reveal-delay-' . ($index % 3) : '' }}">
                <div class="solution-card">
                    <div class="solution-card-header">
                        <div class="solution-icon"><i class="bi {{ $solution['icon'] }}"></i></div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="solution-title">{{ $solution['name'] }}</div>
                            <span class="solution-tag" style="background:{{ $solution['tag_bg'] }};color:{{ $solution['tag_color'] }};">{{ $solution['tag'] }}</span>
                        </div>
                        <p style="font-size:0.88rem;margin:0;">{{ $solution['description'] }}</p>
                    </div>
                    <div class="solution-card-body">
                        @foreach($solution['features'] as $feature)
                        <div class="solution-feature">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>{{ $feature }}</span>
                        </div>
                        @endforeach
                        <a href="{{ route('register') }}" class="btn-cooca btn-cooca-primary">
                            Start Free Trial <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Hospitality & Services Section -->
<section class="section-padding" style="background:var(--card-alt);">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-label reveal"><i class="bi bi-building"></i> {{ setting('solutions.hospitality_badge', 'Hospitality & Services') }}</div>
            <h2 class="section-title reveal reveal-delay-1">{!! setting('solutions.hospitality_title', 'Every Guest. Every Room. <span class="text-gradient">Every Revenue Source.</span>') !!}</h2>
            <p class="section-subtitle reveal reveal-delay-2">{{ setting('solutions.hospitality_subtitle', 'From table to hotel to rental — service industry systems that maximize occupancy and revenue.') }}</p>
        </div>
        <div class="row g-4">
            @foreach($hospitalitySolutions as $index => $solution)
            <div class="col-lg-4 col-md-6 reveal {{ $index > 0 ? 'reveal-delay-' . ($index % 3) : '' }}">
                <div class="solution-card">
                    <div class="solution-card-header">
                        <div class="solution-icon"><i class="bi {{ $solution['icon'] }}"></i></div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="solution-title">{{ $solution['name'] }}</div>
                            <span class="solution-tag" style="background:{{ $solution['tag_bg'] }};color:{{ $solution['tag_color'] }};">{{ $solution['tag'] }}</span>
                        </div>
                        <p style="font-size:0.88rem;margin:0;">{{ $solution['description'] }}</p>
                    </div>
                    <div class="solution-card-body">
                        @foreach($solution['features'] as $feature)
                        <div class="solution-feature">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>{{ $feature }}</span>
                        </div>
                        @endforeach
                        <a href="{{ route('register') }}" class="btn-cooca btn-cooca-primary">
                            Start Free Trial <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Health & Professional Section -->
<section class="section-padding">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-label reveal"><i class="bi bi-heart-pulse-fill"></i> {{ setting('solutions.professional_badge', 'Health & Professional') }}</div>
            <h2 class="section-title reveal reveal-delay-1">{!! setting('solutions.professional_title', 'Compliant. Integrated. <span class="text-gradient">Finally Stress-Free.</span>') !!}</h2>
            <p class="section-subtitle reveal reveal-delay-2">{{ setting('solutions.professional_subtitle', 'EMR, workshop, education — professional systems designed for compliance and operational excellence.') }}</p>
        </div>
        <div class="row g-4">
            @foreach($professionalSolutions as $index => $solution)
            <div class="col-lg-4 col-md-6 reveal {{ $index > 0 ? 'reveal-delay-' . ($index % 3) : '' }}">
                <div class="solution-card">
                    <div class="solution-card-header">
                        <div class="solution-icon"><i class="bi {{ $solution['icon'] }}"></i></div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="solution-title">{{ $solution['name'] }}</div>
                            <span class="solution-tag" style="background:{{ $solution['tag_bg'] }};color:{{ $solution['tag_color'] }};">{{ $solution['tag'] }}</span>
                        </div>
                        <p style="font-size:0.88rem;margin:0;">{{ $solution['description'] }}</p>
                    </div>
                    <div class="solution-card-body">
                        @foreach($solution['features'] as $feature)
                        <div class="solution-feature">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>{{ $feature }}</span>
                        </div>
                        @endforeach
                        <a href="{{ route('register') }}" class="btn-cooca btn-cooca-primary">
                            Start Free Trial <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Final CTA Section -->
<section class="section-padding" style="background:var(--card-alt);">
    <div class="container text-center">
        <h2 class="reveal" style="font-size:clamp(1.8rem,3.5vw,2.8rem);">
            {!! setting('solutions.cta_title', 'Not Sure Which Solution <span class="text-gradient">Fits Your Business?</span>') !!}
        </h2>
        <p class="reveal reveal-delay-1" style="max-width:480px;margin:16px auto 36px;">
            {{ setting('solutions.cta_description', 'Start your free 30-day trial and explore all nine industry systems. Or talk to our team — we\'ll match you in 15 minutes.') }}
        </p>
        <div class="d-flex justify-content-center gap-3 flex-wrap reveal reveal-delay-2">
            <a href="{{ route('register') }}" class="btn-cooca btn-cooca-primary" style="padding:16px 40px;">
                {{ setting('solutions.cta_primary_button', 'Start 30-Day Free Trial') }} <i class="bi bi-arrow-right"></i>
            </a>
            <a href="{{ route('about') }}" class="btn-cooca btn-cooca-outline" style="padding:16px 40px;">
                {{ setting('solutions.cta_secondary_button', 'Talk to Sales') }}
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Solutions page specific scripts handled by global system
</script>
@endpush
