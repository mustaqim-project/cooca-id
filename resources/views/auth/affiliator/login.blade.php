@extends('layouts.auth')

@section('title', 'Affiliator Login')
@section('description', 'Login untuk mengelola referral dan komisi Anda')

@section('auth_content')
<div class="row g-0 min-vh-100">
    <!-- Left Side: Branding & Info (Dynamic from Settings) -->
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
            
            <h1 class="display-4 fw-bold mb-3">{{ setting('affiliator_welcome_title', 'Join Our Affiliate Program') }}</h1>
            <p class="lead mb-4 opacity-75">{{ setting('affiliator_welcome_desc', 'Earn commissions by promoting our products and services') }}</p>
            
            <div class="feature-list mt-5">
                @php
                    $affiliatorFeatures = [
                        ['icon' => 'bi-cash-coin', 'text' => setting('aff_feat_1', 'Competitive Commission Rates')],
                        ['icon' => 'bi-graph-up', 'text' => setting('aff_feat_2', 'Real-time Tracking Dashboard')],
                        ['icon' => 'bi-people', 'text' => setting('aff_feat_3', 'Dedicated Affiliate Support')]
                    ];
                @endphp
                @foreach($affiliatorFeatures as $feat)
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-box bg-white bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="{{ $feat['icon'] }} fs-5"></i>
                    </div>
                    <span class="fs-5">{{ $feat['text'] }}</span>
                </div>
                @endforeach
            </div>

            <div class="mt-5 pt-4 border-top border-white border-opacity-25">
                <div class="d-flex align-items-center">
                    <div class="avatar-group me-3">
                        <img src="https://ui-avatars.com/api/?name=Affiliate+Team&background=random" class="rounded-circle border border-2 border-white" width="40" alt="User">
                    </div>
                    <div>
                        <small class="d-block opacity-75">{{ setting('affiliate_support_text', 'Questions about affiliating?') }}</small>
                        <a href="mailto:{{ setting('affiliate_email', 'affiliate@system.com') }}" class="text-white fw-bold text-decoration-none">{{ setting('affiliate_email', 'affiliate@system.com') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side: Login Form -->
    <div class="col-lg-6 d-flex align-items-center justify-content-center p-4 p-lg-5 bg-body">
        <div class="w-100" style="max-width: 480px;">
            <div class="text-center text-lg-start mb-5">
                <h2 class="fw-bold mb-2">{{ setting('affiliator_login_title', 'Affiliator Login') }}</h2>
                <p class="text-muted">{{ setting('affiliator_login_subtitle', 'Enter your credentials to access your affiliate dashboard') }}</p>
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

            <form action="{{ route('affiliator.login.submit') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                
                <!-- Email Input -->
                <div class="form-floating mb-3">
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="affiliatorEmail" placeholder="name@example.com" value="{{ old('email') }}" required autofocus>
                    <label for="affiliatorEmail">{{ __('Email Address') }}</label>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password Input -->
                <div class="form-floating mb-3">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="affiliatorPassword" placeholder="Password" required>
                    <label for="affiliatorPassword">{{ __('Password') }}</label>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                        <label class="form-check-label text-muted" for="rememberMe">
                            {{ __('Remember me') }}
                        </label>
                    </div>
                    <a href="{{ route('affiliator.password.request') }}" class="text-decoration-none small fw-medium">
                        {{ __('Forgot password?') }}
                    </a>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-success btn-lg w-100 py-3 mb-4 shadow-sm hover-lift">
                    <span class="d-flex align-items-center justify-content-center">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        {{ __('Sign In as Affiliator') }}
                    </span>
                </button>
            </form>

            <div class="text-center mt-4">
                <p class="text-muted small">
                    {{ __('Not registered yet?') }}
                    <a href="{{ route('affiliator.register') }}" class="text-success fw-bold text-decoration-none">{{ __('Join our affiliate program') }}</a>
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
</style>
@endpush
