@extends('layouts.guest')
@section('content')
    <section class="page-hero">
        <div class="page-hero-orb page-hero-orb-1"></div>
        <div class="page-hero-orb page-hero-orb-2"></div>
        <div class="grid-bg"></div>
        <div class="container text-center position-relative" style="z-index:2;">
            <div class="badge-glow reveal mb-4">
                <i class="bi bi-star-fill"></i> {{ __('Documentation') }}
            </div>
            <h1 class="hero-title reveal rv-delay-1">{!! __('Documentation & <span class="text-gradient">Resources.</span>') !!}</h1>
            <p class="hero-subtitle reveal rv-delay-2" style="font-size:1.1rem;max-width:640px;margin:16px auto 0;">
                {{ __('Technical documentation, API references, implementation guides, and best practices for getting the most out of COOCA.') }}
            </p>
        </div>
    </section>
    <section class="section">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6 reveal">
                    <div class="card card-3d">
                        <div class="card-icon"><i class="bi bi-book"></i></div>
                        <h3 class="card-title">{{ __('Getting Started') }}</h3>
                        <p class="card-desc">
                            {{ __('Quick start guide covering initial setup, first-time configuration, and onboarding your team.') }}
                        </p>
                        <a href="#" class="btn btn-outline btn-sm">{{ __('Read Guide') }} <i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 reveal rv-delay-1">
                    <div class="card card-3d">
                        <div class="card-icon"><i class="bi bi-code-slash"></i></div>
                        <h3 class="card-title">{{ __('API Reference') }}</h3>
                        <p class="card-desc">
                            {{ __('REST API documentation for integrating COOCA with your existing tools and workflows.') }}
                        </p>
                        <a href="#" class="btn btn-outline btn-sm">{{ __('Explore API') }} <i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 reveal rv-delay-2">
                    <div class="card card-3d">
                        <div class="card-icon"><i class="bi bi-gear"></i></div>
                        <h3 class="card-title">{{ __('Module Guides') }}</h3>
                        <p class="card-desc">
                            {{ __('In-depth documentation for each module: POS, Inventory, CRM, Accounting, HRIS, and more.') }}
                        </p>
                        <a href="#" class="btn btn-outline btn-sm">{{ __('View Modules') }} <i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 reveal">
                    <div class="card card-3d">
                        <div class="card-icon"><i class="bi bi-shield-check"></i></div>
                        <h3 class="card-title">{{ __('Security & Compliance') }}</h3>
                        <p class="card-desc">
                            {{ __('Learn about COOCA\'s security architecture, encryption standards, and compliance certifications.') }}
                        </p>
                        <a href="#" class="btn btn-outline btn-sm">{{ __('Learn More') }} <i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 reveal rv-delay-1">
                    <div class="card card-3d">
                        <div class="card-icon"><i class="bi bi-cloud-arrow-down"></i></div>
                        <h3 class="card-title">{{ __('Migration Guide') }}</h3>
                        <p class="card-desc">
                            {{ __('Step-by-step guide for migrating your data from legacy systems, spreadsheets, or other platforms.') }}
                        </p>
                        <a href="#" class="btn btn-outline btn-sm">{{ __('Start Migration') }} <i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 reveal rv-delay-2">
                    <div class="card card-3d">
                        <div class="card-icon"><i class="bi bi-question-circle"></i></div>
                        <h3 class="card-title">{{ __('Troubleshooting') }}</h3>
                        <p class="card-desc">
                            {{ __('Common issues and solutions, error code references, and diagnostic tools documentation.') }}
                        </p>
                        <a href="#" class="btn btn-outline btn-sm">{{ __('View Guide') }} <i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
