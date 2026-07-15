@extends('layouts.guest')
@section('content')
    <div class="auth-layout">
        <div class="auth-left auth-panel">
            <div class="hero-orb hero-orb-1" style="width:500px;height:500px;top:-150px;right:-100px;"></div>
            <div class="hero-orb hero-orb-2" style="width:300px;height:300px;bottom:-80px;left:-60px;"></div>
            <div class="grid-bg"></div>
            <div class="auth-left-content">
                <div class="d-flex align-items-center justify-content-center gap-3 mb-5">
                    <div class="brand-icon">C</div>
                    <span style="font-size:1.8rem;font-weight:800;">{{ setting('site.name', 'COOCA') }}</span>
                </div>
                <h2 style="font-size:2rem;font-weight:800;line-height:1.2;margin-bottom:16px;">
                    {{ __('Your Business Runs Better When You') }} <span
                        class="text-gradient">{{ __('Own the System.') }}</span></h2>
                <p style="font-size:.95rem;color:var(--text-muted);">
                    {{ __('Welcome back. Your isolated business infrastructure is ready.') }}</p>
                <div class="d-flex flex-column gap-3 mt-5 text-start">
                    <div class="auth-trust-item">
                        <div class="auth-trust-icon"><i class="bi bi-shield-lock-fill"></i></div>
                        <div style="font-size:.82rem;"><strong
                                style="color:var(--text);">{{ __('Isolated Environment') }}</strong><br><span
                                style="color:var(--text-muted);">{{ __('Your data, your system. Zero cross-tenant risk.') }}</span>
                        </div>
                    </div>
                    <div class="auth-trust-item">
                        <div class="auth-trust-icon"><i class="bi bi-lightning-charge-fill"></i></div>
                        <div style="font-size:.82rem;"><strong
                                style="color:var(--text);">{{ __('Always On') }}</strong><br><span
                                style="color:var(--text-muted);">{{ __('99.9% uptime SLA. Business doesnt wait.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="auth-right auth-panel">
            <div class="auth-form-panel">
                <div class="d-flex align-items-center gap-3 d-md-none mb-4">
                    <div class="brand-icon">C</div><span
                        style="font-size:1.6rem;font-weight:800;">{{ setting('site.name', 'COOCA') }}</span>
                </div>
                <div class="form-title" style="font-size:1.7rem;font-weight:800;margin-bottom:4px;">{{ __('Welcome back') }}
                </div>
                <p class="mb-4" style="font-size:.9rem;">{{ __('Log in to your COOCA dashboard.') }} <a
                        href="{{ route('customer.register') }}">{{ __('No account? Start free →') }}</a></p>

                <a href="{{ route('customer.auth.google') }}" class="social-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24">
                        <path fill="#4285F4"
                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                        <path fill="#34A853"
                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                        <path fill="#FBBC05"
                            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                        <path fill="#EA4335"
                            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                    </svg>
                    {{ __('Continue with Google') }}
                </a>

                <div class="divider" style="display:flex;align-items:center;gap:12px;margin:20px 0;">
                    <div style="flex:1;height:1px;background:var(--border);"></div><span
                        style="font-size:.78rem;color:var(--text-muted);">or continue with email</span>
                    <div style="flex:1;height:1px;background:var(--border);"></div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill"></i> {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('customer.login.submit') }}" method="POST" id="loginForm">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">{{ __('Email Address') }}</label>
                        <div class="input-icon-wrap">
                            <i class="bi bi-envelope input-icon"></i>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                                placeholder="you@company.com" required autocomplete="email">
                        </div>
                    </div>
                    <div class="form-group">
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <label class="form-label">{{ __('Password') }}</label>
                            <a href="{{ route('customer.password.request') }}"
                                style="font-size:.82rem;">{{ __('Forgot password?') }}</a>
                        </div>
                        <div class="input-icon-wrap">
                            <i class="bi bi-lock input-icon"></i>
                            <input type="password" name="password" class="form-control" placeholder="Your password" required
                                autocomplete="current-password">
                        </div>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" name="remember" id="rememberMe" class="form-check-input">
                        <label for="rememberMe" class="form-check-label">{{ __('Keep me logged in for 30 days') }}</label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block btn-lg">{{ __('Log In to Dashboard') }} <i
                            class="bi bi-arrow-right"></i></button>
                </form>

                <p style="text-align:center;font-size:.82rem;margin-top:24px;">{{ __('By logging in, you agree to our') }}
                    <a href="{{ route('terms') }}">{{ __('Terms of Service') }}</a> {{ __('and') }} <a
                        href="{{ route('privacy') }}">{{ __('Privacy Policy') }}</a>.</p>
                <p style="text-align:center;font-size:.9rem;margin-top:12px;">{{ __('Do not have an account?') }} <a
                        href="{{ route('customer.register') }}"
                        style="font-weight:700;">{{ __('Start 30-day free trial →') }}</a></p>
            </div>
        </div>
    </div>
@endsection
