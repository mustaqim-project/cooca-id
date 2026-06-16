@extends('layouts.auth')

@section('title', 'Affiliator Register')
@section('description', 'Daftar sebagai affiliator dan dapatkan komisi hingga 25%')

@section('auth_content')
<div class="row g-0 min-vh-100">
    <!-- Left Side: Branding & Benefits (Dynamic from Settings) -->
    <div class="col-lg-6 d-none d-lg-flex flex-column justify-content-center p-5 bg-gradient-success text-white position-relative overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="position-absolute top-0 start-0 w-100 h-100 z-0 opacity-20">
            <div class="shape shape-1 floating"></div>
            <div class="shape shape-2 floating delay-1"></div>
            <div class="shape shape-3 floating delay-2"></div>
        </div>

        <div class="position-relative z-1">
            <div class="mb-4">
                <img src="{{ setting('logo_light', asset('images/logo-light.svg')) }}" alt="{{ setting('app_name', 'Cooca.id Affiliator') }}" class="img-fluid" style="max-height: 60px;">
            </div>
            
            <h1 class="display-4 fw-bold mb-3">{{ setting('affiliate_register_title', 'Start Earning Today') }}</h1>
            <p class="lead mb-4 opacity-75">{{ setting('affiliate_register_desc', 'Join thousands of affiliates earning passive income') }}</p>
            
            <!-- Commission Highlight -->
            <div class="card bg-white bg-opacity-10 border-0 rounded-4 p-4 mb-4 backdrop-blur">
                <div class="text-center">
                    <div class="display-4 fw-bold mb-1">{{ setting('commission_rate', '25') }}%</div>
                    <div class="small text-uppercase letter-spacing-2">{{ setting('commission_label', 'Commission Rate') }}</div>
                </div>
            </div>
            
            <div class="feature-list mt-4">
                @php
                    $registerBenefits = [
                        ['icon' => 'bi-wallet2', 'text' => setting('reg_benefit_1', 'Instant Commission Tracking')],
                        ['icon' => 'bi-link-45deg', 'text' => setting('reg_benefit_2', 'Custom Referral Links')],
                        ['icon' => 'bi-credit-card', 'text' => setting('reg_benefit_3', 'Fast Payout Options')]
                    ];
                @endphp
                @foreach($registerBenefits as $benefit)
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-box bg-white bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="{{ $benefit['icon'] }} fs-5"></i>
                    </div>
                    <span class="fs-5">{{ $benefit['text'] }}</span>
                </div>
                @endforeach
            </div>

            <div class="mt-5 pt-4 border-top border-white border-opacity-25">
                <p class="small opacity-75 mb-2">{{ setting('affiliate_disclaimer', 'No credit card required. Free to join.') }}</p>
            </div>
        </div>
    </div>

    <!-- Right Side: Registration Form -->
    <div class="col-lg-6 d-flex align-items-center justify-content-center p-4 p-lg-5 bg-body">
        <div class="w-100" style="max-width: 480px;">
            <div class="text-center text-lg-start mb-5">
                <h2 class="fw-bold mb-2">{{ setting('affiliator_register_form_title', 'Create Your Account') }}</h2>
                <p class="text-muted">{{ setting('affiliator_register_form_subtitle', 'Fill in your details to start earning commissions') }}</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <ul class="mb-0 ps-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('affiliator.register.submit') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                
                <!-- Full Name Input -->
                <div class="form-floating mb-3">
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="fullName" placeholder="Full Name" value="{{ old('name') }}" required autofocus>
                    <label for="fullName">{{ __('Full Name') }}</label>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email Input -->
                <div class="form-floating mb-3">
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="name@example.com" value="{{ old('email') }}" required>
                    <label for="email">{{ __('Email Address') }}</label>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- WhatsApp Number Input -->
                <div class="form-floating mb-3">
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" id="phone" placeholder="08xxxxxxxxxx" value="{{ old('phone') }}" required>
                    <label for="phone">{{ __('WhatsApp Number') }}</label>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password Input -->
                <div class="form-floating mb-3">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Password" required minlength="8">
                    <label for="password">{{ __('Password') }}</label>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password Input -->
                <div class="form-floating mb-3">
                    <input type="password" name="password_confirmation" class="form-control" id="passwordConfirmation" placeholder="Confirm Password" required minlength="8">
                    <label for="passwordConfirmation">{{ __('Confirm Password') }}</label>
                </div>

                <!-- Terms Agreement -->
                <div class="form-check mb-4">
                    <input class="form-check-input @error('agree_terms') is-invalid @enderror" type="checkbox" name="agree_terms" id="agreeTerms" required>
                    <label class="form-check-label text-muted small" for="agreeTerms">
                        {!! __('I agree to the :terms_link and :privacy_link', [
                            'terms_link' => '<a href="'.route('page.terms').'" class="text-success fw-medium" target="_blank">'.__('Terms of Service').'</a>',
                            'privacy_link' => '<a href="'.route('page.privacy').'" class="text-success fw-medium" target="_blank">'.__('Privacy Policy').'</a>'
                        ]) !!}
                    </label>
                    @error('agree_terms')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-success btn-lg w-100 py-3 mb-4 shadow-sm hover-lift">
                    <span class="d-flex align-items-center justify-content-center">
                        <i class="bi bi-person-plus-fill me-2"></i>
                        {{ __('Join Affiliate Program') }}
                    </span>
                </button>
            </form>

            <div class="text-center mt-4">
                <p class="text-muted small">
                    {{ __('Already have an account?') }}
                    <a href="{{ route('affiliator.login') }}" class="text-success fw-bold text-decoration-none">{{ __('Sign in here') }}</a>
                </p>
                <p class="text-muted small mt-2">
                    <a href="{{ route('home') }}" class="text-decoration-none">{{ __('Back to Homepage') }}</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .bg-gradient-success {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
    }
    .floating {
        animation: float 6s ease-in-out infinite;
    }
    .delay-1 { animation-delay: 1s; }
    .delay-2 { animation-delay: 2s; }
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
        100% { transform: translateY(0px); }
    }
    .shape {
        position: absolute;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }
    .shape-1 { width: 200px; height: 200px; top: 10%; left: 10%; }
    .shape-2 { width: 300px; height: 300px; bottom: 10%; right: 10%; }
    .shape-3 { width: 150px; height: 150px; top: 40%; left: 60%; }
    .backdrop-blur {
        backdrop-filter: blur(10px);
    }
</style>
@endpush
