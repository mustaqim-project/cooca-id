@extends('layouts.auth')

@section('title', 'Login — COOCA')
@section('meta_description', 'Log in to your COOCA dashboard. Your isolated business infrastructure is ready and waiting.')

@section('content')
<main class="auth-layout">
    <!-- LEFT VISUAL PANEL -->
    <div class="auth-left auth-panel">
        <div class="orb" style="width:500px;height:500px;background:#2563EB;top:-150px;right:-100px;"></div>
        <div class="orb" style="width:300px;height:300px;background:#38BDF8;bottom:-80px;left:-60px;"></div>
        <div class="grid-bg"></div>
        <div class="left-content">
            <div class="d-flex align-items-center justify-content-center gap-3 mb-5">
                <div class="logo-icon">C</div>
                <span class="brand-name" style="font-size:1.8rem;font-weight:800;">{{ config('app.name', 'COOCA') }}</span>
            </div>
            <h2>Your Business Runs Better When You <span class="text-gradient">Own the System.</span></h2>
            <p style="font-size:.95rem;color:rgba(248,250,252,.6);margin-top:12px;">Welcome back. Your isolated business infrastructure is ready and waiting.</p>
            <div class="trust-items">
                <div class="trust-item">
                    <div class="trust-icon"><i class="bi bi-shield-lock-fill"></i></div>
                    <div class="trust-text"><strong style="color:#F8FAFC;">Isolated Environment</strong> — Your data, your system. Zero cross-tenant risk.</div>
                </div>
                <div class="trust-item">
                    <div class="trust-icon"><i class="bi bi-lightning-charge-fill"></i></div>
                    <div class="trust-text"><strong style="color:#F8FAFC;">Always On</strong> — 99.9% uptime SLA. Business doesn't wait, and neither do we.</div>
                </div>
                <div class="trust-item">
                    <div class="trust-icon"><i class="bi bi-graph-up-arrow"></i></div>
                    <div class="trust-text"><strong style="color:#F8FAFC;">Real-Time Insight</strong> — Full visibility into every transaction, team member, and rupiah.</div>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT FORM PANEL -->
    <div class="auth-right auth-panel">
        <div class="form-panel">
            <!-- Mobile only brand -->
            <div class="d-flex align-items-center gap-3 d-md-none mb-4">
                <div class="logo-icon">C</div>
                <span class="brand-name" style="font-size:1.8rem;font-weight:800;">{{ config('app.name', 'COOCA') }}</span>
            </div>

            <div class="form-title">Welcome back</div>
            <p class="form-subtitle">Log in to your COOCA dashboard. <a href="{{ route('customer.register') }}">No account? Start free →</a></p>

            @if ($errors->any())
                <div class="error-msg" id="errorMsg" style="display:block;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            @if (session('status'))
                <div class="success-msg" id="successMsg" style="display:block;">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('status') }}
                </div>
            @endif

            <!-- SOCIAL LOGIN -->
            <button class="social-btn" onclick="window.location.href='{{ route('customer.auth.google') }}'">
                <svg width="18" height="18" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                Continue with Google
            </button>
            <button class="social-btn" type="button">
                <i class="bi bi-microsoft" style="color:#00A4EF;"></i>
                Continue with Microsoft
            </button>

            <div class="divider"><span>or continue with email</span></div>

            <!-- FORM -->
            <form id="loginForm" action="{{ route('customer.login') }}" method="POST" novalidate>
                @csrf
                <div class="input-wrap">
                    <label class="input-label">Email Address</label>
                    <div style="position:relative;">
                        <i class="bi bi-envelope input-icon"></i>
                        <input type="email" class="input-field" id="emailField" name="email" placeholder="you@company.com" required autocomplete="email" value="{{ old('email') }}">
                    </div>
                </div>
                <div class="input-wrap">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <label class="input-label">Password</label>
                        <a href="{{ route('customer.password.request') }}" style="font-size:.82rem;">Forgot password?</a>
                    </div>
                    <div style="position:relative;">
                        <i class="bi bi-lock input-icon"></i>
                        <input type="password" class="input-field" id="passwordField" name="password" placeholder="Your password" required autocomplete="current-password">
                        <span class="input-toggle" id="pwToggle"><i class="bi bi-eye" id="pwIcon"></i></span>
                    </div>
                </div>
                <div class="check-wrap">
                    <input type="checkbox" id="rememberMe" name="remember">
                    <label for="rememberMe">Keep me logged in for 30 days</label>
                </div>
                <button type="submit" class="btn-cooca btn-cooca-primary" style="width:100%;padding:15px;font-size:1rem;border-radius:12px;" id="submitBtn">
                    <span id="btnText">Log In to Dashboard</span>
                    <i class="bi bi-arrow-right" id="btnIcon"></i>
                </button>
            </form>

            <p style="text-align:center;font-size:.82rem;margin-top:28px;color:var(--text-muted);">
                By logging in, you agree to our <a href="{{ route('terms') }}">Terms of Service</a> and <a href="{{ route('privacy') }}">Privacy Policy</a>.
            </p>
            <p style="text-align:center;font-size:.9rem;margin-top:16px;">
                Don't have an account? <a href="{{ route('customer.register') }}" style="font-weight:700;">Start 30-day free trial →</a>
            </p>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
(function() {
    'use strict';
    
    // Form validation
    const loginForm = document.getElementById('loginForm');
    const emailField = document.getElementById('emailField');
    const passwordField = document.getElementById('passwordField');
    const errorMsg = document.getElementById('errorMsg');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            if (!emailField.value || !emailField.validity.valid) {
                emailField.style.borderColor = 'var(--danger)';
                isValid = false;
            } else {
                emailField.style.borderColor = 'var(--border)';
            }
            
            if (!passwordField.value) {
                passwordField.style.borderColor = 'var(--danger)';
                isValid = false;
            } else {
                passwordField.style.borderColor = 'var(--border)';
            }
            
            if (!isValid) {
                e.preventDefault();
                if (errorMsg) {
                    errorMsg.style.display = 'block';
                    errorMsg.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-2"></i>Please fill in all required fields.';
                }
            }
        });
    }
})();
</script>
@endpush
