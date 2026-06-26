@extends('layouts.guest')
@section('content')
<main class="auth-layout" style="margin-top: var(--navbar-height); display: grid; grid-template-columns: 1fr 1fr; min-height: calc(100vh - var(--navbar-height));">
    <!-- LEFT VISUAL PANEL -->
    <div class="auth-left auth-panel" style="background: linear-gradient(160deg, #020617 0%, #0F172A 40%, #1E3A5F 80%, #020617 100%); position: relative; overflow: hidden; display: flex; align-items: center; justify-content: center; padding: 48px 40px;">
        <div class="orb" style="width:500px;height:500px;background:#2563EB;top:-150px;right:-100px;position:absolute;border-radius:50%;filter:blur(80px);opacity:.15;pointer-events:none;"></div>
        <div class="orb" style="width:300px;height:300px;background:#38BDF8;bottom:-80px;left:-60px;position:absolute;border-radius:50%;filter:blur(80px);opacity:.15;pointer-events:none;"></div>
        <div class="grid-bg" style="position:absolute;inset:0;background-image:linear-gradient(rgba(56,189,248,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(56,189,248,.04) 1px,transparent 1px);background-size:60px 60px;"></div>
        <div class="left-content" style="position:relative;z-index:2;max-width:420px;text-align:center;">
            <div class="d-flex align-items-center justify-content-center gap-3 mb-5">
                <div class="logo-icon" style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,var(--primary),var(--accent));display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.1rem;font-weight:800;">C</div>
                <span class="brand-name" style="font-size:1.8rem;font-weight:800;">{{ setting('site_name', 'COOCA') }}</span>
            </div>
            <h2 style="font-size:2rem;font-weight:800;line-height:1.2;margin-bottom:16px;letter-spacing:-0.02em;">Your Business Runs Better When You <span class="text-gradient">Own the System.</span></h2>
            <p style="font-size:.95rem;color:rgba(248,250,252,.6);margin-top:12px;">Welcome back. Your isolated business infrastructure is ready and waiting.</p>
            <div class="trust-items" style="display:flex;flex-direction:column;gap:12px;margin-top:36px;text-align:left;">
                <div class="trust-item" style="display:flex;align-items:center;gap:12px;padding:14px 16px;background:rgba(255,255,255,.04);border:1px solid rgba(56,189,248,.1);border-radius:12px;">
                    <div class="trust-icon" style="width:36px;height:36px;border-radius:10px;background:rgba(56,189,248,.1);display:flex;align-items:center;justify-content:center;color:var(--accent);font-size:1rem;flex-shrink:0;"><i class="bi bi-shield-lock-fill"></i></div>
                    <div class="trust-text" style="font-size:.85rem;color:rgba(248,250,252,.7);"><strong style="color:#F8FAFC;">Isolated Environment</strong> — Your data, your system. Zero cross-tenant risk.</div>
                </div>
                <div class="trust-item" style="display:flex;align-items:center;gap:12px;padding:14px 16px;background:rgba(255,255,255,.04);border:1px solid rgba(56,189,248,.1);border-radius:12px;">
                    <div class="trust-icon" style="width:36px;height:36px;border-radius:10px;background:rgba(56,189,248,.1);display:flex;align-items:center;justify-content:center;color:var(--accent);font-size:1rem;flex-shrink:0;"><i class="bi bi-lightning-charge-fill"></i></div>
                    <div class="trust-text" style="font-size:.85rem;color:rgba(248,250,252,.7);"><strong style="color:#F8FAFC;">Always On</strong> — 99.9% uptime SLA. Business doesn't wait, and neither do we.</div>
                </div>
                <div class="trust-item" style="display:flex;align-items:center;gap:12px;padding:14px 16px;background:rgba(255,255,255,.04);border:1px solid rgba(56,189,248,.1);border-radius:12px;">
                    <div class="trust-icon" style="width:36px;height:36px;border-radius:10px;background:rgba(56,189,248,.1);display:flex;align-items:center;justify-content:center;color:var(--accent);font-size:1rem;flex-shrink:0;"><i class="bi bi-graph-up-arrow"></i></div>
                    <div class="trust-text" style="font-size:.85rem;color:rgba(248,250,252,.7);"><strong style="color:#F8FAFC;">Real-Time Insight</strong> — Full visibility into every transaction, team member, and rupiah.</div>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT FORM PANEL -->
    <div class="auth-right auth-panel" style="background:var(--bg); display:flex; align-items:center; justify-content:center; padding:48px 40px;">
        <div class="form-panel" style="width:100%; max-width:420px;">
            <!-- Mobile only brand -->
            <div class="d-flex align-items-center gap-3 d-md-none mb-4">
                <div class="logo-icon" style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,var(--primary),var(--accent));display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.1rem;font-weight:800;">C</div>
                <span class="brand-name" style="font-size:1.8rem;font-weight:800;">{{ setting('site_name', 'COOCA') }}</span>
            </div>

            <div class="form-title" style="font-size:1.8rem;font-weight:800;margin-bottom:6px;letter-spacing:-0.02em;">Welcome back</div>
            <p class="form-subtitle" style="font-size:.92rem;margin-bottom:32px;">Log in to your COOCA dashboard. <a href="{{ route('customer.register') }}">No account? Start free →</a></p>

            <!-- SOCIAL LOGIN -->
            <a href="{{ route('customer.auth.google') }}" class="social-btn" style="display:flex;align-items:center;justify-content:center;gap:10px;width:100%;padding:13px;border-radius:12px;border:1px solid var(--border);background:transparent;color:var(--text);font-family:var(--font);font-size:.9rem;font-weight:600;cursor:pointer;transition:all var(--transition);margin-bottom:10px;text-decoration:none;">
                <svg width="18" height="18" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                Continue with Google
            </a>
            <button class="social-btn" style="display:flex;align-items:center;justify-content:center;gap:10px;width:100%;padding:13px;border-radius:12px;border:1px solid var(--border);background:transparent;color:var(--text);font-family:var(--font);font-size:.9rem;font-weight:600;cursor:pointer;transition:all var(--transition);margin-bottom:10px;">
                <i class="bi bi-microsoft" style="color:#00A4EF;"></i>
                Continue with Microsoft
            </button>

            <div class="divider" style="display:flex;align-items:center;gap:12px;margin:20px 0;">
                <div style="flex:1;height:1px;background:var(--border);"></div>
                <span style="font-size:.78rem;color:var(--text-muted);white-space:nowrap;">or continue with email</span>
                <div style="flex:1;height:1px;background:var(--border);"></div>
            </div>

            <!-- ERROR -->
            @if ($errors->any())
            <div class="error-msg" style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);border-radius:10px;padding:12px 16px;font-size:.85rem;color:#EF4444;margin-bottom:16px;display:block;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ $errors->first() }}
            </div>
            @endif

            <!-- FORM -->
            <form action="{{ route('customer.login.submit') }}" method="POST" id="loginForm">
                @csrf
                <div class="input-wrap" style="position:relative;margin-bottom:16px;">
                    <label class="input-label" style="font-size:.82rem;font-weight:600;margin-bottom:8px;display:block;color:var(--text);">Email Address</label>
                    <div style="position:relative;">
                        <i class="bi bi-envelope input-icon" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:1rem;pointer-events:none;"></i>
                        <input type="email" name="email" value="{{ old('email') }}" class="input-field" style="width:100%;padding:14px 16px 14px 46px;border-radius:12px;border:1px solid var(--border);background:var(--card-alt);color:var(--text);font-family:var(--font);font-size:.95rem;outline:none;transition:all var(--transition);" placeholder="you@company.com" required autocomplete="email">
                    </div>
                </div>
                <div class="input-wrap" style="position:relative;margin-bottom:16px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <label class="input-label" style="font-size:.82rem;font-weight:600;margin-bottom:8px;display:block;color:var(--text);">Password</label>
                        <a href="{{ route('customer.password.request') }}" style="font-size:.82rem;">Forgot password?</a>
                    </div>
                    <div style="position:relative;">
                        <i class="bi bi-lock input-icon" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:1rem;pointer-events:none;"></i>
                        <input type="password" name="password" class="input-field" style="width:100%;padding:14px 16px 14px 46px;border-radius:12px;border:1px solid var(--border);background:var(--card-alt);color:var(--text);font-family:var(--font);font-size:.95rem;outline:none;transition:all var(--transition);" placeholder="Your password" required autocomplete="current-password">
                    </div>
                </div>
                <div class="check-wrap" style="display:flex;align-items:center;gap:10px;margin-bottom:20px;">
                    <input type="checkbox" name="remember" id="rememberMe" style="width:18px;height:18px;accent-color:var(--primary);border-radius:4px;cursor:pointer;flex-shrink:0;">
                    <label for="rememberMe" style="font-size:.85rem;color:var(--text-muted);cursor:pointer;">Keep me logged in for 30 days</label>
                </div>
                <button type="submit" class="btn-cooca btn-cooca-primary" style="width:100%;padding:15px;font-size:1rem;border-radius:12px;display:inline-flex;align-items:center;justify-content:center;gap:8px;border:none;cursor:pointer;font-weight:600;">
                    <span>Log In to Dashboard</span>
                    <i class="bi bi-arrow-right"></i>
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
